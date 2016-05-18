<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomModel extends Model {

    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function date($option, $pretty = false) {
    	if ($option == "created_at" || $option == "updated_at") {
    		if ($pretty) return date("d F Y",strtotime($this->$option))." ~ ".date("g:ia",strtotime($this->$option));
    		else return date("m/d/y",strtotime($this->$option))." - ".date("g:ia",strtotime($this->$option));
    	}
    	else {
    		throw new \Exception('Model date() method, invalid argument 1');
    	}
    }

    public function setNameAttribute($name)
    {
        $this->attributes['name'] = trim($name) !== '' ? $name : null;
    }

    public function setDescriptionAttribute($description)
    {
        $this->attributes['description'] = trim($description) !== '' ? $description : null;
    }

    public function setTitleIdAttribute($number)
    {
        $this->attributes['title_id'] = trim($number) !== '' ? $number : null;
    }

    public function setDepartmentIdAttribute($number)
    {
        $this->attributes['department_id'] = trim($number) !== '' ? $number : null;
    }

}