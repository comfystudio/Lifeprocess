<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailTemplateForClientLeaveMessageToEmailTemplatesTable extends Migration
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
                ['template_name' => 'Client Leave Message to Coach', 'slug' => 'client-leave-message-to-coach', 'trigger' => 'When client leave a message to coach','tags'=>'[client-email],[coach-email],[coach-first-name],[client-name],[client-first-name]','to' => '[coach-email]','subject'=>'Alert – you have a new message from [client-name]','content'=>'Hi [coach-first-name], Just a quick note to let you know that [client-name] has left you a new message in the messaging area'],

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
            App\Models\EmailTemplate::where('slug', 'client-leave-message-to-coach')->delete();
            \Cache::flush();
        });
    }
}
