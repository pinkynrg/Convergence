<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesServicesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employees_services',function(Blueprint $table) {
			$table->increments('id');
			$table->integer('service_id')->unsigned()->nullable();
			$table->integer('employee_id')->unsigned()->nullable();;
			$table->integer('division_id')->unsigned()->nullable();;
			$table->integer('work_description');
			$table->datetime('internal_start')->nullable();
			$table->datetime('internal_end')->nullable();
			$table->integer('internal_estimated_hours')->nullable();
			$table->datetime('onsite_start')->nullable();
			$table->datetime('onsite_end')->nullable();
			$table->integer('onsite_estimated_hours')->nullable();
			$table->datetime('remote_start')->nullable();
			$table->datetime('remote_end')->nullable();
			$table->integer('remote_estimated_hours')->nullable();
			$table->timestamps();
		});

		Schema::table('employees_services',function(Blueprint $table) {
			$table->foreign('service_id')->references('id')->on('services');
			$table->foreign('employee_id')->references('id')->on('employees');
			$table->foreign('division_id')->references('id')->on('divisions');
		});	
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('employees_services');
	}

}
