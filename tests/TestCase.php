<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	/**
	 * Creates the application.
	 *
	 * @return \Illuminate\Foundation\Application
	 */
	public function createApplication()
	{
		$app = require __DIR__.'/../bootstrap/app.php';
		$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
		return $app;
	}

	/**
	 * Set up the environment for tests
	 */
	public function setUp() {
      parent::setUp();
	  Artisan::call("migrate:refresh");
      // Globally disable events 
      $this->withoutEvents();
	}
}
