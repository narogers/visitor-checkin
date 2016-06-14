<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Checkin extends Model {
  protected $fillable = ['created_at'];

  public function user() {
	return $this->belongsTo('App\Models\User');
  }
}
