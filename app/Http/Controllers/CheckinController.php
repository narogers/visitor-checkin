<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Checkin;
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
			list($message_key, $view) = $this->validateCheckin('barcode', $barcode);
			/**
			 * If the barcode was valid but there is no information in 
			 * the local database create a shadow copy with a stub of
			 * the signature so that the record is valid
			 */

			//return view($view)
			//	->withMessageKey($message_key);
			return view('checkin.expired')
			 	->withMessageKey('checkin.expired');
		} else {
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

		$user_matches = User::activeCheckin($query_string);

		switch ($user_matches->count()) {
			case 0:
				$message_key = "checkin.notfound";
				$view = "checkin.retry";
				break;
			case 1:
			  list($message_key, $view) = $this->validateCheckin('user', $user_matches->first()->aleph_id);
				$user = $user_matches->first();
				// TODO: Should expired checkins count or not be counted?
				//       This may require a tweak to the database model to
				//       add a status flag.
				$this->saveCheckin($user);
				break;
			case 2:
				$message_key = "checkin.multiplefound";
				$view = "checkin.retry";
				$user = $user_matches->get();
				break;
			default:
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
		// TODO: Fix this
		$user = User::all()->first();
		return view('checkin.success')
			->withMessageKey('checkin.success')
			->withUser($user);
	}

	/**
	 * Simple method for now that creates a checkin and ties it to
	 * the user. This might eventually be deduped so that it verifies
	 * there was only one checkin per 24 hour period but that can
	 * wait until everything else works
	 */
	protected function saveCheckin(User $user) {
		$checkin = new Checkin();
		$user->checkins()->save($checkin);
	}

	/**
	 * Makes a call to the Aleph service based on the key and
	 * determines if the particular record is current or not.
	 */
	protected function validateCheckin($field, $key) {
		if (!$field) {
			$field = 'user';
		}
		$return_values = [];
		// Default to expired unless proven otherwise
		$active = false;
		$aleph = new Aleph();

		if (in_array($field, ['user', 'barcode'])) {
				$active = $aleph->isActive($key);
				/**
		 			* If the requested barcode does not already exist
		 			* create a shadow account with just the email address,
		 			* name, and role. Zero out the signature
		 		 */
			if ('barcode' == $field && $active) {
				importUserFromAleph($key);
			}
		}
		

		if ($active) {
			return ['checkin.success', 'checkin.welcome'];
		} else {
			return ['checkin.expired', 'checkin.expired'];
		}
	}

	/**
	 * Imports a user record from Aleph by making another query
	 * only this time it retrieves the email address, role, and
	 * name to be cached locally. If an email address already 
	 * exists the record is simply updated
	 */
	protected function importUserFromAleph($barcode) {
		$aleph = new Aleph();
		$aleph_data = $aleph->getUserDetails();

		$user_qry = User::where('email_address', $aleph_data->email);
		switch ($user_qry->count) {
			case 0:
			  // Create a new user stub with an empty signature to
			  // make sure that it passes validation properly
			  $user = new User();
			  $user->email_address = $aleph_data->email;
			  $user->name = $aleph_data->name;
			  $user->signature = '';
			  $user->role = Role::ofType($aleph_data->role)->get()->first();
			  $user->save();
			  break;
			case 1:
				$user = $user_qry->get()->first();
				$user->barcode = $barcode;
				$user->save();
		}
	}
}
