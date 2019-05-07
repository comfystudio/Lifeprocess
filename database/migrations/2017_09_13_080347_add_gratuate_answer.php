<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGratuateAnswer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_modules_exercises_questions', function (Blueprint $table) {
                $table->enum('is_gratuate_answer', ['y','n'])->default('n');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_modules_exercises_questions', function (Blueprint $table) {
                $table->dropColumn('is_gratuate_answer');
        });
    }
}
