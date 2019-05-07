<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('agents', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('paypal_id')->nullable();
			$table->string('city', 50)->nullable();
			$table->string('zip_code', 50)->nullable();
			$table->mediumText('biography')->nullable();
			$table->mediumText('qualifications')->nullable();
			$table->mediumText('experience')->nullable();
			$table->string('hourly_rate', 255)->nullable();
			$table->float('program_fee', 8, 2)->nullable();
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
		Schema::dropIfExists('agents');
	}
}
