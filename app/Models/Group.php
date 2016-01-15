<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends CustomModel {

	protected $table = 'groups';
	protected $fillable = ['name','display_name','description','group_type_id'];

	public function roles()
	{
		return $this->belongsToMany('App\Models\Role');
	}

	public function group_type() 
	{
		return $this->belongsTo('App\Models\GroupType');
	}

}
