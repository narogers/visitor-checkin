<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

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
	 * ID has been created yet or updates the Verified User flag to be true.
	 * It will then redirect to the user's record with the registration 
	 * information and updated statuses displayed
	 *
	 * @return Response
	 */
	public function postRegistration(User $user, Request $request) {
		$event = $request->input('event');

		Log::info('[ADMIN] Preparing to process command ' . $event);
		switch ($event) {
			case 'hide_registration':
				$this->hideRegistration($user);
				Session::flash('alert', "Record is now archived in the database");
				break;
			case 'refresh_aleph_id':
				$this->updateAlephID($user);
				break;
			case 'verify_id':
				$this->setVerifiedStatus($user);
		}

		return view('admin.registration.show')
			->withUser($user);
	}

	/**
	 * Soft delete the registration to hide it from the database without completely
	 * removing the record for long term preservation
	 */
	protected function hideRegistration(User $user) {
		Log::info('[ADMIN] Removing old record ' . $user->id . ' from the database');
		$user->delete();
	}

	/**
	 * Ping Aleph for a new ID and, if found, refresh the record
	 */
	protected function updateAlephID(User $user) {
		Log::info('[ADMIN] Updating Aleph ID for ' . $user->email_address);
		$user->importPatronDetails();
		if (null == $user->aleph_id) {
			Session::flash('error', 'Could not resolve a valid Aleph ID');
			Log::info('[ADMIN] Unable to resolve an Aleph ID');
			Log::info('[ADMIN] Check connection to service and email address');
		} else {
			Session::put('alert', 'The Aleph ID has been resolved to ' . $user->aleph_id);
		}
		$user->save();
	}

	/**
	 * Sets the verified status to true for the given user
	 */
	protected function setVerifiedStatus(User $user) {
		Log::info('[ADMIN] Setting verified status to true for ' . $user->email_address);
		Session::flash('alert', "The visitor has now been marked as verified. If this is a renewal check Aleph to make sure that the expiration date has been updated.");
		$user->verified_user = true;
		$user->save();
	}
}
