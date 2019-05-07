<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GratuateUserMonthlyActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:gratuate-user-monthly-activity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'update gratuate user data every month for gratuation benifits';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $find_user = User::where('gratuate_date','<=',Carbon::now()->subMonth()->format('Y-m-d H:i:s'))->where('deleted', '0')->where('is_gratuate', 'y')->get();
        foreach ($find_user as $value) {
            User::where('id',$value->id)->update(array('gratuate_date'=>Carbon::now()->format('Y-m-d H:i:s'),'gratuate_token'=>'','gratuate_option'=>null,'is_gratuate_session_booked'=>'','is_unloack_module'=>'','is_booked_gratuate_session'=>'','unlocked_module'=>'','is_gratuate_question_asked'=>'n'));
        }
    }
}
