<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\PatronInterface;

use Illuminate\Http\Request;

class AdminCheckinController extends Controller {
  protected $patrons;
  protected $ranges = ['today', 'week', 'month', 'lastmonth'];

  /**
   * Build a new instance of the controller
   *
   * @param PatronInterface $patron
   * @return AdminCheckinController
   */
  public function __construct(PatronInterface $patrons) {
    $this->patrons = $patrons;
  }

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function getIndex($range = 'today')
	{
	  $range = in_array($range, $this->ranges) ? $range : 'today';
      $dates = DateHelper::rangeFor($range);
      $checkins = $this->patronModel
        ->getCheckins(["starting_date" => $range[0], "ending_date" => $range[1]])
        ->groupBy("user.role_id")
        ->orderBy("user.name");
       
	  return view('admin.checkin.index')
	    ->withUsers($users)
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
