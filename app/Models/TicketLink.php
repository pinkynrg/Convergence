<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketLink extends CustomModel {

	protected $table = 'ticket_links';

	public function ticket()
	{
		return $this->belongsTo('App\Models\Ticket','ticket_id','id');		
	}

	public function linked_ticket()
	{
		return $this->belongsTo('App\Models\Ticket','linked_ticket_id','id');
	}
}
