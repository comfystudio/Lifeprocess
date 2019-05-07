<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourceLibrary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_library', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255);
            $table->enum('status',array('draft','published'))->default('published');
            $table->longText('description')->nullable();
            $table->string('files', 255)->nullable();
            $table->enum('deleted',array('0','1'))->default('0');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resource_library');
    }
}
