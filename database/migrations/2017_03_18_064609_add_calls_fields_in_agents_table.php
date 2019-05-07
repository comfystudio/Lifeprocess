<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCallsFieldsInAgentsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('agents', function (Blueprint $table) {
			$table->string('promotional_call', 255)->nullable();
			$table->string('one_hour_session', 255)->nullable();
			$table->string('free_20_min_session', 255)->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('agents', function (Blueprint $table) {
			$table->dropColumn(['promotional_call', 'one_hour_session', 'free_20_min_session']);
		});
	}
}
