<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumsInGratuateUnloackModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gratuate_module_progresses', function (Blueprint $table) {
            $table->string('status', 255)->nullable();
            $table->dateTime('reviewed_at')->nullable();
            $table->integer('reviewed_user_id')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gratuate_module_progresses', function (Blueprint $table) {
            $table->dropColumn(['status', 'reviewed_at', 'reviewed_user_id']);
        });
    }
}
