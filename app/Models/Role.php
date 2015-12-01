<?php namespace Convergence\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	protected $table = 'roles';
	protected $fillable = ['name','display_name','description'];

	public function groups()
	{
		return $this->belongsToMany('Convergence\Models\Group');
	}

	public function permissions()
	{
		return $this->belongsToMany('Convergence\Models\Permission');
	}
}