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

	/**
	 * A janky way of presenting a total over a provided range since when
	 * you do a count Eloquent conveinantly forgets and will gladly spew
	 * ALL the records rather than the filtered subset
	 *
	 * Eventually a more efficient method can be developed if this
	 * becomes a performance issue
	 */
	public function checkinCountFor($range) {
		$users = User::whereHas('checkins', function($q) use ($range) { 
			$q->during($range);
		})->where('role_id', $this->id)
		  ->get(['id', 'name', 'role_id']);
		$total = 0;
		foreach ($users as $user) {
			$total = $total + $user->checkinCountFor($range);
		}

		return $total;
	}
}
