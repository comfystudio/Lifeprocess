<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGratuateExcersice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_module_exercises_progresses', function (Blueprint $table) {
            $table->enum('is_gratuate_excersize', ['y','n'])->default('n');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_module_exercises_progresses', function (Blueprint $table) {
            $table->dropColumn('is_gratuate_excersize');
        });
    }
}
