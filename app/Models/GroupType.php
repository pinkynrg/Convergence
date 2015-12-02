<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupType extends Model {

	protected $table = 'group_types';
	protected $fillable = ['name','display_name','description'];

}
