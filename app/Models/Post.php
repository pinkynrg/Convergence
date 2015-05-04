<?php namespace Convergence\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {

	public function author()
	{
		return $this->belongsTo('Convergence\Models\Employee');
	}

}
