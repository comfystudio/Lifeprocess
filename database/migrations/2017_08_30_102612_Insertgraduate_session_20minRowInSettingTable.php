<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertgraduateSession20minRowInSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up() {
        Schema::table('settings', function (Blueprint $table) {
            App\Models\Setting::create(['name' => 'graduate_session_20min', 'title' => 'Graduate Session (20 min)', 'value' => '10']);
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
            App\Models\Setting::where('graduate_session_20min', 'Graduate Session (20 min)')->delete();
            \Cache::flush();
        });
    }
}
