<?php namespace App\Http\Controllers;

use App\Libraries\SlackManager;
use App\Libraries\EmailsManager;
use App\Http\Requests\CreateTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Http\Requests\UpdateTicketDraftRequest;
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
use App\Models\Level;
use App\Models\Post;
use Session;
use Request;
use Response;
use Input;
use Form;
use Auth;
use DB;


class TicketsController extends BaseController {

	public function index() 
	{
		if (Auth::user()->can('read-all-ticket')) 
		{
	    	$data['tickets'] = self::API()->all(Request::input());
			$data['companies'] = Company::orderBy('name','asc')->get();
			
			$data['employees'] = CompanyPersonController::API()->all([
				'where' => ['company_person.company_id|=|'.ELETTRIC80_COMPANY_ID], 
				'order' => ['people.last_name|ASC','people.first_name|ASC'], 
				'paginate' => 'false']
			);

			$data['divisions'] = Division::orderBy('name','asc')->get();
			$data['statuses'] = Status::where('id','!=',TICKET_DRAFT_STATUS_ID)->orderBy('id','asc')->get();

			$data['active_search'] = implode(",",['tickets.id','tickets.title','tickets.post']);

			if (Auth::user()->active_contact->isE80())
				$data['menu_actions'] = [Form::addItem(route('tickets.create'),"Create Ticket",Auth::user()->can('create-ticket'))];
			else {
				$data['menu_actions'] = [Form::addItem(route('ticket_requests.create'),"Request Ticket",Auth::user()->can('create-ticket'))];
			}
	    	$data['title'] = "Tickets";
			
			return Request::ajax() ? view('tickets/tickets',$data) : view('tickets/index',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to tickets index page']);      
	}

	public function show($id)
	{
		if (Auth::user()->can('read-ticket')) {
			
			$data['ticket'] = self::API()->find(['id'=>$id]);

			if ($data['ticket']) {

			    $data['title'] = "Ticket #".$data['ticket']->id;
				$data['menu_actions'] = [Form::editItem( route('tickets.edit', $id),"Edit This Ticket",Auth::user()->can('update-ticket'))];
				$data['ticket']['posts'] = PostsController::API()->all(['where' => ['ticket_id|=|'.$id], "order" => ['posts.created_at|ASC'], "paginate" => "false"]);
				$data['ticket']['history'] = TicketHistory::where('ticket_id','=',$id)->orderBy('created_at')->get();
				$data['statuses'] = Status::where('id',TICKET_WFF_STATUS_ID)->orWhere('id',TICKET_SOLVED_STATUS_ID)->get();
				
				$data['draft_post'] = Post::where("ticket_id",$id)
					->where("status_id",POST_DRAFT_STATUS_ID)
					->where("author_id",Auth::user()
					->active_contact->id)->first();

				$data['important_post'] = null;

				if (in_array($data['ticket']->status_id,[TICKET_SOLVED_STATUS_ID,TICKET_WFF_STATUS_ID])) {
					foreach ($data['ticket']['posts']->reverse() as $post) {
						if ($post->ticket_status_id != $data['ticket']->status_id) break;
						else $data['important_post'] = $post;
					}
				}

				$links = [];
				$temp = TicketLink::where("ticket_id","=",$id)->get();
				foreach ($temp as $elem) $links[] = $elem->linked_ticket_id;
				$data['ticket']['links'] = self::API()->all(['where' => ['tickets.id|IN|'.implode(":",$links)]]);

				$linked_to = [];
				$temp = TicketLink::where("linked_ticket_id","=",$id)->get();
				foreach ($temp as $elem) $linked_to[] = $elem->ticket_id;
				$data['ticket']['linked_to'] = self::API()->all(['where' => ['tickets.id|IN|'.implode(":",$linked_to)]]);

				if (isset($data['important_post'])) { 
					switch ($data['ticket']->status_id) {
						case TICKET_WFF_STATUS_ID: $data['important_post']->alert_type = "danger"; break;
						case TICKET_SOLVED_STATUS_ID: $data['important_post']->alert_type = "success"; break;
					}
				}

				return view('tickets/show',$data);
			}
			else {
				return redirect()->back()->withErrors(['404 The following Ticket coudn\'t be found']);	
			}
		}
		else return redirect()->back()->withErrors(['Access denied to tickets show page']);	
	}

	public function create() {
		if (Auth::user()->can('create-ticket')) {

			$data['ticket'] = self::API()->getDraft();
			$data['priorities'] = Priority::orderBy('id','desc')->get();
			$data['divisions'] = Division::orderBy('name')->get();
			$data['job_types'] = JobType::orderBy('name')->get();
			$data['levels'] = Level::orderBy('name')->get();
			
			$data['assignees'] = CompanyPersonController::API()->all([
				"where" => ["companies.id|=|".ELETTRIC80_COMPANY_ID], 
				"order" => ["people.last_name|ASC","people.first_name|ASC"], 
				"paginate" => 'false'
			]);

			$data['companies'] = CompaniesController::API()->all([
				'where' => ['companies.id|!=|'.ELETTRIC80_COMPANY_ID],
				'order' => ['companies.name|ASC'],
				'paginate' => 'false'
			]);

	        $data['title'] = "Create Ticket";

			return view('tickets/create', $data);
		}
		else return redirect()->back()->withErrors(['Access denied to tickets create page']);	
	}

	public function edit($id)
	{
		if (Auth::user()->can('update-ticket')) {

			$data['ticket'] = self::API()->find(['id'=>$id]);

			$temp = DB::table("ticket_links")->where("ticket_id","=",$id)->get();
			foreach ($temp as $elem) $links[] = $elem->linked_ticket_id;
			$data['ticket']['linked_tickets_id'] = isset($links) ? implode(",",$links) : '';

			$data['companies'] = Company::where('id','!=',ELETTRIC80_COMPANY_ID)->orderBy('name')->get();
			$data['priorities'] = Priority::orderBy('id','desc')->get();
			$data['divisions'] = Division::orderBy('name')->get();
			$data['job_types'] = JobType::orderBy('name')->get();
			$data['levels'] = Level::orderBy('name')->get();
			
			$data['assignees'] = CompanyPersonController::API()->all(
				["where" => ["companies.id|=|".ELETTRIC80_COMPANY_ID], 
				"order" => ["people.last_name|ASC","people.first_name|ASC"], 
				"paginate" => "false"
			]);

			$data['companies'] = CompaniesController::API()->all([
				'where' => ['companies.id|!=|'.ELETTRIC80_COMPANY_ID],
				'order' => ['companies.name|ASC'],
				'paginate' => 'false'
			]);


			$data['tags'] = "";

			foreach ($data['ticket']->tags as $tag) {
				$data['tags'] .= $tag->name.",";
			}

			$is_draft = $data['ticket']->status_id == TICKET_DRAFT_STATUS_ID ? true : false;

	        $data['title'] = "Edit Ticket #".$id;

			return view('tickets/edit',$data);
		}
		else return redirect()->back()->withErrors(['Access denied to tickets edit page']);	
	}

	public function draft(UpdateTicketDraftRequest $request) 
	{
		$draft = self::API()->getDraft();
		$ticket = $draft ? $draft : new Ticket();

		$ticket->title = $request->get('title');
		$ticket->post = $request->get('post');
		$ticket->creator_id = Auth::user()->active_contact->id;
		$ticket->status_id = TICKET_DRAFT_STATUS_ID;
		$ticket->assignee_id = $request->get('assignee_id');
		$ticket->priority_id = $request->get('priority_id');
		$ticket->division_id = $request->get('division_id');
		$ticket->equipment_id = $request->get('equipment_id');
		$ticket->company_id = $request->get('company_id');
		$ticket->contact_id = $request->get('contact_id') != 0 ? $request->get('contact_id') : NULL;
		$ticket->job_type_id = $request->get('job_type_id');
		$ticket->level_id = $request->get('level_id');
		$ticket->emails = $request->get('emails');
		$ticket->save();

       	$this->updateTags($ticket);
       	$this->updateLinks($ticket);

       	return 'success';
	}

	public function store(CreateTicketRequest $request)
	{
		$draft = self::API()->getDraft();
		$ticket = $draft ? $draft : new Ticket();

		$ticket->title = $request->get('title');
		$ticket->post = $request->get('post');
		$ticket->creator_id = Auth::user()->active_contact->id;
		$ticket->status_id = TICKET_NEW_STATUS_ID;
		$ticket->assignee_id = $request->get('assignee_id');
		$ticket->priority_id = $request->get('priority_id');
		$ticket->division_id = $request->get('division_id');
		$ticket->equipment_id = $request->get('equipment_id');
		$ticket->company_id = $request->get('company_id');
		$ticket->contact_id = $request->get('contact_id');
		$ticket->job_type_id = $request->get('job_type_id');
		$ticket->level_id = $request->get('level_id');
		$ticket->emails = $request->get('emails');
		if (isset($ticket->updated_at)) $ticket->created_at = $ticket->updated_at;
		$ticket->save();

       	$this->updateTags($ticket);
       	$this->updateLinks($ticket);
   		$this->updateHistory($ticket); 
   		
   		EmailsManager::sendTicket($ticket->id);
		SlackManager::sendTicket($ticket);

        return redirect()->route('tickets.index')->with('successes',['Ticket created successfully']);
	}

	public function update($id, UpdateTicketRequest $request)
	{
		$ticket = self::API()->find(['id'=>$id]);

		$ticket->company_id = $request->get('company_id');
		$ticket->title = $request->get('title');
		$ticket->post = $request->get('post');
		$ticket->assignee_id = $request->get('assignee_id');
		$ticket->division_id = $request->get('division_id');
		$ticket->equipment_id = $request->get('equipment_id');
		$ticket->contact_id = $request->get('contact_id');
		$ticket->job_type_id = $request->get('job_type_id');
		$ticket->level_id = $request->get('level_id');
		$ticket->emails = $request->get('emails');
		$ticket->priority_id =  $request->get('priority_id');	

		if ($ticket->status_id == TICKET_REQUESTING_STATUS_ID) {
			$ticket->status_id = TICKET_NEW_STATUS_ID;
		}

       	$this->updateLinks($ticket);
       	$this->updateTags($ticket);

		if ($ticket->isDirty()) {

			$ticket->save();
	       	$this->updateHistory($ticket);

	       	EmailsManager::sendTicketUpdate($ticket->id);
			SlackManager::sendTicketUpdate($ticket);
		}

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

		$last_history = TicketHistory::where('ticket_id',$ticket->id)->orderBy("created_at","DESC")->first();

		$history->previous_id = count($last_history) ? $last_history->id : NULL;
		$history->changer_id = Auth::user()->active_contact->id;
		$history->ticket_id = $ticket->id;
		$history->title = $ticket->title;
		$history->post = $ticket->post;
		$history->creator_id = $ticket->creator_id;
		$history->assignee_id = $ticket->assignee_id;
		$history->status_id = $ticket->status_id;
		$history->priority_id = $ticket->priority_id;
		$history->division_id = $ticket->division_id;
		$history->equipment_id = $ticket->equipment_id;
		$history->company_id = $ticket->company_id;
		$history->contact_id = $ticket->contact_id;
		$history->level_id = $ticket->level_id;
		$history->job_type_id = $ticket->job_type_id;
		$history->emails = $ticket->emails;
	
		$history->save();
	}
}
