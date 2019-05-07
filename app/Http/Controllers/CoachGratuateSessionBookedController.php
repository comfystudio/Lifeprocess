<?php

namespace App\Http\Controllers;
use App;
use App\Events\CreditHistoryEvent;
use App\Events\NotificationEvent;
use App\Models\Client;
use App\Models\User;
use App\Models\CoachGratuateSessionBooked;
use App\Models\CoachGratuateSession;
use App\Models\EmailTemplate;
use Carbon\Carbon;
use DB;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Mail;

class CoachGratuateSessionBookedController extends Controller {

	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		//$this->middleware('check_for_permission.access:book_schedule.create', ['only' => ['create', 'store']]);
		//$this->middleware('check_for_permission.access:book_schedule.view', ['only' => ['index', 'show']]);
		$this->middleware('check_coachIsAssigned', ['except' => 'cancelBookedSchedule']);
		$this->title = "Book Gratuate 20 Minutes Coach Schedule";
		view()->share('title', $this->title);
	}
	/**
	 * Display a listing of the resource.

	 *
	 * @return \Illuminate\Http\Response

	 */
	public function index(Request $request) {
		$cur_user = Auth::user();
		$user_timezone = User::where('id',$cur_user->id)->get()->toArray();
		$coach_timezone = $user_timezone[0]['timezone'];

		$user_data = User::where('id','=', Auth::id())->first();
		$gratuate_session = $user_data->is_gratuate_session_booked;
		if($gratuate_session==1){
			return redirect()->route('clients.dashboard.coaching');
		}
		$cur_user = Auth::user();
		$schedule = [];
		$client = Client::where('user_id', '=', $cur_user->id)->with('coach.user')->first(); //->coach->user->id;
		if ($request->start!='') {
					$first_day_this_month = $request['start'];
					$last_day_this_month = $request['end'];
		}
		else{
			$first_day_this_month = date('Y-m-01 00:00:00');
			$last_day_this_month  = date('Y-m-t 00:00:00');
		}
		//echo $last_day_this_month;exit;
		if ($client != null) {
			$schedule = CoachGratuateSession::where("deleted", "0")
				->where('status','=','available')
				->where('start_datetime','>=',$first_day_this_month)
				->where('end_datetime','<=',$last_day_this_month)
				->with(['coachgratuatesessionbooked' => function ($query) {
					$query->whereNull('session_status');
				}])
				->where('created_user_id', '=', $client->coach->user->id)
				->get(['id', 'start_datetime', 'end_datetime','status']);
		}
		//dd($schedule);



		if ($cur_user->user_type == 'client') {
			$client_user_timezone = $client->user->timezone;
			if($client_user_timezone ==''){
				$client_user_timezone = 'UTC';
			}
			$coach_user_timezone = ($client->coach->user->timezone);
			if($coach_user_timezone == ''){
				$coach_user_timezone = ($client->coach->user->timezone);
			}
			else{
				$coach_user_timezone = 'UTC';
			}
		}
		else{
			$coach_user_timezone = 'UTC';
		}
		//echo $coach_user_timezone;
		//echo $client_user_timezone;
		//exit;
		view()->share('coach_user_timezone', $coach_user_timezone);
		$coach_user_time = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->toDateTimeString(),$client_user_timezone)->setTimezone($coach_user_timezone);
		//echo $coach_user_time;exit;
		view()->share('coach_timezone', $coach_timezone);
		view()->share('coach_user_time', $coach_user_time->toDateTimeString());
		view()->share('coach_user_name', $client->coach->user->name);
		view()->share('client_user_timezone',$client_user_timezone);
		if($request->ajax()){
			return response()->json([
						'success' => 'true','schedule' => $schedule

					]);
		}
		return view('bookgratuateschedule.index', compact('schedule'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function renderEvent(Request $request){
		if ($request->id!='') {
					$cur_user = Auth::user();
					$user_timezone = User::where('id',$cur_user->id)->get()->toArray();
					$coach_timezone = $user_timezone[0]['timezone'];
					$cur_user = Auth::user();
					$client = Client::where('user_id', '=', $cur_user->id)->with('coach.user')->first();

					$schedule = CoachGratuateSession::where("deleted", "0")
					->where('status','=','booked')
					->where('id','=',$request->id)
					->where('created_user_id', '=', $client->coach->user->id)
					->get(['id', 'start_datetime', 'end_datetime']);
				//dd($schedule);exit;
					$booked_id = CoachGratuateSessionBooked::where("coach_schedules_id", $schedule[0]['id'])->get(['meeting_type'])->toArray();
					$meeting_type = $booked_id[0]['meeting_type'];

					$start_time = Carbon::createFromFormat('Y-m-d H:i:s',$schedule[0]['start_datetime'])->format('H:i');
					$end_time = Carbon::createFromFormat('Y-m-d H:i:s',$schedule[0]['end_datetime'])->format('H:i');
					$total_time = $start_time."-".$end_time;
					$final_date = Carbon::createFromFormat('Y-m-d H:i:s',$schedule[0]['start_datetime'])->format('Y/m/d');
					if ($cur_user->user_type == 'client') {
						$client_user_timezone = $client->user->timezone;

						if($client_user_timezone ==''){
							$client_user_timezone = 'UTC';
						}
						$coach_user_timezone = ($client->coach->user->timezone);
						if($coach_user_timezone == ''){
							$coach_user_timezone = 'UTC';
						}

					}
					view()->share('coach_user_timezone', $coach_user_timezone);
		$coach_user_time = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->toDateTimeString(),$client_user_timezone)->setTimezone($coach_user_timezone);
		//echo $coach_user_time;exit;
		view()->share('coach_timezone', $coach_timezone);
		view()->share('total_time', $total_time);
		view()->share('final_date', $final_date);
		view()->share('meeting_type', $meeting_type);



		view()->share('coach_user_time', $coach_user_time->toDateTimeString());
		view()->share('coach_user_name', $client->coach->user->name);
		view()->share('client_user_timezone',$client_user_timezone);

					return view('bookgratuateschedule.index', compact('schedule'));
				}
	}
	public function renderGratuateTimeslot(Request $request){
		if ($request->start!='') {
					$cur_user = Auth::user();
					$client = Client::where('user_id', '=', $cur_user->id)->with('coach.user')->first();
					$start = $request['start'];
					$end = $request['end'];
					$date_click = $request['date_click'];
					// $date_click = Carbon::createFromFormat('Y-m-d H:i:s', $date_click." 00:00:00",$client->user->timezone)->setTimezone('UTC');
					// $date_click = Carbon::createFromFormat('Y-m-d H:i:s',$date_click)->format('Y-m-d');
					//  echo $date_click;
					 $nextDate = date('Y-m-d', strtotime('+1 day', strtotime($date_click)));
					// echo $nextDate;exit;
					$cur_user = Auth::user();
					$client = Client::where('user_id', '=', $cur_user->id)->with('coach.user')->first();

					$schedule = CoachGratuateSession::where("deleted", "0")
					->where('status','=','available')
				->where('start_datetime','>=',$date_click." 00:00:00")
				->where('end_datetime','<=',$nextDate." 00:00:00")
				->with(['coachgratuatesessionbooked' => function ($query) {
					$query->whereNull('session_status');
				}])
				->where('created_user_id', '=', $client->coach->user->id)
				->get(['id', 'start_datetime', 'end_datetime']);
				if ($cur_user->user_type == 'client') {
			$client_user_timezone = $client->user->timezone;
			if($client_user_timezone ==''){
				$client_user_timezone = 'UTC';
			}
			$coach_user_timezone = ($client->coach->user->timezone);
			if($coach_user_timezone == ''){
				$coach_user_timezone = ($client->coach->user->timezone);
			}
			else{
				$coach_user_timezone = 'UTC';
			}
		}
		else{
			$coach_user_timezone = 'UTC';
		}
		view()->share('coach_user_timezone', $coach_user_timezone);
		$coach_user_time = Carbon::createFromFormat('Y-m-d H:i:s', Carbon::now()->toDateTimeString(),$client_user_timezone)->setTimezone($coach_user_timezone);
		//echo $coach_user_time;exit;
		view()->share('coach_user_time', $coach_user_time->toDateTimeString());
		view()->share('coach_user_name', $client->coach->user->name);
		view()->share('client_user_timezone',$client_user_timezone);

				//dd($schedule);exit;

					return response()->json([
						'success' => 'true','schedule' => $schedule

					]);
				}
	}
	public function create() {
		//
	}
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		//echo Auth::id();exit;
		$result = $this->validate($request, [
			'time-slot' => "required",

			'meeting_type'=> "required",
			],
			[
				'time-slot.required' => 'Please Select Schedule Time',
				'meeting_type.required' => 'Please Select Meeting Type'
			]
		);
		$check_credit = App::make('App\Http\Controllers\ClientController');
		$credit = $check_credit->ajaxCheckClientCredit();
		if($credit['is_available'] == 'false')
		{
			Flash::error("Sorry, you don't have enough credit to book the session.");
			return redirect()->route('bookgratuateschedule.index');
		}
		$booked_user_id = Auth::id();
		$coach_schedules_id = $request->get('coach_schedules_id');
		$meeting_type = $request->get('meeting_type');
		$scheduledetail=CoachGratuateSession::where("id",$coach_schedules_id)->first();

		if(!isset($scheduledetail))
		{		//echo "sd";exit;
				Flash::error("This schedule Recently Deleted By coach");
				return redirect()->route('bookgratuateschedule.index');
		}
		else
		{
			$input =
				[
				'meeting_type' => $meeting_type,
				'coach_schedules_id' => $coach_schedules_id,
				'booked_user_id' => $booked_user_id,
			];
			try {
				//print_r($input);exit;
				DB::beginTransaction();
				$model = CoachGratuateSessionBooked::where('coach_schedules_id', $coach_schedules_id)->where('booked_user_id', $booked_user_id)->first();
				if (!empty($model)) {
					$input = [
						'session_status' => null,
						'cancelled_by_user_id' => 0,
						'reminder_sent' => '0',
						'deleted' => '0',
						'created_at' => Carbon::now(),
						'meeting_type' => $meeting_type,
					];
					CoachGratuateSessionBooked::where('id', $model->id)->update($input);
				} else {
					$model = CoachGratuateSessionBooked::create($input);
				}
				if(!empty($model)){

					//$user = User::where('id', Auth::id())->first();
					//echo $user;exit;
					CoachGratuateSession::where('id',$coach_schedules_id)->update(['status' => 'booked']);



				}
				if (!empty($model)) {
					$client = Client::where('user_id', Auth::id())->with('coach.user')->first();
					// // event to deduct credit on session scheduled...
					// $client->update(['credits' => ($client->credits - config('srtpl.credit'))]);
					// $credit_history_arr = [
					// 	'user_id' => Auth::id(), // clients table  user_id field...
					// 	'object_id' => $model->id,
					// 	'object_type' => 'coach_schedules_booked',
					// 	'transaction_type' => 'minus',
					// 	'credit_score' => config('srtpl.credit'),
					// ];
					// //fire event..
					// event(new CreditHistoryEvent($credit_history_arr));
					$text = '<strong>' . link_to_route('client.detail', Auth::user()->name, ['client_id' => Crypt::encryptString(Auth::id())]) . '</strong> has requested an appointment.';
					$notification_arr = [
						'text' => $text,
						'receiver_id' => [$client->coach->user->id],
					];
					//fire event..
					event(new NotificationEvent($notification_arr));
					$email_template = EmailTemplate::where('slug','user-booked-a-session')->first()->toArray();
					if(isset($email_template))
					{
						$booking_date_time = Carbon::createFromFormat('Y-m-d H:i:s',$scheduledetail->start_datetime, $client->user->timezone)->format('D dS F Y \a\t h:i a');

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

//						$tag = ['[client-email]','[first-name]','[coach-name]','[booking-date-time]'];
                        $tag = ['[client-email]', '[first-name]', '[coach-name]', '[date]', '[time]', '[session]'];
                        $session='One Hour';
//						$replace_tag = [$client->user->email,$client->user->first_name,$client->coach->user->name,$booking_date_time];
                        $replace_tag = [$client->user->email, $client->user->first_name, $client->coach->user->name, $start_time, $start ,$session];

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
						'email_template.client_schedule_booked', ['start_at' => $scheduledetail->start_datetime,'client' => $client],function ($message) use($client) {
						$message->to($client->user->email)
							->subject("Congratulations"." ".Auth::user()->first_name ."  Your coaching session with ". $client->coach->user->name ." "."has been confirmed");
						$bcc = explode(',', config('srtpl.bccmail'));
						if (!empty($bcc)) {
							$message->bcc($bcc);
						}
						});
					}
				}
				DB::commit();
				Flash::success("Schedule Booked Successfully!");
				return response()->json([
						'success' => 'true',
						'message' => 'Schedule Booked Successfully!',
						'url' => route('clients.dashboard.coaching'),
					]);
				return redirect()->route('clients.dashboard.coaching');
			} catch (Exception $e) {
				DB::rollback();
				\Log::info($e->getMessage());
				Flash::error("Schedule is not booked due to some internal error!");
				return redirect()->route('bookgratuateschedule.index');
			}
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {

	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//
	}
	public function cancelBookedSchedule(Request $request) {
		$schedule = Carbon::createFromFormat('Y-m-d H:i:s', $request->get('schedule'), 'UTC');
		$user = Auth::user();
			$booking_cancel_within = 0;
			if (\Config::get('srtpl.settings')['cancel_booking_within']) {
				$booking_cancel_within = \Config::get('srtpl.settings')['cancel_booking_within'];
			}
			$timezone = 'UTC';
			if (isset($user->timezone)) {
				$timezone = $user->timezone;
			}
			$now = Carbon::now($timezone);
			// dump($now);
			// dump(Carbon::createFromFormat('Y-m-d H:i:s', $request->get('schedule'), $timezone));
			$hourDiff = $now->diffInHours(Carbon::createFromFormat('Y-m-d H:i:s', $request->get('schedule'), $timezone));
			// dump($hourDiff); exit();
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
			$booked_user_id = $request->get('booked_user_id');
			$updated = CoachSceduleBooked::where('coach_schedules_id', $booked_schedule_id)->where('booked_user_id', $booked_user_id)->update(['session_status' => 'cancelled', 'cancelled_by_user_id' => $user->id,'cancel_reson' => $request->get('reson')]);
			if ($updated) {
				$credit_history_arr = [
					'user_id' => $booked_user_id, // clients table  user_id field...
					'object_id' => $booked_schedule_id,
					'object_type' => 'coach_schedules_booked',
					'transaction_type' => 'plus',
					'credit_score' => config('srtpl.credit'),
				];
				//fire event..
				event(new CreditHistoryEvent($credit_history_arr));
				$client = Client::with(['user', 'coach.user'])->where('user_id', $booked_user_id)->first();
				if (!empty($client)) {
					$text = ($client->user->name) . ' has cancelled the appointment that was scheduled at ' . Carbon::createFromFormat('Y-m-d H:i:s', $schedule, $client->user->timezone)->format('D dS F Y \a\t h:i a');
					$notification_arr = [
						'text' => $text,
						'receiver_id' => [$client->coach->user->id],
					];
					//fire event..
					event(new NotificationEvent($notification_arr));
					$client_credit = $client->credits + config('srtpl.credit');
					$client->update(['credits' => $client_credit]);
				}
				$email_template = EmailTemplate::where('slug','user-canceled-a-session')->first()->toArray();
				if(isset($email_template))
				{
					$booking_date_time = Carbon::createFromFormat('Y-m-d H:i:s',$schedule, $client->coach->user->timezone)->format('m/d/Y H:i:s');
					$tag = ['[client-email]','[first-name]','[coach-name]','[booking-date-time]','[client-name]','[reason]'];
					$replace_tag = [$client->user->email,$client->user->first_name,$client->coach->user->name,$booking_date_time,$client->user->name,$request->get('reson')];
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
					'email_template.cancel_schedule_client', ['schedule' => $schedule,'client' => $client,'reson' => $request->get('reson')],function ($message) use($client) {
						$message->to($client->coach->user->email)
							->subject("Your Session has been cancelled");
						$bcc = explode(',', config('srtpl.bccmail'));
						if (!empty($bcc)) {
							$message->bcc($bcc);
						}
					});

				}
				// all admin alert schedule cancel by client
				$all_admin = User::where('user_type','user')->get();
				foreach($all_admin as $admin)
				{
					$admin_name = $admin->name;
					$admin_email = $admin->email;
					$timezone_config = config('app.timezone');
					$schedule = Carbon::createFromFormat('Y-m-d H:i:s',$request->get('schedule'),$client->coach->user->timezone)->setTimezone($timezone_config)->format('m/d/Y H:i:s');
					Mail::send(
					'email_template.cancel_schedule_admin_alert', ['schedule' => $schedule,'client' => $client->coach->user->name,'reson' => $request->get('reson'),'admin_name' => $admin_name],function ($message) use($admin_email) {
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
				'success' => 'true',
				'message' => 'Your booking is cancelled successfully, and credit refunded to your account.',
				'final_credits' => $client_credit,
			]);
		}
	}
	public function getReson($id)
	{
		return view('allsessions.cancel',['id' => $id]);
	}
	public function saveReson($id,Request $request)
	{
		$result = $this->validate($request, [
			'cancel_reson' => "required",
		]);
		try {
			\DB::beginTransaction();
			$user= Auth::id();
			$updated = CoachSceduleBooked::where('id', $id)->update(['session_status' => 'cancelled', 'cancelled_by_user_id' => $user,'cancel_reson' => $request->get('cancel_reson')]);
			if ($updated) {
				$data = CoachSceduleBooked::where('id',$id)->with('coach_schedule')->first();
				$schedule = Carbon::createFromFormat('Y-m-d H:i:s', $data->coach_schedule->start_datetime, 'UTC');
				$booked_user_id = $data->booked_user_id;
				$booked_schedule_id = $data->coach_schedules_id;
				$credit_history_arr = [
					'user_id' => $booked_user_id, // clients table  user_id field...
					'object_id' => $booked_schedule_id,
					'object_type' => 'coach_schedules_booked',
					'transaction_type' => 'plus',
					'credit_score' => config('srtpl.credit'),
				];
				//fire event..
				event(new CreditHistoryEvent($credit_history_arr));
				$client = Client::with(['user', 'coach.user'])->where('user_id', $booked_user_id)->first();
				if (!empty($client)) {
					$text = ($client->user->name) . ' has cancelled the appointment that was scheduled at ' . Carbon::createFromFormat('Y-m-d H:i:s', $schedule, $client->user->timezone)->format('D dS F Y \a\t h:i a');
					$notification_arr = [
						'text' => $text,
						'receiver_id' => [$client->coach->user->id],
					];
					//fire event..
					event(new NotificationEvent($notification_arr));
					$client_credit = $client->credits + config('srtpl.credit');
					$client->update(['credits' => $client_credit]);
					$email_template = EmailTemplate::where('slug','coach-canceled-a-session')->first()->toArray();
					if(isset($email_template))
					{
						$booking_date_time = Carbon::createFromFormat('Y-m-d H:i:s',$schedule, $client->user->timezone)->format('m/d/Y H:i:s');
						$tag = ['[client-email]','[first-name]','[coach-name]','[booking-date-time]','[reason]'];
						$replace_tag = [$client->user->email,$client->user->first_name,$client->coach->user->name,$booking_date_time,$request->get('cancel_reson')];
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
						'email_template.cancel_schedule', ['schedule' => $schedule,'client' => $client,'reson' => $request->get('cancel_reson')],function ($message) use($client) {
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
				$all_admin = User::where('user_type','user')->get();
				foreach($all_admin as $admin)
				{
					$admin_name = $admin->name;
					$admin_email = $admin->email;
					$timezone_config = config('app.timezone');
					$schedule = Carbon::createFromFormat('Y-m-d H:i:s',$data->coach_schedule->start_datetime,$client->coach->user->timezone)->setTimezone($timezone_config)->format('m/d/Y H:i:s');
					Mail::send(
					'email_template.cancel_schedule_admin_alert', ['schedule' => $schedule,'client' => $client->user->name,'reson' => $request->get('cancel_reson'),'admin_name' => $admin_name],function ($message) use($admin_email) {
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
	public function canclesession(Request $request, $param = array()){
		$booked= CoachSceduleBooked::where('id',$request->get('bookedid'));
		$booked->update(array('deleted'=>'1','cancel_reson'=>$request->get('reason')));
		return $request;
	 }

}
