<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGratuateModuleExercize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gratuate_module_exercises_progresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable()->comment('uses.id');
            $table->integer('program_id')->unsigned()->nullable()->comment('programs.id');
            $table->integer('module_id')->unsigned()->nullable()->comment('modules.id');
            $table->integer('module_exercise_id')->unsigned()->nullable()->comment('module_exercises.id');
            $table->dateTime('completed_at')->nullable();
            $table->enum('deleted', ['0', '1'])->default('0');
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
        Schema::dropIfExists('gratuate_module_exercises_progresses');
    }
}
