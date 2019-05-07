<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModulesExercisesQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules_exercises_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('module_id')->unsigned();
            $table->integer('module_exercise_id')->unsigned();
            $table->integer('parent_question_id')->unsigned()->nullable();
            $table->text('question_title')->nullable();
            $table->integer('question_no')->nullable();
            $table->text('helpblock')->nullable();
            $table->string('answer_format')->nullable();
            $table->enum('deleted',array('0','1'))->default('0');
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
        Schema::dropIfExists('modules_exercises_questions');
    }
}
