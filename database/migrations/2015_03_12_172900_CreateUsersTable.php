<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('aleph_id')->nullable();
			$table->integer('barcode')->nullable();
			$table->string('email_address')->unique();
			$table->string('name')->index();
			$table->binary('signature')->default('');
			$table->timestamps();
		});

		Schema::table('registrations', function(Blueprint $table) {
			$table->integer('user_id')->references('id')->on('users')->default(0);
		});
		Schema::table('registrations', function(Blueprint $table) {
			$table->dropColumn(['name', 'email_address', 
				'signature']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// WARNING: This migration is not completely reversible
		Schema::drop('users');
		Schema::table('registrations', function(Blueprint $table)
		{
			$table->string('name');
			$table->string('email_address');
			$table->binary('signature');
		});
	}

}
