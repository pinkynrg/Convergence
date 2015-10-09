<?php namespace Convergence\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {

	protected $table = 'posts';

	protected $fillable = ['ticket_id','post','author_id','is_public'];

	public function author()
	{
		return $this->belongsTo('Convergence\Models\CompanyPerson');
	}

}
