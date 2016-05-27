<?php

use App\Repositories\PatronInterface;

class RegistrationService {
  /**
   * The interface to interact with patrons through
   */
  protected $patronRepo;

  /**
   * Load a new instance with a reference to the underlying repository
   *
   * @param PatronInterface
   * @return RegistrationService
   */
  public function __construct(PatronInterface $patronRepo) {
    $this->patronRepo = $patronRepo;
  } 
}
