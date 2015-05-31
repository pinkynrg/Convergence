<?php namespace Convergence\Models;

use Illuminate\Database\Eloquent\Model;
use Convergence\Models\CompanyPerson;

class Person extends Model {

	protected $table = 'people';

	protected $fillable = ['first_name', 'last_name', 'phone', 'extension', 'cellphone','email'];
	
	public function phone() 
	{
		return preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "+1 ($1) $2-$3", $this->phone);
	}

	public function cellphone() 
	{
		return preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "+1 ($1) $2-$3", $this->cellphone);
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
