<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;


class Useraddedbyadmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscribe:addedbyadmin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'client added by admin for a one month trial';
    //protected $dateFormat = 'Y-m-d';
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
        $users = User::where('addedby', 'admin')->get();

        if(isset($users) && !empty($users))
        {
            foreach($users as $user)
            {
                $usercreatedate= Carbon::createFromFormat('Y-m-d H:i:s',$user->created_at)->format('Y-m-d');
                // $trialexpires = Carbon::createFromFormat('Y-m-d H:i:s',$user->created_at)->addDays(30)->format('Y-m-d');
                $trialexpires=$user->nextpaymentdate;
                $curr_date = Carbon::now()->format('Y/m/d');
                if($trialexpires<=$curr_date)
                {
                    User::where('id',$user->id)->update(array('addedby'=>'self','subscription_plan_status'=>''));
                }
            }
        }
    }
}
