<?php namespace App\Http\Middleware;


use App\Registration;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class BackNavigation {

	/**
	 * Maybe this shoud not be hard coded but for the time being it gets
	 * the job done. One idea is to move it into an application level
	 * configuration file.
	 */
	public static $registration_steps = ['Index', // First step
		'New', // Second step
		'TermsOfUse', // Third step
		'Welcome']; // Fourth step

	/**
	 * Handles processing the 'Go back' button in the workflow by
	 * trapping it before form validation and redirecting to the
	 * previous page in the form.
	 *
	 * The naive implementation relies only on 
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if (!$request->has('previous_step')) {
			return $next($request);
		}

		Log::info('[FILTER] Requesting method was: ' . $request->method());
		Log::info('[FILTER] Current action(s): ' . 
			$request->route()->getActionName());
		/**
		 * The following regular expression extracts the action after
		 * stripping away the request type. This key can then be used to
		 * redirect to the correct action (always as a GET)
		 *
		 * Since a typical request will look like
		 *
		 * App\Http\Controllers\RegistrationController@getIndex
		 *
		 * The resulting array should contain
		 *
		 * @getIndex [full match]
		 * get [method]
		 * Index [action]
		 */
		preg_match('/@(get|post)(.*)$/',
			$request->route()->getActionName(),
			$route_segments);
		Log::info('[FILTER] Current view is of the ' .
			$route_segments[2]);
		$step_index = array_search($route_segments[2], 
			self::$registration_steps);
		/**
		 * Even if we happen to match the first step do nothing but redirect
		 * back to the requesting page. In the off chance you somehow hit
		 * this page without matching a valid workflow step simply default
		 * to the first step.
		 *
		 * Jump back two spots because we are actually one index AHEAD of
		 * where we started
		 */
		$step_index = ($step_index > 0) ? $step_index - 2 : 0;
		Log::info('[FILTER] Redirecting to RegistrationController@get' . 
			self::$registration_steps[$step_index]);
		return redirect()->action('RegistrationController@get' . 
			self::$registration_steps[$step_index]);
	}

}
