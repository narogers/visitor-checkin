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
		$role = Session::get('registration')->registration_type;
		$rules = $this->rules;

		/**
		 * Apply custom rules for each field that rely on the specified role to
		 * determine if they should apply or not. Note that everything is prefixed
		 * with 'sometimes' because no field is required for every case
		 */
		if (!in_array($role, ['Fellow', 'Staff'])) {
			$rules['street_address'] = 'required';
			$rules['city'] = 'required';
			$rules['zip_code'] = 'required|numeric';

			$rules['telephone'] = 'required|alpha_dash';
		}

		/**
		 * Driver's license is only required for two cases
		 */
		if (in_array($role, ['Academic', 'Public'])) {
			$rules['license'] = 'required';
		}
		/**
		 * But the similar field member's number is only used in a
		 * single case
		 */
		if ('Member' == $role) {
			$rules['badge_number'] = 'required|int';
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
			$rules['extension'] = 'required|int';
		}
		/**
		 * which get their own special fields
		 */
		if ('Intern' == $role) {
			$rules['supervisor'] = 'required';
			$rules['ending_date'] = 'required|date';
		}

		Log::info('Following rules have been applied to data');
		Log::info($rules);

		return $rules;
	}
}
