<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStripeCustomerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('stripe_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id');
            $table->string("stripe_customer_id");
            $table->string("object")->default("customer");
            $table->double("account_balance",8,2)->default(0);
            $table->string("currency")->default("cad");
            $table->string("default_source");
            $table->string("description");
            $table->string("discount");
            $table->string("email");
            $table->string("livemode")->default('false');
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
        Schema::dropIfExists('stripe_customers');
    }
}
