<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('person_id')->unsigned()->nullable();
			$table->integer('active_contact_id')->unsigned()->nullable();
			$table->string('username')->unique();
			$table->string('password', 60);
			$table->rememberToken();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('last_login')->nullable();
            $table->softDeletes();
		});

		Schema::table('users',function(Blueprint $table) {
			$table->foreign('person_id')->references('id')->on('people');
			$table->foreign('active_contact_id')->references('id')->on('company_person');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
