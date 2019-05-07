<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InsertReviewedWithinLastDaysRowInSettingTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('settings', function (Blueprint $table) {
			App\Models\Setting::create(['name' => 'reviewed_within_last_days', 'title' => 'Review Within Last Days', 'value' => '7']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('settings', function (Blueprint $table) {
			App\Models\Setting::where('name', 'reviewed_within_last_days')->delete();
		});
	}
}
