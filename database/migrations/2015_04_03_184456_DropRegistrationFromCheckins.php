<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropRegistrationFromCheckins extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('checkins', function(Blueprint $table) {
			$table->dropColumn('registration_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('checkins', function(Blueprint $table) {
			$table->integer('registration_id');
		});
	}

}
