<?php

namespace App\Http\Controllers;
use AppHelper;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\CoachSceduleBooked;
use App\Models\CoachNote;
use Illuminate\Support\Facades\Crypt;
use Auth;
use App;
use PDF;
use DB;
use Flash;
use Carbon\Carbon;
use App\Models\UserModuleProgress;
use PayPal;
use App\Models\EmailTemplate;
use Mail;
use App\Models\Setting;


class ClientDetailController extends Controller {
    public function __construct() {
        parent::__construct();
        $this->middleware('auth');

        $this->title = trans('comman.clients');
        view()->share('title', $this->title);
        //$this->ajax = new AjaxController();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $client_id) {
        $client_id = Crypt::decryptString($client_id);
        $coach_id = Auth::id();
        $client = Client::with('user')->where('user_id', $client_id)->first();
        //dd($client);
        $client_activity = Client::with(['user.latest_module' => function($query) {
                    $query->wherePivot('completed_at', '!=', '');
                }, 'user.module_progress' => function($query) {
                    $query->wherePivot('completed_at', '!=', '');
                }])->where('user_id', $client_id)->first();
        // dd($client_activity);
        // dump($client_activity->toArray());
        view()->share('client', $client);
        $completed = UserModuleProgress::with('module_excercise','modules')->where('user_id',  $client_id)->get();

        view()->share('complete',$completed);

        $object = App::make('App\Http\Controllers\ModuleController');

        //dd($modules);
        view()->share('client_activity', $client_activity);
        $coach_notes = App::make('App\Http\Controllers\CoachNoteController');
        $filter['client_id'] = array(
            'value' => $client_id,
            'operator' => '=',
        );
        $client_details = Client::with('user','user.credit_card_detail','user.credit_history','user.credit_history.coach_booked_schedule','user.credit_history.coach_booked_schedule.coach_schedule','coach')->where('user_id', $client_id)->first();
        view()->share('client_details', $client_details);
        view()->share('coach_notes', $coach_notes->get_index($filter));
        // dump($this->getCoachingSessions($client_id)->toArray());
        view()->share('coaching_sessions', $this->getCoachingSessions($client_id));
        view()->share('upcoming_coaching_sessions', $this->getUpcomingCoachingSessions($client_id));
        $coach_cancle_session=CoachSceduleBooked::withoutGlobalScopes()->with('coach_schedule')->where('booked_user_id',$client_id)->where('deleted','1')->get();
        view()->share('coach_cancle_session', $coach_cancle_session);
        return view('coaches.dashboard.client_detail.view_client');
    }
    public function downloadCoachFeedback(Request $request, $module_id, $client_id,$excercise_id) {
        $client_id = Crypt::decryptString($client_id);
        $module_id = Crypt::decryptString($module_id);
        $excercise_id=Crypt::decryptString($excercise_id);

        $object = App::make('App\Http\Controllers\ClientProgramModulesController');
        $client_module_exercises = $object->getSubmittedExerciseFeedback($module_id, $client_id,$excercise_id)->get();
        $client_module = $object->getSubmittedModuleFeedback($module_id, $client_id,$excercise_id)->get();
        $total_exercise = count($client_module_exercises);
        // dump($client_module_exercises);

        $pdf = PDF::loadView('coaches.dashboard.client_detail.download_feedback', ['total_exercise' => $total_exercise, 'client_module_exercises' => $client_module_exercises, 'module_id' => $module_id,'excercise_id'=>$excercise_id, 'theme' => 'limitless.pdf', 'title' => 'Download Feedback','client_module'=>$client_module]);
        $pdf->setPaper('a4');
        $pdf->setOrientation('portrait');
        $pdf->setOption('margin-top', 15);
        // $pdf->setOption('margin-right', 15);
        $pdf->setOption('margin-bottom', 15);
        // $pdf->setOption('margin-left', 15);
        $pdf->setOption('header-right', '');
        return $pdf->stream('client-'. $client_id .'-module-'. $module_id .'.pdf');
    }
    public function getCoachingSessions($client_id)
    {
        $bookedSchedule = CoachSceduleBooked::with(['client.user', 'coach_schedule','completed_session'])
            ->where('booked_user_id', $client_id)
            ->has('coach_schedule', '>', 0)
            ->get();
        return $bookedSchedule;
    }
    public function getUpcomingCoachingSessions($client_id)
    {
        $today = Carbon::now()->format('Y-m-d');
        $bookedSchedule = CoachSceduleBooked::with(['client','client.user','client.user.credit_history', 'coach_schedule' => function($query){ $today = Carbon::now()->format('Y-m-d'); $query->where('start_datetime','>=',$today)->orderBy('start_datetime','ASC'); },'completed_session'])
            ->where('booked_user_id', $client_id)
            ->where('session_status',null)
            ->has('coach_schedule', '>', 0)

            ->get();
         return $bookedSchedule;
    }
    public function update(Request $request,$id)
    {
        $user = User::findOrFail($id);
        $client=Client::where('user_id',$id)->first();

        $extra_rules = array(
            'email' => [
                'required',
                'max:190',
                Rule::unique('users')->ignore($user->id)->where(function ($query) {
                    $query->where('deleted', '0');
                }),
            ],
        );
        if ($request->has('password')) {
            $extra_rules['password'] = 'required|min:8|regex:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]+$/|confirmed';
        }
        if($request['timezone']=="")
        {
            $extra_rules['timezone'] = 'required';
        }
        $userFields = array();
        $clientFields = array();
        $userFields = [
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'name' => $request['first_name'] . ' ' . $request['last_name'],
            'email' => $request['email'],
            'timezone' => $request['timezone'],
            'status' => $request['status'],
        ];
        // Do we need to update the password as well?
        if ($request->has('password')) {
            $userFields['password'] = bcrypt($request['password']);
        }
        $clientFields = [
            'user_id' => $user->id,
            'coach_id' => $request['coach_id'],
            'program_id' => $request['program_id'],
            'module_restriction'=>$request['module_restriction'],
            ];

        if($request['status']=='in_active')
        {
            $user = User::with('client','client.coach','client.program')->findOrFail($id);

            if($user->addedby="self")
            {

                if($user->status=='active')
                {

                    if(!empty($user))
                    {
                        if($user->paypal_start_date!=NULL)
                        {
                            $data =
                                [
                                    'ACTION'  => 'Canceled'
                                ];
                            $profileid=$user->stripe_sub_id;
                            $provider           = PayPal::setProvider('express_checkout');
                            $response = $provider->cancelRecurringPaymentsProfile($profileid);
                        }
                        else
                        {
                            if($user->stripe_sub_id!=NULL)
                            {
                                $profileid=$user->stripe_sub_id;
                                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

                                try {
                                    $sub = \Stripe\Subscription::retrieve($profileid);
                                    $response=$sub->cancel();
                                } catch (\Stripe\Error\InvalidRequest $e) {
                                    $response='';
                                      // Invalid parameters were supplied to Stripe's API
                                }

                            }
                        }
                        $email_template = EmailTemplate::where('slug', 'user-churns')->first()->toArray();

                        if (isset($email_template)) {
                            $tag         = ['[client-name]', '[client-first-name]','[program-name]', '[coach-name]'];
                            $coach_email=$user->client->coach->user->email;
                            $program=$user->client->program->program_name;
                            $replace_tag = [$user->name,$user->first_name, $program, $user->client->coach->user->name];

                            $to          = str_replace($tag, $replace_tag, $coach_email);
                            $subject     = str_replace($tag, $replace_tag, $email_template['subject']);
                            $content     = str_replace($tag, $replace_tag, $email_template['content']);
                            Mail::send(
                                'email_template.comman', ['content' => $content], function ($message) use ($to, $subject) {
                                    $message->to($to)
                                        ->subject($subject);
                                    $bcc = explode(',', config('srtpl.bccmail'));
                                    if (!empty($bcc)) {
                                        $message->bcc($bcc);
                                    }
                                });

                            //added this so Daithi's mailchimp can be updated.
                            $setting = Setting::where('name','admin_email')->first();
                            $adminemail = $setting->value;
                            $useremail = $user->email;
                            Mail::send(
                                'email_template.comman', ['content' => $content], function ($message) use ($to, $subject, $adminemail, $useremail) {
                                    $message->to($adminemail)
                                        ->subject($subject);
                                    $bcc = explode(',', config('srtpl.bccmail'));
                                    if (!empty($bcc)) {
                                        $message->bcc($bcc);
                                    }
                                });
                        }
                        $email_template = EmailTemplate::where('slug', 'user-requests-cancellation')->first()->toArray();

                        if (isset($email_template)) {
                            $tag         = ['[client-name]', '[client-name]'];
                            $client_email=$user->email;

                            $replace_tag = [$user->first_name,$user->first_name];

                            $to          = str_replace($tag, $replace_tag, $client_email);
                            $subject     = str_replace($tag, $replace_tag, $email_template['subject']);
                            $content     = str_replace($tag, $replace_tag, $email_template['content']);
                            Mail::send(
                                'email_template.comman', ['content' => $content], function ($message) use ($to, $subject) {
                                    $message->to($to)
                                        ->subject($subject);
                                    $bcc = explode(',', config('srtpl.bccmail'));
                                    if (!empty($bcc)) {
                                        $message->bcc($bcc);
                                    }
                                });
                        }
                    }
                }
            }

        }
        if(!is_null($client))
        {
            $client->update($clientFields);
        }
        if(!is_null($user))
        {
            $user->update($userFields);
        }

        if ($request->get('save_exit')) {
            return redirect()->route('clients.index');
        } else {
            return redirect()->route('clients.show',Crypt::encryptString($id));
        }
    }
    public function updatenote(Request $request,$id)
    {
        $input = $request->all();

        if(isset($input['addcoachnote']) && !empty($input['addcoachnote']))
        {
            //$coach_note = CoachNote::find($key);
             CoachNote::create(array('client_id'=>$id,'note'=>$input['addcoachnote']));
        }
        if(isset($input['coachnote']) && count($input['coachnote']) > 0){
            foreach ($input['coachnote'] as $key => $value) {
                $coach_note = CoachNote::find($key);
                if(!is_null($coach_note)){
                    $coach_note->update(array('note'=>$value));
                }
            }
        }
        $client= Client::where('user_id',$id);
        $clientFields= ['admin_note'=>$request['adminnote']];
         if(!is_null($clientFields)){
           $client->update(array('admin_note'=>$request['adminnote']));
          }
        if ($request->get('save_exit')) {
            return redirect()->route('clients.index');
        } else {
            $r=$input['route'];
            return redirect($r);
        }
    }
    public function downloadCoachmoduleFeedback(Request $request, $module_id, $client_id,$excercise_id)
    {
        $client_id = Crypt::decryptString($client_id);
        $module_id = Crypt::decryptString($module_id);
        $excercise_id=Crypt::decryptString($excercise_id);

        $object = App::make('App\Http\Controllers\ClientProgramModulesController');
        $client_module_exercises = $object->getSubmittedExerciseFeedback($module_id, $client_id,$excercise_id)->get();
        $client_module = $object->getSubmittedModuleFeedback($module_id, $client_id,$excercise_id)->get();

        $total_exercise = count($client_module_exercises);
        $user= Auth::user();
        if($user->user_type=='client')
        {
            $pdf = PDF::loadView('coaches.dashboard.client_detail.download_client_module_feedback', ['total_exercise' => $total_exercise, 'client_module_exercises' => $client_module_exercises, 'module_id' => $module_id,'excercise_id'=>$excercise_id, 'theme' => 'limitless.pdf', 'title' => 'Download Feedback','client_module'=>$client_module]);

        }
        else
        {
            $pdf = PDF::loadView('coaches.dashboard.client_detail.download_module_feedback', ['total_exercise' => $total_exercise, 'client_module_exercises' => $client_module_exercises, 'module_id' => $module_id,'excercise_id'=>$excercise_id, 'theme' => 'limitless.pdf', 'title' => 'Download Feedback','client_module'=>$client_module]);
        }

        $pdf->setPaper('a4');
        $pdf->setOrientation('portrait');
        $pdf->setOption('margin-top', 15);
        // $pdf->setOption('margin-right', 15);
        $pdf->setOption('margin-bottom', 15);
        // $pdf->setOption('margin-left', 15);
        $pdf->setOption('header-right', '');
        return $pdf->stream('client-'. $client_id .'-module-'. $module_id .'.pdf');
    }
}