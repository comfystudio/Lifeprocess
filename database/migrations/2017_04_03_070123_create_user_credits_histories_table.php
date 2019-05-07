<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCreditsHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_credits_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('users.id');
            $table->integer('object_id')->unsigned()->comment('used or credit modules table id')->nullable();
            $table->string('object_type', 50)->comment('table name')->nullable();
            $table->enum('transaction_type', ['plus', 'minus']);
            $table->integer('credit_score');
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
        Schema::dropIfExists('user_credits_histories');
    }
}
