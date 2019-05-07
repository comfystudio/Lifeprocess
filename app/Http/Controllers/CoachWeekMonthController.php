<?php

namespace App\Http\Controllers;

use App\Models\CoachSchedule;
use App\Models\User;
use App\Models\Coach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class CoachWeekMonthController extends Controller {
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
		$this->title = "Adjust Schedule";
		view()->share('title', $this->title);
	}

	public function index() {
		$user = Auth::user();
		$min_slot = Coach::where('user_id',$user->id)->get()->toarray();
		$coach_min_slot = $min_slot[0]['min_slots_availability_per_week'];
		$user_timezone = User::where('id',$user->id)->get()->toArray();
		$coach_timezone = $user_timezone[0]['timezone'];
		$schedule = CoachSchedule::where("coach_schedules.deleted", "0")
			->with(['coachschedulebooked' => function ($query) {
				// $query->whereNull('session_status');
			}])
			->where('created_user_id', '=', $user->id)
			->get(['id', 'start_datetime', 'end_datetime']);
		return view('adjust_schedule.index', compact('schedule','coach_timezone','coach_min_slot'));
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


			$input = [
				'created_user_id' => $user_id,
				'start_datetime' => $starttime,
				'end_datetime' => $endtime,

			];
			$model = CoachSchedule::create($input);
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
		$model = CoachSchedule::find($id);
		if ($model) {
			$dependency = $model->deleteValidate($id);
			if (!$dependency) {
				$model->deleted = '1';
				$model->save();
				if ($request->ajax()) {
					return response()->json([
						'success' => 'Delete',
						'message' => 'Scedule Deleted Successfully!',
					]);
				}

			} else {
				if ($request->ajax()) {
					return response()->json([
						'success' => 'Not Delete',
						'message' => 'This Scedule is Booked By client So not deleted!',
					]);
				}

			}

		}

	}
	public function getCalenderTotalSession()
	{
		$user = Auth::user();
		$current_date = Carbon::now()->format('Y-m-d');
		$schedule = CoachSchedule::where("coach_schedules.deleted", "0")
			->where('created_user_id', '=', $user->id)
			->where(\DB::raw("DATE_FORMAT(start_datetime,'%Y-%m-%d')"),'>=',$current_date)
			->select('start_datetime', DB::raw('count(*) as total_scedule'),DB::raw('count(coach_schedules_booked.coach_schedules_id) as total_booked_scedule'),DB::raw('count(coach_schedules_booked.session_status) as total_cancel'))
			->Leftjoin('coach_schedules_booked','coach_schedules.id','=','coach_schedules_booked.coach_schedules_id')->groupBy(DB::raw("DATE_FORMAT(start_datetime,'%Y/%m/%d')"))
			->get()
			->toArray();
		return $schedule;
	}
}
