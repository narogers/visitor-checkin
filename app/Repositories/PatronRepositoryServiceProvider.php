<?php

namespace App\Repositories;

use App\Models\User as User;
use Illuminate\Support\ServiceProvider; 

class PatronRepositoryServiceProvider extends ServiceProvider {
  public function register() {
    $this->app->bind('App\Repositories\PatronInterface', function($app) {
      return new PatronRepository(new User());
    });
  }
}