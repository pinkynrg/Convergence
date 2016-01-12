<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('hotels', function (Blueprint $table ) {
			$table->increments('id');
			$table->string('name');
			$table->string('address');
			$table->float('rating')->nullable();
			$table->float('distance')->nullable();
			$table->float('walking_time')->nullable();
			$table->float('driving_time')->nullable();
			$table->integer('company_id')->unsigned();
			$table->timestamps();
		});

		Schema::table('hotels',function(Blueprint $table) {
			$table->foreign('company_id')->references('id')->on('companies');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('hotels');
	}

}
