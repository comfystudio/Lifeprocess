<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusFieldInUserModuleProgressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_module_progresses', function (Blueprint $table) {
            $table->string('status', 255)->nullable()->after('completed_at');
            $table->dateTime('reviewed_at')->nullable()->after('status');
            $table->integer('reviewed_user_id')->unsigned()->default(0)->after('reviewed_at');
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
            $table->dropColumn(['status', 'reviewed_at', 'reviewed_user_id']);
        });
    }
}
