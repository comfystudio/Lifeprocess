<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Coach;
use App\Models\User;
use App\Models\CoachSchedule;
use App\Models\CoachSceduleBooked;
use App\Models\CompletedCoachingSession;
use App\Models\CoachFreeSession;
use App\Events\CoachTransactionHistoryEvent;
use Carbon\Carbon;
use App\Models\EmailTemplate;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mail;
use PDF;
use Cache;
use App\Models\CoachTransactionHistory;


class CompletedSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:completed-sessions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check Completed Sessions and update status';

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
        /*for coach schedule*/
        $complete_id = CoachSchedule::where("end_datetime",'<=',Carbon::now()->format('Y-m-d H:i:s'))->where('deleted', '0')->where('status', 'booked')->where('booked_for', 's')->with(['coachschedulebooked' => function ($query) {
                 $query->whereNull('session_status');
            }])->get();
        //dd($complete_id);

        foreach ($complete_id as $value) {

            if(!empty($value->coachschedulebooked)){
            $coach_user_id = $value->created_user_id;
            $inputs = [
                'booked_schedule_id' => $value->coachschedulebooked->id,
                'contact_methods' => $value->coachschedulebooked->meeting_type,
                'completed_at' => Carbon::now(),
            ];
            CompletedCoachingSession::create($inputs);

            $bookedSchedule = CoachSceduleBooked::where('id', $value->coachschedulebooked->id)->with('client.user')->first();
            $bookedSchedule->session_status = 'Completed';
            $bookedSchedule->save();

            $user = User::where('id',$bookedSchedule->client->user->id)->update(['is_free_session_complete' => 'y']);
            $coach = Coach::where('user_id', $coach_user_id)->with('user')->first();
            $one_hour_session_rate = 0;
            if (!empty($coach)) {
                $one_hour_session_rate = $coach->one_hour_session;
            }
            $coach_schedule = CoachSchedule::where('id', $bookedSchedule->coach_schedules_id)->first();
            $transation_history_arr = [
                'user_id' => $coach_user_id,
                'object_id' => $bookedSchedule->id,
                'object_type' => 'coach_schedules_booked',
                'transaction_type' => 'plus',
                'transaction_amount' => $one_hour_session_rate,
                'transaction_detail' => 'Has Completed One hour session <strong>' . Carbon::createFromFormat('Y-m-d H:i:s', $coach_schedule->start_datetime)->format('D dS F Y \a\t h:i a') . '</strong> with client <strong>' . $bookedSchedule->client->user->name . '</strong>',
            ];

            $coach=Coach::where('user_id',$coach_user_id)->first();
            $total_balance=$coach->balance+$one_hour_session_rate;
            Coach::where('user_id', $coach_user_id)->update(['balance' => $total_balance]);
            //fire event..

            CoachTransactionHistory::create($transation_history_arr);
            //event(new CoachTransactionHistoryEvent($transation_history_arr));

            $email_template = EmailTemplate::where('slug','user-completed-a-session')->first()->toArray();
            if(isset($email_template))
            {

                $booking_date_time = Carbon::createFromFormat('Y-m-d H:i:s',$coach_schedule->start_datetime)->setTimezone($bookedSchedule->client->user->timezone)->format('D dS F Y \a\t h:i a');
                $tag = ['[client-email]','[first-name]','[coach-name]','[booking-date-time]'];
                $replace_tag = [$bookedSchedule->client->user->email,$bookedSchedule->client->user->name,$coach->user->name,$booking_date_time];
                $to = str_replace($tag,$replace_tag,$email_template['to']);
                $subject = str_replace($tag,$replace_tag,$email_template['subject']);
                $content = str_replace($tag,$replace_tag,$email_template['content']);
                Mail::send(
                    'email_template.comman', ['content' => $content],function ($message) use($to,$subject){
                     $message->to($to)
                    ->subject($subject);
                    $bcc = explode(',', config('srtpl.bccmail'));
                    if (!empty($bcc)) {
                    $message->bcc($bcc);
                    }
                });
            }
            else
            {
                Mail::send(
                        'email_template.client_session_completed', ['client_name' => $bookedSchedule->client->user->first_name,'date' => $coach_schedule->start_datetime,'coach_name' => $coach->user->name,'timezone' => $bookedSchedule->client->user->timezone],function ($message) use($bookedSchedule){
                            $message->to($bookedSchedule->client->user->email)
                                ->subject("How was your session?");
                            $bcc = explode(',', config('srtpl.bccmail'));
                            if (!empty($bcc)) {
                                $message->bcc($bcc);
                            }
                 });
            }
            }

        }

        //dd($inputs);
        ///*for free session*/
        $complete_id = CoachSchedule::where("end_datetime",'<=',Carbon::now()->format('Y-m-d H:i:s'))->where('deleted', '0')->where('status', 'booked')->where('booked_for', 'f')->with(['coachschedulebooked' => function ($query) {
                 $query->whereNull('session_status');
                 $query->where('booked_slot','>',0);
            }])->get();
        //dd($complete_id);

        foreach ($complete_id as $value) {
            if(!empty($value->coachschedulebooked)){
            $coach_user_id = $value->created_user_id;
            $inputs = [
                'booked_schedule_id' => $value->coachschedulebooked->id,
                'contact_methods' => $value->coachschedulebooked->meeting_type,
                'completed_at' => Carbon::now(),
            ];
            CompletedCoachingSession::create($inputs);
            $bookedSchedule = CoachSceduleBooked::where('id', $value->coachschedulebooked->id)->with('client.user')->first();
            $bookedSchedule->session_status = 'Completed';
            $bookedSchedule->save();
            $coach = Coach::where('user_id', $coach_user_id)->with('user')->first();
            $free_session_rate = 0;
            if (!empty($coach)) {
                $free_session_rate = $coach->free_20_min_session;
            }
            $coach_schedule = CoachSchedule::where('id', $bookedSchedule->coach_schedules_id)->first();
            $transation_history_arr = [
                'user_id' => $coach_user_id,
                'object_id' => $bookedSchedule->id,
                'object_type' => 'coach_schedules_booked',
                'transaction_type' => 'plus',
                'transaction_amount' => $free_session_rate,
                'transaction_detail' => 'Has Completed free session <strong>' . Carbon::createFromFormat('Y-m-d H:i:s', $coach_schedule->start_datetime)->format('D dS F Y \a\t h:i a') . '</strong> with client <strong>' . $bookedSchedule->client->user->name . '</strong>',
            ];
            CoachTransactionHistory::create($transation_history_arr);
            //fire event..
            //event(new CoachTransactionHistoryEvent($transation_history_arr));

            $email_template = EmailTemplate::where('slug','user-completed-a-session')->first()->toArray();
            if(isset($email_template))
            {

                $booking_date_time = Carbon::createFromFormat('Y-m-d H:i:s',$coach_schedule->start_datetime)->setTimezone($bookedSchedule->client->user->timezone)->format('D dS F Y \a\t h:i a');
                $tag = ['[client-email]','[first-name]','[coach-name]','[booking-date-time]'];
                $replace_tag = [$bookedSchedule->client->user->email,$bookedSchedule->client->user->name,$coach->user->name,$booking_date_time];
                $to = str_replace($tag,$replace_tag,$email_template['to']);
                $subject = str_replace($tag,$replace_tag,$email_template['subject']);
                $content = str_replace($tag,$replace_tag,$email_template['content']);
                Mail::send(
                    'email_template.comman', ['content' => $content],function ($message) use($to,$subject){
                     $message->to($to)
                    ->subject($subject);
                    $bcc = explode(',', config('srtpl.bccmail'));
                    if (!empty($bcc)) {
                    $message->bcc($bcc);
                    }
                });
            }
            else
            {
                Mail::send(
                        'email_template.client_session_completed', ['client_name' => $bookedSchedule->client->user->first_name,'date' => $coach_schedule->start_datetime,'coach_name' => $coach->user->name,'timezone' => $bookedSchedule->client->user->timezone],function ($message) use($bookedSchedule){
                            $message->to($bookedSchedule->client->user->email)
                                ->subject("How was your session?");
                            $bcc = explode(',', config('srtpl.bccmail'));
                            if (!empty($bcc)) {
                                $message->bcc($bcc);
                            }
                 });
            }
            }

        }


    }
}
