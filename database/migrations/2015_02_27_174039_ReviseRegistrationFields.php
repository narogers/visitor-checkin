<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReviseRegistrationFields extends Migration {
	public function up()
	{
		Schema::table('registrations', function(Blueprint $table) {
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
			$table->renameColumn('expires_on', 'expiration_date');
		});
	}

}
