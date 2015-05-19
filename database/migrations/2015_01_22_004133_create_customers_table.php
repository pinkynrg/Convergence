<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('customers', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('company_name')->nullable();
			$table->string('address')->nullable();
			$table->string('country')->nullable();
			$table->string('city')->nullable();
			$table->string('state')->nullable();
			$table->string('zip_code')->nullable();
			$table->string('group_email')->nullable();
			$table->string('airport')->nullable();
			$table->text('plant_requirment')->nullable();
			$table->integer('account_manager_id')->unsigned()->nullable();
			$table->integer('main_contact_id')->nullable();
			$table->timestamps();
		});

		Schema::table('customers',function(Blueprint $table) {
			$table->foreign('account_manager_id')->references('id')->on('employees');
			// $table->foreign('main_contact_id')->references('id')->on('contacts');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('customers');
	}

}
