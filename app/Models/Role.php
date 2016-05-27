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
		return $this->hasMany('App\User');
	}

    /**
     * WIP: Migrate this logic higher up in the tree
     */
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
