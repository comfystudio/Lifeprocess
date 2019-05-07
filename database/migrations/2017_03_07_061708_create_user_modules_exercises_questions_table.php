<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserModulesExercisesQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_modules_exercises_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable()->comment('uses.id');
            $table->integer('program_id')->unsigned()->nullable()->comment('programs.id');
            $table->integer('module_id')->unsigned()->nullable()->comment('modules.id');
            $table->integer('module_exercise_id')->unsigned()->nullable()->comment('module_exercises.id');
            $table->integer('question_id')->unsigned()->nullable()->comment('modules_exercises_questions.id');
            $table->text('answer')->nullable();
            $table->text('coach_respond')->nullable();
            $table->dateTime('coach_respond_at')->nullable();
            $table->enum('deleted', ['0', '1'])->default('0');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_modules_exercises_questions');
    }
}
