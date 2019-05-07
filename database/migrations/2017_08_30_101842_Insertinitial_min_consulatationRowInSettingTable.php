<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertinitialMinConsulatationRowInSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up() {
        Schema::table('settings', function (Blueprint $table) {
            App\Models\Setting::create(['name' => 'initial_min_consulatation', 'title' => 'Initial Consultation', 'value' => '10']);
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
            App\Models\Setting::where('initial_min_consulatation', 'Initial Consultation')->delete();
            \Cache::flush();
        });
    }
}
