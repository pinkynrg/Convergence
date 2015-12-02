<?php namespace App\Http\Controllers;

use App\Http\Controllers\ChartsController;
use DB;

class StatisticsController extends Controller {

	 public function index() {
		$data['title'] = "Statistics";
		$data['statuses']  = DB::table('statuses')->get();
        $data['tickets_status_data'] = ChartsController::ticketsStatusData();
		$data['tickets_status'] = ChartsController::ticketsStatus();
		$data['tickets_division_data'] = ChartsController::ticketsDivisionData();
		$data['tickets_division'] = ChartsController::ticketsDivision();
		return view('statistics/index',$data);
	}
}

?>