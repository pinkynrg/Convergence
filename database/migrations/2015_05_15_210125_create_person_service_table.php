<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonServiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('person_service',function(Blueprint $table) {
			$table->increments('id');
			$table->integer('service_id')->unsigned()->nullable();
			$table->integer('technician_id')->unsigned()->nullable();;
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

		Schema::table('person_service',function(Blueprint $table) {
			$table->foreign('service_id')->references('id')->on('services');
			$table->foreign('technician_id')->references('id')->on('people');
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
		Schema::drop('person_service');
	}

}
