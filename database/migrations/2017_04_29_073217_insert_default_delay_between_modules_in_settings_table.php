<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertDefaultDelayBetweenModulesInSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            App\Models\Setting::create(['name' => 'default_delay_between_modules', 'title' => 'Default Delay Between Modules (Days)', 'value' => '0']);
            \Cache::flush();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settings', function (Blueprint $table) {
            App\Models\Setting::where('name', 'default_delay_between_modules')->delete();
            \Cache::flush();
        });
    }
}
