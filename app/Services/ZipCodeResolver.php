<?php
/**
 * Helper functions for Visitor Checkin to help with API calls and other
 * features that should be broken out from the models.
 */
namespace App\Services;

use Illuminate\Support\Facades\Log;

class ZipCodeResolver {
	/**
 	 * This API call acts as a resolver for zip codes to states. Assuming there 
 	 * are no edge cases where a zip code spans state borders it shuld be an 
 	 * easy way to get the information without people having to complete another * field. The zip should be provided as five digits only.
 	 *
 	 * TODO: In case of error return null unless the server cannot be contacted * in which case the method should throw a RunimeException
 	 */
	public function resolve($zipcode = '') {
		/**
   	 	 * Hard coding this is not a viable long term solution but since this 
   	 	 * is just a prototype we'll let it slide for now
	 	 */
		$resolver = "http://ziptasticapi.com/";

		if (!is_numeric($zipcode) ||
			(5 != strlen($zipcode))) {
			return null;
		}

		$query = $resolver . $zipcode;
		
		// This is where we need to improve error handling in case the service 
		// goes down. Avoid silent errors!
		$query_data = json_decode(file_get_contents($query));
		if (array_key_exists('error', $query_data)) {
			Log::error('[ZIP CODE] Zip code API returned an error');
			Log::error('[ZIP CODE] Please check query ' . $query);
			return '';
		} else {
			// Here we should have a state that can be returned
			return $query_data->state;
		}
	}
}
?>