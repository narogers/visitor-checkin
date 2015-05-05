<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Checkin;
use App\Registration;
use App\Role;
use App\Services\Aleph;

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
   * having to instantiate it every time
   */
  protected $alephInterface = null;

  public function __construct($attributes = array()) {
  	parent::__construct($attributes = array());
  	# Do local stuff now
  	$this->alephInterface = new Aleph();
  }

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
	 * Makes a call to the Aleph service based on the key and
	 * determines if the particular record is current or not.
	 */
	public function isActiveUser($field, $key) {
		if (!$field) {
			$field = 'user';
		}

		// Default to expired unless proven otherwise
		$active = false;
		
		if (in_array($field, ['user', 'barcode'])) {
				$active = $this->alephInterface->isActive($key);
				Log::info('User key => ' . $key);
				Log::info('Response => ' . $active);

				/**
		 			* If the requested barcode does not already exist
		 			* create a shadow account with just the email address,
		 			* name, and role. Zero out the signature
		 		 */
			if ('barcode' == $field && $active) {
				$this->importPatronDetails($key);
			}
		}

		return $active;
	}

	public function importPatronDetails($barcode) {
		$patron_data = $this->alephInterface->getUserByBarcode($barcode);
		$user_qry = self::where('email_address', $patron_data['email']);
		
		if (0 == $user_qry->count()) {
			  // Create a new user stub with an empty signature to
			  // make sure that it passes validation properly
			  $this->email_address = $patron_data['email'];
			  $this->name = $patron_data['name'];
			  $this->signature = '';
			  $this->role_id = Role::ofType($patron_data['role'])->first()->id;
			  break;
		}

		$this->barcode = $barcode;
		$this->save();
	}
}
