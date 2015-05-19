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
			$table->string('name')->nullable();
			$table->string('cc_number')->nullable();
			$table->string('serial_number')->nullable();
			$table->integer('equipment_type_id')->unsigned();
			$table->integer('customer_id')->unsigned();
			$table->string('notes')->nullable();
			$table->date('warranty_expiration')->nullable();
		});

		Schema::table('equipments',function(Blueprint $table) {
			$table->foreign('equipment_type_id')->references('id')->on('equipment_types');
			$table->foreign('customer_id')->references('id')->on('customers');
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
