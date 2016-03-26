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
		return RESOURCES.DS.$this->file_path.DS.$this->file_name;
	}

	public function thumbnail() {
		$file_exists = $this->thumbnail_id;
		
		if ($file_exists) {
			$file_id = $this->thumbnail_id;
		}
		elseif (in_array(strtolower($this->file_extension),['rar','7z','zip'])) {
			$file_id = COMPRESSED_FILE_PICTURE_ID;
		}
		elseif ($this->file_extension == "msg") {
			$file_id = EMAIL_FILE_PICTURE_ID;
		}
		else {
			$file_id = DEFAULT_MISSING_PICTURE_ID;
		}

		$thumbnail_url = DS.'files'.DS.$file_id;

		return $thumbnail_url;
	}

	public function is_image() {
		return  in_array($this->file_extension, ['gif','jpeg','jpg','png','bmp']) ? true : false;
	}

	public function name() {
		$length = strlen($this->name);
		return ($length > 16) ? substr($this->name,0,7)."...".substr($this->name,$length-8,$length) : $this->name;
	}
}
