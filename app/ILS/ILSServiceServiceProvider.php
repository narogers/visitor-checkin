<?php

namespace App\ILS;

use Illuminate\Support\ServiceProvider;

class ILSServiceServiceProvider extends ServiceProvider {
  /**
   * Indicates if loading should be immediate or deferred
   *
   * @var bool
   */
  protected $defer = true;
 
  /**
   * Register the service provider
   *
   * @return void
   */
  public function register() {
    $this->app->bind("App\ILS\ILSInterface", "App\ILS\AlephService");
  }

  /**
   * List of interfaces provided by the Service
   *
   * @return array
   */
  public function provides() {
    return ['App\ILS\ILSInterface'];
  }
}
