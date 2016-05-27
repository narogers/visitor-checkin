<?php

namespace App\Repositories;

/**
 * Interface for access to patron details
 */
interface PatronInterface {
  /**
   * Retrieve user by ID
   *
   * @var ID
   * @return User
   */
  public function getUser(integer $uid);

  /**
   * Retrieve user by name
   *
   * @var Name
   * @return User
   */
  public function getUserByName(string $name);

  /**
   * Retrieve user by barcode
   *
   * @var Barcode
   * @return User
   */
  public function getUserByBarcode(integer $barcode);

  /**
   * Retrieves all Users from the backing data store
   *
   * @return array of Users
   */
  public function all();

  /**
   * Assign a role to a patron
   *
   * @var Role
   * @return boolean
   */
  public function setRole(User $user, Role $role);

  /**
   * Add a registration to an existing user
   *
   * @var User
   * @var hash
   * @return boolean
   */
  public function addRegistration(string $user, array $registration);

  /**
   * Update an existing registration
   *
   * @var User
   * @var array
   * @return boolean
   */
  public function updateRegistration(string $user, array $registration);

  /**
   * Set a registration as verified or unverified
   *
   * @var User
   * @var boolean
   * @return boolean
   */
  public function markPatronVerified(string $user);

  /**
   * Check in a patron for a given date
   *
   * @var User
   * @var Date (optional)
   * @return boolean
   */
  public function checkIn(string $user, Date $date = null);

  /**
   * Retrieve checkins for a given patron
   * 
   * @var User
   * @var Starting date
   * @var Ending date
   * @return array
   */
  public function checkinsInFor(string $user, Date $since = null, Date $until = null);

  /**
   * Retrieve a list of all users who have checked in during a given window
   *
   * @var Starting date
   * @var Ending date
   * @return array
   */
  public function checkInsBetween(Date $since = null, Date $until = null);

  /**
   * Return a canonical list of available roles
   *
   * @return array
   */
  public function getRoles();
}
