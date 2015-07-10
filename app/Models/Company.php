<?php namespace Convergence\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model {

	protected $table = 'companies';

	protected $fillable = ['name', 'address', 'country', 'city', 'state', 'zip_code', 'group_email', 'support_type_id', 'connection_type_id'];

	public function tickets() {
		return $this->hasMany('Convergence\Models\Ticket');
	}

	public function equipments() {
		return $this->hasMany('Convergence\Models\Equipment');
	}

	public function main_contact() 
	{	
		return $this->hasOne('Convergence\Models\CompanyMainContact');
	}

	public function contacts() {
		return $this->belongsToMany('Convergence\Models\CompanyPerson','company_person');
	}

	public function account_manager() {
		return $this->hasOne('Convergence\Models\CompanyAccountManager');
	}

	public function connection_type() {
		return $this->belongsTo('Convergence\Models\ConnectionType');
	}

	public function support_type() {
		return $this->belongsTo('Convergence\Models\SupportType');
	}

}
