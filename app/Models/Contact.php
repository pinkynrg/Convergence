<?php namespace Convergence\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model {

	protected $fillable = ['customer_id', 'name', 'phone', 'cellphone', 'email'];
	
	public function customer() {
		return $this->belongsTo('Convergence\Models\Customer');
	}

	public function phone() 
	{
		return preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "+1 ($1) $2-$3", $this->phone);
	}

	public function cellphone() 
	{
		return preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "+1 ($1) $2-$3", $this->cellphone);
	}

	public function name() {
		return $this->name;
	}
}
