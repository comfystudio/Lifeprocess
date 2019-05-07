<?php

namespace App\Http\Controllers;
use App;
use AppHelper;
use App\Events\CoachTransactionHistoryEvent;
use App\Models\Coach;
use App\Models\User;
use App\Models\CoachSceduleBooked;
use App\Models\CoachSchedule;
use App\Models\CompletedCoachingSession;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\EmailTemplate;
use Mail;
use PDF;
use Cache;

class AllSessionController extends Controller {
	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->middleware('check_for_permission.access:all_session.view', ['only' => ['index', 'show']]);
		$this->title = "All Session";
		view()->share('title', $this->title);
	}
	public function index(Request $request) {
		$user = Auth::user();
		if ($user->user_type == "coach") {
			$object = App::make("App\Http\Controllers\CoachController");
			view()->share('clients', $object->get_coach_clients());
		} else {
			$object = App::make("App\Http\Controllers\CoachController");
			view()->share('coaches', $object->getCoach());
			$object = App::make("App\Http\Controllers\ClientController");
			view()->share('clients', $object->getClient());
		}
		view()->share('title', $this->title);
		view()->share('user_type', $user->user_type);
		 //dump($this->get_index(array()));

		view()->share('allsession', $this->get_index(array()));
		return view('allsessions.index');
	}
	// load the form to complete scheduled session...
	public function create(Request $request) {
		$client_booked_schedule_id = $request->get('client_booked_schedule_id');
		$bookedSession = CoachSceduleBooked::where('id', $client_booked_schedule_id)->with('coach_schedule', 'client.user')->first();
		// dump($bookedSession);
		$sessionTime = isset($bookedSession->coach_schedule) ? Carbon::createFromFormat('Y-m-d H:i:s', $bookedSession->coach_schedule->start_datetime)->format('D dS F Y \a\t h:i a') : '';
		view()->share('sessionTime', $sessionTime);
		view()->share('booked_session', $bookedSession);
		view()->share('contact_methods', config('srtpl.contact_methods'));
		return view('allsessions.complete-session');
	}
	public function store(Request $request) {
		$inputs = AppHelper::getTrimmedData($request->all());
		$coach_id = $inputs['coach_id'];
		Validator::make($request->all(), [
			'contact_methods' => 'required',
			'contact_detail' => 'required',
		])->validate();
		// dump($inputs);
		try {
			DB::beginTransaction();
			CompletedCoachingSession::create(array_except($inputs, 'session_status') + ['completed_at' => Carbon::now()]);
			$bookedSchedule = CoachSceduleBooked::where('id', $inputs['booked_schedule_id'])->with('client.user')->first();
			$bookedSchedule->session_status = $inputs['session_status'];

			$bookedSchedule->save();
			$session_type = $bookedSchedule->booked_for;
			$user = User::where('id',$bookedSchedule->client->user->id)->update(['is_free_session_complete' => 'y']);

			$coach = Coach::where('user_id', $coach_id)->with('user')->first();
			if($session_type=='f'){
				$one_hour_session_rate = 0;
	            if (!empty($coach)) {
	                $one_hour_session_rate = $coach->free_20_min_session;
	                $type = "Free";
	            }
			}
			elseif($session_type=='s'){
				$one_hour_session_rate = 0;
				if (!empty($coach)) {
					$one_hour_session_rate = $coach->one_hour_session;
					$type = "One hour";
				}
			}
			elseif($session_type=='g'){
				$one_hour_session_rate = 0;
				if (!empty($coach)) {
					$one_hour_session_rate = $coach->graduate_session;
					$type = "Gratuate";
				}
			}

			// fire event of coach transaction history...
			//array of the transaction event
			$coach_schedule = CoachSchedule::where('id', $bookedSchedule->coach_schedules_id)->first();
			$transation_history_arr = [
				'user_id' => $coach_id,
				'object_id' => $bookedSchedule->id,
				'object_type' => 'coach_schedules_booked',
				'transaction_type' => 'plus',
				'transaction_amount' => $one_hour_session_rate,
				'transaction_detail' => 'Has Completed '.$type.' session <strong>' . Carbon::createFromFormat('Y-m-d H:i:s', $coach_schedule->start_datetime)->format('D dS F Y \a\t h:i a') . '</strong> with client <strong>' . $bookedSchedule->client->user->name . '</strong>',
			];
			//fire event..
			event(new CoachTransactionHistoryEvent($transation_history_arr));
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
			DB::commit();
			if (request()->ajax()) {
				return [
					'status' => 'success',
					'message' => 'Coach\'s '.$type.' session completed successfully.',
					'data' => [
						'booked_schedule_id' => $inputs['booked_schedule_id'],
						'content' => '<label class="label bg-success"> Completed </label>',
					],
				];
			}
			Flash::success('Coach\'s '.$type.' session completed successfully.');
			return redirect()->route('allsession');
		} catch (Exception $e) {
			DB::rollback();
		}
	}
	public function get_index($limit = null, $filters = array(), $sort_order = array()) {

		$user = Auth::user();
		$models = CoachSceduleBooked::withoutGlobalScopes()->with(['user','user.transactionHistories', 'problem_with_session','completed_session']);
		$models->select(array(
			"coach_schedules_booked.*",
			'coach_schedules.start_datetime'
		))->join('coach_schedules', function ($join) {
			$join->on('coach_schedules_booked.coach_schedules_id', '=', 'coach_schedules.id');
		});

		// $models->whereHas('coach_schedule', function ($q) {
		// 		$q->orderBy('start_datetime','ASC');
		// });



		if ($user->user_type == 'coach') {
			$models->whereHas('coach_schedule', function ($q) {
				$user_id = Auth::id();
				$q->where('created_user_id', $user_id);
			});
		}
		if (request()->get('coach', false)) {
			$models->whereHas('coach_schedule.user', function ($q) {
				$q->where('id', '=', request()->get("coach"));
			});
		}
		if (request()->get('client', false)) {
			$models->whereHas('user', function ($q) {
				$q->where('id', '=', request()->get("client"));
			});
		}
		if (request()->get('from_date', false) && request()->get('from_date') != '__/__/____') {
			$models->whereHas('coach_schedule', function ($q) {
				$q->where(\DB::raw("DATE(start_datetime)"), '>=', Carbon::createFromFormat('m/d/Y', request()->get("from_date"))->format('Y-m-d'));
			});
		}
		if (request()->get('to_date', false) && request()->get('to_date') != '__/__/____') {
			$models->whereHas('coach_schedule', function ($q) {
				$q->where(\DB::raw("DATE(start_datetime)"), '<=', Carbon::createFromFormat('m/d/Y', request()->get("to_date"))->format('Y-m-d'));
			});
		}


		if (request()->get('session_status', false)) {
			$models->where('session_status', '=', request()->get('session_status'));
		}
		if(request()->get('session')=='upcoming')
		{
			$models->whereNull('session_status');
		}
		if(request()->get('session')=='')
		{
			$models->whereNull('session_status');
		}


		if (request()->get('session', false) && request()->get('session') == 'past') {
			$models->whereHas('coach_schedule', function ($q) {
				$q->where(\DB::raw("DATE_FORMAT(start_datetime,'%m-%d-%Y')"), '<', Carbon::now()->format('m-d-Y'));
			});
		} else {
			$models->whereHas('coach_schedule', function ($q) {
				$q->where(\DB::raw("DATE_FORMAT(start_datetime,'%m-%d-%Y')"), '>=', Carbon::now()->format('m-d-Y'));
			});
		}

		if ($user->user_type == 'coach') {
			$models->whereHas('user', function ($q) {
				$q->where('status', '=', 'active');
			});
		}

		if (!empty($sort_order) && is_array($sort_order)) {
			foreach ($sort_order as $column => $direction) {
				$models->orderBy($column, $direction);
			}

		}
		$models->orderBy('coach_schedules.start_datetime', 'ASC');


		$per_page = config('srtpl.row_per_page');
        if(isset(Cache::get('settings')['per_page'])) {
            $per_page = Cache::get('settings')['per_page'];
        }
        if (!empty($limit)) {
			return $models->limit($limit)->get();
		} else {
			return $models->paginate($per_page);
		}

	}
	public function getPDF() {
		$user_type = Auth::user()->user_type;
		$allsession = $this->get_index(array());
		$counter = 0;
		$pdf = PDF::loadView('allsessions.pdf_report', ['allsession' => $allsession, 'counter' => $counter, 'theme' => 'limitless.pdf', 'user_type' => $user_type]);
		$pdf->setPaper('a4');
		$pdf->setOrientation('portrait');
		$pdf->setOption('margin-top', 10);
		$pdf->setOption('margin-right', 10);
		$pdf->setOption('margin-bottom', 10);
		$pdf->setOption('margin-left', 10);
		$pdf->setOption('header-right', '');
		return $pdf->stream('allsessions.pdf');
		//return $pdf->download("mylifestory.pdf");
	}
}
