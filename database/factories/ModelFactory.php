<?php

$factory->define(App\Models\User::class, function($faker) {
  return [
    'name' => $faker->name,
    'aleph_id' => $faker->unique()->lastName,
    'email_address' => $faker->unique()->safeEmail,
    'signature' => 'aaaaaaaaaaaaaaaaaaaaaa',
    'verified_user' => true
  ];
});

/**
 * Since each registration type uses different fields we will just define
 * a factory for exceptions. The default roles (academic, public, etc) are
 * covered by the default case
 */
$factory->define(App\Models\Registration::class, function($faker) {
  return [
    'address_street' => $faker->streetAddress,
    'address_city' => $faker->city,
    'address_zip' => $faker->postcode,
    'telephone' => $faker->phoneNumber, 
  ];
});

$factory->defineAs(App\Models\Registration::class, "fellow_or_staff", function($faker) {
  return [
    'department' => $faker->catchphrase,
    'job_title' => $faker->jobTitle,
    'extension' => $faker->numberBetween(2000, 8000)
  ];
});

$factory->defineAs(App\Models\Registration::class, "intern", function($faker) {
  return [
    'address_street' => $faker->streetAddress,
    'address_city' => $faker->city,
    'address_zip' => $faker->postcode,
    'department' => $faker->catchphrase,
    'supervisor' => $faker->name
  ];
});

$factory->defineAs(App\Models\Registration::class, "member", function($faker)
  use ($factory) {
  $registration = $factory->raw(App\Models\Registration::class);
  $registration["barcode"] = $faker->ean13;
  return $registration;
});
