<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPopupOptionInUserModulesExercisesQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_modules_exercises_questions', function (Blueprint $table) {
             $table->string('popup_option')->default('yes');
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
             $table->dropColumn('popup_option');
        });
    }
}
