<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('roles', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('role')->unique();
			$table->string('description');
		});

		Schema::table('users', function(Blueprint $table) 
		{
			$table->integer('role_id')->references('id')->on('roles');
		});

		Schema::table('registrations', function(Blueprint $table) 
		{
			$table->dropColumn('registration_type');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('registrations', function(Blueprint $table) 
		{
			$table->string('registration_type');
		});

		Schema::table('users', function(Blueprint $table) 
		{
			$table->dropColumn(['role_id']);
		});
		Schema::drop('roles');
	}

}
