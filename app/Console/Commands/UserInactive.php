<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Client;
use App\Models\EmailTemplate;
use Carbon\Carbon;
use Mail;
use Log;

class UserInactive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:user-inactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'When user is in-active in 13 days and 27 in website then alert';

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
        $client_last_active = User::where('user_type','client')->where('status','active')->get();

        if(!empty($client_last_active))
        {
            foreach ($client_last_active as $inactive)
            {
                if(isset($inactive->last_active))
                {
                    $day = Carbon::now()->diffInDays(Carbon::createFromFormat('Y-m-d H:i:s',$inactive->last_active));

                    $email_template = EmailTemplate::where('slug','user-inactive-last-13-day')->first()->toArray();
                    if($email_template){
                        $tag = ['[client-name]','[client-email]','[first-name]','[days]'];
                        $replace_tag = [$inactive->name,$inactive->email,$inactive->first_name,$day.' days.'];
                        $to = str_replace($tag,$replace_tag,$email_template['to']);
                        $subject = str_replace($tag,$replace_tag,$email_template['subject']);
                        $content = str_replace($tag,$replace_tag,$email_template['content']);
                    }else{
                        $content = "Dear $inactive->name,<br/>
                                You are not login from last $day days,
                                Please login and complete your Program.</p>
                                <p>Thank you</p><br/>
                                The Life Process Team";
                        $subject = "How are you getting on $inactive->first_name ?";
                        $to = $inactive->email;
                    }

                    if($day >= 13 && $day < 17 && $inactive->last_active_email == 0){
                        if(!empty($to) && !empty($subject) && !empty($content)){
                            $this->mailsend($to,$subject,$content);
                            User::where('id',$inactive->id)->update(array('last_active_email'=>'1'));
                        }
                    }else if($day >= 17 && $inactive->last_active_email != 2){
                        if(!empty($to) && !empty($subject) && !empty($content)){
                            $this->mailsend($to,$subject,$content);
                            User::where('id',$inactive->id)->update(array('last_active_email'=>'2', 'in'));

                            //If User is a client we want to set their invite_coach to NULL
                            $client = Client::where('user_id', $inactive->id);
                            if($client != null && !empty($client)){
                                $client->update(array('invite_coach' => NULL));
                            }

                            //User needs to be removed from mailchimp list.
                            $this->deleteMailChimpUser($inactive);
                        }
                    }
                }
            }
            $this->info('Email sent successfully!');
        }

    }

    public function mailsend($to,$subject,$content)
    {
        $response = Mail::send(
            'email_template.comman', ['content' => $content],function ($message) use($to,$subject){
                $message->to($to)
                ->subject($subject);
                $bcc = explode(',', config('srtpl.bccmail'));
                if (!empty($bcc)) {
                $message->bcc($bcc);
                }
            });

        \Log::info($response);
    }
}