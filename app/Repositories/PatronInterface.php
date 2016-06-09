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
   * Retrieve first user that matches a set of criteria
   *
   * @param array $properties
   * @return User
   */
  public function getUserWhere(array $properties);

  /**
   * Retrieves all patrons with an optional filter to limit results
   *
   * @param array $filters (optional)
   * @return collection
   */
  public function getUsers(array $filters);
  
  /**
   * Retrieves all registered users with or without a filter
   *
   * @param string (optional)
   * @return array
   */
  public function getRegisteredUsers($limit = null);
  
  /**
   * Retrieves all users which have not completed the registration process
   *
   * @param string $limit (optional)
   * @return array
   */
  public function getPendingRegistrations($limit = null);

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
   * Update a user's details 
   *
   * @param integer
   * @param array
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
  public function checkin($uid, $timestamp = null);

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


