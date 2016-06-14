<?php

namespace App\Services;

use App\Services\ZipCodeInterface;
use Illuminate\Support\Facades\Log;

/**
 * This API call acts as a resolver for zip codes to states. Assuming there 
 * are no edge cases where a zip code spans state borders it shuld be an 
 * easy way to get the information without people having to complete 
 * another field. The zip should be provided as five digits only.
 */
class ZiptasticService implements ZipCodeInterface {
  /**
   * Uses the Ziptastic API to resolve cities and states from the
   * zip code. Use either xxxxx or xxxxx-xxxx format
   *
   * @param $zipcode
   * @return array
   */
  public function lookup($zipcode = '') {
		/**
   	 	 * Hard coding this is not a viable long term solution but since this 
   	 	 * is just a prototype we'll let it slide for now
	 	 */
		$resolver = "http://ziptasticapi.com/";

		if (!is_numeric($zipcode)) {
		  return null;
		}

		$query = $resolver . $zipcode;
		
		// This is where we need to improve error handling in case the service 
		// goes down. Avoid silent errors!
		$results = json_decode(file_get_contents($query));
		if (array_key_exists('error', $results)) {
		  Log::error('[ZIP CODE] Zip code API returned an error');
		  Log::error('[ZIP CODE] Please check results for ' . $query);
			return null;
		} else {
		  return ["city" => $results->city, "state" => $results->state];
		}
	}
}
?>
