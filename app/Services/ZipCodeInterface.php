<?php

namespace App\Services;

interface ZipCodeInterface {
  /**
   * Resolve a zip code and return the associated city and state
   *
   * @param $zip
   * @return array
   */
  public function lookup($zip);
}
