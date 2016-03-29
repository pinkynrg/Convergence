<?php namespace App\Http\Controllers;

use App\Libraries\ChartsManager;

class ChartsController extends Controller {
	
	public function statusCount() {
		
		$data['title'] = 'History Status Count';
		
		$data['open_status_count'] = ChartsManager::statusCountPerDate(TICKET_NEW_STATUS_ID);
		$data['progress_status_count'] = ChartsManager::statusCountPerDate(TICKET_IN_PROGRESS_STATUS_ID);
		$data['wff_status_count'] = ChartsManager::statusCountPerDate(TICKET_WFF_STATUS_ID);
		$data['solved_status_count'] = ChartsManager::statusCountPerDate(TICKET_SOLVED_STATUS_ID);
		$data['closed_status_count'] = ChartsManager::statusCountPerDate(TICKET_CLOSED_STATUS_ID);

		return view('charts/status_count',$data);
	}
}

?>