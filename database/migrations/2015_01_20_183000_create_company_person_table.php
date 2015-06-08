<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyPersonTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_person', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('person_id')->unsigned();
			$table->integer('company_id')->unsigned();
			$table->integer('department_id')->unsigned()->nullable();
			$table->integer('title_id')->unsigned()->nullable();
			$table->string('phone')->nullable();
			$table->string('extension')->nullable();
			$table->string('cellphone')->nullable();
			$table->string('email')->nullable();
			$table->timestamps();
		});

		Schema::table('company_person',function(Blueprint $table) {
			$table->foreign('person_id')->references('id')->on('people');
			$table->foreign('company_id')->references('id')->on('companies');
			$table->foreign('department_id')->references('id')->on('departments');
			$table->foreign('title_id')->references('id')->on('titles');
			$table->unique( array('person_id','company_id') );
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('company_person');
	}

}
