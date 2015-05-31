<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompanyAccountManagerTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('company_account_manager', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('company_id')->unsigned()->unique();
			$table->integer('account_manager_id')->unsigned();
			$table->timestamps();
		});


		Schema::table('company_account_manager',function(Blueprint $table) {
			$table->foreign('company_id')->references('id')->on('companies');
			$table->foreign('account_manager_id')->references('id')->on('people');
			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('company_account_manager');
	}

}
