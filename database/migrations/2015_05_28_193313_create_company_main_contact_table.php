<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyMainContactTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_main_contact', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->unsigned()->unique();
			$table->integer('main_contact_id')->unsigned();
			$table->timestamps();
		});


		Schema::table('company_main_contact',function(Blueprint $table) {
			$table->foreign('company_id')->references('id')->on('companies');
			$table->foreign('main_contact_id')->references('id')->on('people');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('company_main_contact');
	}

}
