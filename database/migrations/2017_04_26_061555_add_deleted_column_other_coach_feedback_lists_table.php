<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedColumnOtherCoachFeedbackListsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('other_coach_feedback_lists', function (Blueprint $table) {
			$table->enum('deleted', array('0', '1'))->default('0');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('other_coach_feedback_lists', function (Blueprint $table) {
			$table->dropColumn('deleted');
		});
	}
}
