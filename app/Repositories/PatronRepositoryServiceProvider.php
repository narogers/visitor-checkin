<?php

namespace App\Repositories;

use App\Models\Checkin as Checkin;
use App\Models\Registration as Registration;
use App\Models\User as User;
use Illuminate\Support\ServiceProvider; 

class PatronRepositoryServiceProvider extends ServiceProvider {
  public function register() {
    $this->app->bind('App\Repositories\PatronInterface', function($app) {
      return new PatronRepository(new User(), new Registration(), 
        new Checkin());
    });
  }
}
