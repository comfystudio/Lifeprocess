<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class RemoveTableAgentModuleRates extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::drop('agent_module_rates');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::create('agent_module_rates', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('agent_id')->unsigned()->nullable();
			$table->integer('program_id')->unsigned()->nullable();
			$table->integer('module_id')->unsigned()->nullable();
			$table->float('rate', 8, 2)->nullable();
			$table->enum('deleted', ['0', '1'])->default('0');
			$table->timestamps();
			$table->engine = 'InnoDB';
		});
	}
}
