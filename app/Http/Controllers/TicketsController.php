<?php namespace App\Http\Controllers;

use App\Libraries\SlackController;
use App\Libraries\EmailsManager;
use App\Http\Requests\CreateTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Controllers\CompanyPersonController;
use App\Models\Ticket;
use App\Models\TicketLink;
use App\Models\TicketHistory;
use App\Models\Company;
use App\Models\Person;
use App\Models\CompanyPerson;
use App\Models\Division;
use App\Models\Status;
use App\Models\Equipment;
use App\Models\Priority;
use App\Models\JobType;
use App\Models\TagTicket;
use App\Models\Tag;
use App\Models\Post;
use Html2Text\Html2Text;
use Request;
use Response;
use Input;
use Form;
use Auth;
use DB;


class TicketsController extends BaseController {

	public function index() {
		if (Auth::user()->can('read-all-company')) {
			return parent::index();
		}
		else return redirect()->back()->withErrors(['Access denied to tickets index page']);      
	}

	protected function main() {
		$params = Request::input() != [] ? Request::input() : ['order' => ['tickets.id|DESC']];
    	$data['tickets'] = self::api($params);
    	$data['title'] = "Tickets";
		$data['menu_actions'] = [Form::editItem( route('tickets.create'),"Add new Ticket")];
		$data['active_search'] = implode(",",['tickets.title','tickets.post']);
		$data['companies'] = Company::orderBy('name','asc')->get();
		$data['employees'] = CompanyPersonController::api(['where' => ['company_person.company_id|=|1'], 'order' => ['people.last_name|ASC','people.first_name|ASC'], 'paginate' => 'false']);
		$data['divisions'] = Division::orderBy('name','asc')->get();
		$data['statuses'] = Status::orderBy('id','asc')->get();
		return view('tickets/index',$data);
	}

	public function create(Request $request) {

		$ticket = Ticket::where('creator_id',Auth::user()->active_contact->id)->where("status_id","9")->first();
		
		if ($ticket) {
			// if there is a started draft redirect to that
			return redirect()->route('tickets.edit',$ticket->id)->with('infos',['This is a draft ticket lastly updated the '.date('m/d/Y H:i:s',strtotime($ticket->updated_at))]);
		}
		else {
			// otherwise redirect to empty form
			$data['companies'] = Company::all();
			$data['priorities'] = Priority::all();
			
			$data['assignees'] = CompanyPersonController::api(
				["where" => ["companies.id|=|".ELETTRIC80_COMPANY_ID], "order" => ["people.last_name|ASC","people.first_name|ASC"], "paginate" => "false"]
			);

			$data['divisions'] = Division::all();
			$data['job_types'] = JobType::all();

	        $data['title'] = "Create Ticket";

			return view('tickets/create', $data);
		}
	}

	public function store(CreateTicketRequest $request)
	{
		$draft = Ticket::where('creator_id',Auth::user()->active_contact->id)->where("status_id",TICKET_DRAFT_STATUS_ID)->first();

		$ticket = $draft ? $draft : new Ticket();

		$ticket->title = $request->get('title');
		$ticket->post = $request->get('post');
		$ticket->post_plain_text = Html2Text::convert($request->get('post'));
		$ticket->creator_id = Auth::user()->active_contact->id;
		$ticket->assignee_id = $request->get('assignee_id');
		$ticket->status_id = $request->get('status_id');
		$ticket->priority_id = $request->get('priority_id');
		$ticket->division_id = $request->get('division_id');
		$ticket->equipment_id = $request->get('equipment_id');
		$ticket->company_id = $request->get('company_id');
		$ticket->contact_id = $request->get('contact_id') != 0 ? $request->get('contact_id') : NULL;
		$ticket->job_type_id = $request->get('job_type_id');
		$ticket->emails = $request->get('emails');

		$ticket->save();

       	$this->updateTags($ticket);
       	$this->updateLinks($ticket);

       	if ($ticket->status_id != TICKET_DRAFT_STATUS_ID) { $this->updateHistory($ticket); }

		// EmailsManager::sendTicket($ticket->id);
		// SlackManager::sendTicket($ticket);

        return (Request::ajax()) ? 'success' : redirect()->route('tickets.index')->with('successes',['Ticket created successfully']);
        return redirect()->route('tickets.index')->with('successes',['Ticket created successfully']);
	}

	public function show($id)
	{
		if (Auth::user()->can('read-ticket')) {
			
			if (Request::ajax()) {
				return Ticket::find($id);
			}
			else {

				$data['menu_actions'] = [
					Form::editItem( route('tickets.edit', $id),"Edit this ticket")
				];
										 
				$data['ticket'] = Ticket::find($id);
				$data['ticket']['posts'] = Post::where('ticket_id',$id)->where('status_id','!=',POST_DRAFT_STATUS_ID)->get();
				$data['ticket']['history'] = TicketHistory::where('ticket_id','=',$id)->orderBy('created_at')->get();
				$data['statuses'] = Status::where('id',TICKET_WFF_STATUS_ID)->orWhere('id',TICKET_SOLVED_STATUS_ID)->get();
				$data['draft_post'] = Post::where("ticket_id",$id)->where("status_id",1)->where("author_id",Auth::user()->active_contact->id)->first();
				
				$links = [];
				$temp = TicketLink::where("ticket_id","=",$id)->get();
				foreach ($temp as $elem) $links[] = $elem->linked_ticket_id;
				$data['ticket']['links'] = self::api(['where' => ['tickets.id|IN|'.implode(":",$links)]]);

				$linked_to = [];
				$temp = TicketLink::where("linked_ticket_id","=",$id)->get();
				foreach ($temp as $elem) $linked_to[] = $elem->ticket_id;
				$data['ticket']['linked_to'] = self::api(['where' => ['tickets.id|IN|'.implode(":",$linked_to)]]);

				if (isset($data['draft_post']->post)) {
					$data['draft_post']->post = $data['draft_post']->post != "[undefined]" ? $data['draft_post']->post : "";
				}

				switch ($data['ticket']->status_id) {
					case '1' :
					case '2' : $data['status_class'] = 'ticket_status_new'; break;
					case '3' :
					case '4' :
					case '5' : $data['status_class'] = 'ticket_status_on_hold'; break;
					case '6' :
					case '7' : $data['status_class'] = 'ticket_status_closed'; break;
				};

			    $data['title'] = "Ticket #".$id;

				return view('tickets/show',$data);
			}
		}
		else return redirect()->back()->withErrors(['Access denied to tickets show page']);	
	}

