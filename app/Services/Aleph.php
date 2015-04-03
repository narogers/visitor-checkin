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
		'status' => '/patronStatus/registration'
		// Barcode validation for X Service
	];
	protected $user;

	public function __construct(User $user) {
		$this->AlephWebService = $this->AlephHost . ":1891/rest-dlf/patron/";
		$this->AlephXService = $this->AlephHost . "/X?";

		$this->user = $user;
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
	public function getPatronID() {
		$aleph_ids = $this->parse_name();
		Log::info('Attempting to resolve IDs');
		
		foreach ($aleph_ids as $aleph_id) {
			// Get it working and then refactor
			$response = file_get_contents($this->build_endpoint('address', $aleph_id));
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
			if($this->compare_name_equality($this->user->name,
				$aleph_data->{'address-information'}->{'z304-address-1'})) {
				return $aleph_id;
			}
		}

		// If we happen to fall through to here then there are no valid IDs.
		// Returning NULL is a bandaid until something better can be done.
		Log::warning('WARNING: Unable to resolve an Aleph ID for ' . 
			$this->user->name);
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
	public function isExpired($patronID) {
	}

	public function endpoint($target) {
		Log::info('Resolving ' . $target . "\r\n");
		switch ($target) {
			case 'address':
				return $this->Endpoint['address'];
				break;
			case 'status':
				return $this->Endpoint['status'];
				break;
			default:
				Log::warning('ERROR: Could not resolve endpoint');
				return null;
		}
	}

	protected function build_endpoint($service, $aleph_id) {
		Log::info('Constructing call to ' . $this->AlephWebService . urlencode($aleph_id) . $this->Endpoint('address'));
		return $this->AlephWebService . urlencode($aleph_id) . 
				$this->Endpoint('address');
	}

	protected function parse_name() {
		// It is assumed that the first and second values are the important
		// ones. Code to handle edge cases lie hyphenation can be added down
		// the road
		$name = $this->normalize_name($this->user->name);

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
		
		$name_parts = preg_split("/,?\s+/", $this->user->name);
		if (preg_match("/,/", $this->user->name)) {
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