<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceTechnician extends CustomModel {

	protected $table = 'service_technician';

	protected $fillable = ['service_id','technician_id','division_id','work_description','internal_start','internal_end','internal_estimated_hours','onsite_start','onsite_end','onsite_estimated_hours','remote_start','remote_end','remote_estimated_hours'];

	public function technician()
	{
		return $this->belongsTo('App\Models\CompanyPerson');		
	}

	public function division()
	{
		return $this->belongsTo('App\Models\Division');		
	}


}
