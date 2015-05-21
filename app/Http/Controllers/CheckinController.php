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
			$is_registered_user = User::whereBarcode($barcode)->count();
			if ($is_registered_user) {
				$user = User::whereBarcode($barcode)->first();
			} else {
				$user = new User;
			}
			
			$is_active = $user->isActive($barcode);
			if ($is_active) {
		  	Log::info('[USER] Adding shadow details to local database for quick lookup');
		  	// The Aleph ID is resolved when you query for the active
		  	// user
			  $user->importPatronDetails($user->aleph_id);
			  // If the information is loaded via barcode it exists in 
			  // Aleph. Assume therefore that the ID has been checked 
			  // since this is only applicable to members and staff at
			  // the moment
			  $user->verified_user = true;
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
    
    Log::info('[CHECKIN] Looking up users that match the string ' . $query_string);		

		$user_matches = User::registeredUsers($query_string);

		switch ($user_matches->count()) {
			case 0:
				Log::info("[CHECKIN] No results found");
				$message_key = "checkin.notfound";
				$view = "checkin.retry";
				break;
			case 1:
				Log::info("[CHECKIN] Exact match found");
			  $user = $user_matches->first();			  
			  $is_active = $user->isActive();
			  list($view, $message_key) = $this->getView($is_active);
			  if ($is_active) {
				  $user->addCheckin();
			  }
				break;
			case 2:
				Log::info("[CHECKIN] Two matches found");
				$message_key = "checkin.multiplefound";
				$view = "checkin.retry";
				$user = $user_matches->get();
				break;
			default:
				Log::info("[CHECKIN] Too many matches found");
				$message_key = "checkin.notfound";
				$view = "checkin.retry";
		}
		// TODO: Come up with a way to only set the user if the view was
		//  		 a success. Otherwise omit the parameter instead of 
		//			 setting a null placeholder
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
	protected function getView($status) {
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
