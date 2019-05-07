<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CoachSceduleBooked;
use Illuminate\Support\Facades\Crypt;
use App\Models\EmailTemplate;
use Carbon\Carbon;
use Mail;
use Log;

class EmailBefore24HoursOfSession extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:email-before-24-hours-of_session';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '24 hours before a coaching session, send an email alert to the client.';

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
        $sessions = CoachSceduleBooked::with(['coach_schedule', 'client.user', 'client.coach.user'])->whereNull('session_status')->where('reminder_sent', '0')->get();

        if(!empty($sessions)) {
            foreach ($sessions as $row) {
                if(!empty($row->coach_schedule))
                {
                $timezone = 'UTC';
                if(isset($row->client->user->timezone)) {
                    $timezone = $row->client->user->timezone;
                }
                // $session_time = isset($row->coach_schedule->start_datetime) ? $row->coach_schedule->start_datetime : '0000-00-00 00:00:00' ;

                $session_time=$row->coach_schedule->start_datetime;

                $hourDiff = Carbon::now()->diffInHours(Carbon::createFromFormat('Y-m-d H:i:s', $session_time));

                $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i',Carbon::now());

                $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $session_time);

                $diff_in_hours = $to->diffInHours($from);


                if ($hourDiff <= 23) {
                    //dump($session_time);
                    if(isset($row->client->user)) {
                        $user = $row->client->user;

                        // $email_template = EmailTemplate::where('slug','reminder-upcoming-session')->first()->toArray();
                        // if(isset($email_template))
                        // {
                            $link = "<a href=".route('scheduled-session-problem.create',Crypt::encryptString($row->id))."> Click here to Submit report to Admin </a>";
                            $booking_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $row->coach_schedule->start_datetime)->setTimezone($row->client->user->timezone)->format('m/d/Y H:i');
                            $tag = ['[client-email]','[client-name]','[coach-name]','[booking-date-time]','[session-problem-link]'];
                            $replace_tag = [$user->email,$user->name,$row->client->coach->user->name,$booking_date_time,$link];

                        //     $to = str_replace($tag,$replace_tag,$email_template['to']);
                        //     $subject = str_replace($tag,$replace_tag,$email_template['subject']);
                        //     $content = str_replace($tag,$replace_tag,$email_template['content']);

                            //dump($row->meeting_type);
                            $format=$row->meeting_type;
                            if($row->booked_for=='f')
                            {
                                $session='Free Session';
                            }
                            else
                            {
                                $session='1-1 Session';
                            }

                            $date = Carbon::createFromFormat('Y-m-d H:i:s', $row->coach_schedule->start_datetime)->setTimezone($row->client->coach->user->timezone)->format('Y-m-d');
//                            $time = Carbon::createFromFormat('Y-m-d H:i:s', $row->coach_schedule->start_datetime)->setTimezone($row->client->user->timezone)->format('H:i:s');
                            $time = Carbon::createFromFormat('Y-m-d H:i:s', $row->coach_schedule->start_datetime)->setTimezone($row->client->coach->user->timezone)->format('H:i');

                            //offset the time based on the slot
                            if($row->booked_slot == 2){
                                $time = Carbon::parse($time)->addMinutes(20)->format('H:i');
                            }elseif($row->booked_slot == 3){
                                $time = Carbon::parse($time)->addMinutes(40)->format('H:i');
                            }else{
                                $time = Carbon::parse($time)->format('H:i');
                            }




                           //dd($email_template_coach);
                            $email_template_coach = EmailTemplate::where('slug', 'coaching-session-scheduled-24hr')->first()->toArray();
                            if(isset($email_template_coach))
                            {
                            $tag         = ['[coach-email]','[coach-name]','[client-name]','[date]','[time]','[format]','[session]'];
                            $replace_tag = [$row->client->coach->user->email,$row->client->coach->user->name,$user->name,$date,$time,$format,$session];
                            $to          = str_replace($tag, $replace_tag, $email_template_coach['to']);

                            $subject     = str_replace($tag, $replace_tag, $email_template_coach['subject']);
                            $content     = str_replace($tag, $replace_tag, $email_template_coach['content']);

                            Mail::send(
                                'email_template.comman', ['content' => $content], function ($message) use ($to, $subject) {
                                    $message->to($to)
                                        ->subject($subject);
                                    // $bcc = explode(',', config('srtpl.bccmail'));
                                    // if (!empty($bcc)) {
                                    //     $message->bcc($bcc);
                                    // }
                                });
                            }  // over here
                            $email_template_client=EmailTemplate::where('slug','reminder-upcoming-session-24hr')->first()->toArray();
                            if(isset($email_template_client))
                            {

                                $tag         = ['[client-email]','[client-name]','[coach-name]','[booking-date-time]','[date]','[time]','[session]'];
                                $replace_tag = [$row->client->user->email,$row->client->user->name,$row->client->coach->user->name,$booking_date_time,$date,$time,$session];

                                $to          = str_replace($tag, $replace_tag, $email_template_client['to']);
                                $subject     = str_replace($tag, $replace_tag, $email_template_client['subject']);
                                $content     = str_replace($tag, $replace_tag, $email_template_client['content']);


                            }

                        //}

                        // else
                        // {
                        //     $response = Mail::send('email_template.emailBefore24HoursOfSession', ['scheduled_session' => $row], function ($mail) use ($user, $row) {
                        //     $coach_name = isset($row->client->coach->user->name) ? $row->client->coach->user->name: '' ;
                        //     $mail->to($user->email)
                        //         ->subject('Reminder - Your Coaching Session with ' . $coach_name . ' tomorrow');
                        //     });
                        //     \Log::info($response);
                        // }
                    }
                    CoachSceduleBooked::where('id', $row->id)->update(['reminder_sent' => '1']);
                }
                    if ($hourDiff <= 1)
                    {
                        if(isset($row->client->user))
                        {
                        $user = $row->client->user;
                        $email_template = EmailTemplate::where('slug','reminder-upcoming-session-1hr')->first()->toArray();
                        if(isset($email_template))
                        {
                            $link = "<a href=".route('scheduled-session-problem.create',Crypt::encryptString($row->id))."> Click here to Submit report to Admin </a>";
                            $booking_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $row->coach_schedule->start_datetime)->setTimezone($row->client->user->timezone)->format('m/d/Y H:i');
                            $tag = ['[client-email]','[client-name]','[coach-name]','[booking-date-time]','[session-problem-link]'];
                            $replace_tag = [$user->email,$user->name,$row->client->coach->user->name,$booking_date_time,$link];

                            $to = str_replace($tag,$replace_tag,$email_template['to']);

                            $subject = str_replace($tag,$replace_tag,$email_template['subject']);
                            $content = str_replace($tag,$replace_tag,$email_template['content']);

                            $email_template_coach = EmailTemplate::where('slug', 'coaching-session-scheduled-1hr')->first()->toArray();
                            $format='';
                            $session='1-1 session';
                            $date = Carbon::createFromFormat('Y-m-d H:i:s', $row->coach_schedule->start_datetime)->setTimezone($row->client->coach->user->timezone)->format('Y-m-d');
//                            $time = Carbon::createFromFormat('Y-m-d H:i:s', $row->coach_schedule->start_datetime)->setTimezone($row->client->user->timezone)->format('H:i:s');
                            $time = Carbon::createFromFormat('Y-m-d H:i:s', $row->coach_schedule->start_datetime)->setTimezone($row->client->coach->user->timezone)->format('H:i');

                            //offset the time based on the slot
                            if($row->booked_slot == 2){
                                $time = Carbon::parse($time)->addMinutes(20)->format('H:i');
                            }elseif($row->booked_slot == 3){
                                $time = Carbon::parse($time)->addMinutes(40)->format('H:i');
                            }else{
                                $time = Carbon::parse($time)->format('H:i');
                            }


                            //dd($email_template_coach);
                            if(isset($email_template_coach))
                            {
                            $tag         = ['[coach-email]','[coach-name]','[client-name]','[date]','[time]','[format]','[session]','[coach-timezone-time]'];
                            $replace_tag = [$row->client->coach->user->email,$row->client->coach->user->name,$user->name,$date,$time,$format,$session,$booking_date_time];

                            $to          = str_replace($tag, $replace_tag, $email_template_coach['to']);
                            $subject     = str_replace($tag, $replace_tag, $email_template_coach['subject']);
                            $content     = str_replace($tag, $replace_tag, $email_template_coach['content']);


                            }  // over here

                            $email_template_client=EmailTemplate::where('slug','reminder-upcoming-session-1hr')->first()->toArray();
                            $booking_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $row->coach_schedule->start_datetime)->setTimezone($row->client->user->timezone)->format('m/d/Y H:i');
                            if(isset($email_template_client))
                            {
                                $tag         = ['[client-email]','[client-name]','[coach-name]','[booking-date-time]','[start-time-in-client-timezone]','[date]','[time]','[format]','[session]'];
                                $replace_tag = [$row->client->user->email,$row->client->user->name,$row->client->coach->user->name,$booking_date_time,$date,$time,$format,$session];
                                $to          = str_replace($tag, $replace_tag, $email_template_client['to']);
                                // dump($to);
                                $subject     = str_replace($tag, $replace_tag, $email_template_client['subject']);
                                $content     = str_replace($tag, $replace_tag, $email_template_client['content']);

                                Mail::send(
                                    'email_template.comman', ['content' => $content], function ($message) use ($to, $subject) {
                                        $message->to($to)
                                            ->subject($subject);
                                        // $bcc = explode(',', config('srtpl.bccmail'));
                                        // if (!empty($bcc)) {
                                        //     $message->bcc($bcc);
                                        // }
                                    });
                            }

                        }
                        else
                        {
                            $response = Mail::send('email_template.emailBefore24HoursOfSession', ['scheduled_session' => $row], function ($mail) use ($user, $row) {
                            $coach_name = isset($row->client->coach->user->name) ? $row->client->coach->user->name: '' ;
                            $mail->to($user->email)
                                ->bcc('darshika.akhiyaniya@sphererays.net')
                                ->subject('Reminder - Your Coaching Session with ' . $coach_name . ' tomorrow');
                            });
                            \Log::info($response);
                        }

                    }
                    CoachSceduleBooked::where('id', $row->id)->update(['reminder_sent' => '1']);
                }
            }
        }
        }
    }
}
