<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends CustomModel {

	protected $table = 'statuses';

	public function icon()
	{
		$icon = MISSING_ICON;

		switch ($this->id) {
			case TICKET_NEW_STATUS_ID: $icon = TICKET_NEW_ICON; break;
			case TICKET_IN_PROGRESS_STATUS_ID: $icon = TICKET_IN_PROGRESS_ICON; break;
			case TICKET_WFF_STATUS_ID: $icon = TICKET_WFF_ICON; break;
			case TICKET_WFP_STATUS_ID: $icon = TICKET_WFP_ICON; break;
			case TICKET_REQUESTING_STATUS_ID: $icon = TICKET_REQUESTING_ICON; break;
			case TICKET_DRAFT_STATUS_ID: $icon = TICKET_DRAFT_ICON; break;
			case TICKET_SOLVED_STATUS_ID: $icon = TICKET_SOLVED_ICON; break;
			case TICKET_CLOSED_STATUS_ID: $icon = TICKET_CLOSED_ICON; break;
		}

		return $icon;
	}

	public function color_class() {
		return "status_".strtolower($this->label);
	}

}
