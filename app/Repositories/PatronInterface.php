<?php

namespace App\Repositories;

/**
 * Interface for access to patron details
 */
interface PatronInterface {
  /**
   * Create or find user with provided attributes
   *
   * @param array
   * @return User
   */
  public function createOrFindUser(array $properties);

  /**
   * Retrieve user by ID
   *
   * @param ID
   * @return User
   */
  public function getUser($uid);

  /**
   * Retrieve user by name
   *
   * @param Name
   * @return User
   */
  public function getUserByName(\string $name);

  /**
   * Retrieve user by barcode
   *
   * @param Barcode
   * @return User
   */
  public function getUserByBarcode(\string $barcode);

  /**
   * Retrieves all Users from the backing data store
   *
   * @return collection
   */
  public function getUsers(array $filters);

  /**
   * Assign a role to a patron
   *
   * @param Role
   * @return boolean
   */
  public function setRole($uid, $role);

  /**
   * Add or update registration to an existing user
   *
   * @param User
   * @param hash
   * @return boolean
   */
  public function setRegistration($uid, array $registration);

  /**
   * Set a registration as verified or unverified
   *
   * @param User
   * @param boolean
   * @return boolean
   */
  public function update($uid, array $properties);

  /**
   * Check in a patron for a given date
   *
   * @param User
   * @param Date (optional)
   * @return boolean
   */
  public function checkin($uid, Date $timestamp = null);

  /**
   * Retrieve checkins for a given patron
   * 
   * @param array
   * @return collection
   */
  public function getCheckins(array $filters);

  /**
   * Return a canonical list of available roles
   *
   * @return array
   */
  public function getRoles();

  /**
   * Verify a role exists in the database
   *
   * @param string
   * @return boolean
   */
  public function hasRole($role);
}


