<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Checkin extends Model {
	protected $fillable = [
		'role',
		'description'
	];

	public function user() {
		return $this->belongsTo('App\User');
	}
}
