<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client;
use App\Models\EmailTemplate;
use Carbon\Carbon;
use Mail;
use App\Models\User;

class UserNotBookSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:user-notbooked-schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'User Not Booked Schedule Specific Days';

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
        $clients = Client::where('LPAP_initial_fee','paid')->with('schedule_booked','user','coach')->get();
        $email_template = EmailTemplate::where('slug','user-not-booked-coach-schedule')->first()->toArray();
        foreach ($clients as $client)
        {
            //Need to check if coach is LLP coach or not so we can ignore sending email reminder if coach is not an LLP coach
            $coach = User::where('id', '=', $client['coach']['user_id'])->first();
            $creator = User::where('id', '=', $coach['created_by'])->first();

            if(isset($client->user) && $creator['role_id'] != 5)
            {
                if($client->user->status=='active')
                {
                    $day = Carbon::createFromFormat('Y-m-d H:i:s',$client->created_at)->diffInDays();
                    $schedulebooked = count($client->schedule_booked);

                    if(($day == 4 && ($schedulebooked == 0)))
                    {
                            $email_template_coach = EmailTemplate::where('slug', 'user-not-booked-initial-4d')->first()->toArray();
                            $link='';

                            if(isset($email_template_coach))
                            {
                                if(isset($client->coach) && !empty($client->coach))
                                {
                                    $tag         = ['[coach-name]','[client-name]','[client-email]','[link]'];
                                    $replace_tag = [$client->coach->user->name,$client->user->name,$client->user->email,$link];
                                    $to          = str_replace($tag, $replace_tag, $email_template_coach['to']);

                                    $subject     = str_replace($tag, $replace_tag, $email_template_coach['subject']);
                                    $content     = str_replace($tag, $replace_tag, $email_template_coach['content']);

                                    Mail::send(
                                        'email_template.comman', ['content' => $content], function ($message) use ($to, $subject) {
                                            $message->to($to)
                                                ->subject($subject);
                                            $bcc = explode(',', config('srtpl.bccmail'));
                                            if (!empty($bcc)) {
                                                $message->bcc($bcc);
                                            }
                                        });
                                }  // over header_remove()
                            }

                    }
                    if(($day == 8 && ($schedulebooked == 0)))
                    {
                            $email_template_coach = EmailTemplate::where('slug', 'user-not-booked-initial-8d')->first()->toArray();
                            $link='';
                            if(isset($email_template_coach))
                            {
                                if(isset($client->coach) && !empty($client->coach))
                                {
                                    $tag         = ['[coach-name]','[client-name]','[client-email]','[link]'];
                                    $replace_tag = [$client->coach->user->name,$client->user->name,$client->user->email,$link];
                                    $to          = str_replace($tag, $replace_tag, $email_template_coach['to']);

                                    $subject     = str_replace($tag, $replace_tag, $email_template_coach['subject']);
                                    $content     = str_replace($tag, $replace_tag, $email_template_coach['content']);

                                    Mail::send(
                                        'email_template.comman', ['content' => $content], function ($message) use ($to, $subject) {
                                            $message->to($to)
                                                ->subject($subject);
                                            $bcc = explode(',', config('srtpl.bccmail'));
                                            if (!empty($bcc)) {
                                                $message->bcc($bcc);
                                            }
                                        });
                            }  // over header_remove()
                            }
                    }
                    if(($day == 13 && ($schedulebooked == 0)))
                    {
                            $email_template_coach = EmailTemplate::where('slug', 'user-not-booked-initial-13d')->first()->toArray();
                            $link='';
                            if(isset($email_template_coach))
                            {
                                if(isset($client->coach) && !empty($client->coach))
                                {
                                    $tag         = ['[coach-name]','[client-name]','[client-email]','[link]'];
                                    $replace_tag = [$client->coach->user->name,$client->user->name,$client->user->email,$link];
                                    $to          = str_replace($tag, $replace_tag, $email_template_coach['to']);

                                    $subject     = str_replace($tag, $replace_tag, $email_template_coach['subject']);
                                    $content     = str_replace($tag, $replace_tag, $email_template_coach['content']);

                                    Mail::send(
                                        'email_template.comman', ['content' => $content], function ($message) use ($to, $subject) {
                                            $message->to($to)
                                                ->subject($subject);
                                            $bcc = explode(',', config('srtpl.bccmail'));
                                            if (!empty($bcc)) {
                                                $message->bcc($bcc);
                                            }
                                        });
                            }  // over header_remove()
                            }

                    }
                    if(($day == 27 && ($schedulebooked == 0)))
                    {
                            $email_template_coach = EmailTemplate::where('slug', 'user-not-booked-initial-27d')->first()->toArray();
                            $link='';
                            if(isset($email_template_coach))
                            {
                                if(isset($client->coach) && !empty($client->coach))
                                {
                                    $tag         = ['[coach-name]','[client-name]','[client-email]','[link]'];
                                    $replace_tag = [$client->coach->user->name,$client->user->name,$client->user->email,$link];
                                    $to          = str_replace($tag, $replace_tag, $email_template_coach['to']);

                                    $subject     = str_replace($tag, $replace_tag, $email_template_coach['subject']);
                                    $content     = str_replace($tag, $replace_tag, $email_template_coach['content']);

                                    Mail::send(
                                        'email_template.comman', ['content' => $content], function ($message) use ($to, $subject) {
                                            $message->to($to)
                                                ->subject($subject);
                                            $bcc = explode(',', config('srtpl.bccmail'));
                                            if (!empty($bcc)) {
                                                $message->bcc($bcc);
                                            }
                                        });
                                }  // over header_remove()
                            }
                    }
                }
            }
        }
    }
}
