<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketHistory extends Ticket {

	protected $table = 'tickets_history';

	protected $fillable = ['ticket_id','title','post','creator_id','assignee_id','status_id','priority_id','division_id','equiment_id','company_id','contact_id','job_type_id'];

	public function ticket()
	{
		return $this->belongsTo('App\Models\Ticket');
	}

	public function changer()
	{
		return $this->belongsTo('App\Models\CompanyPerson');		
	}

	public function previous($level, $self) {
		if ($level < 0) return null;
		elseif ($level == 0) return $self;
		else {
			$previous = TicketHistory::where('id','=',$self->previous_id)->first();
			return count($previous) ? $this->previous(--$level, $previous) : null;
		}
	}
}
