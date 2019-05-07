<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMinMaxValueFieldInModulesExercisesQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modules_exercises_questions', function (Blueprint $table) {
            $table->integer('min_value')->after('answer_format')->default('0')->nullable();
            $table->integer('max_value')->after('min_value')->default('0')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modules_exercises_questions', function (Blueprint $table) {
            $table->dropColumn(['min_value', 'max_value']);
        });
    }
}
