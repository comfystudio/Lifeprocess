<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompletedCoachingSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('completed_coaching_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('booked_schedule_id')->unsigned();
            $table->string('contact_methods', 255);
            $table->string('contact_detail', 255);
            $table->text('remarks')->nullable();
            $table->dateTime('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('completed_coaching_sessions');
    }
}
