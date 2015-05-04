<?php namespace Convergence\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model {

	protected $table = 'equipments';

	public function equipment_type() {
		return $this->belongsTo('Convergence\Models\EquipmentType');
	}

	public function customer() {
		return $this->belongsTo('Convergence\Models\Customer');
	}

}
