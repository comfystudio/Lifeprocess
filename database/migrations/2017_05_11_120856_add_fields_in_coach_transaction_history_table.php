<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsInCoachTransactionHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coach_transaction_histories', function (Blueprint $table) {
            $table->integer('object_id')->after('user_id')->default('0');
            $table->string('object_type', 50)->after('object_id');
            $table->string('paypal_token', 255)->after('object_type')->nullable();
            $table->string('paypal_payerId', 255)->after('paypal_token')->nullable();
            $table->string('paypal_profile_id', 255)->after('paypal_payerId')->nullable();
            $table->string('paypal_profile_status', 255)->after('paypal_profile_id')->nullable();  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coach_transaction_histories', function (Blueprint $table) {
            $table->dropColumn(['object_id', 'object_type', 'paypal_token', 'paypal_payerId', 'paypal_profile_id', 'paypal_profile_status']);
        });
    }
}
