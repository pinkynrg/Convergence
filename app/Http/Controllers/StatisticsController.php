<?php namespace App\Http\Controllers;

use App\Libraries\StatisticsManager;

class StatisticsController extends Controller {
	
	public function statusCountToDate() {
		
		$data['title'] = 'History Status Count To Date';
		
		$data['open_status_count'] = StatisticsManager::statusCountToDate(TICKET_NEW_STATUS_ID);
		$data['progress_status_count'] = StatisticsManager::statusCountToDate(TICKET_IN_PROGRESS_STATUS_ID);
		$data['wff_status_count'] = StatisticsManager::statusCountToDate(TICKET_WFF_STATUS_ID);
		$data['solved_status_count'] = StatisticsManager::statusCountToDate(TICKET_SOLVED_STATUS_ID);
		$data['closed_status_count'] = StatisticsManager::statusCountToDate(TICKET_CLOSED_STATUS_ID);

		return view('statistics/status_count',$data);
	}

	public function statusCountPerDay() {
		
		$data['title'] = 'History Status Count per Day';
		
		$data['open_status_count'] = StatisticsManager::statusCountPerDay(TICKET_NEW_STATUS_ID);
		$data['progress_status_count'] = StatisticsManager::statusCountPerDay(TICKET_IN_PROGRESS_STATUS_ID);
		$data['wff_status_count'] = StatisticsManager::statusCountPerDay(TICKET_WFF_STATUS_ID);
		$data['solved_status_count'] = StatisticsManager::statusCountPerDay(TICKET_SOLVED_STATUS_ID);
		$data['closed_status_count'] = StatisticsManager::statusCountPerDay(TICKET_CLOSED_STATUS_ID);

		return view('statistics/status_count',$data);
	}

	public function resolutionTime() {

		$data['title'] = 'Resolution Time';

		$data['data']['Month'] = StatisticsManager::resolutionTime(30);
		$data['data']['Three Months'] = StatisticsManager::resolutionTime(90);
		$data['data']['Six Months'] = StatisticsManager::resolutionTime(180);
		$data['data']['One Year'] = StatisticsManager::resolutionTime(360);

		return view('statistics/resolution_time',$data);

	}

	public function workingTimeByDivision($days = 1) {

		$data['title'] = 'Working Time';
		$data['dataset']['division'] = StatisticsManager::workingTime($days,"division");
		$data['dataset']['priority'] = StatisticsManager::workingTime($days,"priority");
		$data['dataset']['level'] = StatisticsManager::workingTime($days,"level");
		return view('statistics/working_time',$data);

	}

	public function workingTimeByCustomer($days = 1) {

		$data['title'] = 'Working Time';
		$data['dataset']['company'] = StatisticsManager::workingTime($days,"company");
		return view('statistics/working_time',$data);
	}

	public function workingTimeByAssignee($days = 1) {

		$data['title'] = 'Working Time';
		$data['dataset']['assignee'] = StatisticsManager::workingTime($days,"assignee");
		return view('statistics/working_time',$data);
	}
}

?>