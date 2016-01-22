<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends CustomModel {

	protected $table = 'equipment';

	protected $fillable = ['name','cc_number','serial_number','equipment_type_id','company_id','notes','warranty_expiration'];

	public function equipment_type() {
		return $this->belongsTo('App\Models\EquipmentType');
	}

	public function company() {
		return $this->belongsTo('App\Models\Company');
	}

	public function cc() {
		return 'CC'.$this->cc_number;
	}

}
