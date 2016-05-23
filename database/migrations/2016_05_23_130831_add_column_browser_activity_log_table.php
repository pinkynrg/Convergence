<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnBrowserActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->text('browser')->after('ip_address')->nullable();
            $table->text('browser_version')->after('browser')->nullable();
            $table->text('os')->after('browser_version')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity_log', function($table) {
            $table->dropColumn('browser');
            $table->dropColumn('browser_version');
            $table->dropColumn('os');
        });
    }
}
