<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {
	/**
	 * WIP: Move this logic to a Service layer that can be configured 
	 */
	protected $roles_map = [
		'CWRU Joint Program' => 'Academic',
		'Other CWRU' => 'Academic',
		'Museum Staff' => 'Staff',
	];

	protected $fillable = [
		'role',
		'description'
	];

	public function users() {
		return $this->hasMany('App\Models\User');
	}
}
