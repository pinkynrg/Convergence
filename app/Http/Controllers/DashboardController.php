<?php namespace App\Http\Controllers;

use App\Http\Controllers\ChartsController;
use App\Models\CompanyPerson;
use Auth;

class DashboardController extends Controller {

	public function dashboardLoggedContact() {
		$contact_id = Auth::user()->active_contact_id;
        return $this->dashboardContact($contact_id);
	}

	public function dashboardContact($contact_id) {
		$data['contact'] = $contact = CompanyPerson::find($contact_id);
		$data['title'] = (Auth::user()->active_contact_id == $contact_id) ?  "My Dashboard" : $contact->person->name()." Dashboard";
        $data['user_tickets_status_data'] = ChartsController::userTicketsStatusData($contact_id);
        $data['user_tickets_involvement_data'] = ChartsController::userTicketsInvolvementData($contact_id);
		$data['user_tickets_status'] = ChartsController::userTicketsStatus($contact_id);
		$data['user_tickets_involvement'] = ChartsController::userTicketsInvolvement($contact_id);
		return view('dashboard/index',$data);
	}

}

?>