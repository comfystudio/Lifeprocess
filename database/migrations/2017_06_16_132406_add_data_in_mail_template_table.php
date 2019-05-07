<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDataInMailTemplateTable extends Migration
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
                ['template_name' => 'User Completes Module', 'slug' => 'user-completes-module', 'trigger' => 'User Completes Module','tags'=>'[client-email],[first-name],[module-no],[module-name],[module-number]','to' => '[client-email]','subject'=>'Congratulation [first-name] - You have completed Module [module-number]!','content'=>'<h2>Dear {{$first_name}} </h2>
                    <p>You are submitted Your Module {{$module->module_no}} {{$module->module_title}} successfully 
                    </p><p>Thank you<br>The Life Process Team</p>'],
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
