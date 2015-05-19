<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('services',function(Blueprint $table) {
			$table->increments('id');
			$table->integer('customer_id')->unsigned();
			$table->integer('contact_id')->unsigned();
			$table->string('job_number_internal')->nullable();
			$table->string('job_number_onsite')->nullable();
			$table->string('job_number_remote')->nullable();
			$table->integer('employee_id')->unsigned();
			$table->integer('hotel_id')->nullable();
			$table->timestamps();
		});

		Schema::table('services',function(Blueprint $table) {
			$table->foreign('customer_id')->references('id')->on('customers');
			$table->foreign('contact_id')->references('id')->on('contacts');
			$table->foreign('employee_id')->references('id')->on('employees');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('services');
	}

}
