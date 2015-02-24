<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\RegistrationDetailsRequest;
use App\Http\Requests\RegistrationTypeRequest;
use App\Registration;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class RegistrationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$registration = new Registration;
        return view('registration.index')->with('registration', $registration);
	}

	public function postIndex() {
		return view('registration.index');
	}

	public function getNew() {
		Log::info('SESSION');
		Log::info(var_dump(Session::all()));
		
		return view('registration.forms.academic');
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
		Log::info('Processing registration for a(n) ' . $request->input('role'));

 		$active_view = null;
 		$label = null;

 		switch (strtolower($request->input('role'))) {
 			case "academic":
 				$active_view = 'registration.forms.academic';
 				$label = 'Academic Guest';
 				break;
 			case "docent":
 				$active_view = 'registration.forms.docent';
 				$label = 'Docent';
 				break;
 			case "fellow":
 				$active_view = 'registration.forms.fellow';
 				$label = 'Fellow';
 				break;
 			case "intern":
 				$active_view = 'registration.forms.intern';
 				$label = 'Intern';
 				break;
 			case "member":
 				$active_view = 'registration.forms.member';
 				$label = 'Member';
 				break;
 			case "public":
 				$active_view = 'registration.forms.public';
 				$label = 'Public';
 				break;
 			case "staff":
 				$active_view = 'registration.forms.staff';
 				$label = 'Staff';
 				break;
 			case "volunteer":
 				$active_view = 'registration.forms.volunteer';
 				$label = 'Visitor';
 		}

 		Log::info('Directing request to ' . $active_view);
 		/**
 		 * Bail if you don't have a view to supply
 		 */
 		if (null == $active_view) {
 			return redirect('register')
 			  ->with('notice', 'Please select a valid role')
 			  ->with('registration', $registration);	
 		}

 		/**
 		 * Store everything in the session so that at the end of the
 		 * registration process you can build up a Registration object
 		 * that gets stored to the database
 		 */
 		$registration = new Registration;
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
 		  ->nest('registration_form', $active_view)
 		  ->with('label', $label)
 		  ->with('registration', $registration);
	}

	public function postTermsOfUse(RegistrationDetailsRequest $request) {
		Log::info('Submission processed - forwarding to the terms of use for acceptance');
		Log::info('Session contains ...');
	 	foreach ($request->session()->all() as $value) {
			Log::info($value);
		}
		Log::info('Request contains ...');
		foreach ($request->all() as $value) {
			Log::info($value);
		}

		return view('registration.termsofuse');
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
}
