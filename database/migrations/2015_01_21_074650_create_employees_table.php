<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('employees', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->integer('department_id')->unsigned()->nullable();
			$table->integer('title_id')->unsigned()->nullable();
			$table->string('phone')->nullable();
			$table->string('extension')->nullable();
			$table->string('speed_dial')->nullable();
			$table->string('email')->nullable();
			$table->timestamps();
		});

		Schema::table('employees',function(Blueprint $table) {
			$table->foreign('department_id')->references('id')->on('departments');
			$table->foreign('title_id')->references('id')->on('titles');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('employees');
	}

}
