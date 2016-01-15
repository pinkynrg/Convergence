<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketHistory extends CustomModel {

	protected $table = 'tickets_history';

	protected $fillable = ['ticket_id','title','post','creator_id','assignee_id','status_id','priority_id','division_id','equiment_id','company_id','contact_id','job_type_id'];

	public function ticket()
	{
		return $this->belongsTo('App\Models\Ticket');
	}

	public function status()
	{
		return $this->belongsTo('App\Models\Status');
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

	public function changer()
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

	public function posts() 
	{
		return $this->hasMany('App\Models\Post');
	}

	public function tags() 
	{
		return $this->belongsToMany('App\Models\Tag');
	}

}