	public function edit($id)
	{
		$data['ticket'] = Ticket::find($id);

		$temp = DB::table("ticket_links")->where("ticket_id","=",$id)->get();
		foreach ($temp as $elem) $links[] = $elem->linked_ticket_id;
		$data['ticket']['linked_tickets_id'] = isset($links) ? implode(",",$links) : '';

		$data['companies'] = Company::all();
		$data['divisions'] = Division::all();
		$data['statuses'] = Status::all();
		$data['job_types'] = JobType::all();
		$data['priorities'] = Priority::all();
		
		$data['assignees'] = CompanyPersonController::api(
			["where" => ["companies.id|=|".ELETTRIC80_COMPANY_ID], "order" => ["people.last_name|ASC","people.first_name|ASC"], "paginate" => "false"]
		);

		$data['tags'] = "";

		foreach ($data['ticket']->tags as $tag) {
			$data['tags'] .= $tag->name.",";
		}

		$is_draft = $data['ticket']->status_id == TICKET_DRAFT_STATUS_ID ? true : false;

		$data['ticket']->title = ($is_draft && $data['ticket']->title == '[undefined]') ? '' : $data['ticket']->title;
		$data['ticket']->post = ($is_draft && $data['ticket']->post_plain_text == '[undefined]') ? '' : $data['ticket']->post;

        $data['title'] = "Edit Ticket #".$id;
        $data['title'] .= $is_draft ? " ~ Draft" : "";

		return view('tickets/edit',$data);
	}

	public function update($id, UpdateTicketRequest $request)
	{
		$ticket = Ticket::find($id);

		$ticket->company_id = $request->get('company_id');
		$ticket->title = $request->get('title');
		$ticket->post = $request->get('post');
		$ticket->post_plain_text = Html2Text::convert($request->get('post'));
		$ticket->assignee_id = $request->get('assignee_id');
		$ticket->status_id = $request->get('status_id');
		$ticket->priority_id = $request->get('priority_id');
		$ticket->division_id = $request->get('division_id');
		$ticket->equipment_id = $request->get('equipment_id');
		$ticket->contact_id = $request->get('contact_id') != 0 ? $request->get('contact_id') : NULL;
		$ticket->job_type_id = $request->get('job_type_id');
		$ticket->emails = $request->get('emails');

		$ticket->save();

       	$this->updateTags($ticket);
       	$this->updateHistory($ticket);
       	$this->updateLinks($ticket);


        return redirect()->route('tickets.show',$id)->with('successes',['Ticket updated successfully']);
	}

	private function updateTags($ticket) {

		TagTicket::where('ticket_id',$ticket->id)->forceDelete();

		if (Input::get('tagit')) {

			$tags = explode(",",Input::get('tagit'));

			foreach ($tags as $new_tag) {
				
				$tag = Tag::where('name',$new_tag)->first();
				
				if (!isset($tag->id)) {
					$tag = new Tag;
					$tag->name = $new_tag;
					$tag->save();
				}

				$tag_ticket = new TagTicket;
				$tag_ticket->ticket_id = $ticket->id;
				$tag_ticket->tag_id = $tag->id;
				$tag_ticket->save();
			}
		}
	}

	private function updateLinks($ticket) {

		TicketLink::where('ticket_id',$ticket->id)->forceDelete();

		if (Input::get('linked_tickets_id')) {

			$links = explode(",",Input::get('linked_tickets_id'));

			foreach ($links as $link) {
				
				$new_link = new TicketLink;
				$new_link->ticket_id = $ticket->id;
				$new_link->linked_ticket_id = $link;
				$new_link->save();
			}
		}
	}

	private function updateHistory($ticket) {

		$history = new TicketHistory;

		$history->changer_id = Auth::user()->active_contact->id;
		$history->ticket_id = $ticket->id;
		$history->title = $ticket->title;
		$history->post = $ticket->post;
		$history->post_plain_text = $ticket->post_plain_text;
		$history->creator_id = $ticket->creator_id;
		$history->assignee_id = $ticket->assignee_id;
		$history->status_id = $ticket->status_id;
		$history->priority_id = $ticket->priority_id;
		$history->division_id = $ticket->division_id;
		$history->equipment_id = $ticket->equipment_id;
		$history->company_id = $ticket->company_id;
		$history->contact_id = $ticket->contact_id;
		$history->job_type_id = $ticket->job_type_id;
		$history->emails = $ticket->emails;
	
		$history->save();
	}

	public function destroy($id)
	{
		echo 'ticket destroy method to be created';
	}
}
