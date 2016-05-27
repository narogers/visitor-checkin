<?php

namespace App\Repositories;

class PatronRepository implements PatronInterface {
  /**
   * User model that acts as an information hub
   */
  protected $patronModel;

  /**
   * Set the internal patron model to our injected version
   *
   * @param User $patron
   * @return PatronRepository
   */
  public function __construct(User $patron) {
    $this->patronModel = $patron;
  }

  /**
   * Retrieve a user by unique identifier
   *
   * @param integer
   * @return User
   */
  public function getUser(integer $uid) {
    return $user = $this->patronModel->find($uid);
  }

  /**
   * Retrieve a user by last name. In case of multiple matches it will
   * return only the first
   *
   * @param name
   * @return User
   */
  public function getUserByName(string $name) {
    $user = $this->patronModel->where("name", "LIKE", "%${name}");
    if ($user) {
      return $user->first();
    }

    return null;
  }

  /**
   * Retrieve a user by barcode or membership ID
   *
   * @param string
   * @return User
   */
  public function getUserByBarcode(string $barcode) {
    $user = $this->patronModel->where("barcode", $barcode);
    if ($user) {
      return $user->first();
    }
   
    return null;
  }

  /**
   * Retrieves multiple users filtered by provided criteria
   *
   * @param array [optional]
   * @return collection
   */
  public function getUsers(array $filters = []) {
    $results = [];

    $query = $this->patronModel->select();     
    if (count($filters) > 0) {
      foreach (array_keys($filters) as $filter => $value) {
        $query->where($filter, $value);
      }
    }

    return $query->get();
  } 

  /**
   * Assign a role to a user
   *
   * @param integer
   * @param string 
   * @return boolean
   */
  public function setRole(integer $uid, string $role) {
    $user = $this->patronModel->find($uid);
    $role = Role::where('role', $role)->get();

    $user->role()->associate($role);
    return $user->save();
  }

  /**
   * Add registration details for a user and then associate them
   *
   * @param integer
   * @param array
   * @return boolean
   */
  public function setRegistration(integer $uid, array $registration) {
      $user = $this->patronModel->find($uid);
      $user->registration()->updateOrCreate($reg);

      return (null == $user->registration());
  }

  /**
   * Update details for an existing patron 
   *
   * @param string 
   * @param array
   * @return boolean
   */
  public function update(integer $uid, array $properties) {
    $user = $this->patronModel->find($uid);
    return $user->update($properties);
  }

  /**
   * Returns checkins that meet certain filter criteria. Permissible limits
   * include
   * - UID
   * - Starting Date
   * - Ending Date
   *
   * @param array
   * @return collection
   */
  public function getCheckins(array $filters = []) {
    $query = Checkin::select();
    if (key_exists('uid', $filters)) {
      $query = $query->where('user_id', $filters['uid']);
    }
    if (key_exists('starting_date', $filters)) {
      $query = $query->where('updated_at', '=>', $filters['starting_date']);
    }
    if (key_exists('ending_date', $filters)) {
      $query = $query->where('updated_at', '<=',  $filters['ending_date']);
    }

    return $query->get();
  }

  /**
   * Returns a collection of all roles
   *
   * @return collection
   */
  public function getRoles() {
    Role::all();
  }
}
