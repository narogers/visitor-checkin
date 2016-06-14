<?php

namespace App\Observers;

use App\Models\Registration;
use App\Services\ZipCodeInterface;

use Illuminate\Support\Facades\Log;

class RegistrationObserver {
  protected $service;

  public function __construct(ZipCodeInterface $service) {
    $this->service = $service;
  }

  public function saving(Registration $registration) {
    if (isset($registration->address_zip)) {
      $details = $this->service->lookup($registration->address_zip);
      $registration->address_state = $details["state"];

      Log::debug("[REGISTRATION] Resolved zip code " 
        . $registration->address_zip . " to " .
        $details["state"]);
    }
  }
}
