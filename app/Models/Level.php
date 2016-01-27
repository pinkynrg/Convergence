<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Level extends CustomModel
{
	protected $table = 'levels';
	protected $fillable = ['name'];
}