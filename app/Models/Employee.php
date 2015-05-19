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

	public function phone() 
	{
		return preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "+1 ($1) $2-$3", $this->phone);
	}

	public function name() 
	{
		$this->first_name = $this->first_name ? $this->first_name : '[first name missing]';
		$this->last_name = $this->last_name ? $this->last_name : '[last name missing]';
		return $this->last_name." ".$this->first_name;
	}

}
