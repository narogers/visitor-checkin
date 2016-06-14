<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class RegistrationTypeRequest extends Request {

	/**
	 * Always return true since this application does not have any
	 * authentication layer to protect it
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
		return [
			'name' => 'required',
			'email_address' => 'required|email',
			'role' => 'required|in:Academic,Docent,Fellow,Intern,Member,Public,Staff,Volunteer'
		];
	}

}
