<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model {

	protected $table = 'hotels';

	protected $fillable = ['name','address','company_id'];

	public function rating() {
		return isset($this->rating) ? $this->rating."/5" : "-";
	}

	public function walking_time() {
		return $this->secondsToTime($this->walking_time);
	}

	public function driving_time() {
		return $this->secondsToTime($this->driving_time);
	}

	public function distance($in_km = false) {
		if ($in_km) {
			$distance = $this->distance/1000;
			return round($distance, 2)." Km";
		}
		else {
			$distance = $this->distance/1609;			
			return round($distance, 2)." Miles";
		}
	}

	public function secondsToTime($seconds) {
    	$dtF = new \DateTime("@0");
    	$dtT = new \DateTime("@$seconds");
    	return $dtF->diff($dtT)->format('%h hours, %i minutes and %s seconds');
	}
}
