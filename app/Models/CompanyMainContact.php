<?php namespace Convergence\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyMainContact extends Model {

	protected $table = 'company_main_contact';

	public function company_person() {
		return $this->hasOne('Convergence\Models\CompanyPerson','id','main_contact_id');
	}


}
