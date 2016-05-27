<?php

namespace App\Services;

use Illuminate\Support\ServiceProvider;

class RegistrationServiceServiceProvider extends ServiceProvider {
  public function register() {
    $this->app->bind('patronService', function($app) {
      return new RegistrationService(
        $app->make('App\Repositories\PatronInterface')
      );
    });
  }
}
