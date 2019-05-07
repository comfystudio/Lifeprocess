<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableReferFriend extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('refer_friends', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('create_user_id');
			$table->enum('use_your_name', ['yes', 'no'])->default('yes');
			$table->string('name', 255);
			$table->string('email', 255);
			$table->string('friends_email', 255);
			$table->longText('message');
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
		Schema::dropIfExists('refer_friends');
	}
}
