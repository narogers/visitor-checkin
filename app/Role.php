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
		'Researcher' => 'Academic'
	];

	protected $fillable = [
		'role',
		'description'
	];

	public function users() {
		return $this->hasMany('App\User');
	}

	public function scopeOfType($query, $type) {
		if (key_exists($type, $this->roles_map)) {
			$type = $this->roles_map[$type];
		}
		// If null then return the special type 'Unknown'
		if (empty($type)) {
			$type = "Unknown";
		}
		 
		return $query->whereRole($type);
	} 
}
