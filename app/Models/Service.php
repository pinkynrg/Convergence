<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model {

	protected $table = 'services';

	protected $fillable = ['company_id','external_contact_id','internal_contact_id','hotel_id','job_number_onsite','job_number_internal','job_number_remote'];

	public function hotel() 
	{	
		return $this->belongsTo('App\Models\Hotel');
	}

	public function internal_contact()
	{
		return $this->belongsTo('App\Models\CompanyPerson');		
	}

	public function external_contact()
	{
		return $this->belongsTo('App\Models\CompanyPerson');		
	}

	public function company()
	{
		return $this->belongsTo('App\Models\Company');		
	}

	public function service_technicians() {
		return $this->hasMany('App\Models\ServiceTechnician');
	} 

}
