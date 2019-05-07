<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGratuateSlotField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('is_gratuate_session_booked')->default(0);
            $table->integer('is_unloack_module')->default(0);
            $table->integer('is_booked_gratuate_session')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_gratuate_session_booked');
            $table->dropColumn('is_unloack_module');
            $table->dropColumn('is_booked_gratuate_session');
        });
    }
}
