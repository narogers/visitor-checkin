<?php namespace App;

use Carbon\Carbon;

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

	public function scopePendingRegistrations($query) {
		$qry = $query->where('aleph_id', '');
		$qry = $query->orWhere('aleph_id', null);
		$qry = $query->orWhere('verified_user', false);

		return $qry;
	}

	public function scopeActiveSince($query, $days = null) {
		$qry = $query->whereHas('checkins', function($q) use ($days) {
			$q->activeSince($days);
		});
		return $qry;
	}

	public function scopeActiveDuring($query, $starting, 
		$ending = null) {
		$qry = $query->whereHas('checkins', function($q) 
			use ($starting, $ending) {
  		$q->during($starting, $ending);	
		});
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
	public function isActive($user_key = null) {
		// If no user_key is provided rely on the current Aleph ID
		if (null == $user_key) {
			$user_key = $this->aleph_id;
		}
		$aleph_id = $this->getAlephClient()->getPatronID($user_key);

		/**
		 * As a side effect set the Aleph ID if it is not already
		 * present. Assume that it does not change so there is no
		 * need to do this repeatedly
		 */
		if (null == $this->aleph_id) {
			$this->aleph_id = $aleph_id;
		}

		$active = (!empty($aleph_id)) ? 
		  $this->getAlephClient()->isActive($user_key) :
		  false;
		return $active;
	}

	public function isExpired($user_key = null) {
		return !$this->isActive($user_key);
	}

	/**
	 * Updates the patron record to reflect the information present in
	 * Aleph *BUT* it does not save this automatically. Be sure to call
	 * $user->save() if you want this information to persist
	 */
	public function importPatronDetails($user_key = null) {
		if (null == $user_key) {
			$user_key = $this->email_address;
		} else if (preg_match("/^\d*$/", $user_key)) {
			$this->barcode = $user_key;
		}
		Log::info('[USER] Executing query using key ' . $user_key);
		
		$patron_data = $this->getAlephClient()->getPatronDetails($user_key);
		
		/**
		 * Only insert these details if the record is new
		 */
		if (empty($this->id)) {
			  /** 
			   * Create a new user stub with an empty signature to
			   * make sure that it passes validation properly.
			   *
			   * If the email address happens to be empty then make up a stub
			   * which is not legal to replace it
			   */
			  if (!empty($patron_data['email'])) {
			  	$this->email_address = $patron_data['email'];
			  } else {
			  	$this->email_address = $this->generateEmailStub();
			  }
			  $this->name = $patron_data['name'];
			  $this->signature = '';
			  
			  // Assume that if the role was preset it should be honored. Otherwise
			  // populate it from the database
			  if (empty($this->role_id)) {
			    $this->role_id = (0 == Role::ofType($patron_data['role'])->count()) ?
			  	  Role::ofType("Unknown")->first()->id :
			  	  Role::ofType($patron_data['role'])->first()->id;
			  }
		}

		/**
		 * If the key is numeric assume it is a barcode. Otherwise
		 * don't cache it locally. This may need to be reconsidered
		 * if any bugs pop up
		 */
		if (preg_match("/^\d*$/", $user_key)) {
		  $this->barcode = $user_key;
		}
		$this->aleph_id = $patron_data['aleph_id'];
	}

	/**
	 * Inserts a checkin
	 *
	 * TODO: Prevent duplicate entries for the same day by updating a record rather than just carte blanche
	 *       inserting a new one
	 */
	public function addCheckin() {
		# Check for existing checkins before you insert a new one. Otherwise just
		# scroll right past
		$not_checked_in = (0 == $this->checkins()
			->where('created_at', '>=', Carbon::now())->count());
		if ($not_checked_in) {
			$checkin = new Checkin();
  		$this->checkins()->save($checkin);
		}
	}

	public function formattedCreationDate() {
		return Carbon::parse($this->created_at)->toFormattedDateString();
	}

	/**
	 * For this first pass it does not filter out any dates that may be
	 * beyond the range provided. When supporting the view for the 
	 * previous month this may be a consideration
	 */
	public function formattedLastCheckin($starting = null, 
		$ending = null) {
		$starting = empty($starting) ? Carbon::now() : $starting;
		
		$last_checkin = $this->checkins()->during($starting, $ending)->orderBy('created_at', 'DESC')->first();
		if (null == $last_checkin) {
			return "Not available";
		} else {
			return Carbon::parse($last_checkin->created_at)->format('F jS');
		}
	}

	/**
	 * Retrieve a list of checkins from a specific
	 * range. If passed 'daily', 'weekly', 'monthly',
	 * or 'lastmonth' then convert to natural dates.
	 * Otherwise use the dates as provided to scope
	 * the query
	 */
	public function checkinsDuring($starting, $ending = null) {
		return $this->checkins()->during($starting,
			$ending)->get();
	}

	/**
	 * Utility method that returns only the count of checkins
	 * over a specific range rather than the actual values
	 */
	public function checkinCountFor($range) {
		return $this->checkinsDuring($range)->count();
	}

	/**
	 * Generates a fake email address by hashing the date and time. This is only
	 * a placeholder for edge cases where the email field is empty and not meant to
	 * be used routinely
	 *
	 * @return String
	 */
	protected function generateEmailStub() {
		$host = "null.null";
		$address = substr(hash('md5', date('Ymd His' . microtime(true))), 0, 16);

		$faux_email = $address . "@" . $host;
		return $faux_email;
	}

	/**
	 * Given a string tries to convert it to a pair of dates. If
	 * the end date is null or the first date contains a human
	 * readable string it will be cast to today's date instead
	 * of parsed
	 */
	protected function normalizeDate(&$range) {
		switch($range[0]) {
			case "daily":
				$range[0] = Carbon::now();
				$range[1] = Carbon::now();
				break;
			case "weekly":
				$range[0] = Carbon::now()->startOfWeek();
				$range[1] = Carbon::now();
				break;
			case "monthly":
				$range[0] = Carbon::now()->startOfMonth();
				$range[1] = Carbon::now();
				break;
			case "lastmonth":
				$range[0] = Carbon::now()->startOfMonth()->subMonth(1);
				$range[1] = Carbon::now()->subMonth(1)->endOfMonth();
				break;
      default:
        $range[0] = Carbon::parse($range[0]);
        $range[1] = empty($range[1]) ? Carbon::now() : Carbon::parse($range[1]);
		}
	}
}
