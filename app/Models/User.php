<?php namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

use App\Checkin;
use App\Registration;
use App\Role;

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
     * Masks the email address for privacy when listing multiple values
     *
     * eg foo@bar.com would become f*****@cl*****.org
     */
   public function masked_email() {
     list($account, $domain) = explode("@", $this->email_address);
     $account_mask = substr($account, 0, 2) . str_repeat("*", 5);
     $domain_components = explode(".", $domain);
     $domain_mask = substr($domain_components[0], 0, 2) . str_repeat("*", 5);
     $tld = array_pop($domain_components);

     return "${account_mask}@${domain_mask}.${tld}";
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
	 * Construct a list of potential keys to use when
	 * resolving calls to Aleph. If the ID and barcode are
	 * set they will be used. Otherwise it will try to
	 * generate options based on the name
	 */
	public function getAlephKeys() {
		$keys = [];
		if (!empty($this->aleph_id)) {
			array_push($keys, $this->aleph_id);
		}
		if (!empty($this->barcode)) {
			array_push($keys, $this->barcode);
		}

		# If it is still empty then generate keys
		if (empty($keys)) {
			$this->generateAlephKeys($keys);
		}
		return $keys;
	}

	/**
	 * Return the primary Aleph key as calculated by
	 * the other helper method(s)
	 */
  public function getAlephKey() {
  	$aleph_keys = $this->getAlephKeys();
  	return array_shift($aleph_keys);
  }

  /**
   * Uses the name to algorithmicly create possible
   * matches based on the standard Ingalls library 
   * practice of <first initial>.<last name> truncated
   * to a total length of 12 characters
   *
   * ie
   * James Joyce -> j.joyce
   * Leopold Bloom -> l.bloom
   * HC Earwigger -> h.earwigger
   */
  public function generateAlephKeys(&$keys) {
  	# First normalize the names to accept only the first
  	# and last strings. If a comma is present then invert
  	# them
  	$pieces = [];
  	preg_match("/^(\w+)\s?.*\s([-a-zA-Z]+)$/", $this->name, 
  		$pieces);

  	$name['initial'] = '';
  	$name['last'] = '';

  	if (preg_match("/,/", $this->name)) {
  		$name['initial'] = $pieces[2];
  		$name['last'] = $pieces[1];
  	} else {
  		$name['initial'] = $pieces[1];
  		$name['last'] = $pieces[2];
  	}
  	$name['initial'] = strtoupper(substr($name['initial'], 
  		0, 1));
  	$name['last'] = preg_replace("/\W+/", "", $name['last']);
  	$name['last'] = strtoupper(substr($name['last'], 
  		0, 10));
  	
  	$keys[0] = $name['initial'] . "." . $name['last'];
  	$keys[1] = $name['initial'] . $name['last'];
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
