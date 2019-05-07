<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumsInCoachFreeSessionBookedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coach_free_session_booked', function (Blueprint $table) {
            $table->string('meeting_type')->after('booked_user_id')->nullable();
            $table->enum('session_status', ['completed', 'cancelled'])->after('booked_user_id')->nullable();
            $table->enum('reminder_sent', ['0', '1'])->after('session_status')->default('0');
            $table->integer('cancelled_by_user_id')->comment('users.id')->after('session_status')->default(0);
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
        Schema::table('coach_free_session_booked', function (Blueprint $table) {
            $table->dropColumn(['meeting_type']);
            $table->dropColumn('session_status');
            $table->dropColumn(['reminder_sent']);
            $table->dropColumn(['cancelled_by_user_id']);
            $table->dropColumn(['cancel_reson']);
        });
    }
}
