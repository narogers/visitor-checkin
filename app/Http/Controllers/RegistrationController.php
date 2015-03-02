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
		$this->middleware('navigation.back',
			['except' => '']);
		$this->middleware('session.registration',
			['except' => '']);
		// Eventually make an exception for the confirmation page at
		// the end of the workflow
	}

	/**
	 * Initial page of the registration process where the visitor is
	 * able to provide reqired information that includes name, email
	 * address, and registration type.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
        return view('registration.index');
	}

	public function postIndex() {
		return view('registration.index');
	}

	public function getNew() {
		/**
		 * This is probably not the way you are meant to do this but
		 * it gets the job done for now. Consider a serious refactoring
		 * once some proper unit tests are in place.
		 *
		 * We can be certain that the session exists because a filter
		 * in the middleware will redirect all requests without one to
		 * the index with a 'Session expired' flash notice
		 */
		$registration = Session::get('registration');
		$properties = $this->getRoleView($registration->registration_type);
		Log::info('[GET] Directing request to ' . $properties['view']);

 		return view('registration.new')
 		  ->withLabel($properties['label'])
		  ->withRegistrationForm($properties['view']);
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
 			  ->with('notice', 'Please select a valid role');
 		}

 		/**
 		 * Store everything in the session so that at the end of the
 		 * registration process you can build up a Registration object
 		 * that gets stored to the database
 		 *
 		 * Also cache the request object so that you can extract it in
 		 * case somebody navigates backwards
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
 		  ->withLabel($properties['label'])
		  ->withRegistrationForm($properties['view']);
	}

	public function getTermsOfUse() {
		return view('registration.termsofuse');

	}
	public function postTermsOfUse(RegistrationDetailsRequest $request) {
		$registration = Session::get('registration');
		$registration->fill($request->all());
		Session::put('registration', $registration);

		return view('registration.termsofuse');
	}

	public function postWelcome(TermsOfUseAgreementRequest $request) {
		$registration = Session::get('registration');
		$registration->signature = $request->get('signature_data');
		$registration->save();
		
		/**
		 * For now hardcode the internal / external flag but eventually
		 * this too can be neatly handled by a piece of middleware
		 */
		return view('registration.welcome')
			->withInternalIp('false');
	}

	/**
	 * Safe methd that captures any requests which could not be matched. It 
	 * logs the request to the system and then redirects to the New Visitor 
	 * Registration page. This does not replace the need for proper error 
	 * handling - think of it more as a debugging tool and safeguard
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
