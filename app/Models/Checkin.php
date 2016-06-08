<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model {
  public function user() {
	return $this->belongsTo('App\Models\User');
  }
}
