<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TestDatabaseSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        $this->call(DatabaseSeeder::class);

        // Now call any custom classes for testing only
		$this->call(AcademicTestUsersSeeder::class);
		$this->call('DocentTestUsersSeeder');
   		$this->call('FellowTestUsersSeeder');
        $this->call('InternTestUsersSeeder');	
        $this->call('MemberTestUsersSeeder');
 		$this->call('PublicTestUsersSeeder');
		$this->call('StaffTestUsersSeeder');
		$this->call('VolunteerTestUsersSeeder');
	}
}
