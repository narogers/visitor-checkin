<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AllowOptionalFields extends Migration {

	/**
	 * Adds fields that were overlooked last week for registrations that
	 * require a job description, supervisor, and anything that requires the
	 * address since city somehow got missed among the list
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('registrations', function(Blueprint $table) {
			#$table->string('job_title')->nullable(true)->change();
			#$table->string('supervisor')->nullable(true)->change();

			#$table->string('address_city')->nullable(true)->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('registrations', function(Blueprint $table) {
			#$table->string('job_title')->change();
			#$table->string('supervisor')->change();
			#$table->string('address_city')->change();
		});
	}


}
