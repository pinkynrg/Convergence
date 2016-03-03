<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CompanyPerson;

class Person extends CustomModel {

	protected $table = 'people';

	protected $fillable = ['first_name', 'last_name'];
	
	public function company_person() {
		return $this->hasMany('App\Models\CompanyPerson');
	} 

	public function user() {
		return $this->hasOne('App\Models\User');
	}

	public function name() {
		$this->first_name = $this->first_name ? $this->first_name : '[first name missing]';
		$this->last_name = $this->last_name ? $this->last_name : '[last name missing]';
		return $this->last_name." ".$this->first_name;
	}

	public function image() {
		return $this->image ? '/images/profile_pictures/'.$this->image : '/images/profile_pictures/default.png';
	}
}
