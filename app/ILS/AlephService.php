<?php

namespace App\ILS;

use Illuminate\Support\Facades\Log;

class AlephService implements ILSInterface {
  protected $alephWebService;
  protected $alephXService;

  /**
   * Instantiate a new client
   *
   * @return AlephService
   */
  public function __construct() {
    $this->alephWebService = "http://" . Config('ils.host', 'localhost') .
      ":1891/rest-dlf";
    $this->alephXService = "http://" . Config('ils.host', 'localhost') .
      "/X?";
    Log::info("[ILSClient] Aleph connection initialized and ready for use");
    Log::info("[ILSClient] Using URI base " . $this->alephWebService . " for requests");  
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
   *
   * @param name
   * @return array
   */
  public function getIdentifiers($input) {
  	$pieces = [];
  	preg_match("/^(\w+)\s?.*\s([-a-zA-Z]+)$/", $input, $pieces);

  	$name['initial'] = '';
  	$name['last'] = '';

  	if (preg_match("/,/", $input)) {
  		$name['initial'] = $pieces[2];
  		$name['last'] = $pieces[1];
  	} else {
  		$name['initial'] = $pieces[1];
  		$name['last'] = $pieces[2];
  	}
  	$name['initial'] = strtoupper(substr($name['initial'], 0, 1));
  	$name['last'] = preg_replace("/\W+/", "", $name['last']);
  	$name['last'] = strtoupper(substr($name['last'], 0, 10));
  	
  	$identifiers = [
      $name['initial'] . "." . $name['last'],
  	  $name['initial'] . $name['last']
    ];

    return $identifiers;
  }

  /**
   * Resolve the canonical ID from Aleph
   *
   * @param identifier
   * @return string
   */
  public function getILSId($identifier) {
    $response = file_get_contents($this->endpointFor('id', $identifier));
	$aleph_data = simplexml_load_string($response);

	/*
	 * The two most likely responses are
	 *
	 * <error>Error in verification</error>
	 * <internal-id>[Internal ID for patron]</internal-id>
	 */
	if ($aleph_data->{'error'}) {
		Log::info("[ALEPH] Could not resolve " . $identifier . " to a canonical ID");
		Log::info("[ALEPH] ${response}");
        return null;
	} 
	if ($aleph_data->{'internal-id'}) {
		return $aleph_data->{'internal-id'}->__toString();
	}
  }

  /**
   * Look up a patron's registration in Aleph to determine if they
   * should be marked as active or expired
   *
   * @param string
   * @return boolean
   */
  public function isActive($identifier) {
    if (null == $identifier) {
      return false;
    }

    $response = file_get_contents($this->endpointFor("status", $identifier));
    $aleph_data = simplexml_load_string($response);
    $expires_on = $aleph_data->xpath("//z305-expiry-date");
    /**
     * If we get an error instead of the record then we'll just set the date
     * to null. This can be done easily by popping off the first value even if
     * the value is []
     */
    $expires_on = array_pop($expires_on);
 
    return (strtotime($expires_on) > time());
  }

  /**
   * Look up a patron's registration and determine if their account
   * has expired
   *
   * @param string
   * @return boolean
   */
  public function isExpired($identifier) {
    return !$this->isActive($identifier);
  }
 
  /**
   * Retrieve the patron's name, email address, and role from Aleph
   *
   * @param string
   * @return array
   */
  public function getPatronDetails($identifier) {
    $response = file_get_contents($this->endpointFor("address", $identifier));
    $patron_details = simplexml_load_string($response);

    $response = file_get_contents($this->endpointFor("status", $identifier));
    $patron_status = simplexml_load_string($response);

    /**
     * 0002 is Aleph speak for an invalid patron request
     */
    $reply_code = $patron_details->xpath("//reply-code")[0];
    if ("0002" == $reply_code) {
      return null;
    }

    /**
     * Otherwise press on and extract the relevant details from both feeds
     */
    $details = [];
    $details["name"] = $this->getFirstValueFor($patron_details, "//z304-address-1");
    $details["email"] = trim($this->getFirstValueFor($patron_details, "//z304-email-address"));
    $details["role"] = $this->getFirstValueFor($patron_status, "//z305-bor-type");
    $details["id"] = $identifier;

    return $details;
  } 

  /**
   * Retrieve the canonical endpoint for a particular service. A helper 
   * method which should not be publicly exposed
   *
   * @param string $endpoint
   * @param string $identifier
   * @return string $path
   */
  protected function endpointFor($endpoint, $identifier) {
    $uri = null;

    switch ($endpoint) {
	  case 'address':
		$uri = $this->alephWebService . "/patron/" . urlencode($identifier) . "/patronInformation/address";
		break;
	  case 'id':
		$uri = $this->alephXService . "library=CMA50&op=bor_by_key&bor_id=" . urlencode($identifier);
        break;
	  case 'status':
	    $uri = $this->alephWebService . "/patron/" . urlencode($identifier) . "/patronStatus/registration";
		break;
	  default:
	    Log::warning('ERROR: Could not resolve endpoint for ${endpoint}');
	  }
    Log::info("[ILSClient] Initializing call to ${uri}");
 
    return $uri;
  }

  /**
   * Gets the value from an XPath expression and returns it as a string.
   * If no matches it will return null
   *
   * @param $xml
   * @param string $xpath
   * @return string
   */
  protected function getFirstValueFor($xml, $xpath) {
    $values = $xml->xpath($xpath);
    if (0 == count($values)) {
      return null;
    } else {
      return $values[0]->__toString();
    }
  }
}
