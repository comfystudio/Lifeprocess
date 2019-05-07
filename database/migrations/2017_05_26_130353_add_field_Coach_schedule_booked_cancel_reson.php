<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldCoachScheduleBookedCancelReson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('coach_schedules_booked', function (Blueprint $table) {
            $table->text('cancel_reson')->after('reminder_sent')->nullable();
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
            $table->dropColumn(['cancel_reson']);
        });
    }
}
