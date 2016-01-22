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
			$table->text('post_plain_text');
			$table->integer('creator_id')->unsigned();
			$table->integer('assignee_id')->unsigned();
			$table->integer('status_id')->unsigned();
			$table->integer('priority_id')->unsigned();
			$table->integer('division_id')->unsigned();
			$table->integer('equipment_id')->unsigned();
			$table->integer('company_id')->unsigned();
			$table->integer('contact_id')->unsigned()->nullable();		
			$table->integer('job_type_id')->unsigned()->nullable();		
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->softDeletes();
		});

		Schema::table('tickets',function(Blueprint $table) {
			$table->foreign('creator_id')->references('id')->on('company_person');
			$table->foreign('assignee_id')->references('id')->on('company_person');
			$table->foreign('status_id')->references('id')->on('statuses');
			$table->foreign('priority_id')->references('id')->on('priorities');
			$table->foreign('division_id')->references('id')->on('divisions');
			$table->foreign('company_id')->references('id')->on('companies');
			$table->foreign('contact_id')->references('id')->on('company_person');
			$table->foreign('job_type_id')->references('id')->on('job_types');
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
