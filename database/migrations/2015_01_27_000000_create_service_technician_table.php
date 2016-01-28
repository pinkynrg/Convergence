<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceTechnicianTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('service_technician',function(Blueprint $table) {
			$table->increments('id');
			$table->integer('service_id')->unsigned()->nullable();
			$table->integer('technician_id')->unsigned()->nullable();
			$table->integer('division_id')->unsigned()->nullable();;
			$table->text('work_description');
			$table->datetime('internal_start')->nullable();
			$table->datetime('internal_end')->nullable();
			$table->integer('internal_estimated_hours')->nullable();
			$table->datetime('onsite_start')->nullable();
			$table->datetime('onsite_end')->nullable();
			$table->integer('onsite_estimated_hours')->nullable();
			$table->datetime('remote_start')->nullable();
			$table->datetime('remote_end')->nullable();
			$table->integer('remote_estimated_hours')->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->softDeletes();
		});

		Schema::table('service_technician',function(Blueprint $table) {
			$table->foreign('service_id')->references('id')->on('services');
			$table->foreign('technician_id')->references('id')->on('company_person');
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
		Schema::drop('service_technician');
	}

}
