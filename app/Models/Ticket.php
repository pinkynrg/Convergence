<?php namespace Convergence\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {

	protected $table = 'tickets';

	protected $fillable = ['title','post','creator_id','assignee_id','status_id','priority_id','division_id','equiment_id','company_id','contact_id','job_type_id'];

	public function status()
	{
		return $this->belongsTo('Convergence\Models\Status');
	}

	public function priority()
	{
		return $this->belongsTo('Convergence\Models\Priority');		
	}

	public function job_type()
	{
		return $this->belongsTo('Convergence\Models\JobType');		
	}

	public function assignee()
	{
		return $this->belongsTo('Convergence\Models\CompanyPerson');		
	}

	public function creator()
	{
		return $this->belongsTo('Convergence\Models\CompanyPerson');		
	}

	public function company()
	{
		return $this->belongsTo('Convergence\Models\Company');		
	}

	public function division()
	{
		return $this->belongsTo('Convergence\Models\Division');
	}

	public function contact() 
	{
		return $this->belongsTo('Convergence\Models\CompanyPerson');
	}

	public function posts() 
	{
		return $this->hasMany('Convergence\Models\Post');
	}

	public function tags() 
	{
		return $this->belongsToMany('Convergence\Models\Tag');
	}

	public function history() 
	{
		return $this->hasMany('Convergence\Models\TicketHistory')->orderBy('created_at');
	}

}
