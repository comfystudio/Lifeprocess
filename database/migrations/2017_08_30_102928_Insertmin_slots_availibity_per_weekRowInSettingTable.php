<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertminSlotsAvailibityPerWeekRowInSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
 public function up() {
        Schema::table('settings', function (Blueprint $table) {
            App\Models\Setting::create(['name' => 'min_slots_availibility_per_week', 'title' => 'Min Slots Availibility per week', 'value' => '10']);
            \Cache::flush();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
 public function down() {
        Schema::table('settings', function (Blueprint $table) {
            App\Models\Setting::where('min_slots_availibility_per_week', 'Min Slots Availibility per week')->delete();
            \Cache::flush();
        });
    }
}
