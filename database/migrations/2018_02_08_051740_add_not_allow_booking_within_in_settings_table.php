<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNotAllowBookingWithinInSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            App\Models\Setting::create(['name' => 'allow_booking_hour', 'title' => 'bookig gap', 'value' => '0']);
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
            App\Models\Setting::where('name', 'allow_booking_hour')->delete();
            \Cache::flush();
        });
    }
}
