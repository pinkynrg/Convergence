<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEscalationProfileEventTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('escalation_profile_event',function (Blueprint $table) 
		{
			$table->increments('id');
			$table->integer('level_id')->unsigned();
			$table->integer('profile_id')->unsigned();
			$table->string('event_id');
			$table->integer('priority_id')->unsigned();
			$table->integer('delay_time')->unsigned();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->softDeletes();
		});

		Schema::table('escalation_profile_event',function(Blueprint $table) {
			$table->foreign('level_id')->references('id')->on('levels');
			$table->foreign('profile_id')->references('id')->on('escalation_profiles');
			$table->foreign('priority_id')->references('id')->on('priorities');
			$table->unique( array('profile_id','level_id','priority_id') );
			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('escalation_profile_event');
	}

}
