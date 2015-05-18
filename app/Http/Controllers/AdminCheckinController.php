<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Role;

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
		$roles = Role::all(); 
		return view('admin.checkin.index')
		  ->withRoles($roles);
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
