<?php namespace Convergence\Http\Controllers;

use Convergence\Models\Ticket;
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
		$data['tickets'] = Ticket::orderBy('id','desc')->paginate(50);
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

	public function ajaxTicketsRequest()
    {
        $data['tickets'] = Ticket::orderBy('id','desc')->paginate(50);
        return view('tickets/tickets',$data);
    }
}
