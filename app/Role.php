<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {
	protected $fillable = [
		'role',
		'description'
	];

	public function user() {
		return $this->belongsTo('App\User');
	}

}
