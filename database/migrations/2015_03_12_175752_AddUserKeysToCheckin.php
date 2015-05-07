<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserKeysToCheckin extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('checkins', function(Blueprint $table) 
		{
			$table->integer('user_id')->references('id')->on('users')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('checkins', function(Blueprint $table) 
		{
			$table->dropColumn('user_id');
		});		
	}

}
