<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRowInEmailTemplateTable extends Migration
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
                ['template_name' => 'Coach feedback has not been read/opened', 'slug' => 'coach-feedback-has-not-been-read-7-days', 'trigger' => 'Coach feedback has not been read/opened feedback provide 7 days','tags'=>'[client-email],[first-name],[coach-name],[module-no],[module-title]','to' => '[client-email]','subject'=>'[coach-name] is waiting for you to read your feedback','content'=>'<h2>Dear {{$first_name}} </h2>
                    <p>You have not view or download the feedback of the module [module-no][module-title] you submitted till the date. </p>
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
            App\Models\EmailTemplate::where('slug', 'user-completes-module')->delete();
            \Cache::flush();
        });
    }
}
