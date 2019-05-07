<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserModuleProgress;
use Carbon\Carbon;
use App\Models\EmailTemplate;
use Cache;
use Log;
use Mail;

class SendEmailIfNotAccessedCoachFeedback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:email-if-not-accessed-coach-feedback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email to client if they have not accessed feedback within the defined days.';

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
        $modules = UserModuleProgress::with(['submittedBy', 'modules', 'reviewedBy'])->where('status', 'reviewed')->whereNull('view_feedback_at')->take(25)->get();
        foreach ($modules as $module) {
            // Send the email to submitter to view or download the feedback/review
            // $timezone = 'UTC';
            // if(isset($module->submittedBy->timezone)) {
            //     $timezone = $module->submittedBy->timezone;
            // }
            $reviewed_at = isset($module->reviewed_at) ? $module->reviewed_at : '0000-00-00 00:00:00' ;
            $dayDiff = Carbon::now()->diffInDays(Carbon::createFromFormat('Y-m-d H:i:s', $reviewed_at));
            \Log::debug("DayDiff " . $dayDiff);
            //$this->info($dayDiff);
            $email_template = EmailTemplate::where('slug','coach-feedback-has-not been-read')->first()->toArray();
            $coach_name = isset($module->reviewedBy->name) ? $module->reviewedBy->name : '' ;
            $to = $subject = $content = '';

            if($email_template){
                $tag = ['[client-email]','[first-name]','[module-no]','[module-title]','[coach-name]'];
                $client_email   = isset($module->submittedBy->email) ? $module->submittedBy->email : '';
                $first_name     = isset($module->submittedBy->name) ? $module->submittedBy->name : '';
                $module_no      = isset($module->modules->module_no) ? $module->modules->module_no : '';
                $module_title   = isset($module->modules->module_title) ? $module->modules->module_title : '';

                $replace_tag = [$client_email,$first_name,$module_no,$module->modules->module_title,$coach_name];

                $to = str_replace($tag,$replace_tag,$email_template['to']);
                $subject = str_replace($tag,$replace_tag,$email_template['subject']);
                $content = str_replace($tag,$replace_tag,$email_template['content']);
            }else{
                $to = $module->submittedBy->email;
                $subject = "$coach_name's feedback is still waiting for you";
                $content = 'Hi ' . $module->submittedBy->name . ', <br>' .
                    ' You have not view or download the feedback of the module "' . $module->modules->module_no . '. ' . $module->modules->module_title . '" you submitted till the date. <br><br>
                    Thank you.
                    Life process Alcohol Program
                    ';
            }

            if($dayDiff == 3 || $dayDiff == 7)
            {
                if(!empty($to) && !empty($subject) && !empty($content))
                {
                        $email_template = EmailTemplate::where('slug', 'coach-feedback-not-open-7day')->first()->toArray();
                        if(isset($email_template))
                        {
                            $tag         = ['[coach-name]','[client-name]','[client-email]','[excercise-name]'];
                            $replace_tag = [$module->submittedBy->email,$module->submittedBy->name,$coachname,$module->modules->module_no,$module->modules->module_title];
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
                        }
                        $email_template_coach = EmailTemplate::where('slug', 'coach-feedback-has-not been-read')->first()->toArray();
                        $coachname='';
                           //dd($email_template_coach);
                        if(isset($email_template_coach))
                        {
                            $tag         = ['[client-email]','[first-name]','[coach-name]','[module-no]','[module-title]'];
                            $replace_tag = [$module->submittedBy->email,$module->submittedBy->name,$coachname,$module->modules->module_no,$module->modules->module_title];
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
                        }  // over here
                        $this->mailsend($to,$subject,$content);
                }
            }
        }
       $this->info('Email sent successfully!');
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