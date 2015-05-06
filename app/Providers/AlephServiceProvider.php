<?php
namespace App\Providers;

use App\Services\AlephClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AlephServiceProvider extends ServiceProvider {
	/**
	 * Defer loading until actually needed during run time
	 */
	protected $defer = true;

	/**
	 * Register the provider
	 */
	public function register() {
		$this->app->bind('App\Services\AlephClient', function($app) {
			return new AlephClient();
		});
	}

	/**
	 * Get services provided by this provider
	 */
	public function provides() {
		return ['App\Services\AlephClient'];
	}
}