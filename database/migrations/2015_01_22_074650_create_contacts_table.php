<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('contacts',function(Blueprint $table) {
			$table->increments('id');
			$table->integer('customer_id')->unsigned();
			$table->string('name')->nullable();
			$table->string('phone')->nullable();
			$table->string('cellphone')->nullable();
			$table->string('email')->nullable();
			$table->timestamps();
		});

		Schema::table('contacts',function(Blueprint $table) {
			$table->foreign('customer_id')->references('id')->on('customers');
		});	
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('contacts');
	}

}
