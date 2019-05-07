<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserNextModuleProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_next_module_progress', function (Blueprint $table) {
            $table->increments('id');
            $table->string('module_id', 255)->nullable();
            $table->datetime('start_datetime');
            $table->datetime('end_datetime');
            $table->datetime('billing_cycle');
            $table->string('user_id');
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
        Schema::dropIfExists('user_next_module_progress');
    }
}
