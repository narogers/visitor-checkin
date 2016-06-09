<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ILS\ILSInterface;
use App\Repositories\PatronInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AdminRegistrationController extends Controller {
    protected $patrons;
    protected $ils;

    /**
     * Instantiate a new controller with access to services
     *
     * @param PatronInterface $patronRepo
     * @return AdminRegistrationController
     */
    public function __construct(PatronInterface $patronRepo,
      ILSInterface $ils) {
      $this->patrons = $patronRepo;
      $this->ils = $ils;
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$pending = $this->patrons->getPendingRegistrations();
		return view('admin.registration.index')
			->withUsers($pending);
	}

	/**
	 * Displays registration details for a specific visitor
	 *
	 * @return Response
	 */
	public function getRegistration($uid) {
      $user = $this->patrons->getUser($uid);
      return view('admin.registration.show')
	    ->withUser($user);
	}

	/**
	 * Based on the command passed to the URL either pings Aleph to see if an
	 * ID has been created yet or updates the Verified User flag to be true.
	 * It will then redirect to the user's record with the registration 
	 * information and updated statuses displayed
	 *
	 * @return Response
	 */
	public function postRegistration($uid, Request $request) {
		$action = $request->input('action');

		Log::info('[ADMIN] Preparing to process command ' . $action);
		switch ($action) {
		  case 'refresh_ils':
		    $this->updateILSId($uid, $request);
			break;
		  case 'verify_id':
			$this->setVerifiedStatus($uid, $request);
		}
    
        $user = $this->patrons->getUser($uid);
		return view('admin.registration.show')
		  ->withUser($user);
	}

	/**
	 * Ping Aleph for a new ID and, if found, refresh the record
     *
     * @param $uid
     * @return boolean
	 */
	protected function updateILSId($uid, $request) {
      $user = $this->patrons->getUser($uid);
 	  Log::info('[ADMIN] Updating Aleph ID for ' . $user->email_address);

      $default_ids = $request->has("ils_id") ?
        [$request->get("ils_id")] :
 	    $this->ils->getIdentifiers($user->name);
      $verified_id = null;

      foreach ($default_ids as $ils_id) {
        $details = $this->ils->getPatronDetails($ils_id);
 
        if (isset($details) && 
           (0 == strcasecmp($details["name"], $user->name))) {
          $this->patrons->update($user->id, ["aleph_id" => $details["id"]]); 
          $verified_id = $ils_id;
          break;
        }
      }

	  if (null == $verified_id) {
		Session::flash('errors', 'Could not resolve ' . $default_ids[0]);
		Log::info('[ADMIN] Unable to resolve an Aleph ID');
	  } else {
		Session::flash('alert', 'Aleph ID has been set to ' . $verified_id);
        Log::info("[ADMIN] Resolved identifier to ${verified_id}");
	  }
	}

	/**
	 * Sets the verified status to true for the given user
	 */
	protected function setVerifiedStatus($uid, $request) {
		Log::info('[ADMIN] Setting verified status for ' . $uid);
        $this->patrons->update($uid, ["verified_user" => true]);

		Session::flash('alert', "The visitor has now been marked as verified. If this is a renewal check Aleph to make sure that the expiration date has been updated.");
	}
}
