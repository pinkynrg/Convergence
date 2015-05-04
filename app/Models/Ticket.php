<?php namespace Convergence\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model {

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
		return $this->belongsTo('Convergence\Models\Employee');		
	}

	public function creator()
	{
		return $this->belongsTo('Convergence\Models\Employee');		
	}

	public function customer()
	{
		return $this->belongsTo('Convergence\Models\Customer');		
	}

	public function division()
	{
		return $this->belongsTo('Convergence\Models\Division');
	}

	public function contact() 
	{
		return $this->belongsTo('Convergence\Models\Contact');
	}

	public function posts() 
	{
		return $this->hasMany('Convergence\Models\Post');
	}
}
