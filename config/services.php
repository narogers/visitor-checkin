<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/
	'aleph' => [
		'x-server' => env('ALEPH_X_SERVER', 'http://localhost/X'),
		'rest' => env('ALEPH_REST_SERVICE', "http://localhost:1893/rest-dlf")
	]
];
