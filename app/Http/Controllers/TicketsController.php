<?php namespace Convergence\Http\Controllers;

use Convergence\Http\Controllers\SlackController;
use Convergence\Http\Requests\CreateTicketRequest;
use Convergence\Http\Requests\UpdateTicketRequest;
use Convergence\Models\Ticket;
use Convergence\Models\TicketHistory;
use Convergence\Models\Company;
use Convergence\Models\Person;
use Convergence\Models\CompanyPerson;
use Convergence\Models\Division;
use Convergence\Models\Status;
use Convergence\Models\Equipment;
use Convergence\Models\Priority;
use Convergence\Models\JobType;
use Convergence\Models\TagTicket;
use Convergence\Models\Tag;
use Requests;
use Input;
use Form;

use Illuminate\Http\Request;

class TicketsController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index() {
		$data['menu_actions'] = [Form::editItem( route('tickets.create'),"Add new Ticket")];
		$data['active_search'] = true;
		$data['tickets'] = Ticket::orderBy('id','desc')->paginate(50);
		$data['companies'] = Company::orderBy('name','asc')->get();
		$employees = CompanyPerson::select('company_person.*');
		$employees->leftJoin('people','people.id','=','company_person.person_id');
		$employees->where('company_person.company_id','=',1);
		$employees->orderBy('people.last_name','asc')->orderBy('people.first_name','asc');
		$data['employees'] = $employees->get();
		$data['divisions'] = Division::orderBy('name','asc')->get();
		$data['statuses'] = Status::orderBy('id','asc')->get();

        $data['title'] = "Tickets";

