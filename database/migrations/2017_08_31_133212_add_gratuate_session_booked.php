<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGratuateSessionBooked extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coach_gratuate_session_booked', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('coach_schedules_id');
            $table->integer('booked_user_id');
            $table->enum('deleted', array('0', '1'))->default('0');
            $table->string('meeting_type')->nullable();
            $table->enum('session_status', ['completed', 'cancelled'])->nullable();
            $table->enum('reminder_sent', ['0', '1'])->default('0');
            $table->integer('cancelled_by_user_id')->comment('users.id')->default(0);
            $table->text('cancel_reson')->nullable();
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
        Schema::dropIfExists('coach_gratuate_session_booked');
    }
}
