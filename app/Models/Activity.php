<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends CustomModel {

	protected $table = 'activity_log';

	public function user() {
		return $this->belongsTo('App\Models\User');
	}
}
