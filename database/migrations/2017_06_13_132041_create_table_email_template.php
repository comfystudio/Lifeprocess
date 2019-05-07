<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableEmailTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('email_templates')) {
             Schema::create('email_templates', function (Blueprint $table) {
                $table->increments('id');
                $table->string('template_name',255);
                $table->string('slug', 255);
                $table->string('trigger', 255);
                $table->text('tags', 255)->nullable();
                $table->string('to', 255);
                $table->string('subject', 255);
                $table->text('content');
                $table->timestamps();
            });            //
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_templates');
    }
}
