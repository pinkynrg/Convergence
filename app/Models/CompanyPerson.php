<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPerson extends CustomModel {

	protected $table = 'company_person';

	protected $fillable = ['company_id', 'person_id', 'title_id', 'department_id', 'phone', 'extension', 'cellphone','email','group_id','division_ids'];

	public function company() {
		return $this->belongsTo('App\Models\Company');
	}

	public function person() {
		return $this->belongsTo('App\Models\Person');
	}

	public function department() {
		return $this->belongsTo('App\Models\Department');
	}

	public function title() {
		return $this->belongsTo('App\Models\Title');
	}

	public function phone() 
	{
		return $this->parsePhoneNumber($this->phone,$this->extension);
	}

	public function cellphone() 
	{
		return $this->parsePhoneNumber($this->cellphone);
	}

	public function email() {
		$email = isset($this->email) ? "<a href='mailto:".$this->email."'>".$this->email."</a>" : "";
		return $email;
	}

	public function group() {
		return $this->belongsTo('App\Models\Group');
	}

	public function group_type() {
		return $this->belongsTo('App\Models\GroupType');
	}

	public function isE80() {
		$result = $this->company_id == ELETTRIC80_COMPANY_ID;
		return $result;
	}

	public function division_ids() {
		return explode(",",$this->division_ids);
	}

	private function parsePhoneNumber($number, $ext = null) {
		$phone = "";
		$phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
		try {
			$parsed = $phoneUtil->parse($number, \libphonenumber\PhoneNumberFormat::INTERNATIONAL);
			if ($phoneUtil->isValidNumber($parsed)) {
				$phone = $phoneUtil->format($parsed, \libphonenumber\PhoneNumberFormat::INTERNATIONAL);
				$phone .= isset($ext) && $ext != '' ? ' ext. '.$ext : '';
			}
		}
		catch (\libphonenumber\NumberParseException $e) {
 		   // var_dump($e);
		}

		return $phone;
	}
}
