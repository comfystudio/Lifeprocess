<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserModuleProgressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_module_progresses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable()->comment('uses.id');
            $table->integer('program_id')->unsigned()->nullable()->comment('programs.id');
            $table->integer('module_id')->unsigned()->nullable()->comment('modules.id');
            $table->enum('watch_video', ['yes', 'no'])->nullable();
            $table->enum('read_material', ['yes', 'no'])->nullable();
            $table->dateTime('completed_at')->nullable();
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
        Schema::dropIfExists('user_module_progresses');
    }
}
