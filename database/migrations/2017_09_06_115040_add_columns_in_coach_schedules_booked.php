<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInCoachSchedulesBooked extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coach_schedules_booked', function (Blueprint $table) {
            $table->enum('booked_for', ['f','g','s'])->default('s')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coach_schedules_booked', function (Blueprint $table) {
            $table->dropColumn('booked_for');
        });
    }
}
