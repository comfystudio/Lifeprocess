<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;
use App\Models\Client;
use App\Models\Coach;
use App\Models\User;
use App\Models\CoachModuleRate;
use App\Models\UserModuleExercisesProgress;
use App\Models\ModuleExercise;
use App\Models\UserModuleProgress;
use App\Models\UserModulesExercisesQuestion;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Crypt;
use App\Events\CreditHistoryEvent;
use App\Events\CoachTransactionHistoryEvent;
use Carbon\Carbon;
use Flash;
use App;
use AppHelper;
use DB;
use Mail;

class CoachRespondController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->title = "Coach Respond";
        $this->module_title = "Welcome to the coaching area";
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $module_id, $client_id,$module_exercise_id)
    {
        $client_id = Crypt::decryptString($client_id);
        $module_id = Crypt::decryptString($module_id);
        $module_exercise_id=Crypt::decryptString($module_exercise_id);
        $gratuate = User::where('id',$client_id)->first(['is_gratuate']);
        $user=User::where('id',$client_id)->first();
        view()->share('user_name', $user->name);
        //echo $gratuate->is_gratuate;exit;
        //echo "module".$module_id; echo "client_id".$client_id; echo "excercise".$module_exercise_id; exit;
        $user_id = Auth::id();
       // ->withoutGlobalScope('user_modules_exercises_questions.deleted')
        $client_module_exercises = UserModuleExercisesProgress::with(['module', 'module_exercise' => function($query){
            $query->orderBy('exercise_no');
        }, 'user_module_exercise_questions' => function($query){
            $query->orderBy('question_no');
            $query->where('parent_question_id', DB::raw('0'));
        }, 'user_module_exercise_questions.question_answer' => function($query) use($client_id) {
            $query->where('user_id', $client_id);
        }, 'user_module_exercise_questions.sub_questions' => function($query){
            $query->orderBy('question_no');
        } ,'user_module_exercise_questions.sub_questions.question_answer' => function($query) use($client_id) {
            $query->where('user_id', $client_id);
        }])
        ->select(['user_id', 'module_id', 'module_exercise_id'])
        ->where('user_id', $client_id)
        ->where('module_id', $module_id)
        ->where('is_gratuate_excersize',$gratuate->is_gratuate)
        ->where('module_exercise_id',$module_exercise_id)
        ->distinct();
        $total_exercise = count($client_module_exercises->get());
        $client_module_exercises = $client_module_exercises->paginate(1);
        //dd($client_module_exercises);
        /* Code to create array of submitted response START */
        $coachResponse = [];

        foreach ($client_module_exercises->items() AS $exercise_questions) {

            if(isset($exercise_questions->user_module_exercise_questions) && count($exercise_questions->user_module_exercise_questions) > 0) {
                // /dd($exercise_questions->user_module_exercise_questions);
                foreach ($exercise_questions->user_module_exercise_questions AS $question){

                    if(!empty($question->question_answer))
                    {
                    $coachResponse['response'][$question->question_answer->id] = $question->question_answer->coach_respond;

                        if(isset($question->sub_questions) && count($question->sub_questions)) {
                        foreach($question->sub_questions AS $sub_question) {
                            $coachResponse['response'][$sub_question->question_answer->id] = $sub_question->question_answer->coach_respond;
                        }
                        }
                    }
                }
            }
        }
        /* Code to create array of submitted response END  */

        // dump($client_module_exercises);
        view()->share('title', $this->title);
        view()->share('total_exercise', $total_exercise);
        view()->share('coachResponse', $coachResponse);
        view()->share('client_module_exercises', $client_module_exercises);
        // dump(($client_module_exercises));
        $module = head($client_module_exercises->items());
        $module_description = '';
        if(!empty($module)) {
            $module = !empty($module) ? $module : '' ;
            $module_exercise = $module->module_exercise;
            $this->module_title = $module->module->module_no . '.' . $module_exercise->exercise_no . ' - ' . $module_exercise->title;
            $module_description = $module_exercise->sort_description;
        }
        view()->share('module_title', $this->module_title);
        view()->share('module_description', $module_description);
        view()->share('module_id', $module_id);
        view()->share('client_id', $client_id);
        view()->share('module_exercise_id', $module_exercise_id);
        return view('coaches.dashboard.coach-respond');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $module_id, $client_id,$module_exercise_id)
    {
        $client_id = Crypt::decryptString($client_id);
        $module_id = Crypt::decryptString($module_id);
        $module_exercise_id=Crypt::decryptString($module_exercise_id);
        //echo $module_exercise_id; echo $module_id;echo $client_id;exit;
        // dump($request->all());

        $input = AppHelper::getTrimmedData($request->all());
        // dump($input); exit();
        $rules = [];
        $messages = [];
        // if(isset($input['response'])) {
        //     foreach ($input['response'] as $key => $value) {
        //         $rules['response.' . $key] = 'required';
        //         $messages['response.' . $key . '.required'] = 'This field is required.';
        //     }
        // }
        // $result = $this->validate($request, $rules, $messages);

        if(isset($input['response'])) {
            foreach ($input['response'] as $key => $value) {
                $response = $input['response'][$key];
                $coachreview=UserModulesExercisesQuestion::where('id', $key)->get();
                //dd($coachreview);
                $coachreviewed=UserModuleProgress::where('user_id', $client_id)->where('module_id',$module_id)->where('module_exercise_id',$module_exercise_id)->first();
                //$coachreviewed=UserModulesExercisesQuestion::where('id', $key)->first();
                if(empty($coachreviewed->status))
                {
                     UserModulesExercisesQuestion::where('id', $key)->update(['coach_respond' => $response, 'coach_respond_at' => Carbon::now()]);
                }
                else
                {


                    Flash::success('Review already submitted !');
                    return redirect()->route('coach.dashboard');
                }
            }
        }

        if(isset($input['save_exit'])){
            return redirect()->route('coach.dashboard');
        } else if (isset($input['submit_review'])) {
            // DB::transaction(function () use($client_id, $module_id) {
            try {
                DB::beginTransaction();
                $module_progress = UserModuleProgress::where('user_id', $client_id)->where('module_id', $module_id)->where('module_exercise_id',$module_exercise_id)->first();
                $client = Client::with('user')->where('user_id', $client_id)->first();
                if(!empty($module_progress)) {
                    $module_progress->update(['status' => 'reviewed', 'reviewed_at' => Carbon::now(), 'reviewed_user_id' => Auth::id()]);
                    $coach = Coach::with('user')->where('user_id', Auth::id())->first();

                    if(!empty($coach)) {
                        $module = Module::where('id', $module_id)->first();
                        $module_excercise=ModuleExercise::where('id',$module_exercise_id)->where('module_id',$module_id)->first();

                        // event to add transaction log..
                        $transation_history_arr = [];

                        if(request()->get('receive_p', false) && request()->get('receive_p') === 'false') {
                            $transaction_amount = CoachModuleRate::where('coach_id', $coach->id)->where('module_id', $module_id)->orderBy('id','DESC')->first();
                            $transaction_amount=($transaction_amount->rate)/$total_excercise;
                            $balance = $coach->balance;
                            $coach->update(['balance' => ($balance + $transaction_amount)]);
                            $transation_history_arr = [
                                'user_id' => Auth::id(),
                                'transaction_type' => 'plus',
                                'object_id' => $module_progress->id,
                                'object_type' => 'user_module_progresses',
                                'transaction_amount' =>  $transaction_amount,
                                'transaction_detail' => 'Feedback to <strong>' . (!empty($client) ? $client->user->name : '') . '</strong>\'s Module - <strong> '. (!empty($module) ? $module->module_no . '. ' . $module->module_title : '') .' </strong>, and you may not receive payment because client received the maximum number of reviews for current billing period.',
                                'module_progress_id' => $module_progress->id
                            ];
                        }
                        else
                        {
                            // CoachProgram::where('program_id','');
                            $transaction_amount = CoachModuleRate::where('coach_id', $coach->id)->where('module_id', $module_id)->first();

                            if(isset($transaction_amount) && !empty($transaction_amount))
                            {
                                $program_id = $transaction_amount->program_id;
                                $excercise = ModuleExercise::where('module_id',$module_id)->get();
                                $total_excercise=count($excercise);
                                $transaction_amount=($transaction_amount->rate)/$total_excercise;

                                if(empty($transaction_amount)) {

//                                    $transaction_amount=($transaction_amount->rate)/$total_excercise;
                                    $transaction_amount = 0;
                                }

                                $balance = $coach->balance;
                                $coach->update(['balance' => ($balance + $transaction_amount)]);
                                $transation_history_arr = [
                                'user_id' => Auth::id(),
                                'transaction_type' => 'plus',
                                'object_id' => $module_progress->id,
                                'object_type' => 'user_module_progresses',
                                'transaction_amount' => $transaction_amount,
                                'transaction_detail' => 'Feedback to <strong>' . (!empty($client) ? $client->user->name : '') . '</strong>\'s Module - <strong> '. (!empty($module) ? $module->module_no . '. ' . $module->module_title : '') .' Excercise is '.$module_excercise->exercise_no.' '.$module_excercise->title.'   </strong>'
                                ];
                            }
                            //fire event..
                            //check last module reviewd
                            event(new CoachTransactionHistoryEvent($transation_history_arr));
                            $excercise=$module_excercise->title;
                            $this->sendMailToClient($client, $coach, $module,$excercise);
                        }
                    }

               //  //update total credit stored in clients table

               //  if(!empty($client)) {
               //      $client_credits = $client->credits;
               //      $client->update(['credits' => $client_credits + config('srtpl.credit') ]);
               //  }
               // // event to increse client's credit by config('srtpl.credit')..
               //  $credit_history_arr = [
               //      'user_id' => $client_id, // clients table  user_id field...
               //      'object_id' => $module_progress->id,
               //      'object_type' => 'user_module_progresses',
               //      'transaction_type' => 'plus',
               //      'credit_score' => config('srtpl.credit')
               //      ];
               //      //fire event..
               //  event(new CreditHistoryEvent($credit_history_arr));
            }
                DB::commit();
                Flash::success('Review submitted Successfully!');

            } catch (Exception $e) {
                DB::rollback();
                Flash::error($e->getMessage());
            }
            return redirect()->route('coach.dashboard');
        }
        return redirect()->to($input['redirect_to']);
    }
    public function sendMailToClient($client, $coach, $module,$excercise)
    {
        $email_template_ex = EmailTemplate::where('slug','coach-has-submitted-feedback')->first()->toArray();
        if(isset($email_template_ex))
        {
            $tag = ['[client-email]','[first-name]','[coach-name]','[module-no]','[module-title]','[excercise]'];
            $replace_tag = [$client->user->email,$client->user->first_name,$coach->user->name,$module->module_no,$module->module_title,$excercise];
            $to = str_replace($tag,$replace_tag,$email_template_ex['to']);
            $subject = str_replace($tag,$replace_tag,$email_template_ex['subject']);
            $content = str_replace($tag,$replace_tag,$email_template_ex['content']);
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
            $subject = "Good News! " . $coach->user->name . " has left you some feedback on your work";
            Mail::send('email_template.feedbackAlertToClient', ['client' => $client, 'coach' => $coach, 'module' => $module], function ($mail) use ($module, $client, $subject) {
            $mail->to($client->user->email)
                ->bcc('urja.satodiya@sphererays.net')
                ->subject($subject);
            });
        }
    }
}
