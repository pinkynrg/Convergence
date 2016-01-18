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
		return $this->file_path.DIRECTORY_SEPARATOR.$this->file_name;
	}

	public function thumbnail() {
		return DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.$this->thumbnail_id;
	}

	public function extension() {
		$temp = explode(".",$this->file_name);
		return $temp[count($temp)-1];
	}

	public function thumb() {
		switch ($this->extension()) {
			case 'pdf':
			case 'png':
			case 'jpg':
			case 'gif': return $this->path();
		}
	}

}
