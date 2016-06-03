<?php namespace App\Providers;

use App\Models\Registration;
use App\Services\ZipCodeResolver;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Log;

class EventServiceProvider extends ServiceProvider {

	/**
	 * The event handler mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		'event.name' => [
			'EventListener',
		],
	];

	/**
	 * Register any other events for your application.
	 *
	 * @param  \Illuminate\Contracts\Events\Dispatcher  $events
	 * @return void
	 */
	public function boot(DispatcherContract $events)
	{
		parent::boot($events);

	  /**
	   * Whether you are updating a registration or simply creating it for
	   * the first time make sure that you update the state field to be
	   * consistant with everything else.
	   *
	   * It might be worth making this more robust to handle the situation
	   * where the service returns a bad code but that can wait for a future
	   * version.
	   */
	  Registration::saving(function($registration) {
		if ($registration->address_zip) {
			$resolver = new ZipCodeResolver;
			$registration->address_state = $resolver->resolve($registration->address_zip);
		} else {
			$registration->address_state = '';
		}
		Log::debug('[REGISTRATION] State value => ' . $registration->address_state);
	  });
	}		
}
