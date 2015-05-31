<?php namespace Convergence\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPerson extends Model {

	protected $table = 'company_person';

	public function company() {
		return $this->belongsTo('Convergence\Models\Company');
	}

	public function person() {
		return $this->belongsTo('Convergence\Models\Person');
	}

	public function department() {
		return $this->belongsTo('Convergence\Models\Department');
	}

	public function title() {
		return $this->belongsTo('Convergence\Models\Title');
	}

}
