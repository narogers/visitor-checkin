<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Checkin;
use App\Models\Registration;
use App\Models\Role;
use App\Models\User;

abstract class TestUsersSeeder extends Seeder {
  /**
   * Role which will be associated with each mock user
   *
   * @return string $role
   */
  abstract function role();

  /**
   * Create a batch of test users with the role that cover each date range
   */
  public function run() {
    $role = Role::where(["role" => $this->role()])->first();
    $users = factory(App\Models\User::class, 5)
      ->create()
      ->each(function($u, $role) {
        $u->role()->associate($role);
    });
   
    $faker = Faker::create();
    $checkin_today = new App\Models\Checkin;
    $checkin_today->created_at = "2015-03-19 00:02:45";
    $users[0]->checkins()->save($checkin_today);
    
    // This 'week' 
    $checkin_week = new App\Models\Checkin;
    $checkin_week->created_at = $faker->dateTimeBetween("2015-03-15", "2015-03-18");
    $users[1]->checkins()->save($checkin_week);

    // This 'month' excluse of the current 'week'
    $checkin_month = new App\Models\Checkin;
    $checkin_month->created_at = $faker->dateTimeBetween("2015-03-01", "2015-03-12");
    $users[2]->checkins()->save($checkin_month); 

    // Within the last month exclusive of March
    $checkin_pastmonth = new App\Models\Checkin;
    $checkin_pastmonth->created_at = $faker->dateTimeBetween("2015-02-01", "2015-02-28");
    $users[3]->checkins()->save($checkin_pastmonth);

    // And for good measure add a fifth user who has yet to be verified
    $users[4]->verified_user = false;
    $users[4]->save();
  }
}
