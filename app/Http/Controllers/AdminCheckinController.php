<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;

use Illuminate\Http\Request;

use Carbon\Carbon;

class AdminCheckinController extends Controller {
  /**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex($range = 'today')
	{
		/**
		 * Default to today unless you are given a valid alternative
		 * from the approved list
		 */
		$approved_ranges = ['today', 'week', 'month', 'lastmonth'];
		$range = in_array($range, $approved_ranges) ? $range : 'today';
		$label = $this->formatDateLabel($range);

		$users = User::whereHas('checkins', function($q) use ($range) { 
			$q->during($range);
		})->orderBy('role_id')
		  ->get(['id', 'name', 'role_id'])
		  ->groupBy('role_id');
	
		return view('admin.checkin.index')
		  ->withUsers($users)
		  ->withLabel($label)
		  ->withRange($range);
	}

 /**
  * Display checkin details for a specific user over the course of a
  * given time period
  */
 public function getCheckins(User $user, $range = 'today') {
 		/**
		 * Default to today unless you are given a valid alternative
		 * from the approved list
		 */
		$approved_ranges = ['today', 'week', 'month', 'lastmonth'];
		$range = in_array($range, $approved_ranges) ? $range : 'today';
		$label = $this->formatDateLabel($range);

 		return view('admin.checkin.show')
 		  ->withUser($user)
 		  ->withRange($range)
 		  ->withLabel($label);
 }

 // Eventually this should be refactored out of the controller
 // but let's get working code and a first launch before then
 protected function formatDateLabel($range) {
 		$date_label = '';
		switch ($range) {
			case 'today':
				$date_label = Carbon::now()->format('F jS, Y');
				break;
			case "week":
				$date_label = Carbon::now()->startOfWeek()->format('F j') . 
				  " to " .
				  Carbon::now()->format('F jS, Y');
				 break;
			case "month":
				$date_label = Carbon::now()->startOfMonth()->format('F j') .
					" to " .
					Carbon::now()->format('F jS, Y');
				break;
			case "lastmonth":
				$date_label = Carbon::now()->startOfMonth()->subMonth(1)->format('F j') .
				  " to " .
				  Carbon::now()->subMonth(1)->endOfMonth()->format('F jS, Y');
		}

		return $date_label;
	}
}
