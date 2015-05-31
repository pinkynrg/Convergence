<?php namespace Convergence\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {

	protected $table = 'posts';

	public function author()
	{
		return $this->belongsTo('Convergence\Models\Person');
	}

}
