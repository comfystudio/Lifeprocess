<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnFirstModuleSubmitedMail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_module_progresses', function (Blueprint $table) {
            $table->enum('is_first_module_email_send', ['0', '1'])->default('0');
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
            $table->dropColumn(['is_first_module_email_send']);
        });

    }
}
