<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {
	/**
	 * A better pattern would be to configure this in a file
	 * somewhere so that it does not have to be touched every
	 * time an exception pops up
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
		return $this->hasMany('App\User');
	}

	public function scopeOfRole($query, $type) {
		if (key_exists($type, $this->roles_map)) {
			$type = $this->roles_map[$type];
		}
		return $query->whereRole($type);
	} 
}
