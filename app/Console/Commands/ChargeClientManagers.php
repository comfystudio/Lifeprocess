<?php

namespace App\Console\Commands;

use App\Models\Agent;
use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\EmailTemplate;
use Carbon\Carbon;
use Mail;
use App\Models\User;
use \Stripe\Charge;

class ChargeClientManagers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:charge-client-managers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Client Managers Charged At End Of The Month';

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
        $client_managers = Agent::where('deleted', '=', '0')->with('user')->get();
        $report = array();
        foreach($client_managers as $key => $manager){
            $report[$key] = array('pp_coach_normal' => 0, 'pp_llpcoach_normal' => 0, 'pp_coach_fast' => 0, 'pp_llpcoach_fast' => 0 ,'name' => $manager['user']['name']);
            $total = 0;
            $clients = Client::where('agent_id', '=', $manager['user_id'])->with('user')->with('coach')->get();
            foreach($clients as $client){
                if($client['module_restriction'] == 'Yes'){
                    //if coach is their own or one LLPs
                    $creator = User::where('id', '=', $client['coach']['user']['created_by'])->first();
                    if($creator['role_id'] == '5'){
                        $total += $manager['pp_coach_normal'];
                        $report[$key]['pp_coach_normal']++;
                    }else{
                        $total += $manager['pp_llpcoach_normal'];
                        $report[$key]['pp_llpcoach_normal']++;
                    }

                }else{
                    //if coach is their own or one LLPs
                    $creator = User::where('id', '=', $client['coach']['user']['created_by'])->first();
                    if($creator['role_id'] == '5'){
                        $total += $manager['pp_coach_fast'];
                        $report[$key]['pp_coach_fast']++;
                    }else{
                        $total += $manager['pp_llpcoach_fast'];
                        $report[$key]['pp_llpcoach_fast']++;
                    }
                }
            }
            $report[$key]['total'] = $total;
            if(isset($total) && $total != 0 && $manager['user']['stripe_id'] != null){
                //DO STRIPE STUFF CHARGE EACH CLIENT MANAGER BASED ON TOTAL
                $charge =  \Stripe\Charge::create(array(
                    'customer' => $manager['user']['stripe_id'],
                    'amount'   => ($total * 100),
                    "currency" => "usd",
                    "description" => $manager['user']['name']." Client Manager Charge"
                ));
            }
        }
        //send email report to Daithi.
        Mail::send(
            'email_template.client_manager_monthly_report', ['report' => $report], function ($message) use ($report) {
                $message->to('info@lifeprocessprogram.com')->subject("Monthly Client Manager Report ");
            }
        );
    }
}
