<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInCoachFreeSession extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coach_free_session', function (Blueprint $table) {
            $table->enum('status', ['available','booked','completed', 'cancelled'])->default('available')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coach_free_session', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
