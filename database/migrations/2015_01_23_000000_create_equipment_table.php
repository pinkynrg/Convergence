<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEquipmentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('equipment', function (Blueprint $table ) {
			$table->increments('id');
			$table->string('name')->nullable();
			$table->string('cc_number')->nullable();
			$table->string('serial_number')->nullable();
			$table->integer('equipment_type_id')->unsigned();
			$table->integer('company_id')->unsigned();
			$table->string('notes')->nullable();
			$table->date('warranty_expiration')->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->softDeletes();
		});

		Schema::table('equipment',function(Blueprint $table) {
			$table->foreign('equipment_type_id')->references('id')->on('equipment_types');
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
		Schema::drop('equipment');
	}

}
