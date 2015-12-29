<?php namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use App\Http\Requests\Request;
use Auth;

class CreatePermissionRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::user()->can('create-permission');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('permissions.index')->withErrors(['You are not authorized to create a new permission']);
	}

	protected function formatValidationErrors(Validator $validator)
    {
        return $validator->errors()->all();
    }

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'name' => 'required|unique:permissions',
			'display_name' => 'required',
			'description' => 'required'
		];
	}

}
