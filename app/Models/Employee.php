<?php namespace Convergence\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model {

	protected $fillable = ['first_name', 'last_name', 'department_id', 'title_id', 'phone', 'email'];

	public function department()
	{
		return $this->belongsTo('Convergence\Models\Department');
	}

	public function title()
	{
		return $this->belongsTo('Convergence\Models\Title');		
	}

	public function name() 
	{
		$this->first_name = $this->first_name ? $this->first_name : '[first name missing]';
		$this->last_name = $this->last_name ? $this->last_name : '[last name missing]';
		return $this->first_name." ".$this->last_name;
	}

}
