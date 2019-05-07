<?php

namespace App\Http\Controllers;

use App\Models\Coach;
use App\Models\CoachSchedule;
use App\Models\User;
use App\Models\Week;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CoachWeekController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('check_for_permission.access:schedule.create', ['only' => ['create', 'store']]);
        $this->middleware('check_for_permission.access:schedule.view', ['only' => ['index', 'show']]);
        $this->middleware('check_for_permission.access:schedule.delete', ['only' => ['destroy']]);
        $this->title = "My Week";
        view()->share('title', $this->title);
    }

    public function index()
    {

        $user           = Auth::user();
        $min_slot       = Coach::where('user_id', $user->id)->get()->toarray();
        $coach_min_slot = $min_slot[0]['min_slots_availability_per_week'];
        $user_timezone  = User::where('id', $user->id)->get()->toArray();
        $coach_timezone = $user_timezone[0]['timezone'];

        $start_week = Carbon::now()->startOfWeek()->subDays(1)->format('Y-m-d H:i:s');
        $end_week   = Carbon::now()->endOfWeek()->subDays(1)->format('Y-m-d H:i:s');
        $schedule   = Week::where("week.deleted", "0")
            ->where('created_user_id', '=', $user->id)
            ->where('start_datetime', '>=', $start_week)
            ->where('end_datetime', '<=', $end_week)
            ->get(['id', 'start_datetime', 'end_datetime']);
        return view('week.index', compact('schedule', 'coach_timezone', 'coach_min_slot'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //print_r($_REQUEST); exit;
        $user            = Auth::user();
        $user_timezone   = User::where('id', $user->id)->get()->toArray();
        $coach_timezone  = $user_timezone[0]['timezone'];
        $user_id         = Auth::id();
        $starttime       = $request->get('start');
        $endtime         = $request->get('end');
        $last            = date('Y-m-t H:i:s', strtotime($starttime));
        $last            = date('Y-m-t H:i:s', strtotime('+2 month', strtotime($last)));
        $week_start      = Carbon::parse($starttime)->weekOfMonth;
        $week_day        = Carbon::parse($starttime)->format('l');
        $week_start_time = new Carbon($starttime, $coach_timezone);
        $week_start_time->tz('utc');
        $week_start_time = Carbon::parse($week_start_time)->format('H:i:s');
        $week_end_time   = new Carbon($endtime, $coach_timezone);
        $week_end_time->tz('utc');
        $week_end_time = Carbon::parse($week_end_time)->format('H:i:s');
        $week_end      = Carbon::parse($last)->weekOfMonth;
        $total         = $week_end - $week_start;
        $day           = 24 * 3600;
        $from          = strtotime($starttime);
        $to            = strtotime($last) + $day;
        $diff          = abs($to - $from);
        $weeks         = floor($diff / $day / 7);
        $out           = array();
        if ($weeks) {
            $out[] = "$weeks Week" . ($weeks > 1 ? 's' : '');
        }

        $total      = implode(', ', $out);
        $input_week = [
            'created_user_id' => $user_id,
            'day'             => $week_day,
            'start_time'      => $week_start_time,
            'end_time'        => $week_end_time,
        ];
        $model = DB::table('default_week')->insert($input_week);

        $week_start_date = array($starttime);
        $week_end_date   = array($endtime);
        for ($i = 0; $i < $total; $i++) {

            $add      = Carbon::parse($week_start_date[$i])->addDays(7);
            $add_last = Carbon::parse($week_end_date[$i])->addDays(7);

            if ($add <= $last) {

                array_push($week_start_date, $add);
                array_push($week_end_date, $add_last);

            }
        }
        $current = Carbon::now($coach_timezone);
        foreach ($week_start_date as $key => $value) {

            $input = [
                'created_user_id' => $user_id,
                'start_datetime'  => $week_start_date[$key],
                'end_datetime'    => $week_end_date[$key],

            ];
            $week       = Week::create($input);
            $insertedId = $week->id;

            $input_coach = [
                'created_user_id' => $user_id,
                'start_datetime'  => $week_start_date[$key],
                'end_datetime'    => $week_end_date[$key],
                'week_id'         => $insertedId,
            ];

            $model = CoachSchedule::create($input_coach);

        }
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
    public function destroy(Request $request, $id)
    {
        $data=Week::where('id',$id)->first();
        $date=$data->start_datetime;
        $day = date('l', strtotime($date));
        $time=date('H:i:s', strtotime($date));
        $user            = Auth::user();
        $user_timezone   = User::where('id', $user->id)->get()->toArray();
        $coach_timezone  = $user_timezone[0]['timezone'];
        $week_start_time = new Carbon($time, $coach_timezone);
        $week_start_time->tz('utc');
        $time = Carbon::parse($week_start_time)->format('H:i:s');

        $dweek = DB::table('default_week')->where('created_user_id',Auth::id())->where('day',$day)->where('start_time',$time)->get()->first();

        DB::table('default_week')->where('id', $dweek->id)->update(['deleted' => '1']);
        $model      = Week::find($id);
        $last       = date('Y-m-t H:i:s', strtotime($model->start_datetime));
        $week_start = Carbon::parse($model->start_datetime)->weekOfMonth;
        $last       = date('Y-m-t H:i:s', strtotime('+1 month', strtotime($last)));
        $week_end   = Carbon::parse($last)->weekOfMonth;
        $total      = $week_end - $week_start;
        $day        = 24 * 3600;
        $from       = strtotime($model->start_datetime);
        $to         = strtotime($last) + $day;
        $diff       = abs($to - $from);
        $weeks      = floor($diff / $day / 7);
        $out        = array();
        if ($weeks) {
            $out[] = "$weeks Week" . ($weeks > 1 ? 's' : '');
        }

        $total     = implode(', ', $out);
        $delete_id = $id - 1;
        for ($i = 0; $i <= $total; $i++) {
            $model      = Week::where('id', $delete_id + 1)->delete();
            $update_abc = CoachSchedule::where('status', 'available')->where('week_id', $delete_id + 1)->delete();
            $delete_id  = $delete_id + 1;
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => 'Delete',
                'message' => 'Schedule Deleted Successfully!',
            ]);
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
