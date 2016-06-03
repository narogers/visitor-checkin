<?php

namespace App\Repositories;

use App\Models\Checkin as Checkin;
use App\Models\Registration as Registration;
use App\Models\Role as Role;
use App\Models\User as User;

use Illuminate\Support\Facades\Log;

class PatronRepository implements PatronInterface {
  /**
   * User model that acts as an information hub
   */
  protected $patronModel;
  protected $registrationModel;
  protected $checkinModel;

  /**
   * Provide access to internal models to use when interfacing with the
   * data store
   *
   * @param User $patron
   * @param Registration $registration
   * @param Checkin $checkin
   * @return PatronRepository
   */
  public function __construct(User $patron, Registration $registration, 
    Checkin $checkin) {
    $this->patronModel = $patron;
    $this->registrationModel = $registration;
    $this->checkinModel = $checkin;
  }

  /**
   * Create a new user and return it for processing downstream
   *
   * @param array
   * @return User
   */
  public function createOrFindUser(array $properties) {
    if (!array_key_exists('email_address', $properties)) {
      return null;
    }

    $qry = $this->patronModel->where('email_address', $properties['email_address']);
    if (0 == $qry->count()) {
      $user = $this->patronModel->create($properties);
      return $user;
    }

    /**
     * Otherwise return the user
     */
    return $qry->get()->first();
  }

  /**
   * Retrieve a user by unique identifier
   *
   * @param integer
   * @return User
   */
  public function getUser($uid) {
    return $user = $this->patronModel->find($uid);
  }

  /**
   * Retrieve a user based on a set of properties
   *
   * @param array
   * @return User
   */
  public function getUserWhere(array $properties) {
    $results = getUsers($properties);
    
    if (0 < count($results)) {
      $results->first();
    } else {
      return null;
    }
  }

  /**
   * Retrieves multiple users filtered by provided criteria
   *
   * @param array [optional]
   * @return collection
   */
  public function getUsers(array $properties = []) {
    $results = [];

    $query = $this->patronModel->select();     
    if (count($filters) > 0) {
      foreach (array_keys($filters) as $filter => $value) {
        /**
         * For names allow loose matches. For all other fields exact matches
         * only are needed to get a list of users
         */
        if ($filter == "name") {
          $query->where($filter, "LIKE", "%${value}");
        } else {
          $query->where($filter, $value);
        }
      }
    }

    return $query->get();
  } 

  /**
   * Retrieve all registered users, or a subset if a filter is provided
   *
   * @param string 
   * @return array
   */
  public function getRegisteredUsers($limit = null) {
    $query = $this->patronModel->select();
    $query->whereNotNull('aleph_id')
      ->where('verified_user', true);
    
    /**
     * Now filter further if the limit is set
     */
    if ($limit) {
      $query->where('name', 'LIKE', "%${limit}")
        ->orWhere('aleph_id', $limit)
        ->orWhere('barcode', $limit);
    }
   
    return $query->get()->toArray();
  }

  /**
   * Assign a role to a user
   *
   * @param integer
   * @param string 
   * @return boolean
   */
  public function setRole($uid, $role) {
    $user = $this->patronModel->findOrFail($uid);
    $role = Role::where('role', $role)->firstOrFail();
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
  public function setRegistration($uid, array $registration) {
      $user = $this->patronModel->find($uid);
      $user->registration()->updateOrCreate([], $registration);

      return (null == $user->registration());
  }

  /**
   * Update details for an existing patron 
   *
   * @param string 
   * @param array
   * @return boolean
   */
  public function update($uid, array $properties) {
    $user = $this->patronModel->find($uid);
    $fields = ["aleph_id", "barcode", "name", "signature", "verified_user"];
    foreach ($properties as $field => $value) {
      if (in_array($field, $fields)) {
        $user[$field] = $value;
      }
    }
    
    return $user->save();
  }

  /**
   * Register a check in at the current time
   *
   * @param integer
   * @return boolean
   */
  public function checkin($uid, Date $timestamp = null) {
    $user = $this->patronModel->find($uid);
    if (null == $timestamp) {
      $timestamp = time();
    }
    $user->checkins()->create(['created_at' => $timestamp]);

    // The only way for this to fail is to throw an exception
    return true;
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
    $query = $this->checkinModel->select();
    
    if (key_exists('starting_date', $filters)) {
      $query = $query->where('created_at', '>=', $filters['starting_date']);
    }
    if (key_exists('ending_date', $filters)) {
      $query = $query->where('created_at', '<=', $filters['ending_date']);
    }
    if (key_exists("uid", $filters)) {
      $query = $query->where("user_id", $filters["uid"]);
    }
    $query = $query->with("user.role");

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


  /**
   * Verify if a role exists in the database
   *
   * @param string
   * @return boolean
   */
  public function hasRole($role) {
    return (1 == Role::where('role', $role)->count());
  }
  
}
