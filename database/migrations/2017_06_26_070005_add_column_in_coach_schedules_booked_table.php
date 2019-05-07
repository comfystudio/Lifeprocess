<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInCoachSchedulesBookedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('coach_schedules_booked', function (Blueprint $table) {
            $table->string('meeting_type')->after('booked_user_id')->nullable();
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
            $table->dropColumn(['meeting_type']);
        });
    }
}
