<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTagTicketTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tag_ticket', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('ticket_id')->unsigned();
			$table->integer('tag_id')->unsigned();
			$table->timestamps();
		});

		Schema::table('tag_ticket',function(Blueprint $table) {
			$table->foreign('ticket_id')->references('id')->on('tickets');
			$table->foreign('tag_id')->references('id')->on('tags');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tag_ticket');
	}

}
