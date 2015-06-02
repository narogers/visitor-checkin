<?php
/**
 * Helper functions for Visitor Checkin to make calls against the RESTful
 * Aleph API. It interfaces between the code so that you get back just the
 * information you need rather than having to parse the XML in the controller
 */
namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Log;

class AlephClient {
	// These are the URLs for the specific services that will be appended 
	// following the patron ID
	protected $AlephHost = "http://lib-aleph-01.clevelandart.org";

	protected $AlephWebService;
	protected $AlephXService;

	protected $Endpoint = [
		// General information
		'address' => '/patronInformation/address',
		// Expiration date and registration details
		'status' => '/patronStatus/registration',
		// Resolve alternate IDs to a canonical internal reference
		'id' => 'library=CMA50&op=bor_by_key&bor_id='
	];
	protected $user;

	public function __construct() {
		$this->AlephWebService = $this->AlephHost . ":1891/rest-dlf/patron/";
		$this->AlephXService = $this->AlephHost . "/X?";

		Log::info('[ALEPH] Client has been initialized and is ready for use');
	}

	/**
	 * Given a key uses it to query Aleph for a canonical identifier. Unlike
	 * the other version this one does no quality assurance to avoid possible
	 * false positives
	 *
	 * Returns a string if a match is found or NULL for error cases
	 */
	public function getPatronID($user_key) {
		$response = file_get_contents($this->endpoint('id', $user_key));
		$aleph_data = simplexml_load_string($response);

		// Default to NULL if the identifier cannot be resolved
		$canonical_aleph_id = null;

			/*
			 * The two most likely responses are
			 *
			 * <error>Error in verification</error>
			 * <internal-id>Aleph's internal ID for patron</internal-id>
			 *
			 * In the first case log the error for troubleshooting down the road
			 */
		if ($aleph_data->{'error'}) {
			Log::info("[ALEPH] Could not resolve " . $user_key . " to a canonical ID");
			Log::info("[ALEPH] " . $response);
		} 

		if ($aleph_data->{'internal-id'}) {
			$canonical_aleph_id = $aleph_data->{'internal-id'}->__toString();
		}
		return $canonical_aleph_id;
	}

	/**
	 * Invoke this as a callback once the record has been uploaded into Aleph
	 * to get resolve the canonical identifier. Unlike the other method of
	 * resolving IDs this one double checks the details against Aleph for a
	 * match. If you just need to quickly get the canonical ID then use
	 * getPatronID()
	 */
	public function validatePatronID(User $user) {
		$aleph_ids = $user->getAlephKeys();
		Log::info('[ALEPH] Attempting to resolve IDs');
		
		foreach ($aleph_ids as $aleph_id) {
			// In case of a null result assume the worst and move on to the next possible match
			$canonical_aleph_id = $this->getPatronID($aleph_id);
			if (null == $canonical_aleph_id) {
				continue;
			}

			// Take a look at the <z304-address-1> field and compare it to the
			// name in the User model to ensure this is the right person
			$response = file_get_contents($this->endpoint('address', $canonical_aleph_id));
			$aleph_data = simplexml_load_string($response);

			if($this->compareNames($user->name,
				$aleph_data->{'address-information'}->{'z304-address-1'}->__toString())) {
				$canonical_aleph_id = $aleph_id;
				break;
			}
		}

		// If we happen to fall through to here then there are no valid IDs.
		// Returning NULL is a bandaid until something better can be done.
		if (null == $canonical_aleph_id) {
			Log::warning('[ALEPH] WARNING: Unable to resolve an Aleph ID for ' . 
			$user->name);
		}
		return $canonical_aleph_id;
	}

