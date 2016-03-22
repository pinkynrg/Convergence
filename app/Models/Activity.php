<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends CustomModel {

	protected $table = 'activity_log';

	public function user() {
		return $this->belongsTo('App\Models\User');
	}

	public function contact() {
		return $this->belongsTo('App\Models\CompanyPerson');
	}


	public function text() {
		if (isset($this->text)) {
			$text = $this->text;
		}
		else {
			$temp = explode(".",$this->route);
			$target = $temp[0];
			$action = $temp[1];
			$text = ucfirst($target)." ".ucfirst($action);
		}
		return $text;
	}
}
