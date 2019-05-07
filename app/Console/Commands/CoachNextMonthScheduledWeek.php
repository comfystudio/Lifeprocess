<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Week;
use App\Models\CoachSchedule;
use App\Models\User;
use App\Models\Coach;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use DB;


class CoachNextMonthScheduledWeek extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:coach-next-month-scheduled-Week';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Coach default week of every month added by cron';

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
       $coach=Coach::where('deleted','0')->get();
       foreach($coach as $coach)
       {
            $model = DB::table('default_week')->select('day','start_time','end_time','created_user_id')->where('created_user_id',$coach->user_id)->where('deleted','0')->get();

                foreach ($model as $model_day) {
                $now = Carbon::now();

                $month = $now->month;
                $nextmonth=$now->addMonth();

                $start = new Carbon('first day of next month');
                $end = new Carbon('last day of next month');

                $day=$model_day->day;
                $stime=$model_day->start_time;
                $etime=$model_day->end_time;

                $begin  = new \DateTime($start);
                $end    = new \DateTime($end);
                $input=array();
                while ($begin <= $end) // Loop will work begin to the end date
                {

                    if($begin->format("D") == "Sun" && $model_day->day=='Sunday') //Check that the day is Sunday here
                    {
                        $date=$begin->format("Y-m-d");

                        $scom=Carbon::parse($date)->format('Y-m-d')." ".$stime;

                        $ecom=Carbon::parse($date)->format('Y-m-d')." ".$etime;
                        $schedule = CoachSchedule::where("coach_schedules.deleted", "0")->where('created_user_id', '=', $model_day->created_user_id)->where('start_datetime', '=', $scom)->get();

                        if(count($schedule)==0)
                        {

                            $input = [
                                'created_user_id' => $model_day->created_user_id,
                                'start_datetime' => $scom,
                                'end_datetime' => $ecom
                            ];

                            $week = DB::table('week')->insert($input);
                            $insertedId = $week;
                            $input_coach = [
                            'created_user_id' => $model_day->created_user_id,
                            'start_datetime' => $scom,
                            'end_datetime' => $ecom,
                            'week_id' => $insertedId,
                            ];
                            DB::table('coach_schedules')->insert($input_coach);
                        }
                    }
                    if($begin->format("D") == "Mon" && $model_day->day=='Monday') //Check that the day is Sunday here
                    {
                       $date=$begin->format("Y-m-d");

                        $scom=Carbon::parse($date)->format('Y-m-d')." ".$stime;
                        $ecom=Carbon::parse($date)->format('Y-m-d')." ".$etime;
                        $schedule = CoachSchedule::where("coach_schedules.deleted", "0")->where('created_user_id', '=', $model_day->created_user_id)->where('start_datetime', '=', $scom)->get();
                        if(count($schedule)==0)
                        {
                            $input = [
                                'created_user_id' => $model_day->created_user_id,
                                'start_datetime' => $scom,
                                'end_datetime' => $ecom
                            ];
                            $week = DB::table('week')->insert($input);
                            $insertedId = $week;
                            $input_coach = [
                            'created_user_id' => $model_day->created_user_id,
                            'start_datetime' => $scom,
                            'end_datetime' => $ecom,
                            'week_id' => $insertedId,
                            ];
                            DB::table('coach_schedules')->insert($input_coach);
                        }
                    }
                    if($begin->format("D") == "Tue" && $model_day->day=='Tuesday') //Check that the day is Sunday here
                    {
                       $date=$begin->format("Y-m-d");

                        $scom=Carbon::parse($date)->format('Y-m-d')." ".$stime;
                        $ecom=Carbon::parse($date)->format('Y-m-d')." ".$etime;
                        $schedule = CoachSchedule::where("coach_schedules.deleted", "0")->where('created_user_id', '=', $model_day->created_user_id)->where('start_datetime', '=', $scom)->get();
                        if(count($schedule)==0)
                        {
                            $input = [
                                'created_user_id' => $model_day->created_user_id,
                                'start_datetime' => $scom,
                                'end_datetime' => $ecom
                            ];
                            $week = DB::table('week')->insert($input);
                            $insertedId = $week;
                            $input_coach = [
                            'created_user_id' => $model_day->created_user_id,
                            'start_datetime' =>$scom,
                            'end_datetime' => $ecom,
                            'week_id' => $insertedId,
                            ];
                            DB::table('coach_schedules')->insert($input_coach);
                        }
                    }
                    if($begin->format("D") == "Wed" && $model_day->day=='Wednesday') //Check that the day is Sunday here
                    {
                       $date=$begin->format("Y-m-d");
                       // print_r($begin['date']);
                        $scom=Carbon::parse($date)->format('Y-m-d')." ".$stime;
                        $ecom=Carbon::parse($date)->format('Y-m-d')." ".$etime;
                        $schedule = CoachSchedule::where("coach_schedules.deleted", "0")->where('created_user_id', '=', $model_day->created_user_id)->where('start_datetime', '=', $scom)->get();
                        if(count($schedule)==0)
                        {
                            $input = [
                                'created_user_id' => $model_day->created_user_id,
                                'start_datetime' => $scom,
                                'end_datetime' => $ecom
                            ];
                            $week = DB::table('week')->insert($input);
                            $insertedId = $week;
                            $input_coach = [
                            'created_user_id' => $model_day->created_user_id,
                             'start_datetime' =>$scom,
                            'end_datetime' => $ecom,
                            'week_id' => $insertedId,
                            ];
                            DB::table('coach_schedules')->insert($input_coach);
                        }
                    }
                    if($begin->format("D") == "Thu" && $model_day->day=='Thursday') //Check that the day is Sunday here
                    {
                       $date=$begin->format("Y-m-d");
                       // print_r($begin['date']);
                        $scom=Carbon::parse($date)->format('Y-m-d')." ".$stime;
                        $ecom=Carbon::parse($date)->format('Y-m-d')." ".$etime;
                        $schedule = CoachSchedule::where("coach_schedules.deleted", "0")->where('created_user_id', '=', $model_day->created_user_id)->where('start_datetime', '=', $scom)->get();
                        if(count($schedule)==0)
                        {
                            $input = [
                                'created_user_id' => $model_day->created_user_id,
                                'start_datetime' => $scom,
                                'end_datetime' => $ecom
                            ];
                            $week = DB::table('week')->insert($input);
                            $insertedId = $week;
                            $input_coach = [
                            'created_user_id' => $model_day->created_user_id,
                            'start_datetime' =>$scom,
                            'end_datetime' => $ecom,
                            'week_id' => $insertedId,
                            ];
                            DB::table('coach_schedules')->insert($input_coach);
                        }
                    }
                    if($begin->format("D") == "Fri" && $model_day->day=='Friday') //Check that the day is Sunday here
                    {
                       $date=$begin->format("Y-m-d");
                       // print_r($begin['date']);
                        $scom=Carbon::parse($date)->format('Y-m-d')." ".$stime;
                        $ecom=Carbon::parse($date)->format('Y-m-d')." ".$etime;
                        $schedule = CoachSchedule::where("coach_schedules.deleted", "0")->where('created_user_id', '=', $model_day->created_user_id)->where('start_datetime', '=', $scom)->get();
                        if(count($schedule)==0)
                        {
                            $input = [
                                'created_user_id' => $model_day->created_user_id,
                                'start_datetime' => $scom,
                                'end_datetime' => $ecom
                            ];
                            $week = DB::table('week')->insert($input);
                            $insertedId = $week;
                            $input_coach = [
                            'created_user_id' => $model_day->created_user_id,
                            'start_datetime' =>$scom,
                            'end_datetime' => $ecom,
                            'week_id' => $insertedId,
                            ];
                            DB::table('coach_schedules')->insert($input_coach);
                        }
                    }
                    if($begin->format("D") == "Sat" && $model_day->day=='Saturday') //Check that the day is Sunday here
                    {
                       $date=$begin->format("Y-m-d");
                       // print_r($begin['date']);
                        $scom=Carbon::parse($date)->format('Y-m-d')." ".$stime;
                        $ecom=Carbon::parse($date)->format('Y-m-d')." ".$etime;
                        $schedule = CoachSchedule::where("coach_schedules.deleted", "0")->where('created_user_id', '=', $model_day->created_user_id)->where('start_datetime', '=', $scom)->get();
                        if(count($schedule)==0)
                        {
                            $input = [
                                'created_user_id' => $model_day->created_user_id,
                                'start_datetime' => $scom,
                                'end_datetime' => $ecom
                            ];
                            $week = DB::table('week')->insert($input);
                            $insertedId = $week;
                            $input_coach = [
                            'created_user_id' => $model_day->created_user_id,
                             'start_datetime' =>$scom,
                            'end_datetime' => $ecom,
                            'week_id' => $insertedId,
                            ];
                            DB::table('coach_schedules')->insert($input_coach);
                        }
                    }
                    $begin->modify('+1 day');
                }

            }
        }
            //exit;
            //


    }
}
