<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReviseRegistrationFields extends Migration {

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
			#$table->string('job_title');
			#$table->string('supervisor');

			#$table->string('address_city');

			// Rename expiration_date to expires_on to follow conventions
			$table->renameColumn('expiration_date', 'expires_on')->default('');
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
			#$table->dropColumn('job_title');
			#$table->dropColumn('supervisor');
			#$table->dropColumn('address_city');

			$table->renameColumn('expires_on', 'expiration_date');
		});
	}

}
