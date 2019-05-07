<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddViewFeedbackAtFieldInUserModuleProgresesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_module_progresses', function (Blueprint $table) {
            $table->dateTime('view_feedback_at')->after('reviewed_user_id')->nullable();
            $table->enum('is_view_feedback_email_sent', ['0', '1'])->default('0')->after('view_feedback_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_module_progresses', function (Blueprint $table) {
            $table->dropColumn(['view_feedback_at', 'is_view_feedback_email_sent']);
        });
    }
}
