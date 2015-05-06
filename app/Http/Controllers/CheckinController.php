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
			$is_active = $this->validateCheckin('barcode', $barcode);
			$user = null;

			/**
			 * TODO: Consider refactoring everything to be more DRY
			 */
			list($view, $message_key) = $this->getView($is_active);

			if ($is_active) {
				$user = User::whereBarcode($barcode)->first();
				$user->addCheckin();
			}

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

		$user_matches = User::activeCheckin($query_string);

		switch ($user_matches->count()) {
			case 0:
				$message_key = "checkin.notfound";
				$view = "checkin.retry";
				break;
			case 1:
			  $user = $user_matches->first();			  
			  $is_active = $this->validateCheckin('user', $user->aleph_id);
			  list($view, $message_key) = $this->getView($is_active);
			  if ($is_active) {
				  $user->addCheckin();
			  }
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
