<?php

namespace App\Providers;

use App\Services\ZipCodeInterface;
use App\Services\ZiptasticService;
use Illuminate\Support\ServiceProvider; 

class ZipCodeServiceProvider extends ServiceProvider {
  public function register() {
    $this->app->bind('App\Services\ZipCodeInterface', 
      "App\Services\ZiptasticService");
  }
}
