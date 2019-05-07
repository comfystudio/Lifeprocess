<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaqsTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('faqs', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('role_id');
			$table->string('question', 255);
			$table->longText('answer');
			$table->enum('deleted', ['0', '1'])->default('0');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('faqs');
	}
}
