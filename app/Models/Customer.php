<?php namespace Convergence\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model {

	protected $fillable = ['company_name', 'country', 'state', 'city', 'address', 'zip_code', 'group_email', 'account_manager_id','airport','plant_requirment'];

	public function tickets() {
		return $this->hasMany('Convergence\Models\Ticket');
	}

	public function equipments() {
		return $this->hasMany('Convergence\Models\Equipment');
	}

	public function main_contact() 
	{
		return $this->belongsTo('Convergence\Models\Contact');
	}

	public function contacts() {
		return $this->hasMany('Convergence\Models\Contact');
	}

	public function account_manager() {
		return $this->belongsTo('Convergence\Models\Employee');
	}

}
