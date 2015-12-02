<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model {

	protected $table = 'posts';

	protected $fillable = ['ticket_id','post','author_id','is_public'];

	public function ticket() {
		return $this->belongsTo('App\Models\Ticket');		
	}

	public function author()
	{
		return $this->belongsTo('App\Models\CompanyPerson');
	}

}
