<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meeting', function (Blueprint $table) {
            $table->increments('id');
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->integer('coach_id')->unsigned();
            $table->integer('client_id')->unsigned();
            $table->string('meeting_id',50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('meeting', function (Blueprint $table) {
            $table->dropColumn(['start_datetime', 'end_datetime', 'coach_id', 'client_id', 'meeting_id']);
        });
    }
}
