<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCoachSchedulesBooked extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('coach_schedules_booked', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('coach_schedules_id');
			$table->integer('booked_user_id');
			$table->enum('deleted', array('0', '1'))->default('0');
			$table->timestamps();
			$table->engine = 'InnoDB';
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {

		Schema::dropIfExists('coach_schedules_booked');

	}
}
