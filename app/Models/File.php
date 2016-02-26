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
		$file_exists = $this->thumbnail_id;
		$thumbnail_url = DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR;
		$thumbnail_url .= $file_exists ? $this->thumbnail_id : 'missing';
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
