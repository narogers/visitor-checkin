<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CheckinRequest extends Request {

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
		/**
		 * TODO: Query the database to make sure the name / email address 
		 *       combination are unique. If not tell the person they are
		 *       already registered and (if local) kick them over to the
		 *		 check in side
		 */
		return [
			'name' => 'required',
		];
	}

}
