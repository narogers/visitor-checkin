<?php
/**
 * Helper functions for Visitor Checkin to make calls against the RESTful
 * Aleph API. It interfaces between the code so that you get back just the
 * information you need rather than having to parse the XML in the controller
 */
namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Log;

class Aleph {
	// These are the URLs for the specific services that will be appended 
	// following the patron ID
	protected $AlephHost = "http://lib-aleph-01.clevelandart.org";

	protected $AlephWebService;
	protected $AlephXService;

	protected $Endpoint = [
		// General information
		'address' => '/patronInformation/address',
		// Expiration date
		'status' => '/patronStatus/registration',
		// Barcode validation for X Service
		'barcode' => 'library=CMA50&base=STACKS&loans=N&cash=N&hold=N&op=bor_info'
	];
	protected $user;

	public function __construct() {
		$this->AlephWebService = $this->AlephHost . ":1891/rest-dlf/patron/";
		$this->AlephXService = $this->AlephHost . "/X?";
	}

	/**
	 * Invoke this as a callback once the record has been uploaded into Aleph
	 * to get back a list of all identifiers which can then be cached locally.
	 * From there you can easily make requests to find out if a patron's record
	 * has expired.
	 *
	 * To verify that we have the right record and not a false positive we look
	 * at the <z304-address-1> field of the patron's address. If it matches 
	 * either 'Last Name, First Name' or 'First Name Last Name' then go ahead and
	 * report it as a match.
	 */
	public function getPatronID($user) {
		$aleph_ids = $this->parse_name($user->name);
		Log::info('Attempting to resolve IDs');
		
		foreach ($aleph_ids as $aleph_id) {
			// Get it working and then refactor
			$response = file_get_contents($this->endpoint('address', $aleph_id));
			$aleph_data = simplexml_load_string($response);

			/*
			 * 0000 and 0002 are the two most likely responses
			 *
			 * 0000 means a record was found and can be parsed
			 * 0002 means that the ID was invalid
			 *
			 * See https://developers.exlibrisgroup.com/aleph/apis/Aleph-RESTful-APIs/Address
			 */
			if ('0000' != $aleph_data->{'reply-code'}) {
				continue;
			}
			// If we get here assume the data is valid. In that case we need to
			// take a look at the <z304-address-1> field and compare it to the
			// name in the User model
			if($this->compare_name_equality($user->name,
				$aleph_data->{'address-information'}->{'z304-address-1'})) {
				return $aleph_id;
			}
		}

		// If we happen to fall through to here then there are no valid IDs.
		// Returning NULL is a bandaid until something better can be done.
		Log::warning('WARNING: Unable to resolve an Aleph ID for ' . 
			$user->name);
		return null;
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
	public function isActive($patronID) {
		if (preg_match("/^\d*$/", $patronID)) {
			return $this->validatePatronByBarcode($patronID);			
		} else {
			return $this->validatePatronByName($patronID);
		}
	}

  /**
   * Given a barcode ID uses the X Service to retrieve the
   * email address, name, and other details to shadow in the
   * local database. Returns a data structute that looks like
   *
   * name: Name normalized into 'First Last' format
   * email: Email address
   * role: Role as described in the Z304 patron information section
   */
  public function getUserByBarcode($barcode) {
  	$endpoint = $this->endpoint('barcode');
  	$response = file_get_contents($endpoint);
  	$aleph_data = simplexml_load_string($response);

  	// Now just check to see if there no error tag. If not
  	// return a complete user. If so return nil and let the
  	// caller deal with the issue upstream
  	$user = null;
  	if (0 == sizeof($aleph_data->xpath("//error")) {
  		$user['name'] = $aleph_data->xpath("//z304-address-0")[0];
  		$user['email'] = $aleph_data
  		->xpath("//z304-email-address")[0];
  		$user['role'] = $aleph_data->xpath("//z305-bor-type")[0];
  	}

  	return $user;
  }

	public function endpoint($target, $key) {
		Log::info('Resolving ' . $target . "\r\n");
		switch ($target) {
			case 'address':
				return $this->AlephWebService . urlencode($key) . $this->Endpoint['address'];
				break;
			case 'barcode':
				return $this->AlephXService . $this->Endpoint['barcode'] . "&bor_id=" . urlencode($key);
			case 'status':
				return $this->AlephWebService . urlencode($key) . $this->Endpoint['status'];
				break;
			default:
				Log::warning('ERROR: Could not resolve endpoint');
				return null;
		}
	}

	protected function validatePatronByName($name) {
		$endpoint = $this->endpoint('status', $name);
		$response = file_get_contents($endpoint);
		$aleph_data = simplexml_load_string($response);

		/**
		 * At this point we just need to look at the 
		 * <z305-expiry-date> element and compare it to the
		 * current date and time. 
		 *
		 * First though we handle the
		 * cases where the user's record was not found by
		 * automatically reporting it expired
		 */
		if ('0000' != $aleph_data->{'reply-code'}) {
			return false;
	  }

	  return $this->isActivePatron($aleph_data->xpath('//z305-expiry-date')[0]);

	}

	protected function validatePatronByBarcode($code) {
		$endpoint = $this->endpoint('barcode', $code);
		$response = file_get_contents($endpoint);
		$aleph_data = simplexml_load_string($response);

		/**
		 * At this point we just need to look at the 
		 * <z305-expiry-date> element and compare it to the
		 * current date and time. 
		 *
		 * First though we handle the
		 * cases where the user's record was not found by
		 * automatically reporting it expired
		 */

		if ($aleph_data->{'error'}) {
			return false;
	  }

	  /**
	   * TODO: As a side effect of validation should some
	   *       information be mirrored to the local database.
	   *       It feels as if this is a bad side effect to not
	   *       explicitly document
	   */
	  return $this->isActivePatron($aleph_data->xpath('//z305-expiry-date')[0]);	}

	/**
	 * Compare UNIX timestamps to see if the record should be
	 * considered expired or valid
	 */
	protected function isActivePatron($timestamp) {
		$expiry = strtotime($timestamp);
		return ($expiry > time());

	}
	protected function parse_name($name) {
		// It is assumed that the first and second values are the important
		// ones. Code to handle edge cases lie hyphenation can be added down
		// the road
		$name = $this->normalize_name($name);

		// We will be returning two versions - one without a . and one
		// with it. For example
		//
		// jsmith
		// j.smith
		$username[0] = substr($name['first'], 0, 1) . $name['last'];
		$username[1] = substr_replace($username[0], ".", 1, 0);

		return $username;
	}

	protected function compare_name_equality($local, $aleph) {
		$names = [
		 	$this->normalize_name($local),
			$this->normalize_name($aleph)
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
	protected function normalize_name($input) {
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
		return ['first' => strtolower($first_name), 
				'last' => strtolower($last_name)];
	}
}
?>