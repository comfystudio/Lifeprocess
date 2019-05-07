<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoachModuleRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coach_module_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('coach_id')->unsigned()->nullable();
            $table->integer('program_id')->unsigned()->nullable();
            $table->integer('module_id')->unsigned()->nullable();
            $table->float('rate', 8,2)->nullable();
            $table->enum('deleted', ['0','1'])->default('0');
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
        Schema::dropIfExists('coach_module_rates');
    }
}
