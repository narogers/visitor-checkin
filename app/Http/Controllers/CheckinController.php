<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Checkin;
use App\User;

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
			// Do code lookup here
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
				$message_key = "checkin.success";
				$view = "checkin.welcome";
				$user = $user_matches->first();
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
	 * Simple method for now that creates a checkin and ties it to
	 * the user. This might eventually be deduped so that it verifies
	 * there was only one checkin per 24 hour period but that can
	 * wait until everything else works
	 */
	protected function saveCheckin(User $user) {
		$checkin = new Checkin();
		$user->checkins()->save($checkin);
	}
}