	/**
	 * Makes a call to the patron's registration data to confirm that the record
	 * has not expired. Three possible values can be returned
	 *
	 * TRUE : The record has not expired
	 * FALSE: The record has expired
	 * NULL : There was a problem resolving the patron
	 *
	 * In the last case messages will be written to the logs but the user 
	 * interface won't say anything by default
	 */
	public function isActive($patron_id) {
		$canonical_aleph_id = $this->getPatronID($patron_id);
		
		// The only case we care about is a NULL result. Eventually this could be redone with
		// exceptions but that would slow down development another week or two
		if (null == $canonical_aleph_id) {
			return null;
		}
		
		// Assume the best - handle any other edge cases in the above method
		$response = file_get_contents($this->endpoint('status', $canonical_aleph_id));
		$aleph_data = simplexml_load_string($response);

		/**
		 * If we've got a valid record then we can just compare the expiry date to the
		 * current time and return a 'yeah' or 'nay'
		 */
		return $this->isActivePatron($aleph_data->xpath("//z305-expiry-date")[0]);
	}

	public function isExpired($patron_id) {
		return !($this->isActive($patron_id));
	}

  /**
   * Given a Aleph ID retrieves the email address, name, and other details to shadow 
   * in the local database. Returns a data structute that looks like
   *
   * name: Name normalized into 'First Last' format
   * email: Email address
   * role: Role as described in the Z304 patron information section
   * aleph_id: Canonical identifier
   */
  public function getPatronDetails($patron_id) {
  	$aleph_id = $this->getPatronID($patron_id);
  	$user = null;

  	if ($aleph_id) {
  		$response = file_get_contents($this->endpoint('address', $aleph_id));
  		$address_data = simplexml_load_string($response);

  		$response = file_get_contents($this->endpoint('status', $aleph_id));
  		$registration_data = simplexml_load_string($response);

  		$normalized_name = $this->normalizeName($address_data->xpath("//z304-address-1")[0]->__toString());
  		$user['name'] = $normalized_name['first'] . " " . $normalized_name['last'];
  		// Trim the email to avoid data entry problems
  		$user['email'] = trim($address_data
  			->xpath("//z304-email-address")[0]->__toString());
  		$user['role'] = (sizeof($registration_data->xpath("//z305-bor-type")) > 0) ?
  			$registration_data->xpath("//z305-bor-type")[0]->__toString() : null;
  		Log::info("Preparing to save the Aleph ID");
  		$user['aleph_id'] = $aleph_id;
  		Log::info("Aleph ID => " . $aleph_id);
  	}
 	
  	return $user;
  }

	public function endpoint($target, $key) {
		Log::info('[ALEPH] Looking up endpoint URL for ' . $target . "\r\n");
		switch ($target) {
			case 'address':
				return $this->AlephWebService . urlencode($key) . $this->Endpoint['address'];
				break;
			case 'id':
				return $this->AlephXService . $this->Endpoint['id'] . "&bor_id=" . urlencode($key);
			case 'status':
				return $this->AlephWebService . urlencode($key) . $this->Endpoint['status'];
				break;
			default:
				Log::warning('ERROR: Could not resolve endpoint');
				return null;
		}
	}

	/**
	 * Compare UNIX timestamps to see if the record should be
	 * considered expired or valid
	 */
	protected function isActivePatron($timestamp) {
		$expiry = strtotime($timestamp);
		return ($expiry > time());

	}

	protected function compareNames($local, $aleph) {
		$names = [
		 	$this->normalizeName($local),
			$this->normalizeName($aleph)
		];

		return (($names[0]['first'] == $names[1]['first']) &&
				($names[0]['last'] == $names[1]['last']));
	}

	/*
	 * Normalizes an input string into <first name> <last name>
	 * 
	 * This method should work with the following formats
	 * FirstName LastName
	 * LastName, FirstName
	 * LastName, FirstName Middle
	 * FirstName () LastName
	 */
	protected function normalizeName($input) {
		// Start by stripping out anything in parens
		$input = preg_replace("/\(.*\)/", "", $input); 
		// Now split on the first whitespace (, or space)
		
		$name_parts = preg_split("/,?\s+/", $input);
		if (preg_match("/,/", $input)) {
			$first_name = $name_parts[1];
			$last_name = $name_parts[0];
		} else {
			$first_name = $name_parts[0];
			$last_name = $name_parts[1];
		}
		return ['first' => ucwords($first_name), 
				'last' => ucwords($last_name)];
	}
}
?>
