<?php

namespace App\Console\Commands;

use App\Models\Agent;
use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\EmailTemplate;
use Carbon\Carbon;
use Mail;
use App\Models\User;

class UpdateClientCredits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update-client-credits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clients of Client Managers updating of their credits';

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
        foreach($client_managers as $key => $manager){
            $clients = Client::where('agent_id', '=', $manager['user_id'])->with('user')->with('coach')->get();
            foreach($clients as $client) {
                if($manager['credits_accumulate'] == 1 && $manager['credits_per_month'] >= 1){
                    $input = array('credits' => ($manager['credits_per_month'] + $client['credits']));
                }else{
                    $input = array('credits' => $manager['credits_per_month']);
                }
                Client::where('id', '=', $client->id)->update($input);
            }
        }
    }
}
