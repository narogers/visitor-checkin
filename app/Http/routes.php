<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');

Route::controller('register', 'RegistrationController');
Route::group(['before' => 'fw-allow-wl'], function ()
{
  Route::controller('checkin', 'CheckinController');

  # Because of a problem wih Drupal use 'reports' instead
  Route::group(['prefix' => 'reports'], function()
  {
	  Route::get('/', 'AdminController@getIndex');
	  Route::get('/registrations', 'AdminRegistrationController@getIndex');
	  Route::get('/registration/{user}', 'AdminRegistrationController@getRegistration');
	  Route::post('/registration/{user}', 
		'AdminRegistrationController@postRegistration');
	  Route::get('/checkins/{range?}', 'AdminCheckinController@getIndex');
	  Route::get('/checkins/{user}/{range?}',
		'AdminCheckinController@getCheckins');
  });
});
