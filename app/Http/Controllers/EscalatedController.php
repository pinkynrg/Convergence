<?php namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Status;
use Form;
use Request;
use Illuminate\Pagination\LengthAwarePaginator;

class EscalatedController extends BaseController {
	
	public function tickets() {

    	$data['tickets'] = self::API()->tickets(Request::input());

		$data['companies'] = CompaniesController::API()->all(["paginate" => "false"]);
		
		$data['employees'] = CompanyPersonController::API()->all([
			'where' => ['company_person.company_id|=|'.ELETTRIC80_COMPANY_ID], 
			'order' => ['people.last_name|ASC','people.first_name|ASC'], 
			'paginate' => 'false']
		);

		$data['divisions'] = Division::orderBy('name','asc')->get();
		$data['statuses'] = Status::orderBy('id','asc')->get();
		$data['active_search'] = implode(",",['tickets.id','tickets.title','tickets.post','companies.name']);
		$data['menu_actions'] = [Form::editItem( route('tickets.create'),"Add new Ticket")];
    	$data['title'] = "Escalating Tickets";
		
		return Request::ajax() ? view('escalated/tickets/tickets',$data) : view('escalated/tickets/index',$data);
	}

}