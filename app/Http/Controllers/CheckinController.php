<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ILS\ILSInterface;
use App\Repositories\PatronInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        $this->patrons->checkin($users[0]["id"]);
        if ($this->ils->isActive($users[0]["aleph_id"])) {
          return redirect()->action("CheckinController@getConfirmation")
            ->withUser($users[0]);
        } else {
          return redirect()->action("CheckinController@getTermsOfUse")
            ->withUser($users[0]);
        }
      } 

      /**
       * Exit point #2
       * No results or too many results found
       */
      if ((0 == $total) || ($total > Config('app.select_threshold'))) {
        return redirect()->action("CheckinController@getNotFound");
      }

      /**
       * Exit point #3
       * Need to provide a list of users to help select the correct
       * person
       */
      return redirect()->action("CheckinController@getSelect")
        ->withUsers($users);      
	}

    public function getCheckin(Request $request) {
      if ($request->has('barcode') || $request->has('name')) {
        return redirect()->action("CheckinController@postCheckin");
      }
      
      return view('checkin.index');
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
        Log::info("[RENEWAL] Looking for user with id " . $request->input('uid'));
		$user = User::find($request->input('uid'));
		$view = '';
		$message_key = '';

		if (null == $user) {
          $view = 'checkin.retry';
          $message_key = 'checkin.notfound';
        } else {
		  $user->signature = $request->input['signature'];
		  $user->addCheckin();
		  $user->save();

		  $view = 'checkin.welcome';
		  $message_key = 'checkin.success';
	    }

		return view($view)
			->withMessageKey($message_key)
			->withUser($user);
	}

    /**
     * Show confirmation of successful check in
     *
     * @return Response
     */
    public function getConfirmation(Request $request) {
      return view("checkin.confirmation");
    }

	/**
	 * Uses a registration status to determine which view to return for the
	 * checkin process (getNew and postNew)
	 */
	public function viewFor($status) {
		  Log::info('[VIEW] Resolving status ' . $status . '...');

		  $view = '';
		  $message_key = '';

			if ($status) {
			  $view = 'checkin.welcome';
			  $message_key = 'checkin.success';
		  } elseif (is_null($status)) {
			  $view = 'checkin.retry';
			  $message_key = 'checkin.notfound';
		  } else {
			  $view = 'checkin.expired';
			  $message_key = 'checkin.expired';
			}

			return [$view, $message_key];
	}
}
