<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertMaintenanceModeFieldsInSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            App\Models\Setting::create(['name' => 'maintenance_mode', 'title' => 'Toggle Maintenance Mode', 'value' => 'off']);
            App\Models\Setting::create(['name' => 'maintenance_mode_message', 'title' => 'Maintenance Mode Message', 'value' => 'Sorry, the site is offline at the moment while we carry out essential maintenance. Please check back soon.']);
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
            App\Models\Setting::where('name', 'maintenance_mode')->delete();
            App\Models\Setting::where('name', 'maintenance_mode_message')->delete();
        });
    }
}
