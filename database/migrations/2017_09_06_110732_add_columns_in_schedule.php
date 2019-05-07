<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coach_schedules', function (Blueprint $table) {
            $table->integer('slot1');
            $table->integer('slot2');
            $table->integer('slot3');
            $table->enum('booked_for', ['f','g','s'])->default('s')->nullable();
            $table->datetime('slot_start_time');
            $table->datetime('slot_end_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coach_schedules', function (Blueprint $table) {
            $table->dropColumn('slot1');
            $table->dropColumn('slot2');
            $table->dropColumn('slot3');
            $table->dropColumn('booked_for');
            $table->dropColumn('slot_start_time');
            $table->dropColumn('slot_end_time');
        });
    }
}
