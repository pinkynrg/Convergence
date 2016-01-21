<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends CustomModel {

	protected $table = 'files';

	public function resource() {
		return $this->morphTo();
	}

	public function path() {
		return DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$this->id;
	}

	public function real_path() {
		return base_path().DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$this->file_path.DIRECTORY_SEPARATOR.$this->file_name;
	}

	public function thumbnail() {
		return DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$this->thumbnail_id;
	}

	public function extension() {
		$temp = explode(".",$this->file_name);
		return $temp[count($temp)-1];
	}
}
