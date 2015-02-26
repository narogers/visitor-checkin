<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\RegistrationDetailsRequest;
use App\Http\Requests\RegistrationTypeRequest;
use App\Http\Requests\TermsOfUseAgreementRequest;
use App\Registration;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class RegistrationController extends Controller {

	/**
	 * Instantiate a new RegistrationController
	 */
	public function __construct() {
		$this->middleware('back_button');
		// Eventually make an exception for the confirmation page at
		// the end of the workflow
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$registration = '';
		if (Session::has('registration')) {
			$registration = Session::get('registration');
			Log::info('[INDEX] Retrieved registration inforation from the session');
		} else {
			$registration = new Registration;
			Log::info('[INDEX] Generating a new registration');
		}

        return view('registration.index')->withRegistration($registration);
	}

	public function postIndex() {
		return view('registration.index');
	}

	public function getNew() {
		/**
		 * This is probably not the way you are meant to do this but
		 * it gets the job done for now. Consider a serious refactoring
		 * once some proper unit tests are in place
		 */
		$properties = $this->getRoleView(Session::get('registration')
			->registration_type);
		Log::info('[GET] Directing request to ' . $properties['view']);
 		
 		return view('registration.new')
 		  ->nest('registration_form', $properties['view'])
 		  ->with('label', $properties['label']);		
	}

	public function postNew(RegistrationTypeRequest $request) {
		/**
		 * Switch on the role input to determine which view to
		 * render in the form. If given an invalid role (or no role
		 * at all) redirect to the index page with a flash notice
		 * about the missing role
		 *
		 * It might be helpful not to hardcode this views but that can come
		 * in a later refactoring
		 */
		$properties = $this->getRoleView($request->input('role'));

		Log::info('[POST] Directing request to ' . $properties['view']);
 		/**
 		 * Bail if you don't have a view to supply
 		 */
 		if (null == $properties['view']) {
 			return redirect('register')
 			  ->with('notice', 'Please select a valid role')
 			  ->with('registration', $registration);	
 		}

 		/**
 		 * Store everything in the session so that at the end of the
 		 * registration process you can build up a Registration object
 		 * that gets stored to the database
 		 */
 		$registration = Session::get('registration', new Registration);
 		$registration->name = $request->input('name');
 		$registration->email_address = $request->input('email_address');
 		$registration->registration_type = $request->input('role');
 		Session::put('registration', $registration);

 		/**
 		 * Otherwise continue the process by loading the registration 
 		 * form and supplying it the proper content. Since all forms 
 		 * funnel to the terms of use there is no need for the 
 		 * workflow to diverge here
 		 */
 		return view('registration.new')
 		  ->nest('registration_form', $properties['view'])
 		  ->with('label', $properties['label']);
	}

	public function getTermsOfUse() {
		return view('registration.termsofuse');

	}
	public function postTermsOfUse(RegistrationDetailsRequest $request) {
		Log::info('Submission processed - forwarding to the terms of use for acceptance');

		return view('registration.termsofuse');
	}

	public function postWelcome(TermsOfUseAgreementRequest $request) {
		$registration = Session::get('registration');
		return view('registration.index')
			->with('notice', 'Registration complete')
			->with('registration', $registration);
	}

	/**
	 * Safe methd that captures any requests which could not be matched. It logs the request to the
	 * system and then redirects to the New Visitor Registration page. This does not replace the need
	 * for proper error handling - think of it more as a debugging tool and safeguard
	 */
	public function missingMethod($parameters = array()) {
		Log::error('Could not match request to a specified route - redirecting to the index page');
		Log::error(var_dump($parameters));
		return redirect()->action('RegistrationController@getIndex');
	}

	protected function getRoleView($role = '') {
		Log::info('Processing registration for a(n) ' . $role);

 		switch ($role) {
 			case "Academic":
 				return ['view' => 'registration.forms.academic',
 						'label' => 'Academic Guest'];

 			case "Docent":
 				return ['view' => 'registration.forms.docent',
 						'label' => 'Docent'];

 			case "Fellow":
 				return ['view' => 'registration.forms.fellow',
 						'label' => 'Fellow'];

 			case "Intern":
 				return ['view' => 'registration.forms.intern',
 						'label' => 'Intern'];

 			case "Member":
 				return ['view' => 'registration.forms.member',
 						'label' => 'Member'];

 			case "Public":
 				return ['view' => 'registration.forms.public',
 						'label' => 'Public'];

 			case "Staff":
 				return ['view' => 'registration.forms.staff',
 						'label' => 'Staff'];

 			case "Volunteer":
 				return ['view' => 'registration.forms.volunteer',
 						'label' => 'Volunteer'];
 			default:
 				return ['view' => null,
 						'label' => null];
 		}
	}
}
