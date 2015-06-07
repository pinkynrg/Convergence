<?php namespace Convergence\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyPerson extends Model {

	protected $table = 'company_person';

	protected $fillable = ['company_id', 'person_id', 'title_id', 'department_id', 'phone', 'extension', 'cellphone','email'];

	public function company() {
		return $this->belongsTo('Convergence\Models\Company');
	}

	public function person() {
		return $this->belongsTo('Convergence\Models\Person');
	}

	public function department() {
		return $this->belongsTo('Convergence\Models\Department');
	}

	public function title() {
		return $this->belongsTo('Convergence\Models\Title');
	}

	public function phone() 
	{
		$phone = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "+1 ($1) $2-$3", $this->phone);
		
		if ($phone != '') {
			$phone = "<a href='tel:+1".$this->phone."'>".$phone;
			$phone .= $this->extension != '' ? " ext. ".$this->extension : '';
			$phone .= "</a>";
		}

		return $phone;
	}

	public function cellphone() 
	{
		$cellphone = preg_replace("/^1?(\d{3})(\d{3})(\d{4})$/", "+1 ($1) $2-$3", $this->cellphone);
		$cellphone = $cellphone != '' ? "<a href='tel:+1".$this->cellphone."'>".$cellphone."</a>" : '';
		return $cellphone;
	}

	public function email() {
		$email = isset($this->email) ? "<a href='mailto:".$this->email."'>".$this->email."</a>" : "";
		return $email;
	}


}
