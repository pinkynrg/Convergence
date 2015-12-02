<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model {

	protected $table = 'equipments';

	public function equipment_type() {
		return $this->belongsTo('App\Models\EquipmentType');
	}

	public function company() {
		return $this->belongsTo('App\Models\Company');
	}

}
