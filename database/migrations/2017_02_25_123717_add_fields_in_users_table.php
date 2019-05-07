<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('role_id')->unsigned()->after('id');
            $table->string('username')->after('email')->nullable();
            $table->string('timezone')->after('state_id')->nullable();
            $table->string('skype_id')->after('timezone')->nullable();
            $table->enum('terms_and_condition', array('yes', 'no'))->after('skype_id')->default('no');
            $table->enum('status', array('active', 'in_active'))->after('terms_and_condition');
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
            $table->dropColumn(['role_id', 'username', 'timezone', 'skype_id', 'terms_and_condition', 'status']);
        });
    }
}
