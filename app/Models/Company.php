<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends CustomModel {

	protected $table = 'companies';

	protected $fillable = ['name', 'address', 'country', 'city', 'state', 'zip_code', 'group_email', 'support_type_id', 'connection_type_id'];

	public function tickets() {
		return $this->hasMany('App\Models\Ticket');
	}

	public function equipment() {
		return $this->hasMany('App\Models\Equipment');
	}

	public function hotels() {
		return $this->hasMany('App\Models\Hotel');		
	}

	public function services() {
		return $this->hasMany('App\Models\Service');		
	}

	public function main_contact() 
	{	
		return $this->hasOne('App\Models\CompanyMainContact');
	}

	public function contacts() {
		return $this->belongsToMany('App\Models\CompanyPerson','company_person');
	}

	public function account_manager() {
		return $this->hasOne('App\Models\CompanyAccountManager');
	}

	public function connection_type() {
		return $this->belongsTo('App\Models\ConnectionType');
	}

	public function support_type() {
		return $this->belongsTo('App\Models\SupportType');
	}

}
