<?php namespace Convergence\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model {

	protected $fillable = ['customer_id', 'name', 'phone', 'cellphone', 'email'];
	
	public function customer() {
		return $this->belongsTo('Convergence\Models\Customer');
	}

	public function name() {
		return $this->name;
	}
}
