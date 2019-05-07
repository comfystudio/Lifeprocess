<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientScheduledSessionProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_scheduled_session_problems', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_session_id')->unsigned()->comment('coach_schedules_booked.id');
            $table->string('problem', 255);
            $table->text('other')->nullable();
            $table->dateTime('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_scheduled_session_problems');
    }
}
