<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsInCoachTransactionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coach_transaction_histories', function (Blueprint $table) {
            $table->dateTime('next_billing_date')->after('transaction_status')->nullable();
            $table->dateTime('last_payment_date')->after('next_billing_date')->nullable();
            // last_payment_amount == transaction_amount
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
            $table->dropColumn(['next_billing_date', 'last_payment_date']);
        });
    }
}
