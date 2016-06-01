<?php 

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\RegistrationDetailsRequest;
use App\Http\Requests\RegistrationTypeRequest;
use App\Http\Requests\TermsOfUseAgreementRequest;
use App\Registration;
use App\Role;
use App\User;

use App\Repositories\PatronInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class RegistrationController extends Controller {
    protected $patrons;

	/**
	 * Instantiate a new RegistrationController
     *
     * @param PatronInterface $patrons
	 */
	public function __construct(PatronInterface $patrons) {
      Log::info("w00t w00t");
      $this->patrons = $patrons;
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
  
	public function postNew(RegistrationTypeRequest $request) {
      $patron = $this->patrons->createOrFindUser($request->only('name', 'email_address'));

      if (isset($patron) && $patron->isIncomplete()) {
        Session::put('uid', $patron->id);
        return redirect()->action('RegistrationController@getDetails',
          ['role' => strtolower($request->role)]);
      } else {
        // Because we are not doing this in the RegistrationTypeRequest we
        // have to do it here
        return back()->withInput()->with('error', 'Email address has already been registered.');
      }
	}

    /**
     * Captures registration details depending on the provided role. If none
     * is provided then it will redirect to the index page
     *
     * @param string 
     * @return Response
     */
    public function getDetails($role) {
     if ($this->patrons->hasRole($role)) {
        Session::put('role', $role);
        $form = "registration.forms.${role}";
        return view($form);
     } else {
       return redirect()->action('RegistrationController@getIndex');
     }
    }

    /**
     * Store registration details and then proceed to the terms of use
     *
     * @param RegistrationTypeRequest $request
     * @return Response
     */
   public function postDetails(RegistrationDetailsRequest $request) {
     $uid = Session::get('uid');
     $role = Session::get('role');

     $this->patrons->setRegistration($uid, $request->all());
     $this->patrons->setRole($uid, $role);
 
     return redirect()->action('RegistrationController@getTermsOfUse');
   }

   /*
    * Show terms of use for final acceptance before the record is commited
    * to the database
    *
    * @return Response
    */
  public function getTermsOfUse() {
    return view('registration.termsofuse');
  }

  /**
   * Write the signature to the database and finish the registration
   * process
   *
   * @param TermsOfUseAgreementRequest $request
   * @return Response
   */
  public function postTermsOfUse(TermsOfUseAgreementRequest $request) {
    $uid = Session::get("uid");
    $signature =  $request->get('signature_data');
    $this->patrons->update($uid, ["signature" => $signature]);

    return redirect()->action("RegistrationController@getConfirmation"); 
  }

  /**
   * Confirmation of registration
   *
   * @return Response
   */
  public function getConfirmation() {
    $uid = Session::get('uid');
    $user = $this->patrons->getUser($uid);

    return view('registration.confirmation')
      ->withUser($user);
  }
}
