<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model {
	protected $fillable = [
		'role',
		'description'
	];

  /**
   * Retrieve a list of checkins from a specific
   * range. If passed 'daily', 'weekly', 'monthly',
   * or 'lastmonth' then convert to natural dates.
   * Otherwise use the dates as provided to scope
   * the query
   */
  public function scopeDuring($query, $starting, 
    $ending = 0) {
    // First normalize the dates
    $date_range = [$starting, $ending];
    $this->normalizeDate($date_range);
    
    // Next make a query against the checkins that limits them
    // to the specific range
    return $query->whereBetween('created_at', $date_range);
  }

	public function user() {
		return $this->belongsTo('App\User');
	}

  public function formattedCheckinDate() {
    return Carbon::parse($this->created_at)->format('F jS');
  }
  
  /**
   * Given a string tries to convert it to a pair of dates. If
   * the end date is null or the first date contains a human
   * readable string it will be cast to today's date instead
   * of parsed
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
