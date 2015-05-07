<?php namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

use App\Checkin;
use App\Registration;
use App\Role;
use App\Services\AlephClient;

class User extends Model {
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'email_address'];

	public function scopeRegisteredUsers($query, $string) {
		$qry = $query->where('name', 'LIKE', '%' . $string);
		$qry->orWhere('aleph_id', $string);
		$qry->orWhere('email_address', $string);

		return $qry;
	} 

  /**
   * Create an Aleph instance that can be shared across the model rather than
   * having to instantiate it every time. May need to add some checks that throw
   * an error when 'find' or other methods are used that might not properly spawn
   * the client as it could lead to wonky states.
   */
  protected $aleph_client = null;

	public function role() {
		return $this->belongsTo('App\Role');
	}

	public function registration() {
		return $this->hasOne('App\Registration');
	}

	public function checkins() {
		return $this->hasMany('App\Checkin');
	}

	/**
	 * These are a bandaid until a better way can be found down the road with some
	 * time to do proper refactoring. For now just get it working
	 */
	public function getAlephClient() {
		if (null == $this->aleph_client) {
			$this->aleph_client = new AlephClient;
		}
		return $this->aleph_client;
	}
	public function setAlephClient(AlephClient $aleph) {
		$this->aleph_client = $aleph;
	}

	/**
	 * Makes a call to the Aleph service based on the key and
	 * determines if the particular record is current or not.
	 */
	public function isActive($user_key) {
		// Default to expired unless proven otherwise
		$active = false;
		
		$active = $this->getAlephClient()->isActive($user_key);
		Log::info('[USER] User key => ' . $user_key);
		Log::info('[USER] Response => ' . $active);

		/**
		 * If the requested user does not already exist and the user key appears
		 * to be formatted as a barcode (as all digits) create a shadow account with just the 
		 * email address, name, and role. Zero out the signature since it is assumed to
		 * be valid by default if Aleph says so.
		 */
		if (preg_match("/^\d+$/", $user_key)) {
			$this->importPatronDetails($user_key);
		}

		return $active;
	}

	public function importPatronDetails($user_key) {
		$patron_data = $this->getAlephClient()->getPatronDetails($user_key);
		$user_qry = $this->where('email_address', $patron_data['email']);
		
		if (0 == $user_qry->count()) {
			  // Create a new user stub with an empty signature to
			  // make sure that it passes validation properly
			  $this->email_address = $patron_data['email'];
			  $this->name = $patron_data['name'];
			  $this->signature = '';
			  $this->role_id = Role::ofType($patron_data['role'])->first()->id;
		}

		$this->aleph_id = $patron_data['aleph_id'];
		$this->barcode = $user_key;
		$this->save();
	}

	/**
	 * Inserts a checkin
	 *
	 * TODO: Prevent duplicate entries for the same day by updating a record rather than just carte blanche
	 *       inserting a new one
	 */
	public function addCheckin() {
		$checkin = new Checkin();
		$this->checkins()->save($checkin);
	}
}
