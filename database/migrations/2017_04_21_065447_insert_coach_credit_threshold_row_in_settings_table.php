<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertCoachCreditThresholdRowInSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            App\Models\Setting::create(['name' => 'coach_credit_threshold', 'title' => 'Coach credit threshold', 'value' => '250']);
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
            App\Models\Setting::where('name', 'coach_credit_threshold')->delete();
        });
    }
}
