<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Role;

class RolesTableSeeder extends Seeder {
	public function run() {
		DB::table('roles')->delete();

		Role::create([
			'role' => 'Academic',
			'description' => 'Student, staff, and faculty guests'
		]);
		Role::create([
			'role' => 'Docent',
			'description' => 'Museum docents'
		]);
		Role::create([
			'role' => 'Fellow',
			'description' => 'Research fellows'
		]);
		Role::create([
			'role' => 'Intern',
			'description' => 'Temporary interns'
		]);
		Role::create([
			'role' => 'Member',
			'description' => 'CMA Member'
		]);
		Role::create([
			'role' => 'Public',
			'description' => 'Public library user without other affiliation'
		]);
		Role::create([
			'role' => 'Staff',
			'description' => 'CMA Employee'
		]);
		Role::create([
			'role' => 'Volunteer',
			'description' => 'CMA Volunteer'
		]);
	}
}
?>