<?php namespace App\Http\Controllers;

use App\Http\Requests\RegistrationTypeRequest;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegistrationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
        return view('registration.index');
	}

	public function postIndex(RegistrationTypeRequest $request) {
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
 				break;
 			case "public":
 				$active_view = 'registration.forms.public';
 				$label = 'Public';
 				break;
 			case "staff":
 				$active_view = 'registration.forms.staff';
 				$label = 'Staff';
 				break;
 			case "visitor":
 				$active_view = 'registrations.forms.visitor';
 				$label = 'Visitor';
 		}

 		Log::info('Directing request to ' . $active_view);
 		/**
 		 * Bail if you don't have a view to supply
 		 */
 		if (null == $active_view) {
 			return redirect('register')->with('notice', 'Please select a valid role');	
 		}
 		/**
 		 * Otherwise continue the process by loading the registration form
 		 * and supplying it the proper content. Since all forms funnel to
 		 * the terms of use there is no need for the workflow to diverge here
 		 */
 		return view('registration.new')->nest('registration_form', $active_view)->with('label', $label);
	}

	public function postTermsOfUse() {
		Log::info('Submission processed - forwarding to the terms of use for acceptance');

		return view('registration.termsofuse');
	}
}
