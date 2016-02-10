<?php namespace App\Http\Controllers;

use App\Libraries\ChartsManager;
use DB;

class StatisticsController extends Controller {

	 public function index() {
		$data['title'] = "Statistics";
		$data['statuses']  = DB::table('statuses')->get();
        $data['tickets_status_data'] = ChartsManager::ticketsStatusData();
		$data['tickets_status'] = ChartsManager::ticketsStatus();
		$data['tickets_division_data'] = ChartsManager::ticketsDivisionData();
		$data['tickets_division'] = ChartsManager::ticketsDivision();
		return view('statistics/index',$data);
	}
}

?>