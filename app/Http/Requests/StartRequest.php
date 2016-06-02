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
			$rules["contact.phone"] = 'numeric|required';
			$rules["contact.extension"] = 'digits_between:1,6';
			$rules["contact.email"] = 'required|email';
		}
		else {
			foreach (Request::get('contacts') as $key => $contact) {
				$rules["contacts.$key.phone"] = 'numeric|required';
	            $rules["contacts.$key.extension"] = 'digits_between:1,6';
				$rules["contacts.$key.email"] = 'required|email';
	        }
	    }

		if (!Session::get('start_session.safe_enough')) {
		    $rules['password'] = 'required|password';
			$rules['password2'] = 'required|same:password';
		}

		return $rules;
	}

}
