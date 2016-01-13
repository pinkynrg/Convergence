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
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		});


		Schema::table('company_account_manager',function(Blueprint $table) {
			$table->foreign('company_id')->references('id')->on('companies');
			$table->foreign('account_manager_id')->references('id')->on('company_person');
			
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
