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
			$table->integer('profile_id')->unsigned();
			$table->integer('event_id')->unsigned();
			$table->integer('fallback_company_person_id')->unsigned();
			$table->integer('delay_time')->unsigned();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->softDeletes();
		});

		Schema::table('escalation_profile_event',function(Blueprint $table) {
			$table->foreign('profile_id')->references('id')->on('escalation_profiles');
			$table->foreign('event_id')->references('id')->on('escalation_events');
			$table->foreign('fallback_company_person_id')->references('id')->on('company_person');
			
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
