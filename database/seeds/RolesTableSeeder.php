<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Role;

class RolesTableSeeder extends Seeder {
	public function run() {
		Role::firstOrCreate([
			'role' => 'Academic',
			'description' => 'Student, staff, and faculty guests'
		]);
		Role::firstOrCreate([
			'role' => 'Docent',
			'description' => 'Museum docents'
		]);
		Role::firstOrCreate([
			'role' => 'Fellow',
			'description' => 'Research fellows'
		]);
		Role::firstOrCreate([
			'role' => 'Intern',
			'description' => 'Temporary interns'
		]);
		Role::firstOrCreate([
			'role' => 'Member',
			'description' => 'CMA Member'
		]);
		Role::firstOrCreate([
			'role' => 'Public',
			'description' => 'Public library user without other affiliation'
		]);
		Role::firstOrCreate([
			'role' => 'Staff',
			'description' => 'CMA Employee'
		]);
		Role::firstOrCreate([
			'role' => 'Volunteer',
			'description' => 'CMA Volunteer'
		]);
		Role::firstOrCreate([
			'role' => "Unknown",
			"description" => "Undefined role"
		]);
	}
}
?>
