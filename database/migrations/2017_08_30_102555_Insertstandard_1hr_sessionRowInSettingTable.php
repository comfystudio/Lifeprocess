<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Insertstandard1hrSessionRowInSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up() {
        Schema::table('settings', function (Blueprint $table) {
            App\Models\Setting::create(['name' => 'standard_1hr_session', 'title' => 'Standard 1 hr Session', 'value' => '10']);
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
            App\Models\Setting::where('standard_1hr_session', 'Standard 1 hr Session')->delete();
            \Cache::flush();
        });
    }
}
