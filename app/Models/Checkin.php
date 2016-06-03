<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model {
  protected $fillable = [
	'role',
	'description'
  ];

  public function user() {
	return $this->belongsTo('App\Models\User');
  }

  /**
   * WIP: Refactor into a presenter and out of the model
   */
  public function formattedCheckinDate() {
    return Carbon::parse($this->created_at)->format('F jS');
  }
  
  /**
   * WIP: Move into a helper class that assists the CheckinService 
   *      instead of heving this be present here    
   */
  protected function normalizeDate(&$range) {
    switch($range[0]) {
      case "today":
        $range[0] = Carbon::today();
        $range[1] = Carbon::now();
        break;
      case "week":
        $range[0] = Carbon::today()->startOfWeek();
        $range[1] = Carbon::now();
        break;
      case "month":
        $range[0] = Carbon::today()->startOfMonth();
        $range[1] = Carbon::now();
        break;
      case "lastmonth":
        $range[0] = Carbon::today()->startOfMonth()->subMonth(1);
        $range[1] = Carbon::now()->subMonth(1)->endOfMonth();
        break;
      default:
        $range[0] = Carbon::parse($range[0]);
        $range[1] = empty($range[1]) ? Carbon::now() : Carbon::parse($range[1]);
    }
  }
}
