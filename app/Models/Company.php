<?php namespace Convergence\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model {

	protected $table = 'companies';

	protected $fillable = ['name', 'address', 'country', 'city', 'state', 'zip_code', 'group_email', 'airport', 'plant_requirment'];

	public function tickets() {
		return $this->hasMany('Convergence\Models\Ticket');
	}

	public function equipments() {
		return $this->hasMany('Convergence\Models\Equipment');
	}

	public function main_contact() 
	{	
		return $this->belongsToMany('Convergence\Models\Person','company_main_contact','company_id','main_contact_id');
	}

	public function contacts() {
		return $this->belongsToMany('Convergence\Models\Person','company_person');
	}

	public function account_manager() {
		return $this->belongsToMany('Convergence\Models\Person','company_account_manager','company_id','account_manager_id');
	}

}
