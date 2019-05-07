<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertContactUsEmailInSettingsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('settings', function (Blueprint $table) {
			App\Models\Setting::create(['name' => 'contact_us_email', 'title' => 'Contact us Email', 'value' => 'dinesh@sphererays.net']);
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
			App\Models\Setting::where('name', 'contact_us_email')->delete();
			\Cache::flush();
		});
	}
}
