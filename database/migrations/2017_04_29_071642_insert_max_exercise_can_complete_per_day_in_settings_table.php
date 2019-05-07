<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertMaxExerciseCanCompletePerDayInSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            App\Models\Setting::create(['name' => 'max_exercise_can_complete_per_day', 'title' => 'Max Exercise Can Complete Per Day', 'value' => '3']);
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
            App\Models\Setting::where('name', 'max_exercise_can_complete_per_day')->delete();
        });
    }
}
