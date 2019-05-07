<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\User;
use App\Models\Meeting;
use Carbon\Carbon;
use App\Models\CoachSchedule;
use App\Models\CoachSceduleBooked;
use App\Models\Coach;
use Fused\Zoom\Zoom;
use DB;
use Illuminate\Support\Facades\Log;

class createmeeting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:createmeeting';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create a meeting chat';

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

        $users = User::with('coach')->where('user_type', 'coach')->get();
        foreach ($users as $value)
        {
            $currenttime =  Carbon::now()->format('Y-m-d H:i:s');
            $coach=Coach::where('user_id',$value->id)->first()->toArray();
            $bookedSchedule = CoachSchedule::with(['coachschedulebooked' => function ($query) {
                $query->whereNull('session_status');
            }])->where('created_user_id','=',$value->id)->where('deleted','0')->where('start_datetime','>=',$currenttime)->get();

            if(isset($bookedSchedule) && !empty($bookedSchedule))
            {

                foreach ($bookedSchedule as $booked)
                {

                    $coachschedule=CoachSceduleBooked::with('client')->where('coach_schedules_id',$booked->id)->where('deleted','0')->get();

                    foreach ($coachschedule as $schedule)
                    {

                        if(!empty($value->coach->api_key))
                        {
                            $start_time=$booked->start_datetime;
                            $end_datetime=$booked->end_datetime;
                            $booked_for=$booked->booked_for;

                            $currentDate = strtotime($start_time);
                            $futureDate = $currentDate-(60*10);
                            $start_datetime = date("Y-m-d H:i:s", $futureDate);

                            if($booked_for=='f')
                            {
                                if($schedule->booked_slot=='1')
                                {
                                   $end_datetime = Carbon::parse($start_time)->addMinutes(20)->format('Y-m-d H:i:s');
                                }
                                if($schedule->booked_slot=='2')
                                {
                                    $start_datetime = Carbon::parse($start_datetime)->addMinutes(20)->format('Y-m-d H:i:s');
                                    $end_datetime = Carbon::parse($start_time)->addMinutes(40)->format('Y-m-d H:i:s');
                                }
                                if($schedule->booked_slot=='3')
                                {
                                    $start_datetime = Carbon::parse($start_datetime)->addMinutes(40)->format('Y-m-d H:i:s');
                                    $end_datetime = $booked->end_datetime;
                                }

                            }
                            else
                            {
                                $end_datetime=$booked->end_datetime;
                            }

                            if($start_datetime>=$currenttime)
                            {

                                $data = array_merge([
                                'key' => $value->coach->api_key,
                                'secret' => $value->coach->api_secret,
                                ]);

                                $getuser = array_merge([
                                'api_key' => $value->coach->api_key,
                                'api_secret' => $value->coach->api_secret,
                                'email' => $value->coach->zoom_email,
                                'login_type' => '100'
                                ]);

                                $action='v1/user/getbyemail';

                                $foo = new Zoom($data);

                                $request=$foo->sendRequest($action,$getuser);

                                if(!isset($request->error))
                                {
                                    //
                                    $k=Meeting::where('coach_schedule_id',$booked->id)->where('start_datetime',$start_datetime)->first();

//                                    $total=count($k);
                                    if(isset($k) && !empty($k)){
                                        $total = $k->count();
                                    }else{
                                        $total = 0;
                                    }


//                                    if($total==0 && empty($total) || $booked_for=='f')
                                    if($total==0 && empty($total))
                                    {

//                                        $topic=$schedule->client->user->name.'-'.$schedule->meeting_type;
//                                        Log::info('schedule: '.$schedule->client);
//                                        Log:info($booked ."/n".$schedule);

                                        $topic=$schedule["client"]["user"]["name"].'-'.$schedule["meeting_type"];

                                        $createAMeetingArray = array_merge([
                                        'api_key' => $value->coach->api_key,
                                        'api_secret' => $value->coach->api_secret,
                                        'host_id' => $request->id,
                                        'type'=>'2',
                                        'topic' => $topic,
                                        'start_time' => Carbon::parse($start_datetime)->format('Ymd\THis\Z')
                                        ]);
                                        $host_id=$request->id;
                                        $action='v1/meeting/create';
                                        $foo = new Zoom($data);
                                        $request=$foo->sendRequest($action,$createAMeetingArray);
                                        Coach::where('user_id',$value->id)->update(['meeting_id' => $request->id, 'host_id'=>$host_id]);

                                        $input = [
                                        'start_datetime' => $start_datetime,
                                        'end_datetime' => $end_datetime,
                                        'coach_id' => $value->id,
                                        'client_id'=> $schedule->booked_user_id,
                                        'meeting_id'=>$request->join_url,
                                        'coach_schedule_id'=>$schedule->coach_schedules_id,
                                        ];

                                        $t=DB::table('meeting')->where('start_datetime',$start_datetime)->where('client_id',$schedule->booked_user_id)->where('coach_id',$value->id)->get();
                                        //Log:info($createAMeetingArray);

                                        //$a=count($t);
                                        if(isset($t) && !empty($t)){
                                            $a = $t->count();
                                        }else{
                                            $a = 0;
                                        }

                                        if($a==0 && empty($a))
                                        {
                                            $meeting = DB::table('meeting')->insert($input);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
