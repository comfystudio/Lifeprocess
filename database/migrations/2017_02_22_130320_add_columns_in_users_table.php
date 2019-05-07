<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('country_id')->unsigned();
            $table->integer('state_id')->unsigned();
            $table->string('first_name',100)->nullable();
            $table->string('last_name',100)->nullable();
            $table->string('middle_name',100)->nullable();
            $table->string('mobile_no')->nullable();
            $table->string('address_line_one',250)->nullable();
            $table->string('address_line_two',250)->nullable();
            $table->string('address_line_three',250)->nullable();
            $table->string('image')->nullable();
            $table->char('deleted', 1)->default('0');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['country_id', 'state_id', 'first_name', 'last_name', 'middle_name', 'mobile_no', 'address_line_one', 'address_line_two', 'address_line_three', 'image', 'deleted']);
        });
    }
}
