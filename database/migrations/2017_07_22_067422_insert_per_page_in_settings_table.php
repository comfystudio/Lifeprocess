<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertPerPageInSettingsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('settings', function (Blueprint $table) {
			App\Models\Setting::create(['name' => 'per_page', 'title' => 'Per Page', 'value' => '10']);
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
			App\Models\Setting::where('name', 'per_page')->delete();
			\Cache::flush();
		});
	}
}
