<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */

	public function up()
	{
		Schema::create('tickets', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title');
			$table->text('post');
			$table->integer('creator_id');
			$table->integer('assignee_id');
			$table->integer('status_id');
			$table->integer('priority_id');
			$table->integer('division_id');
			$table->integer('equipment_id');
			$table->integer('customer_id');
			$table->integer('contact_id');			
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
		Schema::drop('tickets');
	}

}
