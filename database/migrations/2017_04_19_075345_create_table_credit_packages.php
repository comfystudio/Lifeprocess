<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCreditPackages extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('credit_packages', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('credit');
			$table->float('price', 5, 2);
			$table->enum('status', ['draft', 'public'])->default('draft');
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
		Schema::dropIfExists('credit_packages');
	}
}
