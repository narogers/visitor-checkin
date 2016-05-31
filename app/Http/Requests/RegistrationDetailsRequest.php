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
	 * Validate each type of input based on its unique composition of fields
     *
	 * @return array
	 */
	public function rules()
	{
        $role = Session::get('role');
		$rules = $this->rules;

		/**
		 * Apply custom rules for each field that rely on the specified role to
		 * determine if they should apply or not. Note that everything is prefixed
		 * with 'sometimes' because no field is required for every case
		 */
		if (!in_array($role, ['fellow', 'staff'])) {
			$rules['address_street'] = 'required';
			$rules['address_city'] = 'required';
			$rules['address_zip'] = 'required|regex:/\d{5}/';
		}
		if (!in_array($role, ['fellow', 'staff', 'intern'])) {
			$rules['telephone'] = 'required|alpha_dash|min:7|max:13';
		}

		/**
		 * But the similar field member's number is only used in a
		 * single case
		 */
		if ('member' == $role) {
			$rules['badge_number'] = 'required|integer';
		}

		/**
		 * Department is only used by people affiliated with CMA
		 */
		if (in_array($role, ['fellow', 'intern', 'staff'])) {
			$rules['department'] = 'required';
		}
		/**
		 * But the other two are not for temporary interns
		 */
		if (in_array($role, ['fellow', 'staff'])) {
			$rules['job_title'] = 'required';
			$rules['extension'] = 'required|integer';
		}
		/**
		 * which get their own special fields
		 */
		if ('intern' == $role) {
			$rules['supervisor'] = 'required';
			$rules['expires_on'] = 'required|date';
		}

		return $rules;
	}
}
