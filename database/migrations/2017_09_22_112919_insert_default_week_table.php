<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDefaultWeekTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('default_week', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('created_user_id');
            $table->string('day',255);
            $table->string('start_time',255);
            $table->string('end_time',255);
            $table->enum('deleted', array('0', '1'))->default('0');
            $table->engine = 'InnoDB';
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
        Schema::dropIfExists('default_week');
    }
}
