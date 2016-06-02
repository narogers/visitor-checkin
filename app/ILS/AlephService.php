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
    Log::info("[ILSClient] Using " . $this->alephWebService . " for requests");  
  }

  /**
   * Calculate the default keys for a patron based on specific logic. This
   * can be swapped out for different use cases easily.
   *
   * @param name
   * @return array
   */
  public function getIdentifiers($name) {
    return [];
  }

  /**
   * Resolve the canonical ID from Aleph
   *
   * @param identifier
   * @return string
   */
  public function getILSId($identifier) {
    return "foo.bar";
  }

  /**
   * Look up a patron's registration in Aleph to determine if they
   * should be marked as active or expired
   *
   * @param string
   * @return boolean
   */
  public function isActive($identifier) {
    return true;
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
    return [
      "name" => "Uncle Wiggly",
      "email" => "wiggly@rabbits.org",
      "role" => "Member"
    ];
  }  
}
