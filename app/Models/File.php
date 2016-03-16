<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends CustomModel {

	protected $table = 'files';

	public function resource() {
		return $this->morphTo();
	}

	public function path() {
		return DS.'files'.DS.$this->id;
	}

	public function real_path() {
		return IMAGES.DS.$this->file_path.DS.$this->file_name;
	}

	public function thumbnail() {
		$file_exists = $this->thumbnail_id;
		$thumbnail_url = DS.'files'.DS;
		$thumbnail_url .= $file_exists ? $this->thumbnail_id : DEFAULT_MISSING_PICTURE_ID;
		return $thumbnail_url;
	}

	public function is_image() {
		return  in_array($this->file_extension, ['gif','jpeg','jpg','png']) ? true : false;
	}

	public function name() {
		$length = strlen($this->name);
		return ($length > 16) ? substr($this->name,0,7)."...".substr($this->name,$length-8,$length) : $this->name;
	}
}
