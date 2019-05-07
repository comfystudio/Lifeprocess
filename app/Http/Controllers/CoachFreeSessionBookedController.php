<?php

namespace App\Http\Controllers;

use App;
use App\Events\CreditHistoryEvent;
use App\Events\NotificationEvent;
use App\Models\Client;
use App\Models\CoachSceduleBooked;
use App\Models\CoachSchedule;
use App\Models\EmailTemplate;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Mail;
use App\Models\Setting;

class CoachFreeSessionBookedController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        //$this->middleware('check_for_permission.access:book_schedule.create', ['only' => ['create', 'store']]);
        //$this->middleware('check_for_permission.access:book_schedule.view', ['only' => ['index', 'show']]);
        $this->middleware('check_coachIsAssigned', ['except' => 'cancelBookedSchedule']);
        $this->title = "Book Free 20 Minutes Coach Schedule";
        view()->share('title', $this->title);
    }
    /**
     * Display a listing of the resource.

     *
     * @return \Illuminate\Http\Response

     */
    public function index(Request $request)
    {
        $cur_user       = Auth::user();
        $user_timezone  = User::where('id', $cur_user->id)->get()->toArray();
        $coach_timezone = $user_timezone[0]['timezone'];

        $user_data = User::where('id', '=', Auth::id())->first();
        if (Auth::user()->is_gratuate == 'n') {
            $free_session = $user_data->is_free_session_booked;
            if ($free_session == 1) {
                return redirect()->route('clients.dashboard.coaching');
            }
        }
        $cur_user = Auth::user();
        $schedule = [];
        $client   = Client::where('user_id', '=', $cur_user->id)->with('coach.user')->first();
        if ($request->start != '') {
            $current_month = Carbon::parse($request['start']);
        } else {
            $current_month = Carbon::now();
        }
        $client_current_month        = $current_month->copy()->setTimezone($client->user->timezone);
        $client_current_month->month = $current_month->month;
        $client_current_month        = $client_current_month->startOfMonth();
        $first_day_this_month        = $client_current_month->format('Y-m-d H:i:s');
        $last_day_this_month         = $client_current_month->copy()->endOfMonth()->format('Y-m-d H:i:s');
        $current_date=Carbon::now()->setTimezone($client->user->timezone);
        $getdiff=Setting::where('name','allow_booking_hour')->first();
        $setdiff=$getdiff->value;
        if($setdiff>=24)
        {
            $totaldiff=$current_date->addHours($setdiff)->format('Y-m-d h:i:s');
        }
        else
        {
            $totaldiff=$current_date->addHours($setdiff)->format('Y-m-d h:i:s');
        }

        if ($client != null) {
            $schedule = CoachSchedule::where("deleted", "0")
                ->where(function ($q) {
                    $q->where('status', 'available')
                        ->orWhere(function ($sq) {
                            $sq->where('status', 'booked')
                                ->where('booked_for', 'f')
                                ->orWhere('slot1', 0)
                                ->orWhere('slot2', 0)
                                ->orWhere('slot3', 0);

                        });
                })
                ->where('start_datetime','>=',$totaldiff)
                // ->where('start_datetime', '>=', $first_day_this_month)
                ->where('end_datetime', '<=', $last_day_this_month)
                ->where('created_user_id', '=', $client->coach->user->id)
                ->orderBy('start_datetime', 'ASC')
                ->get(['id', 'start_datetime', 'end_datetime', 'status']);
        }
        if ($cur_user->user_type == 'client') {
            $client_user_timezone = $client->user->timezone;

            if ($client_user_timezone == '') {
                $client_user_timezone = 'UTC';
            }
            $coach_user_timezone = ($client->coach->user->timezone);
            if ($coach_user_timezone == '') {
                $coach_user_timezone = 'UTC';
            }
        }

        view()->share('coach_user_timezone', $coach_user_timezone);
        $coach_user_time = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->toDateTimeString(), $client_user_timezone)->setTimezone($coach_user_timezone);

        view()->share('coach_timezone', $coach_timezone);
        view()->share('coach_user_time', $coach_user_time->toDateTimeString());
        view()->share('coach_user_name', $client->coach->user->name);
        view()->share('client_user_timezone', $client_user_timezone);
        if ($request->ajax()) {
            return response()->json([
                'success' => 'true', 'schedule' => $schedule,
            ]);
        }
        return view('bookfreeschedule.index', compact('schedule'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function renderEvent(Request $request)
    {
        if ($request->id != '') {

            $cur_user       = Auth::user();
            $user_timezone  = User::where('id', $cur_user->id)->get()->toArray();
            $coach_timezone = $user_timezone[0]['timezone'];
            $cur_user       = Auth::user();
            $client         = Client::where('user_id', '=', $cur_user->id)->with('coach.user')->first();
            $booked_for     = '';
            if (Auth::user()->is_gratuate == 'y') {
                $booked_for = 'g';
            } else {
                $booked_for = 'f';
            }
            $schedule = CoachSchedule::where("deleted", "0")
                ->with(['coachschedulebooked' => function ($query) {
                    $query->whereNull('session_status')->where('booked_slot', '>', 0);
                }])
                ->where('status', '=', 'booked')
                ->where('id', '=', $request->id)
                ->where('created_user_id', '=', $client->coach->user->id)
                ->get(['id', 'start_datetime', 'end_datetime']);
            $booked_id       = CoachSceduleBooked::where("coach_schedules_id", $schedule[0]['id'])->get(['meeting_type'])->toArray();
            $meeting_type    = $booked_id[0]['meeting_type'];
            $final_date      = Carbon::createFromFormat('Y-m-d H:i:s', $schedule[0]['start_datetime'])->format('Y/m/d');
            $booked_slot_val = $schedule[0]['coachschedulebooked']->booked_slot;
            if ($booked_slot_val > 0) {
                if ($booked_slot_val == 1) {
                    $start_time = Carbon::createFromFormat('Y-m-d H:i:s', $schedule[0]['start_datetime'])->format('H:i a');
                    $start      = Carbon::createFromFormat('Y-m-d H:i:s', $schedule[0]['start_datetime'])->format('H:i');
                    $end_time   = Carbon::parse($start)->addMinutes(20)->format('H:i a');
                    $total_time = $start_time . "-" . $end_time;
                } elseif ($booked_slot_val == 2) {
                    $start_time = Carbon::parse($schedule[0]['start_datetime'])->addMinutes(20)->format('H:i a');
                    $start      = Carbon::parse($schedule[0]['start_datetime'])->addMinutes(20)->format('H:i');
                    $end_time   = Carbon::parse($start)->addMinutes(20)->format('H:i a');
                    $total_time = $start_time . "-" . $end_time;
                } elseif ($booked_slot_val == 3) {
                    $start_time = Carbon::parse($schedule[0]['start_datetime'])->addMinutes(40)->format('H:i a');
                    $end_time   = Carbon::createFromFormat('Y-m-d H:i:s', $schedule[0]['end_datetime'])->format('H:i a');
                    $total_time = $start_time . "-" . $end_time;
                }
            }
            if ($cur_user->user_type == 'client') {
                $client_user_timezone = $client->user->timezone;

                if ($client_user_timezone == '') {
                    $client_user_timezone = 'UTC';
                }
                $coach_user_timezone = ($client->coach->user->timezone);
                if ($coach_user_timezone == '') {
                    $coach_user_timezone = 'UTC';
                }

            }
            view()->share('coach_user_timezone', $coach_user_timezone);
            $coach_user_time = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->toDateTimeString(), $client_user_timezone)->setTimezone($coach_user_timezone);
            view()->share('coach_timezone', $coach_timezone);
            view()->share('total_time', $total_time);
            view()->share('final_date', $final_date);
            view()->share('meeting_type', $meeting_type);
            view()->share('coach_user_time', $coach_user_time->toDateTimeString());
            view()->share('coach_user_name', $client->coach->user->name);
            view()->share('client_user_timezone', $client_user_timezone);
            return view('bookfreeschedule.index', compact('schedule'));
        }
    }
    public function renderFreeTimeslot(Request $request)
    {
        if ($request->start != '') {
            $cur_user   = Auth::user();
            $client     = Client::where('user_id', '=', $cur_user->id)->with('coach.user')->first();
            $start      = $request['start'];
            $end        = $request['end'];
            $date_click = $request['date_click'];
            $cur_user   = Auth::user();
            $client     = Client::where('user_id', '=', $cur_user->id)->with('coach.user')->first();
            $getdiff=Setting::where('name','allow_booking_hour')->first();
            $setdiff=$getdiff->value;
            if($date_click==Carbon::now()->setTimezone('UTC')->toDateString())
            {
                $date_click = Carbon::now()->addHours($setdiff)->setTimezone('UTC')->toDateTimeString();
            }
            else
            {
                $date_click = Carbon::createFromFormat('Y-m-d H:i:s', $request['date_click']." 00:00:00",$client->user->timezone)->setTimezone('+00:00');
            }
            $nextDate = date('Y-m-d', strtotime('+1 day', strtotime($date_click)));
            $nextDate = Carbon::createFromFormat('Y-m-d H:i:s', $request['date_click'] . " 23:59:59", $client->user->timezone)->setTimezone('+00:00');
            $schedule = CoachSchedule::where("deleted", "0")
                ->where(function ($q) {
                    $q->where('status', 'available')
                        ->orWhere(function ($sq) {
                            $sq->where('status', 'booked')
                                ->where('booked_for', 'f')
                                ->where(function ($ssq) {
                                    $ssq->orWhere('slot1', 0)
                                    ->orWhere('slot2', 0)
                                    ->orWhere('slot3', 0);
                                });
                        });
                })
                //->where('start_datetime', '>=', Carbon::now()->toDateTimeString())
                ->where('start_datetime', '>=', $date_click . " " . date('h:i:s'))
                ->where('start_datetime', '<=', $nextDate . " 00:00:00")
                ->with(['coachschedulebooked' => function ($query) {
                    $query->whereNull('session_status');
                }])
                ->where('created_user_id', '=', $client->coach->user->id)
                ->orderBy('start_datetime')
                ->get();

            if ($cur_user->user_type == 'client') {
                $client_user_timezone = $client->user->timezone;
                if ($client_user_timezone == '') {
                    $client_user_timezone = 'UTC';
                }
                $coach_user_timezone = ($client->coach->user->timezone);
                if ($coach_user_timezone == '') {
                    $coach_user_timezone = ($client->coach->user->timezone);
                } else {
                    $coach_user_timezone = 'UTC';
                }
            } else {
                $coach_user_timezone = 'UTC';
            }
            view()->share('coach_user_timezone', $coach_user_timezone);
            $coach_user_time = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->toDateTimeString(), $client_user_timezone)->setTimezone($coach_user_timezone);
            view()->share('coach_user_time', $coach_user_time->toDateTimeString());
            view()->share('coach_user_name', $client->coach->user->name);
            view()->share('client_user_timezone', $client_user_timezone);

            return response()->json([
                'success' => 'true', 'schedule' => $schedule,

            ]);
        }
    }
    public function setFreeFYI(Request $request)
    {
        $cur_user = Auth::user();
        $client   = Client::where('user_id', '=', $cur_user->id)->with('coach.user')->first();
        if ($request->id != '') {
            $schedule = CoachSchedule::where("deleted", "0")
                ->where('id', '=', $request->id)
                ->first();
        }
        $time_display = Carbon::createFromFormat('Y-m-d H:i:s', $schedule->start_datetime, $client->user->timezone)->setTimezone($client->coach->user->timezone);
        if($request->slot == 2){
            $time_display = Carbon::parse($time_display)->addMinutes(20)->format('Y-m-d H:i:s');
        }elseif($request->slot == 3){
            $time_display = Carbon::parse($time_display)->addMinutes(40)->format('Y-m-d H:i:s');
        }else{
            $time_display = Carbon::parse($time_display)->format('Y-m-d H:i:s');
        }

        return $time_display;
//        return $time_display->toDateTimeString();
    }
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

            if (Auth::user()->is_gratuate == 'y') {
            $booked_for = 'g';
        } else {
            $booked_for = 'f';
        }
        $result = $this->validate($request, [
            'time-slot'    => "required",

            'meeting_type' => "required",
        ],
            [
                'time-slot.required'    => 'Please Select Schedule Time',
                'meeting_type.required' => 'Please Select Meeting Type',
            ]
        );
        $check_credit       = App::make('App\Http\Controllers\ClientController');
        $booked_user_id     = Auth::id();
        $coach_schedules_id = $request->get('coach_schedules_id');
        $slot_id            = $request->get('slot_id');
        $meeting_type       = $request->get('meeting_type');
        $scheduledetail     = CoachSchedule::where("id", $coach_schedules_id)->first();
        $schedulebooked     = '';
        if ($scheduledetail == '') {
            $schedulebooked = CoachSchedule::where("id", $coach_schedules_id)->where("status", 'booked')->where("slot" . $slot_id, '1')->first();
        }
        if ($schedulebooked != '') {
            Flash::error("This schedule Recently booked By other client");
            return response()->json([
                'success' => 'false',
                'message' => 'Schedule is Recently booked by other client! Please Select other schedule.',
                'url'     => route('bookfreeschedule.index'),
            ]);
        }
        if (!isset($scheduledetail)) {
            Flash::error("This schedule Recently Deleted By coach");
            return response()->json([
                'success' => 'false',
                'message' => 'This schedule Recently Deleted By coach!',
                'url'     => route('bookfreeschedule.index'),
            ]);
        } else {
            $input =
                [
                'meeting_type'       => $meeting_type,
                'coach_schedules_id' => $coach_schedules_id,
                'booked_user_id'     => $booked_user_id,
                'booked_for'         => $booked_for, //free or gratuate
                'booked_slot'        => $slot_id,
            ];
            try {

                DB::beginTransaction();
                $model = CoachSceduleBooked::where('coach_schedules_id', $coach_schedules_id)->where('booked_user_id', $booked_user_id)->first();

                if (!empty($model)) {
                    $input = [
                        'session_status'       => null,
                        'cancelled_by_user_id' => 0,
                        'reminder_sent'        => '0',
                        'deleted'              => '0',
                        'created_at'           => Carbon::now(),
                        'meeting_type'         => $meeting_type,
                    ];
                    CoachSceduleBooked::where('id', $model->id)->update($input);
                } else {
                    $model = CoachSceduleBooked::create($input);
                }

                if (!empty($model)) {

                    if ($slot_id == 1) {
                        CoachSchedule::where('id', $coach_schedules_id)->update(['status' => 'booked', 'booked_for' => $booked_for, 'slot1' => 1]);
                    }
                    if ($slot_id == 2) {
                        CoachSchedule::where('id', $coach_schedules_id)->update(['status' => 'booked', 'booked_for' => $booked_for, 'slot2' => 1]);
                    }
                    if ($slot_id == 3) {
                        CoachSchedule::where('id', $coach_schedules_id)->update(['status' => 'booked', 'booked_for' => $booked_for, 'slot3' => 1]);
                    }
                    if (Auth::user()->is_gratuate == 'y') {
                        User::where('id', Auth::id())->update(['is_gratuate_session_booked' => 1]);
                    } else {
                        User::where('id', Auth::id())->update(['is_free_session_booked' => 1]);
                    }

                }
                if (!empty($model)) {
                    $client = Client::where('user_id', Auth::id())->with('coach.user')->first();

                    $text             = '<strong>' . link_to_route('client.detail', Auth::user()->name, ['client_id' => Crypt::encryptString(Auth::id())]) . '</strong> has requested an appointment.';
                    $notification_arr = [
                        'text'        => $text,
                        'receiver_id' => [$client->coach->user->id],
                    ];
                    //fire event..
                    event(new NotificationEvent($notification_arr));
                    $email_template = EmailTemplate::where('slug', 'user-booked-a-session')->first()->toArray();
                    if (isset($email_template)) {
                        $booking_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $scheduledetail->start_datetime, $client->user->timezone)->format('D dS F Y \a\t h:i a');

                        $booked_slot_val = $model->booked_slot;

                        if ($booked_slot_val > 0) {
                            if ($booked_slot_val == 1) {
                                $start_time = Carbon::createFromFormat('Y-m-d H:i:s', $model->coach_schedule->start_datetime)->format('Y-m-d');
                                $start      = Carbon::createFromFormat('Y-m-d H:i:s', $model->coach_schedule->start_datetime)->format('H:i');
                                $end_time   = Carbon::parse($start)->addMinutes(20)->format('Y-m-d');
                                $total_time = $start_time . "-" . $end_time;
                            } elseif ($booked_slot_val == 2) {
                                $start_time = Carbon::parse($model->coach_schedule->start_datetime)->addMinutes(20)->format('Y-m-d');
                                $start      = Carbon::parse($model->coach_schedule->start_datetime)->addMinutes(20)->format('H:i');
                                $end_time   = Carbon::parse($start)->addMinutes(20)->format('Y-m-d');
                                $total_time = $start_time . "-" . $end_time;
                            } elseif ($booked_slot_val == 3) {
                                $start_time = Carbon::parse($model->coach_schedule->start_datetime)->addMinutes(40)->format('Y-m-d');
                                $start      = Carbon::parse($model->coach_schedule->start_datetime)->addMinutes(40)->format('H:i');
                                $end_time   = Carbon::createFromFormat('Y-m-d H:i:s', $model->coach_schedule->end_datetime)->format('Y-m-d');
                                $total_time = $start_time . "-" . $end_time;
                            }
                        }
                        $tag         = ['[client-email]', '[first-name]', '[coach-name]', '[date]', '[time]', '[session]'];
                        $session='Introductory Session';
                        if($booked_for=='g')
                        {
                            $session='Graduate Check-in Session';
                        }

                        $replace_tag = [$client->user->email, $client->user->first_name, $client->coach->user->name, $start_time, $start, $session];
                        $to          = str_replace($tag, $replace_tag, $email_template['to']);
                        $subject     = str_replace($tag, $replace_tag, $email_template['subject']);
                        $content     = str_replace($tag, $replace_tag, $email_template['content']);
                        Mail::send(
                            'email_template.comman', ['content' => $content], function ($message) use ($to, $subject) {
                                $message->to($to)
                                    ->subject($subject);
                                $bcc = explode(',', config('srtpl.bccmail'));
                                if (!empty($bcc)) {
                                    $message->bcc($bcc);
                                }
                            });
                    } else {
                        Mail::send(
                            'email_template.client_schedule_booked', ['start_at' => $scheduledetail->start_datetime, 'client' => $client], function ($message) use ($client) {
                                $message->to($client->user->email)
                                    ->subject("Congratulations" . " " . Auth::user()->first_name . "  Your coaching session with " . $client->coach->user->name . " " . "has been confirmed");
                                $bcc = explode(',', config('srtpl.bccmail'));
                                if (!empty($bcc)) {
                                    $message->bcc($bcc);
                                }
                            });
                    }
                    $email_template_coach = EmailTemplate::where('slug', 'user-book-session-coach')->first()->toArray();

                    if (isset($email_template_coach)) {
                        if ($client->coach->user->timezone != '') {
                            $coach_user_timezone = ($client->coach->user->timezone);
                        } else {
                            $coach_user_timezone = 'UTC';
                        }

                        $booked_slot_val = $model->booked_slot;

                        if ($booked_slot_val > 0) {
                            if ($booked_slot_val == 1) {
                                $start_time = Carbon::createFromFormat('Y-m-d H:i:s', $model->coach_schedule->start_datetime)->setTimezone($coach_user_timezone)->format('Y-m-d');
                                $start      = Carbon::createFromFormat('Y-m-d H:i:s', $model->coach_schedule->start_datetime)->setTimezone($coach_user_timezone)->format('H:i');
                                $end_time   = Carbon::parse($start)->setTimezone($coach_user_timezone)->addMinutes(20)->format('Y-m-d');
                                $total_time = $start_time . "-" . $end_time;
                            } elseif ($booked_slot_val == 2) {
                                $start_time = Carbon::parse($model->coach_schedule->start_datetime)->setTimezone($coach_user_timezone)->addMinutes(20)->format('Y-m-d');
                                $start      = Carbon::parse($model->coach_schedule->start_datetime)->setTimezone($coach_user_timezone)->addMinutes(20)->format('H:i');
                                $end_time   = Carbon::parse($start)->setTimezone($coach_user_timezone)->addMinutes(20)->format('Y-m-d');
                                $total_time = $start_time . "-" . $end_time;
                            } elseif ($booked_slot_val == 3) {
                                $start_time = Carbon::parse($model->coach_schedule->start_datetime)->setTimezone($coach_user_timezone)->addMinutes(40)->format('Y-m-d');
                                $start      = Carbon::parse($model->coach_schedule->start_datetime)->setTimezone($coach_user_timezone)->addMinutes(40)->format('H:i');
                                $end_time   = Carbon::createFromFormat('Y-m-d H:i:s', $model->coach_schedule->end_datetime)->setTimezone($coach_user_timezone)->format('Y-m-d');
                                $total_time = $start_time . "-" . $end_time;
                            }
                        }

//                       $date = Carbon::createFromFormat('Y-m-d H:i:s', $scheduledetail->start_datetime, $client->coach->user->timezone)->format('Y-m-d');
//                       $start_time = Carbon::createFromFormat('Y-m-d H:i:s', $scheduledetail->start_datetime, $client->coach->user->timezone)->format('H:i');

                       $meeting_type=$model->meeting_type;

                       $session='Free Session';

                       $tag = ['[client-name]','[coach-name]','[client-name]','[date]','[time]','[format]','[session]'];

                        $replace_tag = [$client->user->name, $client->coach->user->name, $client->user->name, $start_time, $start, $meeting_type, $session];
                        $email_template_coach['to'] = str_replace('[coach-email]',$client->coach->user->email,$email_template_coach['to']);

                        $to          = str_replace($tag, $replace_tag, $email_template_coach['to']);
                        // print_r($to);
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
                    } // over here
                }
                DB::commit();
                Flash::success("Schedule Booked Successfully!");
                return response()->json([
                    'success' => 'true',
                    'message' => 'Schedule Booked Successfully!',
                    'url'     => route('clients.dashboard.coaching'),
                ]);
                return redirect()->route('clients.dashboard.coaching');
            } catch (Exception $e) {
                DB::rollback();
                \Log::info($e->getMessage());
                Flash::error("Schedule is not booked due to some internal error!");
                return redirect()->route('bookfreeschedule.index');
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function cancelBookedSchedule(Request $request)
    {
        $schedule              = Carbon::createFromFormat('Y-m-d H:i:s', $request->get('schedule'), 'UTC');
        $user                  = Auth::user();
        $booking_cancel_within = 0;
        if (\Config::get('srtpl.settings')['cancel_booking_within']) {
            $booking_cancel_within = \Config::get('srtpl.settings')['cancel_booking_within'];
        }
        $timezone = 'UTC';
        if (isset($user->timezone)) {
            $timezone = $user->timezone;
        }
        $now      = Carbon::now($timezone);
        $hourDiff = $now->diffInHours(Carbon::createFromFormat('Y-m-d H:i:s', $request->get('schedule'), $timezone));
        if (!empty($booking_cancel_within) && $hourDiff <= intval($booking_cancel_within)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => 'false',
                    'message' => 'You cannot cancel the booking within ' . $booking_cancel_within . ' hour(s) from the schedule time.',
                ]);
            }
        }
        try {
            \DB::beginTransaction();
            $booked_schedule_id = $request->get('booked_schedule_id');
            $booked_user_id     = $request->get('booked_user_id');
            $updated            = CoachSceduleBooked::where('coach_schedules_id', $booked_schedule_id)->where('booked_user_id', $booked_user_id)->update(['session_status' => 'cancelled', 'cancelled_by_user_id' => $user->id, 'cancel_reson' => $request->get('reson')]);
            if ($updated) {
                $credit_history_arr = [
                    'user_id'          => $booked_user_id, // clients table  user_id field...
                    'object_id'        => $booked_schedule_id,
                    'object_type'      => 'coach_schedules_booked',
                    'transaction_type' => 'plus',
                    'credit_score'     => config('srtpl.credit'),
                ];
                //fire event..
                event(new CreditHistoryEvent($credit_history_arr));
                $client = Client::with(['user', 'coach.user'])->where('user_id', $booked_user_id)->first();
                if (!empty($client)) {
                    $text             = ($client->user->name) . ' has cancelled the appointment that was scheduled at ' . Carbon::createFromFormat('Y-m-d H:i:s', $schedule, $client->user->timezone)->format('D dS F Y \a\t h:i a');
                    $notification_arr = [
                        'text'        => $text,
                        'receiver_id' => [$client->coach->user->id],
                    ];
                    //fire event..
                    event(new NotificationEvent($notification_arr));
                    $client_credit = $client->credits + config('srtpl.credit');
                    $client->update(['credits' => $client_credit]);
                }
                $email_template = EmailTemplate::where('slug', 'user-canceled-a-session')->first()->toArray();
                if (isset($email_template)) {
                    $booking_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $schedule, $client->coach->user->timezone)->format('m/d/Y H:i:s');
                    $tag               = ['[client-email]', '[first-name]', '[coach-name]', '[booking-date-time]', '[client-name]', '[reason]'];
                    $replace_tag       = [$client->user->email, $client->user->first_name, $client->coach->user->name, $booking_date_time, $client->user->name, $request->get('reson')];
                    $to                = str_replace($tag, $replace_tag, $email_template['to']);
                    $subject           = str_replace($tag, $replace_tag, $email_template['subject']);
                    $content           = str_replace($tag, $replace_tag, $email_template['content']);
                    Mail::send(
                        'email_template.comman', ['content' => $content], function ($message) use ($to, $subject) {
                            $message->to($to)
                                ->subject($subject);
                            $bcc = explode(',', config('srtpl.bccmail'));
                            if (!empty($bcc)) {
                                $message->bcc($bcc);
                            }
                        });
                } else {
                    Mail::send(
                        'email_template.cancel_schedule_client', ['schedule' => $schedule, 'client' => $client, 'reson' => $request->get('reson')], function ($message) use ($client) {
                            $message->to($client->coach->user->email)
                                ->subject("Your Session has been cancelled");
                            $bcc = explode(',', config('srtpl.bccmail'));
                            if (!empty($bcc)) {
                                $message->bcc($bcc);
                            }
                        });

                }
                // all admin alert schedule cancel by client
                $all_admin = User::where('user_type', 'user')->get();
                foreach ($all_admin as $admin) {
                    $admin_name      = $admin->name;
                    $admin_email     = $admin->email;
                    $timezone_config = config('app.timezone');
                    $schedule        = Carbon::createFromFormat('Y-m-d H:i:s', $request->get('schedule'), $client->coach->user->timezone)->setTimezone($timezone_config)->format('m/d/Y H:i:s');
                    Mail::send(
                        'email_template.cancel_schedule_admin_alert', ['schedule' => $schedule, 'client' => $client->coach->user->name, 'reson' => $request->get('reson'), 'admin_name' => $admin_name], function ($message) use ($admin_email) {
                            $message->to($admin_email)
                                ->subject("Schedule is cancel by client");
                            $bcc = explode(',', config('srtpl.bccmail'));
                            if (!empty($bcc)) {
                                $message->bcc($bcc);
                            }
                        });
                }
                \DB::commit();
            } else {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => 'false',
                        'message' => 'You can\'t cancel the booking of the schedule.',
                    ]);
                }
            }
        } catch (Exception $e) {
            \DB::rollback();
        }
        if ($request->ajax()) {
            return response()->json([
                'success'       => 'true',
                'message'       => 'Your booking is cancelled successfully, and credit refunded to your account.',
                'final_credits' => $client_credit,
            ]);
        }
    }
    public function getReson($id)
    {
        return view('allsessions.cancel', ['id' => $id]);
    }
    public function saveReson($id, Request $request)
    {
        $result = $this->validate($request, [
            'cancel_reson' => "required",
        ]);
        try {
            \DB::beginTransaction();
            $user    = Auth::id();
            $updated = CoachSceduleBooked::where('id', $id)->update(['session_status' => 'cancelled', 'cancelled_by_user_id' => $user, 'cancel_reson' => $request->get('cancel_reson')]);
            if ($updated) {
                $data               = CoachSceduleBooked::where('id', $id)->with('coach_schedule')->first();
                $schedule           = Carbon::createFromFormat('Y-m-d H:i:s', $data->coach_schedule->start_datetime, 'UTC');
                $booked_user_id     = $data->booked_user_id;
                $booked_schedule_id = $data->coach_schedules_id;
                $credit_history_arr = [
                    'user_id'          => $booked_user_id, // clients table  user_id field...
                    'object_id'        => $booked_schedule_id,
                    'object_type'      => 'coach_schedules_booked',
                    'transaction_type' => 'plus',
                    'credit_score'     => config('srtpl.credit'),
                ];
                //fire event..
                event(new CreditHistoryEvent($credit_history_arr));
                $client = Client::with(['user', 'coach.user'])->where('user_id', $booked_user_id)->first();
                if (!empty($client)) {
                    $text             = ($client->user->name) . ' has cancelled the appointment that was scheduled at ' . Carbon::createFromFormat('Y-m-d H:i:s', $schedule, $client->user->timezone)->format('D dS F Y \a\t h:i a');
                    $notification_arr = [
                        'text'        => $text,
                        'receiver_id' => [$client->coach->user->id],
                    ];
                    //fire event..
                    event(new NotificationEvent($notification_arr));
                    $client_credit = $client->credits + config('srtpl.credit');
                    $client->update(['credits' => $client_credit]);
                    $email_template = EmailTemplate::where('slug', 'coach-canceled-a-session')->first()->toArray();
                    if (isset($email_template)) {
                        $booking_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $schedule, $client->user->timezone)->format('m/d/Y H:i:s');
                        $tag               = ['[client-email]', '[first-name]', '[coach-name]', '[booking-date-time]', '[reason]'];
                        $replace_tag       = [$client->user->email, $client->user->first_name, $client->coach->user->name, $booking_date_time, $request->get('cancel_reson')];
                        $to                = str_replace($tag, $replace_tag, $email_template['to']);
                        $subject           = str_replace($tag, $replace_tag, $email_template['subject']);
                        $content           = str_replace($tag, $replace_tag, $email_template['content']);
                        Mail::send(
                            'email_template.comman', ['content' => $content], function ($message) use ($to, $subject) {
                                $message->to($to)
                                    ->subject($subject);
                                $bcc = explode(',', config('srtpl.bccmail'));
                                if (!empty($bcc)) {
                                    $message->bcc($bcc);
                                }
                            });
                    } else {
                        Mail::send(
                            'email_template.cancel_schedule', ['schedule' => $schedule, 'client' => $client, 'reson' => $request->get('cancel_reson')], function ($message) use ($client) {
                                $message->to($client->user->email)
                                    ->subject("Important information regarding your upcoming coaching session");
                                $bcc = explode(',', config('srtpl.bccmail'));
                                if (!empty($bcc)) {
                                    $message->bcc($bcc);
                                }
                            });
                    }
                }
                //send mail all admin cancel booking by coach
                $all_admin = User::where('user_type', 'user')->get();
                foreach ($all_admin as $admin) {
                    $admin_name      = $admin->name;
                    $admin_email     = $admin->email;
                    $timezone_config = config('app.timezone');
                    $schedule        = Carbon::createFromFormat('Y-m-d H:i:s', $data->coach_schedule->start_datetime, $client->coach->user->timezone)->setTimezone($timezone_config)->format('m/d/Y H:i:s');
                    Mail::send(
                        'email_template.cancel_schedule_admin_alert', ['schedule' => $schedule, 'client' => $client->user->name, 'reson' => $request->get('cancel_reson'), 'admin_name' => $admin_name], function ($message) use ($admin_email) {
                            $message->to($admin_email)
                                ->subject("Schedule is cancel by coach");
                            $bcc = explode(',', config('srtpl.bccmail'));
                            if (!empty($bcc)) {
                                $message->bcc($bcc);
                            }
                        });
                }
                \DB::commit();
            } else {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => 'false',
                        'message' => 'You can\'t cancel the booking of the schedule.',
                    ]);
                }
                Flash::error('You can\'t cancel the booking of the schedule.');
                return redirect()->route('allsession');
            }
        } catch (Exception $e) {
            \DB::rollback();
        }
    }
    public function canclesession(Request $request, $param = array())
    {
        $booked = CoachSceduleBooked::where('id', $request->get('bookedid'));
        $booked->update(array('deleted' => '1', 'cancel_reson' => $request->get('reason')));
        return $request;
    }

}
