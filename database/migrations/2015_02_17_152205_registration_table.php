<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RegistrationTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('registrations', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('email_address');
			/*
			 * Specific columns to cover each type of visitor
			 * that might exist
			 */
			$table->binary('signature');
			$table->string('address_street')->nullable();
			$table->string('address_state')->nullable();
			$table->integer('address_zip')->nullable();
			/*
			 * Store just digits and format on the view end
			 */
			$table->string('telephone', 10)->nullable();
			$table->string('department')->nullable();
			/*
			 * Likewise with the extension - store only the digits
			 */
			$table->integer('extension')->nullable();
			$table->dateTime('expiration_date')->nullable();
			/*
			 * Barcode could stash either a staff barcode or a member's
			 * badge if the front end can read it
			 */
			$table->string('barcode')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('registrations');
	}

}
