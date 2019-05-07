<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoachFreeSessionBooked extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coach_free_session_booked', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('coach_schedules_id');
            $table->integer('booked_user_id');
            $table->enum('deleted', array('0', '1'))->default('0');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coach_free_session_booked');
    }
}
