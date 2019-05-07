<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Mail;
use DB;
use App\Models\User;
use App\Models\Coach;
use App\Models\CoachTransactionHistory;
use App\Models\UserNextModuleProgress;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:ToCheckMailWorks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is the test command to check that Mail or scheduling is working on live environment or not.';

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
        $user=User::where('user_type','client')->where('status','active')->where('deleted','0')->get();
       // dump($user);
        foreach($user as $user)
        {
            $coach_schedule=CoachTransactionHistory::where('user_id',$user->id)->orWhere('transaction_status','success')->orWhere('transaction_status','active')->where('object_type','clients')->get();
            //dump($coach_schedule);
            foreach($coach_schedule as $c)
            {

                UserNextModuleProgress::where('user_id',$c->user_id)->update(['billing_cycle' => $c->next_billing_date]);
            }
        }

    }
}
