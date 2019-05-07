<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMinMaxColumnForRangeToModulesExercisesQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modules_exercises_questions', function (Blueprint $table) {
            $table->integer('min_range_value');
            $table->integer('max_range_value');
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
            $table->dropColumn('min_range_value');
            $table->dropColumn('max_range_value');
        });
    }
}
