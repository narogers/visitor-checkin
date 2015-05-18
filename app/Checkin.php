<?php namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model {
	protected $fillable = [
		'role',
		'description'
	];

  public function scopeActiveSince($query, $cutoff_in_days = null) {
  	if (null == $cutoff_in_days) {
  		return $query;
  	}

  	$qry = $query->where('created_at', '>=', Carbon::now()->subDays($cutoff_in_days));
  	return $qry;
  }

  public function scopePreviousMonth($query) {
  	$qry = $query->whereBetween('created_at', 
  		[Carbon::now()->subMonth(1)->startOfMonth(),
  		 Carbon::now()->subMonth(1)->endOfMonth()]);
  	return $qry;
  }

	public function user() {
		return $this->belongsTo('App\User');
	}
}
