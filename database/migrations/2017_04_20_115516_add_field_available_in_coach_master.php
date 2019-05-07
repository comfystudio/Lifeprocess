<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldAvailableInCoachMaster extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('coaches', function (Blueprint $table) {
			$table->enum('available', ['yes', 'no'])->default('yes');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('coaches', function (Blueprint $table) {
			$table->dropColumn(['available']);
		});
	}
}
