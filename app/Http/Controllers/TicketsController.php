<?php namespace Convergence\Http\Controllers;

use Convergence\Models\Ticket;
use Convergence\Models\Company;
use Convergence\Models\Person;
use Convergence\Models\CompanyPerson;
use Convergence\Models\Division;
use Convergence\Models\Status;
use Requests;
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
		$employees = Person::select('people.*');
		$employees->leftJoin('company_person','company_person.person_id','=','people.id');
		$employees->where('company_person.company_id','=',1);
		$employees->orderBy('people.last_name','asc')->orderBy('people.first_name','asc');
		$data['employees'] = $employees->get();
		$data['divisions'] = Division::orderBy('name','asc')->get();
		$data['statuses'] = Status::orderBy('id','asc')->get();
		return view('tickets/index',$data);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create() {
		return view('tickets/create');;
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		return "store method has to be created";
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
		return "edit method has to be created";
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		return "update method has to be created";
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$ticket = Ticket::find($id);
		$ticket->delete();
		return redirect()->route('tickets.index');
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
}
