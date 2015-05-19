<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

use Illuminate\Http\Request;

class AdminCheckinController extends Controller {
  /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex(Request $request)
	{
		/**
		 * Default to today unless you are given a valid alternative
		 */
		$cutoff = 240;
		$users = User::whereHas('checkins', function($q) use ($cutoff) { 
			$q->activeSince($cutoff);
		})->orderBy('role_id')
		  ->get(['id', 'name', 'role_id'])
		  ->groupBy('role_id');
	
		return view('admin.checkin.index')
		  ->withUsers($users);
	}

 /**
  * Display checkin details for a specific user over the course of a
  * given time period
  */
 public function getCheckin(User $user) {
 		return view('admin.checkin.checkin')
 		  ->withUser($user);
 }
}
