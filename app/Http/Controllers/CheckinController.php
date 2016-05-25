<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use App\Services\Aleph;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckinController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		return view('checkin.index');
	}

	public function getNew(Request $request)
	{
		/**
		 * Check the request URL for the 'code' parameter. If passed
		 * clean it to only digits before passing on to Aleph. If not
		 * present then kick back to the 'Not found' page with a
		 * helpful error message
		 */
		if ($request->input('code')) {
			$barcode = preg_replace("/[^0-9]/", "", $request->input('code'));
		  $user = User::whereBarcode($barcode)->first();
			if (null == $user) {
				$user = new User;
			}
			
			$is_active = $user->isActive($barcode);
			if (is_bool($is_active)) {
		  	Log::info('[USER] Adding shadow details to local database for quick lookup');
		  	// The Aleph ID is resolved when you query for the active
		  	// user
			  $user->importPatronDetails($barcode);

			  /**
			   * Since it is possible that the email address has already
			   * been used let's circumvent that possibility before we
			   * save by doing a query. If the user already exists then
			   * we just copy over the barcode and aleph_id before
			   * committing it to the database
			   */
			  if (null == $user->created_at) {
			  	$is_existing_user = (0 < User::whereEmailAddress($user->email_address)->count());
			  	// TODO: Refactor this into the User model?
			  	if ($is_existing_user) {
			  		$aleph_import = $user; 
			  		$user = User::whereEmailAddress($aleph_import->email_address)->first();
			  		$user->barcode = $barcode;
			  		$user->aleph_id = $aleph_import->aleph_id;
			  	}
			  }

			  // If the information is loaded via barcode it exists in 
			  // Aleph. Assume if it is active that there is no need to
			  // check ID. Otherwise leave it alone
			  if (true == $is_active) {
			    $user->verified_user = true;
			  }
				$user->save();
				$user->addCheckin();
			}
			list($view, $message_key) = $this->getView($is_active);

			return view($view)
				->withMessageKey($message_key)
				->withUser($user);		
		} else {
			// If no barcode was supplied
		  return view('checkin.index');
		}
	}

	public function postNew(Request $request)
	{
		// Search by email address and name to find any hits in the
		// registration database. For more on the limits see the scope
		// in the User model
		$user = null;
		$query_string = $request->input('query');
    
        Log::info("[CHECKIN] Looking up users for query ${query_string}");		
		$user_matches = User::registeredUsers($query_string);
        Log::info("[CHECKIN] " . $user_matches->count() . " matches found");

        /**
         * Default to Not Found then try to determine if any other cases
         * apply
         */
        $message_key = 'checkin.notfound';
        $view = 'checkin.retry';

        if (1 == $user_matches->count()) {
          $user = $user_matches->first();
          $is_active = $user->isActive();
           list($view, $message_key) = $this->getView($is_active);
          if ($is_active) {
            $user->addCheckin();
          }         
        } elseif (0 == $user_matches->count()) {
          $message_key = "checkin.notfound";
        } elseif ($user_matches->count() < Config('app.match_threshold')) {
		  $message_key = "checkin.multiplefound";
		  $user = $user_matches->get();
		}

		return view($view)
			->withMessageKey($message_key)
			->withUser($user);
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
		$user_count = User::where('email_address', $request->input['email']);
		$view = '';
		$message_key = '';
		$user = null;

		switch ($user_count->count()) {
			case 1:
				$user = $user->first();
				$user->signature = $request->input['signature'];
				$user->addCheckin();
				$user->save();

				$view = 'checkin.welcome';
				$message_key = 'checkin.success';
			default:
				$view = 'checkin.retry';
				$message_key = 'checkin.notfound';
		}

		return view($view)
			->withMessageKey($message_key)
			->withUser($user);
	}

	/**
	 * Uses a registration status to determine which view to return for the
	 * checkin process (getNew and postNew)
	 */
	public function getView($status) {
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
