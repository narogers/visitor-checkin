<?php namespace App\Http\Middleware;

use App\Registration;
use Closure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class InjectRegistration {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (Session::has('registration')) {
			$registration = Session::get('registration');
			Log::info('[FILTER] Retrieved form information from the session');
		} else {
			$registration = new Registration;
		}
		
		return $next($request);
	}

}
