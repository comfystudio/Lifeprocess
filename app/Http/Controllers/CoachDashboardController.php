<?php

namespace App\Http\Controllers;

use App;
use App\Models\Client;
use App\Models\Coach;
use App\Models\UserModuleProgress;
use App\Models\OtherCoachFeedbackList;
use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CoachSchedule;
use App\Models\CoachSceduleBooked;
use PayPal;
use PaypalMassPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\CoachTransactionHistory;

class CoachDashboardController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->title = "Coach Dashboard";
		$this->module_title = "Welcome to the coaching area";
	}

	/**
	 * Show the coach application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function index() {
		if (Auth::user()->user_type != 'coach' && Auth::user()->user_type != 'read-only-coach') {
			return redirect()->route('login');
		}
		view()->share('title', $this->title);
		view()->share('module_title', $this->module_title);
		$other_coach_modules = OtherCoachFeedbackList::with(['coach' => function($query){
			$query->where('coaches.available_for_review','no');
		},'clients.user.module_progress' => function ($query) {
			$query->where('reviewed_user_id',Auth::id())->orWhere('reviewed_user_id','0')->where('user_module_progresses.completed_at', '!=', '')->orWhere('user_module_progresses.status', 'unlock')
				->orderBy('user_module_progresses.completed_at', 'DESC')
				->orderBy('user_module_progresses.status')
				->distinct();
		}, 'clients' => function ($query) {
			$query->has('user.module_progress', '>', 0);
		}])->where('proxy_coach_id', Auth::id())->get();
		view()->share('other_coach_modules', $other_coach_modules);
		$module_submitted = Coach::with(['clients.user.module_progress_excercise' => function ($query) {
			$query->where('user_module_progresses.completed_at', '!=', '')->orWhere('user_module_progresses.status', 'unlock')
				->orderBy('user_module_progresses.completed_at','ASC')
				->orderBy('user_module_progresses.status')
				->distinct();
		}, 'clients' => function ($query) {
			$query->has('user.module_progress_excercise', '>', 0);
		}],'clients.user.module_progress_excercise.module_exercises')
		->where('user_id', Auth::id())->first();


		$id=Auth::id();

		$module_submitted=UserModuleProgress::with(['modules','module_excercise','submittedBy'  => function($query){
			$query->where('users.status','active');
		    },'submittedBy.module_progress_excercise','submittedBy.client.coach' => function($query) use($id){
			$query->where('coaches.user_id',$id);
		    }])
		    ->where('user_module_progresses.completed_at', '!=', '')
		    ->orWhere('user_module_progresses.status', 'unlock')
		    ->orderBy('user_module_progresses.completed_at','ASC')
		    ->get();

        view()->share('new_module_submitted', $module_submitted);

        //dd($module_submitted);
       	/*--------------------ra----------------------*/
		// check the maximum review submit to payout
		$module_submitted = Coach::with(['clients.user.module_progress_excercise' => function ($query) {
			$query->where('user_module_progresses.completed_at', '!=', '')->orWhere('user_module_progresses.status', 'unlock')
				->orderBy('user_module_progresses.completed_at','ASC')
				->orderBy('user_module_progresses.status')
				->distinct();
		}, 'clients' => function ($query) {
			$query->has('user.module_progress_excercise', '>', 0);
		}],'clients.user.module_progress_excercise.module_exercises')
		->where('user_id', Auth::id())->first();

		view()->share('module_submitted', $module_submitted);

				if (!empty($module_submitted)) {
			$max_module_review = Client::with(['user.module_progress' => function ($query) {
				$currentMonth = Carbon::now()->format('F');
				$query->where(DB::raw('MONTHNAME(reviewed_at)'), $currentMonth);
			}])->where('coach_id', $module_submitted->id)->whereHas('user.module_progress')
				->get();

			// prepare array to get the total reviewed module for the current month...
			$total_reviews_in_month = [];
			foreach ($max_module_review as $key => $value) {
				$total_reviews_in_month[$value->id] = (!empty($value->user)) ? count($value->user->module_progress) : 0;
			}
			view()->share('total_reviews_in_month', $total_reviews_in_month);
			// dump($total_reviews_in_month);
		}
		$review_perBilling_cycle = 0;
		if (Cache::get('settings')['review_per_billing_cycle']) {
			$review_perBilling_cycle = Cache::get('settings')['review_per_billing_cycle'];
		}
		view()->share('review_perBilling_cycle', $review_perBilling_cycle);
		/*------------------------------------------*/
		$upcoming_sessions = App::make('App\Http\Controllers\AllSessionController');
		$upcoming_sessions = $upcoming_sessions->get_index($limit = 5, array());

		//dd($upcoming_sessions);
		view()->share('allsession', $upcoming_sessions);
        $user = Auth::user();
        view()->share('user_type', $user->user_type);
        $today = Carbon::now()->format('Y-m-d');

   	    $bookedSchedule = CoachSchedule::with(['coachschedulebooked' => function ($query) {
					$query->whereNull('session_status');
				},'coachschedulebooked.user'=>function($query){ $query->where('status','active'); }])->where('created_user_id','=',Auth::id())->where('start_datetime','>=',$today)->orderBy('start_datetime','DESC')->get();

        view()->share('upcoming_coach_sessions',$bookedSchedule);

		$coach_credit_threshold = 0;
		if (Cache::get('settings')['coach_credit_threshold']) {
			$coach_credit_threshold = Cache::get('settings')['coach_credit_threshold'];
		}
		view()->share('coach_credit_threshold', $coach_credit_threshold);

		$reviewed_within_last_days = 0;
		if (Cache::get('settings')['reviewed_within_last_days']) {
			$reviewed_within_last_days = Cache::get('settings')['reviewed_within_last_days'];
		}
		$reviewed_date_start = Carbon::now()->subDays($reviewed_within_last_days)->format('y-m-d H:i:s');
		$module_review_within_date = UserModuleProgress::with(['modules','module_excercise', 'submittedBy.client', 'reviewedBy' => function ($query) {
			$query->where('id', Auth::id());
		}])->where('reviewed_at', '>=', $reviewed_date_start)
			->where('reviewed_at', '<=', Carbon::now()->format('y-m-d H:i:s'))->orderBy('reviewed_at', 'ASC')->get();
		view()->share('module_review_within_date', $module_review_within_date);
		//
		$coach = Coach::with(['user.scheduled_sessions' => function ($query) {
			$query->where(DB::raw('YEARWEEK(`start_datetime`, 1)'), '=', DB::raw('YEARWEEK(CURDATE(), 1)'));
		}])->where('user_id', Auth::id())->first();
        if (Auth::user()->user_type == 'coach') {
            $slots_added_this_week = count($coach->user->scheduled_sessions);
            $slots_remaining = ($coach->min_slots_availability_per_week - $slots_added_this_week);
            $slots_remaining = ($slots_remaining > 0) ? $slots_remaining : 0;
            view()->share('slots_remaining', $slots_remaining);
            view()->share('slots_added', $slots_added_this_week);
        }
		return view('coaches.dashboard.coach-dashboard');
	}
	public function payment()
	{
		$id = Auth::id();
        $name = User::where('id',$id)->first()->name;
        $transaction=App::make('App\Http\Controllers\ClientTransactionController');
        $data = $transaction->getindex($id);
        view()->share('id',$id);
        view()->share('coach_name',$name);
        view()->share('data',$data);
        view()->share('count',1);
        view()->share('title',trans('comman.transaction_report'));
		return view('coaches.dashboard.coach-payment');
	}
	public function payment_withdraw(Request $request)
	{
		$id = Auth::id();
		$coach = Coach::where('user_id',$id)->first();
		$total_balance=$coach->balance;
		$left_balance=$total_balance-$request->amount;
		$receivers = array(
		  0 => array(
		    'ReceiverEmail' => $coach->paypal_id,
		    'Amount'        => $request->amount,
		    'UniqueId'      => "id_001",
		    'Note'          => "Send Coach"),
		);
		$response   = PaypalMassPayment::executeMassPay('Some Subject', $receivers);
		$input['user_id'] = $id;
        $input['deleted'] = 0;
        $input['transaction_amount']=$request->amount;
        $input['transaction_type'] = 'minus';
        $input['transaction_detail']='Withdraw';
        $input['last_payment_date']=Carbon::now();
        $model = CoachTransactionHistory::create($input);
        Coach::where('user_id', $id)->update(['balance' => $left_balance]);
		return redirect()->route('coach.transaction',['id'=> Crypt::encryptString(Auth::user()->id)]);
		//return view('coaches.dashboard.coach-payment');
	}
	public function payment_withdraw_success()
	{
		session()->flash('error', "Payment Withdraw Successfully");
		return view('coaches.dashboard.coach-payment');
	}
	public function payment_withdraw_cancel()
	{
		session()->flash('error', "Payment Cancel");
		return view('coaches.dashboard.coach-payment');
	}

 }
