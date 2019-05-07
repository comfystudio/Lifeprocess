<?php

namespace App\Http\Controllers;

use App;
use AppHelper;
use App\Models\Coach;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class ReportController extends Controller {
	protected $current_user;
	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->middleware('check_for_permission.access:clients.create', ['only' => ['create', 'store']]);
		$this->middleware('check_for_permission.access:clients.view', ['only' => ['index', 'show']]);
		$this->middleware('check_for_permission.access:clients.update', ['only' => ['edit', 'update']]);
		$this->middleware('check_for_permission.access:clients.delete', ['only' => ['destroy']]);
		$this->title = trans('comman.report');
		view()->share('timezones', get_timezone_list());
		AppHelper::path('uploads/user/');
		//$this->ajax = new AjaxController();
	}

	public function coaching_report(Request $request) {
		if (!Auth::user()->hasAccess('report.coach_report')) {
			return "Access Denied";
		}

		$object = App::make("App\Http\Controllers\CoachController");
		view()->share('coaches', $object->getCoach());

		$object = App::make("App\Http\Controllers\ProgramController")->get_index(array());
		foreach ($object as $key => $value) {
			$programs[$value->id] = $value->program_name;
		}
		view()->share('programs', $programs);
		view()->share('title', $this->title);

		$report = Coach::with([
			'totalClients',
			'userFromStatus',
			'clients.user.submittedByClientYesterday', //2.Total Number of modules submitted by clients yesterday
			'clients.user.completed_modules_yesterday', //3.Total Number of feedback on modules provided by coach yesterday
			'clients.user.submittedByClient14days',
			'clients.user.submittedByClient21days',
			'clients.user.submittedByClientBefore21days',
			'clients.user.coachschedulebooked.coach_schedule' => function ($query) {

				if (request()->get('to_date') != '__/__/____') {
					$to_date = Carbon::parse(request()->get('to_date'))->format('Y-m-d');
				} else {
					$to_date = Carbon::now()->format('Y-m-d');
				}

				if (!empty(request()->get('from_date')) && request()->get('from_date') != '__/__/____') {
					$query->where(\DB::raw("DATE_FORMAT(coach_schedules.start_datetime,'%Y-%m-%d')"), '>=', Carbon::parse(request()->get('from_date'))->format('Y-m-d'));
				}
				$query->where(\DB::raw("DATE_FORMAT(coach_schedules.end_datetime,'%Y-%m-%d')"), '<=', $to_date);
			},
			'clients.user.coachschedulenotbooked.coach_schedule' => function ($query) {
				if (request()->get('to_date') != '__/__/____') {
					$to_date = Carbon::parse(request()->get('to_date'))->format('Y-m-d');
				} else {
					$to_date = Carbon::now()->format('Y-m-d');
				}

				if (!empty(request()->get('from_date')) && request()->get('from_date') != '__/__/____') {
					$query->where(\DB::raw("DATE_FORMAT(coach_schedules.start_datetime,'%Y-%m-%d')"), '>=', Carbon::parse(request()->get('from_date'))->format('Y-m-d'));
				}
				$query->where(\DB::raw("DATE_FORMAT(coach_schedules.end_datetime,'%Y-%m-%d')"), '<=', $to_date);
			},
		]);

		if (request()->get('coach', false) && request()->get('coach') != '') {
			$report->where('coaches.user_id', request()->get('coach', false));
		}
		$report = $report->get()->toArray();

		foreach ($report as $key => $value) {
			if (!empty($value['user_from_status'])) {

				$data[$value['id']]['coach'] = $value['user_from_status']['name'];
				$data[$value['id']]['total_clients'] = !empty($value['total_clients']) ? $value['total_clients'][0]['total_clients'] : '0';

				$feedback_modules = 0;
				$waiting_feedback = 0;
				$total_submitted = 0;
				$submit1 = 0;
				$submit2 = 0;
				$submit3 = 0;
				$complete = 0;
				$uncomplete = 0;

				foreach ($value['clients'] as $k => $v) {
					if ($value['id'] == $v['coach_id']) {

						$feedback_modules += (!empty($v['user']['completed_modules_yesterday'])) ? $v['user']['completed_modules_yesterday'][0]['total_feedback'] : 0;

						$total_submitted += (!empty($v['user']['submitted_by_client_yesterday'])) ? $v['user']['submitted_by_client_yesterday'][0]['total_submitted'] : 0;
						$submit1 += (!empty($v['user']['submitted_by_client14days'])) ? $v['user']['submitted_by_client14days'][0]['total_submitted1'] : 0;
						$submit2 += (!empty($v['user']['submitted_by_client21days'])) ? $v['user']['submitted_by_client21days'][0]['total_submitted2'] : 0;
						$submit3 += (!empty($v['user']['submitted_by_client_before21days'])) ? $v['user']['submitted_by_client_before21days'][0]['total_submitted3'] : 0;
						$complete += (!empty($v['user']['coachschedulebooked'])) ? (!empty($v['user']['coachschedulebooked'][0]['coach_schedule']) ? $v['user']['coachschedulebooked'][0]['completed'] : 0) : 0;
						$uncomplete += (!empty($v['user']['coachschedulenotbooked'])) ? (!empty($v['user']['coachschedulenotbooked'][0]['coach_schedule']) ? $v['user']['coachschedulenotbooked'][0]['uncompleted'] : 0) : 0;
						$data[$value['id']]['feedback_modules'] = $feedback_modules;
						$data[$value['id']]['yesterday_modules'] = $total_submitted;
						$data[$value['id']]['submit1'] = $submit1;
						$data[$value['id']]['submit2'] = $submit2;
						$data[$value['id']]['submit3'] = $submit3;
						$data[$value['id']]['complete'] = $complete;
						$data[$value['id']]['uncomplete'] = $uncomplete;
					}
				}

			}
		}

		return view()->make('report.coaching_report', compact('data'));
	}

}
