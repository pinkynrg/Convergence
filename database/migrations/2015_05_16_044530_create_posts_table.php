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
			$table->string('post');
			$table->integer('author_id')->unsigned();
			$table->string('is_public');
			$table->timestamps();
		});

		Schema::table('posts',function(Blueprint $table) {
			$table->foreign('ticket_id')->references('id')->on('tickets');
			$table->foreign('author_id')->references('id')->on('people');
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
