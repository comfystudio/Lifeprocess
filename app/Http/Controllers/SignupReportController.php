<?php

namespace App\Http\Controllers;
use AppHelper;
use App\Models\User;
use App\Models\CoachTransactionHistory;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use PDF;

class SignupReportController extends Controller {

	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->middleware('check_for_permission.access:report.signup_report.view', ['only' => ['index']]);
		$this->title = "Signup Report";
		view()->share('title', $this->title);
	}
	public function index(Request $request)
	 {
		view()->share('title', $this->title);

		$report_data = $this->get_index();

		view()->share('SignupReport', $report_data);

		return view('signupreport.index');
	}
	public function get_index() {

		$yesterday = Carbon::now()->subDays(1)->format('Y-m-d');

        $today = Carbon::now()->format('Y-m-d');

		$total_number_of_live = User::select('user_type', DB::raw('count(*) as total'));
		$newuser = User::select('user_type', DB::raw('count(*) as new_signup'));
		$failed_transaction = User::select('user_type',DB::raw('count(coach_transaction_histories.user_id) as failed_transaction'))
				->join("coach_transaction_histories", function ($join) {
				$join->on('users.id', '=', 'coach_transaction_histories.user_id');
				$join->on("coach_transaction_histories.transaction_status", "=", DB::raw('"Failure"'));
			});
		$cancel_transaction = User::select('user_type',DB::raw('count(coach_transaction_histories.user_id) as cancel_transaction'))
				->join("coach_transaction_histories", function ($join) {
				$join->on('users.id', '=', 'coach_transaction_histories.user_id');
				$join->on("coach_transaction_histories.transaction_status", "=", DB::raw('"Cancelled"'));
			});
		$all_user = User::with('fail_transaction','cancel_transaction');
		/* date Filter */
		if (request()->get('from_date', false) && request()->get('from_date') != '__/__/____') {
			$from_date = Carbon::createFromFormat('m/d/Y',request()->get("from_date"))->format('Y/m/d');
			$total_number_of_live->where(DB::raw("DATE_FORMAT(created_at,'%Y/%m/%d')"), '>=', $from_date);
			$newuser->where(DB::raw("DATE_FORMAT(created_at,'%Y/%m/%d')"), '>=', $from_date);
			$failed_transaction->where(DB::raw("DATE_FORMAT(users.created_at,'%Y/%m/%d')"), '>=', $from_date);
			$cancel_transaction->where(DB::raw("DATE_FORMAT(users.created_at,'%Y/%m/%d')"), '>=', $from_date);
			$all_user->where(DB::raw("DATE_FORMAT(created_at,'%Y/%m/%d')"), '>=', $from_date);
		}
		if (request()->get('to_date', false) && request()->get('to_date') != '__/__/____') {
			$to_date = Carbon::createFromFormat('m/d/Y',request()->get("to_date"))->format('Y/m/d');
			$total_number_of_live->where(DB::raw("DATE_FORMAT(created_at,'%Y/%m/%d')"), '<=', $to_date);
			$newuser->where(DB::raw("DATE_FORMAT(created_at,'%Y/%m/%d')"), '<=', $to_date);
			$failed_transaction->where(DB::raw("DATE_FORMAT(users.created_at,'%Y/%m/%d')"), '<=', $to_date);
			$cancel_transaction->where(DB::raw("DATE_FORMAT(users.created_at,'%Y/%m/%d')"), '<=', $to_date);
			$all_user->where(DB::raw("DATE_FORMAT(created_at,'%Y/%m/%d')"), '<=', $to_date);
		}
		/* Active and in active */
		if(request()->get('status') == 'active' || request()->get('status') == null) {
			$total_number_of_live->where('status','active');
			$newuser->where('status','active');
			$failed_transaction->where('status','active');
			$cancel_transaction->where('status','active');
			$all_user->where('status','active')->orderBy('id', 'desc');;
		}
		if (request()->get('status') == 'inactive') {
			$total_number_of_live->where('status','in_active');
			$newuser->where('status','in_active');
			$failed_transaction->where('status','in_active');
			$cancel_transaction->where('status','in_active');
			$all_user->where('status','in_active')->orderBy('id', 'desc');
		}
		/* Group By Filtter */
		if (request()->get('group_by') == 'role' || request()->get('group_by') == null)
		{
			$total_number_of_live = $total_number_of_live->where(\DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"), '<=', $today)->groupBy('user_type')->get()->toArray();

			$newuser = $newuser->where(\DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d')"),'=', $today)->groupBy('user_type')->get()->toArray();

			$failed_transaction = $failed_transaction->groupBy('user_type')->where(\DB::raw("DATE_FORMAT(users.created_at,'%Y-%m-%d')"),'<=', $yesterday)->groupBy('user_type')->get()->toArray();

			$cancel_transaction = $cancel_transaction->where(\DB::raw("DATE_FORMAT(users.created_at,'%Y-%m-%d')"),'<=', $yesterday)->groupBy('user_type')->get()->toArray();

			$all_user = $this->revice_array($total_number_of_live,$newuser,$failed_transaction,$cancel_transaction);
		}
		if (request()->get('group_by') == 'user') {
				$all_user = $all_user->get()->toArray();
		}
		return $all_user;
	}
	private function revice_array($total_number_of_live = array(), $newuser = array(),$failed_transaction = array(),$cancel_transaction = array()) {
		$result = array();

		if (!empty($total_number_of_live)) {
			foreach ($total_number_of_live as $live_user_key => $live_user_value) {
				if (!array_key_exists($live_user_value['user_type'], $result)) {
					$result[$live_user_value['user_type']] = $live_user_value;
				} else {
					$result[$live_user_value['user_type']]['live_user'] = $live_user['live_user'];
				}
			}
		}
		if (!empty($newuser)) {
			foreach ($newuser as $newuser_key => $newuser_value) {
				if (array_key_exists($newuser_value['user_type'], $result)) {
					$result[$newuser_value['user_type']]['new_signup'] = $newuser_value['new_signup'];
				} else {
					$result[$newuser_value['user_type']] = $newuser_value;
				}
			}
		}
		if (!empty($failed_transaction)) {

			foreach ($failed_transaction as $failed_transaction_key => $failed_transaction_value){
				if (array_key_exists($failed_transaction_value['user_type'], $result)) {
					$result[$failed_transaction_value['user_type']]['failed_transaction'] = $failed_transaction_value['failed_transaction'];
				} else {
					$result[$failed_transaction_value['user_type']] = $failed_transaction_value;
				}
			}
		}
		if (!empty($cancel_transaction)) {
			foreach ($cancel_transaction as $cancel_transaction_key => $cancel_transaction_value){
				if (array_key_exists($cancel_transaction_value['user_type'], $result)) {
					$result[$cancel_transaction_value['user_type']]['cancel_transaction'] = $cancel_transaction_value['cancel_transaction'];
				} else {
					$result[$cancel_transaction_value['user_type']] = $cancel_transaction_value;
				}
			}
		}

		return $result;
	}
	public function getPDF() {
		$signupreport = $this->get_index();
		$pdf = PDF::loadView('signupreport.pdf', ['SignupReport' => $signupreport, 'theme' => 'limitless.pdf']);
		$pdf->setPaper('a4');
		$pdf->setOrientation('portrait');
		$pdf->setOption('margin-top', 5);
		$pdf->setOption('margin-right', 5);
		$pdf->setOption('margin-bottom', 5);
		$pdf->setOption('margin-left', 5);
		$pdf->setOption('header-right', '');
		return $pdf->stream();
	}

}
