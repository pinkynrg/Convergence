<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends CustomModel {

	protected $table = 'tickets';

	protected $fillable = ['title','post','creator_id','assignee_id','status_id','priority_id','division_id','equiment_id','company_id','contact_id','job_type_id','emails'];

	public function status()
	{
		return $this->belongsTo('App\Models\Status');
	}

	public function status_icon()
	{
		$icon = MISSING_ICON;

		switch ($this->status_id) {
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

	public function priority()
	{
		return $this->belongsTo('App\Models\Priority');		
	}

	public function job_type()
	{
		return $this->belongsTo('App\Models\JobType');		
	}

	public function assignee()
	{
		return $this->belongsTo('App\Models\CompanyPerson');		
	}

	public function creator()
	{
		return $this->belongsTo('App\Models\CompanyPerson');		
	}

	public function company()
	{
		return $this->belongsTo('App\Models\Company');
	}

	public function division()
	{
		return $this->belongsTo('App\Models\Division');
	}

	public function contact() 
	{
		return $this->belongsTo('App\Models\CompanyPerson');
	}

	public function equipment() 
	{
		return $this->belongsTo('App\Models\Equipment');
	}

	public function posts() 
	{
		return $this->hasMany('App\Models\Post');
	}

	public function tags() 
	{
		return $this->belongsToMany('App\Models\Tag');
	}

	public function history() 
	{
		return $this->hasMany('App\Models\TicketHistory')->orderBy('created_at');
	}

	public function links() 
	{
		return $this->belongsToMany('App\Models\Ticket', 'ticket_links', 'linked_ticket_id', 'ticket_id');
	}

	public function linked_by() 
	{
		return $this->hasMany('App\Models\TicketLink','linked_ticket_id')->orderBy('ticket_id');
	}

	public function attachments() {
		return $this->morphMany('App\Models\File','resource');
	}

	public function last_operation_company_person() 
	{
		return $this->belongsTo('App\Models\CompanyPerson');
	}
}
