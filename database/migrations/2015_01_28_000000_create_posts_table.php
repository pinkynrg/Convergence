<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('posts',function(Blueprint $table) {
			$table->increments('id');
			$table->integer('ticket_id')->unsigned();
			$table->text('post');
			$table->text('post_plain_text');
			$table->integer('author_id')->unsigned();
			$table->integer('status_id')->unsigned();
			$table->integer('ticket_status_id')->unsigned();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->softDeletes();
		});

		Schema::table('posts',function(Blueprint $table) {
			$table->foreign('ticket_id')->references('id')->on('tickets');
			$table->foreign('author_id')->references('id')->on('company_person');
			$table->foreign('status_id')->references('id')->on('post_statuses');			
			$table->foreign('ticket_status_id')->references('id')->on('statuses');			
		});	
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('posts');
	}

}
