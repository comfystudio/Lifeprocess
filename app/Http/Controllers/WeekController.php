<?php

namespace App\Http\Controllers;

use App\Models\Week;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class WeekController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function __construct() {

		parent::__construct();
		$this->middleware('auth');
		$this->middleware('check_for_permission.access:schedule.create', ['only' => ['create', 'store']]);
		$this->middleware('check_for_permission.access:schedule.view', ['only' => ['index', 'show']]);
		$this->middleware('check_for_permission.access:schedule.delete', ['only' => ['destroy']]);
		$this->title = "Coach Schedule";
		view()->share('title', $this->title);
	}

	public function index() {
		$user = Auth::user();
		$schedule = Week::where("week.deleted", "0")
			->where('created_user_id', '=', $user->id)
			->get(['id', 'start_datetime', 'end_datetime']);
		return view('schedule.index', compact('schedule'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {

	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {


		$user_id = Auth::id();
		$starttime = $request->get('start');
		$endtime = $request->get('end');
		$last = date('Y-m-t H:i:s', strtotime($starttime));
		$week_start = Carbon::parse($starttime)->weekOfMonth;
		$week_end = Carbon::parse($last)->weekOfMonth;
 		$total = $week_end - $week_start;
		$next_start_date=array($starttime);
 		$next_end_date=array($endtime);
		for($i=0;$i<$total;$i++){

 			$add = Carbon::parse($next_start_date[$i])->addDays(7);
 			$add_last = Carbon::parse($next_end_date[$i])->addDays(7);
 			array_push($next_start_date,$add);
 			array_push($next_end_date,$add_last);
		}
 		foreach ($next_start_date as $key => $value) {
		$input = [
			'created_user_id' => $user_id,
			'start_datetime' => $next_start_date[$key],
			'end_datetime' => $next_end_date[$key],

		];
		//dump($input);
		$model = Week::create($input);
	};

		if ($request->ajax()) {
			return response()->json([
				'success' => 'save',
				'message' => 'Schedule added Successfully!',
			]);
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
	public function destroy(Request $request, $id) {
		$model = Week::find($id);
		if ($model) {
			$dependency = $model->deleteValidate($id);
			if (!$dependency) {
				$model->deleted = '1';
				$model->save();
				if ($request->ajax()) {
					return response()->json([
						'success' => 'Delete',
						'message' => 'Schedule Deleted Successfully!',
					]);
				}

			} else {
				if ($request->ajax()) {
					return response()->json([
						'success' => 'Not Delete',
						'message' => 'This Schedule is Booked By client So not deleted!',
					]);
				}

			}

		}

	}
	public function getCalenderTotalSession()
	{
		$user = Auth::user();
		//$cur_user = Auth::user();
		$user_timezone = User::where('id',$user->id)->get()->toArray();
		$coach_timezone = $user_timezone[0]['timezone'];
		$current_date = Carbon::now()->format('Y-m-d');
		$schedule = Week::where("coach_schedules.deleted", "0")
			->where('created_user_id', '=', $user->id)
			->where(\DB::raw("DATE_FORMAT(start_datetime,'%Y-%m-%d')"),'>=',$current_date)
			->select('start_datetime', DB::raw('count(*) as total_scedule'),DB::raw('count(coach_schedules_booked.coach_schedules_id) as total_booked_scedule'),DB::raw('count(coach_schedules_booked.session_status) as total_cancel'))
			->Leftjoin('coach_schedules_booked','coach_schedules.id','=','coach_schedules_booked.coach_schedules_id')->groupBy(DB::raw("DATE_FORMAT(start_datetime,'%Y/%m/%d')"))
			->get()
			->toArray();
		//$schedule['timezone']=$coach_timezone;
		return $schedule;
	}
	public function getusertimezone()
	{
		$user = Auth::user();
		//$cur_user = Auth::user();
		$user_timezone = User::where('id',$user->id)->get()->toArray();
		$coach_timezone = $user_timezone[0]['timezone'];
		$current_date = Carbon::now()->format('Y-m-d');
		//$schedule['timezone']=$coach_timezone;
		return $coach_timezone;
	}
}