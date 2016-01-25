<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends CustomModel {

	protected $table = 'posts';

	protected $fillable = ['ticket_id','post','author_id','status_id'];

	public function ticket() {
		return $this->belongsTo('App\Models\Ticket');		
	}

	public function author()
	{
		return $this->belongsTo('App\Models\CompanyPerson');
	}

	public function attachments() {
		return $this->morphMany('App\Models\File','resource');
	}

}
