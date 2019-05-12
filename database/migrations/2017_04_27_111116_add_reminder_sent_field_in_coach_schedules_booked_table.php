<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReminderSentFieldInCoachSchedulesBookedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coach_schedules_booked', function (Blueprint $table) {
            $table->enum('reminder_sent', ['0', '1'])->after('session_status')->default('0');
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
            $table->dropColumn(['reminder_sent']);
        });
    }
}