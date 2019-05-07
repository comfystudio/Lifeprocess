<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertEmailForGratuateQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            $rows = [
                ['template_name' => 'Gratuate User Ask Stanton a question ', 'slug' => 'gratuate-user-ask-question', 'trigger' => 'Gratuate User Ask Stanton a question','tags'=>'[client-email],[program-name],[question],[admin-email]','to' => '[admin-email]','subject'=>'Gratuate User question','content'=>'<h2>Dear Admin, </h2>
                    <p>[client-name] has completed Life Process [program-name] successfully.</p>
                    <p>He/She asked below question:</p>
                    <p>[question]</p>
                    <p>Client Email : [client-email]</p>
                    <p>Thank you<br>The Life Process Team</p>'],
                ];
            App\Models\EmailTemplate::insert($rows);
            \Cache::flush();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_templates', function (Blueprint $table) {
            App\Models\EmailTemplate::where('slug', 'gratuate-user-ask-question')->delete();
            \Cache::flush();
        });
    }
}
