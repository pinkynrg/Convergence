<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomModel extends Model {

	public static function all($columns = ['*']) {
		return parent::where('id','!=',0)->get();
	}

}