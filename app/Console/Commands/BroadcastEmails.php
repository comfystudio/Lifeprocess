<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BroadcastEmail;
use Mail;
use Log;

class BroadcastEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:broadcastEmail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Broadcase Email to clients';

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
        $users = BroadcastEmail::where('is_sent', '0')->get();

        foreach ($users as $user)
        {
            // Send the email to user
            if($user->to!='1')
            {
                $response = Mail::send('email_template.broadcastMessage', ['user' => $user], function ($mail) use ($user) {
                    $mail->to($user->to)
                        ->subject($user->subject);
                });
//                BroadcastEmail::where()->update(['is_sent' => '1']);
//                Coach::where('user_id', $id)->update(['balance' => '0']);
            }

        }
        \Log::info($response);
//        $this->info('Email sent successfully!');
    }
}
