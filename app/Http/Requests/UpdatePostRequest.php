<?php namespace App\Http\Requests;

use App\Http\Requests\Request;
use Auth;

class UpdatePostRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return Auth::user()->can('update-post');
	}

	public function forbiddenResponse()
	{
		return redirect()->route('posts.show',$this->route("id"))->withErrors(['You are not authorized to update posts']);
	}


	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'post' => 'required|string'
		];
	}

}
