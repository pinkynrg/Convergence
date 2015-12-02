<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyMainContact extends Model {

	protected $table = 'company_main_contact';

	public function company_person() {
		return $this->hasOne('App\Models\CompanyPerson','id','main_contact_id');
	}


}
