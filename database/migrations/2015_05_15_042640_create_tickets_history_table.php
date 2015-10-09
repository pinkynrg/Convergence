<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsHistoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */

	public function up()
	{
		Schema::create('tickets_history', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('ticket_id')->unsigned();
			$table->integer('changer_id')->unsigned()->nullable();
			$table->string('title');
			$table->text('post');
			$table->integer('creator_id')->unsigned();
			$table->integer('assignee_id')->unsigned();
			$table->integer('status_id')->unsigned();
			$table->integer('priority_id')->unsigned();
			$table->integer('division_id')->unsigned();
			$table->integer('equipment_id')->unsigned();
			$table->integer('company_id')->unsigned();
			$table->integer('contact_id')->unsigned()->nullable();		
			$table->integer('job_type_id')->unsigned()->nullable();		
			$table->timestamps();
		});

		Schema::table('tickets_history',function(Blueprint $table) {
			$table->foreign('ticket_id')->references('id')->on('tickets');
			$table->foreign('changer_id')->references('id')->on('company_person');
			$table->foreign('creator_id')->references('id')->on('company_person');
			$table->foreign('assignee_id')->references('id')->on('company_person');
			$table->foreign('status_id')->references('id')->on('statuses');
			$table->foreign('priority_id')->references('id')->on('priorities');
			$table->foreign('division_id')->references('id')->on('divisions');
			$table->foreign('company_id')->references('id')->on('companies');
			$table->foreign('contact_id')->references('id')->on('company_person');
			$table->foreign('job_type_id')->references('id')->on('job_types');
			$table->unique( array('ticket_id','created_at') );

		});		
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tickets_history');
	}

}
