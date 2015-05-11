<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

use Illuminate\Http\Request;

class AdminRegistrationController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
		$users = User::pendingRegistrations()->get();
		return view('admin.registration.index')
			->withUsers($users);
	}

	/**
	 * Displays registration details for a specific visitor
	 *
	 * @return Response
	 */
	public function getRegistration(User $user) {
		return view('admin.registration.show')
			->withUser($user);
	}

	/**
	 * Based on the command passed to the URL either pings Aleph to see if an
	 * ID has been created yet or updates the Verified User flag to be true
	 *
	 * @return Response
	 */
	public function postRegistration(Request $request) {
		return view('admin.registration.show')
			->withUser($user);
	}
}
