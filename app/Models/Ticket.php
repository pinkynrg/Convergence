<?php namespace Convergence\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {

	protected $table = 'tickets';

	public function status()
	{
		return $this->belongsTo('Convergence\Models\Status');
	}

	public function priority()
	{
		return $this->belongsTo('Convergence\Models\Priority');		
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
}
