<?php namespace Convergence\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyAccountManager extends Model {

	protected $table = 'company_account_manager';

	public function company_person() {
		return $this->hasOne('Convergence\Models\CompanyPerson','id','account_manager_id');
	}


}
