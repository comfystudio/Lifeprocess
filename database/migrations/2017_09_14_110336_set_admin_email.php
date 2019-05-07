<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetAdminEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
             App\Models\Setting::create(['name' => 'admin_email', 'title' => 'Admin Email', 'value' => 'janki.rathod@sphererays.net']);
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
            App\Models\Setting::where('name', 'admin_email')->delete();
            \Cache::flush();
        });
    }
}
