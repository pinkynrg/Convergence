<?php namespace App\Http\Controllers;

use App\Libraries\ChartsManager;

class ChartsController extends Controller {
	
	public function statusCountToDate() {
		
		$data['title'] = 'History Status Count To Date';
		
		$data['open_status_count'] = ChartsManager::statusCountToDate(TICKET_NEW_STATUS_ID);
		$data['progress_status_count'] = ChartsManager::statusCountToDate(TICKET_IN_PROGRESS_STATUS_ID);
		$data['wff_status_count'] = ChartsManager::statusCountToDate(TICKET_WFF_STATUS_ID);
		$data['solved_status_count'] = ChartsManager::statusCountToDate(TICKET_SOLVED_STATUS_ID);
		$data['closed_status_count'] = ChartsManager::statusCountToDate(TICKET_CLOSED_STATUS_ID);

		return view('charts/status_count',$data);
	}

	public function statusCountPerDay() {
		
		$data['title'] = 'History Status Count per Day';
		
		$data['open_status_count'] = ChartsManager::statusCountPerDay(TICKET_NEW_STATUS_ID);
		$data['progress_status_count'] = ChartsManager::statusCountPerDay(TICKET_IN_PROGRESS_STATUS_ID);
		$data['wff_status_count'] = ChartsManager::statusCountPerDay(TICKET_WFF_STATUS_ID);
		$data['solved_status_count'] = ChartsManager::statusCountPerDay(TICKET_SOLVED_STATUS_ID);
		$data['closed_status_count'] = ChartsManager::statusCountPerDay(TICKET_CLOSED_STATUS_ID);

		return view('charts/status_count',$data);
	}
}

?>