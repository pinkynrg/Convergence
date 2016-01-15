<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends CustomModel
{
	protected $table = 'roles';
	protected $fillable = ['name','display_name','description'];

	public function groups()
	{
		return $this->belongsToMany('App\Models\Group');
	}

	public function permissions()
	{
		return $this->belongsToMany('App\Models\Permission');
	}
}