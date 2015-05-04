<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('equipments', function (Blueprint $table ) {
			$table->increments('id');
			$table->string('name');
			$table->string('cc_number');
			$table->string('serial_number');
			$table->integer('equipment_type_id');
			$table->integer('customer_id');
			$table->string('notes');
			$table->date('warranty_expiration');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('equipments');
	}

}
