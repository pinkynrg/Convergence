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
			$table->string('company_name');
			$table->string('address');		
			$table->string('country');
			$table->string('city');
			$table->string('state');
			$table->string('zip_code');
			$table->string('group_email');
			$table->string('airport');
			$table->text('plant_requirment');
			$table->integer('account_manager_id')->nullable();
			$table->integer('main_contact_id')->nullable();			
			$table->timestamps();
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
