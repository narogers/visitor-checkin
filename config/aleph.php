<?php

return [
/**
 * Aleph configuration parameters
 *
 * Set the properties below so that the application can interface with
 * Aleph's X Service and RESTful interface. It is highly recommended to
 * not commit these to version control; use an environment setting or an
 * override instead
 *
 */

 /**
  * Domain or IP connection
  */
 'host' => env('ALEPH_HOST', 'localhost'),

 /**
  * Port for RESTful services. For a default installation this is 1891
  */
 'dlf_port' => env('ALEPH_DLF_PORT', 1891),

 /**
  * User with access to X Services. Aleph will default to www-x if no
  * value is provided
  */
 'www_user' => env('ALEPH_USER', 'www-x'),
 /**
  * Null means that no password will be appended to the query string
  */
 'www_password' => env('ALEPH_PASSWORD', null)
];
