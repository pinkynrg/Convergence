<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompaniesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('companies', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
			$table->string('address')->nullable();
			$table->string('country')->nullable();
			$table->string('city')->nullable();
			$table->string('state')->nullable();
			$table->string('zip_code')->nullable();
			$table->string('group_email')->nullable();
			$table->integer('connection_type_id')->unsigned()->nullable();
			$table->integer('support_type_id')->unsigned()->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		});


		Schema::table('companies',function(Blueprint $table) {
			$table->foreign('connection_type_id')->references('id')->on('connection_types');
			$table->foreign('support_type_id')->references('id')->on('support_types');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('companies');
	}

}
