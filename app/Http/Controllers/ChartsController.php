<?php namespace Convergence\Http\Controllers;

use Ghunti\HighchartsPHP\Highchart;
use Carbon\Carbon;
use DB;

class ChartsController extends Controller {

	public static function userTicketsStatusData($contact_id) {
		
		$ticket_statuses = DB::table('statuses')->get();
		$total = DB::table('tickets')->where('assignee_id',$contact_id)->count();

		foreach ($ticket_statuses as $ticket_status) {
			$record = new \stdClass(); 
			$record->name = $ticket_status->name;
			$record->number = DB::table('tickets')->where('assignee_id',$contact_id)->where('status_id',$ticket_status->id)->count();			
			$record->percentage = round($record->number*100/$total,2);
			$data[] = $record;
		}

		return $data;
	}

	public static function userTicketsStatus($contact_id) {
		
		$chart = new Highchart();

		$chart->title->text = "My Tickets";
		$chart->legend->enabled = false;
		$chart->chart->options3d->enabled = 'true';
		$chart->chart->options3d->alpha = '10';
		$chart->chart->options3d->beta = '0';
		$chart->chart->options3d->depth = '100';
		$chart->credits->enabled = false;
		$chart->series[0]->type = 'column';
		$chart->series[0]->colorByPoint = true;

		$data = self::userTicketsStatusData($contact_id);

		foreach ($data as $value) {
			$chart->xAxis->categories[] = $value->name;
			$chart->series[0]->data[] = $value->number;
		}

		return $chart->renderOptions();
	}

	public static function userTicketsInvolvementData($contact_id) {
		
		$ticket_involvements = ['Assigned','Issued','Commented','Unread'];

		foreach ($ticket_involvements as $key => $value) {
						
			switch ($key) {
				case '0': $ticket_number = DB::table('tickets')->where('assignee_id',$contact_id)->count(); break;
				case '1': $ticket_number = DB::table('tickets')->where('creator_id',$contact_id)->count(); break;
				case '2': $ticket_number = count(DB::table('posts')->select('posts.ticket_id')->where('posts.author_id',$contact_id)->groupby('posts.ticket_id')->get()); break;
				case '3': $ticket_number = 0; break;
			}
			
			$record = new \stdClass(); 
			$record->name = $value;
			$record->number = $ticket_number;
			$data[] = $record;
		}

		return $data;
	}


	public static function userTicketsInvolvement($contact_id) {
		$chart = new Highchart();

		$chart->title->text = "My Tickets Involvement";
		$chart->legend->enabled = false;
		$chart->chart->options3d->enabled = 'true';
		$chart->chart->options3d->alpha = '10';
		$chart->chart->options3d->beta = '0';
		$chart->chart->options3d->depth = '100';
		$chart->credits->enabled = false;
		$chart->series[0]->type = 'column';
		$chart->series[0]->colorByPoint = true;

		$data = self::userTicketsInvolvementData($contact_id);

		foreach ($data as $value) {
			
			$chart->xAxis->categories[] = $value->name;
			$chart->series[0]->data[] = $value->number;
		}

		return $chart->renderOptions();
	}

	public static function ticketsStatusData() {
		
		$ticket_statuses = DB::table('statuses')->get();
		$total = DB::table('tickets')->count();

		foreach ($ticket_statuses as $ticket_status) {
			$record = new \stdClass(); 
			$record->name = $ticket_status->name;
			$record->number = DB::table('tickets')->where('status_id',$ticket_status->id)->count();			
			$record->percentage = round($record->number*100/$total,2);
			$data[] = $record;
		}

		return $data;
	}

	public static function ticketsStatus() {
		
		$chart = new Highchart();

		$chart->title->text = "Tickets by Status";
		$chart->legend->enabled = false;
		$chart->chart->options3d->enabled = 'true';
		$chart->chart->options3d->alpha = '10';
		$chart->chart->options3d->beta = '0';
		$chart->chart->options3d->depth = '100';
		$chart->credits->enabled = false;
		$chart->series[0]->type = 'column';
		$chart->series[0]->colorByPoint = true;

		$data = self::ticketsStatusData();

		foreach ($data as $value) {
			$chart->xAxis->categories[] = $value->name;
			$chart->series[0]->data[] = $value->number;
		}

		return $chart->renderOptions();
	}

	public static function ticketsDivisionData() {
		
		$ticket_divisions = DB::table('divisions')->get();
		$ticket_statuses = DB::table('statuses')->get();

		$total = DB::table('tickets')->count();

		foreach ($ticket_divisions as $ticket_division) {
			$record = new \stdClass(); 
			$record->name = $ticket_division->name;
			$record->number = DB::table('tickets')->where('division_id',$ticket_division->id)->count();			
			$record->percentage = round($record->number*100/$total,2);
			$record->details = array();
			
			foreach ($ticket_statuses as $ticket_status) {
				$detail = new \stdClass();
				$detail->name = $ticket_status->name;
				$detail->number = DB::table('tickets')->where('division_id',$ticket_division->id)->where('status_id',$ticket_status->id)->count();
				$detail->percentage = round($detail->number*100/$record->number,2);
				$record->details[] = $detail;
			}

			$data[] = $record;
		}

		return $data;
	}

	public static function ticketsDivision() {
		
		$chart = new Highchart();

		$chart->title->text = "Tickets by Division";
		$chart->legend->enabled = false;
		$chart->chart->options3d->enabled = 'true';
		$chart->chart->options3d->alpha = '10';
		$chart->chart->options3d->beta = '0';
		$chart->chart->options3d->depth = '100';
		$chart->credits->enabled = false;
		$chart->series[0]->type = 'column';
		$chart->series[0]->colorByPoint = true;

		$data = self::ticketsDivisionData();

		foreach ($data as $value) {
			$chart->xAxis->categories[] = $value->name;
			$chart->series[0]->data[] = $value->number;
		}

		return $chart->renderOptions();
	}
}

?>