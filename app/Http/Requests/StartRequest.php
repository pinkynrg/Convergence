<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Session;

class StartRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		$rules['first_name'] = 'string|min:3|required';
		$rules['last_name'] = 'string|min:3|required';

		if (Request::get('use_info_all_contacts') == 'true') {
			$rules["contact.phone"] = 'integer|digits_between:9,10|required';
			$rules["contact.extension"] = 'digits_between:1,4';
			$rules["contact.cellphone"] = 'required|digits_between:9,10';
		}
		else {
			foreach (Request::get('contacts') as $key => $contact) {
				$rules["contacts.$key.phone"] = 'integer|digits_between:9,10|required';
	            $rules["contacts.$key.extension"] = 'digits_between:1,4';
	            $rules["contacts.$key.cellphone"] = 'required|digits_between:9,10';
	        }
	    }

		if (!Session::get('password')) {
		    $rules['password'] = 'required|password';
			$rules['password2'] = 'required|same:password';
		}

		return $rules;
	}

}
