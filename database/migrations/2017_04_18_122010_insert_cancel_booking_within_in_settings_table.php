<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertCancelBookingWithinInSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            // Insert row for cancel_booking_within to determine that the client can cancel his/her booking within set value...
            App\Models\Setting::create(['name' => 'cancel_booking_within', 'title' => 'Cancel Booking Within (Hour)', 'value' => '24']);
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
            App\Models\Setting::where('name', 'cancel_booking_within')->delete();
        });
    }
}
