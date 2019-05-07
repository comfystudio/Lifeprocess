<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableOthercoachFeedbackList extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('other_coach_feedback_lists', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('coach_id')->unsigned();
			$table->integer('proxy_coach_id')->unsigned();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('other_coach_feedback_lists');
	}
}
