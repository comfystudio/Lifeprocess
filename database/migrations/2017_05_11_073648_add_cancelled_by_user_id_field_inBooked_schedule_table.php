<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCancelledByUserIdFieldInBookedScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coach_schedules_booked', function (Blueprint $table) {
            $table->integer('cancelled_by_user_id')->comment('users.id')->after('session_status')->default(0);
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
            $table->dropColumn(['cancelled_by_user_id']);
        });
    }
}
