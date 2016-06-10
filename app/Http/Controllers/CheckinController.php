<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
// Models used to pass information to views
use App\Models\User;
// Interfaces with backend services
use App\ILS\ILSInterface;
use App\Repositories\PatronInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CheckinController extends Controller {
    /**
     * Interfaces to data stores 
     */
    protected $ils;
    protected $patrons;
  
    /**
     * Construct a new instance 
     *
     * @param PatronInterface
     * @return CheckinController
     */
    public function __construct(PatronInterface $patrons, ILSInterface $ils) {
      $this->patrons = $patrons;
      $this->ils = $ils;
    }	
   
    /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		return view('checkin.index');
	}

    public function postIndex(Request $request) {
      $limit = $request->has('barcode') ?
        $request->get('barcode') :
        $request->get('name', null);
      $users = $this->patrons->getRegisteredUsers($limit);
 
      Log::info("[CHECKIN] " . count($users) . " results found for ${limit}");
      $total = count($users);

      /**
       * Exit point #1
       * Exactly one match found
       */
      if (1 == $total) {
        Session::put("uid", $users[0]["id"]);
        $this->patrons->checkin($users[0]["id"]);

        if ($this->ils->isActive($users[0]["aleph_id"])) {
          return redirect()->action("CheckinController@getConfirmation");
        } else {
          return redirect()->action("CheckinController@getExpired");
        }
      } 

      /**
       * Exit point #2
       * No results or too many results found
       */
      if ((0 == $total) || ($total > config('app.select_threshold', 5))) {
        return redirect()->action("CheckinController@getNotFound");
      }

      /**
       * Exit point #3
       * Need to provide a list of users to help select the correct
       * person
       */
      Session::put("users", $users);
      return redirect()->action("CheckinController@getSelect");
	}

    public function getCheckin(Request $request) {
      if ($request->has('barcode') || $request->has('name')) {
        return redirect()->action("CheckinController@postCheckin");
      }
      
      return redirect()->action("CheckinController@getIndex");
	}

    /**
     * Permit patrons to select from a list of available options to check
     * in to the library
     *
     * @return Response
     */
    public function getSelect() {
      $users = Session::pull('users', []);
      return view("checkin.select")->withUsers($users);
    }

    /**
     * Allow for expired guests to resign the terms of use
     *
     * @return Response
     */
    public function getExpired() {
      return view("checkin.expired");
    }

	/**
	 * API call that updates the signature data for a user and
	 * possibly even creates it if the record does not exist yet
	 *
	 * One possibility to consider is a matching getExpired()
	 * method then rely on redirects to handle logic related to
	 * this scenario
	 */
	public function postExpired(Request $request) {
        $uid = Session::get("uid");
		$signature = $request->get("signature_data");
        $this->patrons->update($uid, ["signature" => $signature]);
        $u = $this->patrons->getUser($uid);

        // TODO: Send a reminder email to the circulation staff?
        return redirect()->action("CheckinController@getConfirmation");
	}

    /**
     * Show confirmation of successful check in
     *
     * @return Response
     */
    public function getConfirmation() {
      $uid = Session::pull("uid");
      $user = $this->patrons->getUser($uid);
    
      return view("checkin.confirmation")
        ->withUser($user);
    }

    /**
     * Report no results found and offer an opportunity to reenter a
     * search parameter 
     *
     * @return Response
     */
    public function getNotFound() {
      return view("checkin.notfound");
    }
}
