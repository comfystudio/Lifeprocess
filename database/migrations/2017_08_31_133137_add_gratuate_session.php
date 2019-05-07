<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGratuateSession extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coach_gratuate_session', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('created_user_id');
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->enum('deleted', array('0', '1'))->default('0');
            $table->timestamps();
            $table->enum('status', ['available','booked','completed', 'cancelled'])->default('available')->nullable();
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
        Schema::dropIfExists('coach_gratuate_session');
    }
}
