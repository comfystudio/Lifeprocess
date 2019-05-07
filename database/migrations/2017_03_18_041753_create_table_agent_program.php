<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAgentProgram extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('agent_program', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('agent_id')->unsigned();
			$table->integer('program_id')->unsigned();
			$table->enum('deleted', ['0', '1'])->default('0');
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
		Schema::dropIfExists('agent_program');
	}
}
