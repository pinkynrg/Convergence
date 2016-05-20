<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('files',function (Blueprint $table) 
		{
			$table->increments('id');
			$table->text('name');
			$table->text('file_path');
			$table->text('file_name');
			$table->text('file_extension');
			$table->text('resource_type');
			$table->integer('resource_id')->unsigned()->nullable();
			$table->integer('uploader_id')->unsigned();
			$table->integer('thumbnail_id')->unsigned()->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->softDeletes();
		});

		Schema::table('files',function(Blueprint $table) {
			$table->foreign('thumbnail_id')->references('id')->on('files');
			$table->foreign('uploader_id')->references('id')->on('company_person');
		});

		Schema::table('people',function(Blueprint $table) {
			$table->foreign('profile_picture_id')->references('id')->on('files');
		});

		Schema::table('companies',function(Blueprint $table) {
			$table->foreign('profile_picture_id')->references('id')->on('files');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('files');
	}

}
