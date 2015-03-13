<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class RegistrationDetailsRequest extends Request {

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
	 * Despite the fact that there are eight types of registration we can condense 
	 * them all into one Request validation with some clever use of mutual exclusion
	 * across types. This might not be the best approach but should suffice for a
	 * first cut
	 *
	 * @return array
	 */
	public function rules()
	{
		$role = Session::get('user')->role->role;
		$rules = $this->rules;

		/**
		 * Apply custom rules for each field that rely on the specified role to
		 * determine if they should apply or not. Note that everything is prefixed
		 * with 'sometimes' because no field is required for every case
		 */
		if (!in_array($role, ['Fellow', 'Staff'])) {
			$rules['address_street'] = 'required';
			$rules['address_city'] = 'required';
			$rules['address_zip'] = 'required|regex:/\d{5}/';

			$rules['telephone'] = 'required|alpha_dash|min:10|max:13';
		}

		/**
		 * But the similar field member's number is only used in a
		 * single case
		 */
		if ('Member' == $role) {
			$rules['badge_number'] = 'required|integer';
		}

		/**
		 * Department is only used by people affiliated with CMA
		 */
		if (in_array($role, ['Fellow', 'Intern', 'Staff'])) {
			$rules['department'] = 'required';
		}
		/**
		 * But the other two are not for temporary interns
		 */
		if (in_array($role, ['Fellow', 'Staff'])) {
			$rules['job_title'] = 'required';
			$rules['extension'] = 'required|integer';
		}
		/**
		 * which get their own special fields
		 */
		if ('Intern' == $role) {
			$rules['supervisor'] = 'required';
			$rules['ending_on'] = 'required|date';
		}

		return $rules;
	}
}
