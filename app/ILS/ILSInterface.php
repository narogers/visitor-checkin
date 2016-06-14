<?php

namespace App\ILS;

/**
 * Interface for access to library services
 */
interface ILSInterface {
  /**
   * Retrieve a list of possible keys for a given patron
   *
   * @param string
   * @return array
   */
  public function getIdentifiers($patron);

  /**
   * Get canonical patron ID from the ILS
   *
   * @param string $identifier
   * @return string
   */
  public function getILSId($identifier);

  /**
   * Returns true if the user is considered active by the ILS
   *
   * @param string $identifier
   * @return boolean
   */
  public function isActive($identifier);

  /**
   * See isActive()
   *
   * @param identifier
   * @return boolean
   */
  public function isExpired($identifier);

  /**
   * Retrieve patron details from the ILS
   *
   * @param string $identifier
   * @return array
   */
  public function getPatronDetails($identifier);
}
