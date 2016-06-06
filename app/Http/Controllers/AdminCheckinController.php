<?php namespace App\Http\Controllers;

use App\Helpers\DateHelper as DateUtils;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\PatronInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminCheckinController extends Controller {
  protected $patrons;

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
      Log::info("[ADMIN] Looking up checkins for ${range}\n");
 
	  $range = in_array($range, DateUtils::ranges()) ? $range : 'today';
      $dates = DateUtils::rangeFor($range);
      $checkins = $this->patrons
        ->getCheckins(["starting_date" => $dates[0], 
            "ending_date" => $dates[1]]);

      Log::info("[ADMIN] Starting date : " . $dates[0] . "\n");
      Log::info("[ADMIN] Ending date : " . $dates[1] . "\n");
      Log::info("[ADMIN] Found " . $checkins->count() . " matches in the backing data store\n");
       
	  return view('admin.checkin.index')
	    ->withCheckins($checkins)
		->withRange($range);
	}

 /**
  * Display checkin details for a specific user over the course of a
  * given time period
  */
 public function getCheckins($uid, $range = 'today') {
   /**
    * Default to today unless you are given a valid alternative
	* from the approved list
	*/
   $range = in_array($range, DateUtils::ranges()) ? $range : "today";
   $user = $this->patrons->getUser($uid);
   $dates = DateUtils::rangeFor($range);
   $checkins = $this->patrons->getCheckins(["uid" => $uid, 
     "starting_date" => $dates[0],
     "ending_date" => $dates[1]]);
  
   Log::info($dates);
   Log::info($checkins->count());
 
   return view('admin.checkin.show')
     ->withCheckins($checkins)
     ->withRange($range)
     ->withUser($user);
 }
}
