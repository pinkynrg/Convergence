<?php namespace Convergence\Models;

use Illuminate\Database\Eloquent\Model;
use Convergence\Models\CompanyPerson;

class Person extends Model {

	protected $table = 'people';

	protected $fillable = ['first_name', 'last_name'];
	
	public function company_person() {
		return $this->hasMany('Convergence\Models\CompanyPerson');
	} 

	public function name() {
		$this->first_name = $this->first_name ? $this->first_name : '[first name missing]';
		$this->last_name = $this->last_name ? $this->last_name : '[last name missing]';
		return $this->last_name." ".$this->first_name;
	}

	public function isE80() {
		$result = CompanyPerson::where('company_id','=',1)->where('person_id','=',$this->id)->get();
		return count($result) ? true : false;
	}
}
