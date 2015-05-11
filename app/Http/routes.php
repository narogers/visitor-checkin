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
Route::controller('checkin', 'CheckinController');

Route::group(['prefix' => 'admin'], function()
{
	Route::get('/', 'AdminController@getIndex');
	Route::controller('pending', 'AdminRegistrationController');
});