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
		$first_name = $this->first_name ? $this->first_name : '[first name missing]';
		$last_name = $this->last_name ? $this->last_name : '[last name missing]';
		return $last_name." ".$first_name;
	}

	public function short_name() {
		$first_letter_first_name = $this->first_name ? substr($this->first_name,0,1)."." : '[missing].';
		$last_name = $this->last_name ? $this->last_name : '[last name missing]';
		return $last_name." ".$first_letter_first_name;
	}

	public function profile_picture() {
		$picture = File::find($this->profile_picture_id);
		return $picture ? $picture : File::find(DEFAULT_PROFILE_PICTURE_ID);
	}
}