		return view('tickets/index',$data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		$data['companies'] = Company::all();
		$data['priorities'] = Priority::all();
		$data['companies'] = Company::orderBy('name','asc')->get();
		$assignees = CompanyPerson::select('company_person.*');
		$assignees->leftJoin('people','people.id','=','company_person.person_id');
		$assignees->where('company_person.company_id','=',1);
		$assignees->orderBy('people.last_name','asc')->orderBy('people.first_name','asc');
		$data['assignees'] = $assignees->get();
		$data['divisions'] = Division::all();
		$data['job_types'] = JobType::all();

        $data['title'] = "Create Ticket";

		return view('tickets/create', $data);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(CreateTicketRequest $request)
	{
        $ticket = Ticket::create($request->all());

        if (Input::get('tagit')) {
			
			$tags = explode(",",Input::get('tagit'));
			
			foreach ($tags as $new_tag) {
				
				$tag = Tag::where('name','=',$new_tag)->first();
				
				if ( !isset($tag->id) ) {
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

		SlackController::sendTicket($ticket);

        return redirect()->route('tickets.index');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$data['menu_actions'] = [Form::editItem( route('tickets.edit', $id),"Edit this ticket"),
								 Form::deleteItem('tickets.destroy', $id, 'Delete this ticket')];
								 
		$data['ticket'] = Ticket::find($id);
		$data['ticket']['history'] = TicketHistory::where('ticket_id','=',$id)->orderBy('created_at')->get();

		switch ($data['ticket']->status_id) {
			case '1' : $data['status_class'] = 'ticket_status_new'; break;
			case '2' : $data['status_class'] = 'ticket_status_new'; break;
			case '3' : $data['status_class'] = 'ticket_status_on_hold'; break;
			case '4' : $data['status_class'] = 'ticket_status_on_hold'; break;
			case '5' : $data['status_class'] = 'ticket_status_on_hold'; break;
			case '6' : $data['status_class'] = 'ticket_status_closed'; break;
			case '7' : $data['status_class'] = 'ticket_status_closed'; break;
		};

        $data['title'] = "Ticket #".$id;

		return view('tickets/show',$data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$data['ticket'] = Ticket::find($id);
		$data['companies'] = Company::all();
		$data['divisions'] = Division::all();
		$data['job_types'] = JobType::all();
		$data['contacts'] = CompanyPerson::where('company_id','=',$data['ticket']->company_id)->get();
		$data['equipments'] = Equipment::where('company_id','=',$data['ticket']->company_id)->get();
		$data['priorities'] = Priority::all();
		$data['assignees'] = CompanyPerson::where("company_id","=","1")->get();
		$data['tags'] = "";

		foreach ($data['ticket']->tags as $tag) {
			$data['tags'] .= $tag->name.",";
		}

        $data['title'] = "Edit Ticket #".$id;

		return view('tickets/edit',$data);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id, UpdateTicketRequest $request)
	{
		$ticket = Ticket::find($id);
        $ticket->update($request->all());

        $old_tags = TagTicket::where('ticket_id','=',$id)->delete();

		if (Input::get('tagit')) {

			$tags = explode(",",Input::get('tagit'));

			foreach ($tags as $new_tag) {
				
				$tag = Tag::where('name','=',$new_tag)->first();
				
				if ( !isset($tag->id) ) {
					$tag = new Tag;
					$tag->name = $new_tag;
					$tag->save();
				}

				$tag_ticket = new TagTicket;
				$tag_ticket->ticket_id = $id;
				$tag_ticket->tag_id = $tag->id;
				$tag_ticket->save();
			}
		}
	
        return redirect()->route('tickets.show',$id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		echo 'ticket destroy method to be created';
	}
	
	/**
	 * Return list of tickets for an ajax request
	 *
	 * @return Response
	 */
	public function ajaxTicketsRequest($params = "")
    {
    	parse_str($params,$params);

    	$tickets = Ticket::select('tickets.*');
    	$tickets->leftJoin('people as creators','tickets.creator_id','=','creators.id');
    	$tickets->leftJoin('people as assignees','tickets.assignee_id','=','assignees.id');
    	$tickets->leftJoin('statuses','tickets.status_id','=','statuses.id');
    	$tickets->leftJoin('priorities','tickets.priority_id','=','priorities.id');
    	$tickets->leftJoin('companies','tickets.company_id','=','companies.id');
    	$tickets->leftJoin('divisions','tickets.division_id','=','divisions.id');

    	// apply filters
    	if (isset($params['filters'])) {
    		foreach ($params['filters'] as $key => $filter) {
    			
    			$tickets->where(function($query) use ($filter,$key) {
    				for ($i=0; $i<count($filter); $i++) {
	    				if ($i == 0)
	    					$query->where($key,'=',$filter[$i]);
	    				else
	    					$query->orWhere($key,'=',$filter[$i]);
    				}
    			});
    		}
    	}

    	// apply search
    	if (isset($params['search'])) {
    		$tickets->where('title','like','%'.$params['search'].'%');
    	}

    	// apply ordering
    	if (isset($params['order'])) {
    		$tickets->orderByRaw("case when ".$params['order']['column']." is null then 1 else 0 end asc");
    		$tickets->orderBy($params['order']['column'],$params['order']['type']);
    	}

    	// paginate
   		$tickets = $tickets->paginate(50);

	    $data['tickets'] = $tickets;

        return view('tickets/tickets',$data);
    }

    public function ajaxContactsRequest($id) {
    	$contacts = CompanyPerson::select('company_person.id','people.first_name','people.last_name');
    	$contacts->leftJoin('people','company_person.person_id','=','people.id');
    	$contacts->where('company_id','=',$id);
    	$contacts->orderByRaw("case when people.last_name is null then 1 else 0 end asc");
    	$contacts = $contacts->orderBy('people.last_name','asc');
    	$contacts = $contacts->get();
    	return json_encode($contacts);
    }

    public function ajaxEquipmentsRequest($id) {
    	$equipments = Equipment::select('equipments.*')->where('company_id','=',$id);
    	$equipments = $equipments->get();
    	return json_encode($equipments);
    }
}
