<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeStatusFieldInModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('modules', function (Blueprint $table) {
            // $table->string('status')->nullable()->change();
            DB::statement('ALTER TABLE modules MODIFY status varchar(50)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('modules', function (Blueprint $table) {
            // $table->enum('status', array('pending', 'in_progress', 'completed', 'submited'))->default('pending')->change();
            DB::statement("ALTER TABLE `modules` CHANGE `status` `status` ENUM('pending', 'in_progress', 'completed', 'submited')");
        });
    }
}
