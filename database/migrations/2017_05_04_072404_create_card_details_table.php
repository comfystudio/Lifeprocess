<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('card_type', 50)->nullable();
            $table->string('card_number', 50);
            $table->string('expiry_date',10);
            $table->string('CVV_number', 10);
            $table->string('card_issue_date', 10)->comment('card start date if Maestro card')->nullable();
            $table->tinyInteger('issue_number')->comment('Issue number of Maestro card.')->nullable();
            $table->enum('deleted', ['0', '1'])->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_details');
    }
}
