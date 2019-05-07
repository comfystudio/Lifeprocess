<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransactionTypeFieldInCoachTransactionHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coach_transaction_histories', function (Blueprint $table) {
            $table->string('transaction_status', 50)->after('transaction_amount')->nullable();
            $table->text('transaction_response')->after('transaction_detail')->nullable();
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
            $table->dropColumn(['transaction_status', 'transaction_response']);
        });
    }
}
