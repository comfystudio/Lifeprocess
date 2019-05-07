<?php

namespace App\Http\Controllers;

use App;
use AppHelper;
use App\Events\NotificationEvent;
use App\Models\Client;
use App\Models\EmailTemplate;
use App\Models\Module;
use App\Models\ModuleExercise;
use App\Models\ModulesExercisesQuestion;
use App\Models\User;
use App\Models\UserModuleExercisesProgress;
use App\Models\UserModuleProgress;
use App\Models\UserModulesExercisesQuestion;
use Auth;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Mail;
use App\Models\Program;
use Session;
use App\Models\CoachTransactionHistory;
use App\Models\UserNextModuleProgress;
class ClientProgramModuleExerciseQuestionController extends Controller
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
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $module_id, $exercise_id)
    {
        //$value = Session::get('popup_option');
        $user_id     = Auth::id();
        $module_id   = Crypt::decryptString($module_id);
        $exercise_id = Crypt::decryptString($exercise_id);

        $module      = Module::find($module_id);

        $gratuate    = $request['gratuate_session'];

        if (Auth::user()->is_gratuate == 'y') {} elseif (Auth::user()->is_gratuate == 'n') {
            $userHasCompleted = UserModuleProgress::select('completed_at')->where('module_id', $module_id)->where('user_id', $user_id)->where('program_id', $module->program_id)->where('module_exercise_id',$exercise_id)->first();
            // dump(($userHasCompleted->completed_at));
            if (!empty($userHasCompleted->completed_at) && $userHasCompleted->completed_at != '0000-00-00 00:00:00') {
                Flash::warning('Sorry, You can\'t access the exercise because it already been submitted for review.');
                return redirect()->route('client.program_modules.index', ['program_id' => Crypt::encryptString($module->program_id)]);
            }
        }
        $exercise = ModuleExercise::where('module_id', $module_id)
            ->where('id', $exercise_id)
            ->first();
        $module_title         = 'Exercise ' . $module->module_no or '' . '.' . $exercise->exercise_no . ' - ' . $exercise->title;

        $exercise_description = $exercise->sort_description;
        // Exercise 0.1 - Assessment
        $exercise_questions = ModulesExercisesQuestion::with(['module_exercise_question_options', 'sub_questions.module_exercise_question_options'])
            ->where('module_id', $module_id)
            ->where('parent_question_id', '0')
            ->where('module_exercise_id', $exercise_id)
            ->orderBy('question_no')
            ->paginate(1);
         //
         // dump($exercise_questions); exit;
        $userAnswers = [];

         //dump($exercise_questions);
        foreach ($exercise_questions->items() as $question) {
            $submitted_answer                     = UserModulesExercisesQuestion::where('user_id', Auth::id())->where('module_id', $module_id)->where('module_exercise_id', $exercise_id)->where('question_id', $question->id)->first();
            $userAnswers['answer'][$question->id] = !empty($submitted_answer) ? $submitted_answer->answer : null;
            if (isset($question->sub_questions) && count($question->sub_questions)) {
                $userAnswers['answer'] += $this->createMultilevelQuestionsAndAnswerList($question->sub_questions, $question->question_no, '2', $module_id, $exercise_id);
            }
        }
        view()->share('exercise_questions', $exercise_questions);
        view()->share('userAnswers', $userAnswers);
        view()->share('dont_show_dialog', $this->user->dont_show_dialog);
        view()->share('title', 'Exercise ' . Module::find($module_id)->module_no . '.' . $exercise->exercise_no);
        view()->share('module_title', $module_title);
        view()->share('exercise_description', $exercise_description);
        view()->share('module_id', $module_id);
        view()->share('exercise_id', $exercise_id);
        view()->share('lastExerciseIdOfModule', $this->getLastExerciseIdOfModule($module_id));
        $popupoption=UserModulesExercisesQuestion::where('user_id', Auth::id())->where('module_id', $module_id)->where('module_exercise_id', $exercise_id)->orderBy('id', 'desc')->skip(1)->take(1)->first();
        //dump($popupoption);
        if(Auth::user()->dont_show_dialog=='1')
        {
            $popup_option='no';
        }
        else
        {
            $popup_option='yes';
        }
        view()->share('popup_option',$popup_option);
        $module_info=Module::where('id',$module_id)->first();
        view()->share('module_info',$module_info);
        $excercise_info=ModuleExercise::where('id',$exercise_id)->first();
        view()->share('excercise_info',$excercise_info);
        return view('clients.dashboard.exercise_questions');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $module_id, $exercise_id)
    {
        //dd($request->all());
        $input = AppHelper::getTrimmedData($request->all());
        $module_id   = Crypt::decryptString($module_id);
        $exercise_id = Crypt::decryptString($exercise_id);
        // echo $exercise_id; echo $module_id; dd($request);
        $program_id = Module::where('id', $module_id)->first()->program_id;
        $user_id    = Auth::id();

        if ($request->has('dont_show_dialog')) {
            User::where('id', $user_id)->update(['dont_show_dialog' => '1']);
        }
        if ($request->has('btn_name')) {
            return redirect()->route('client.program_modules.index', ['program_id' => Crypt::encryptString($program_id)]);
        }
        // Check that record added in UserModuleprogresses table ... Record added in this table when user has watched video or read the material.. if these fields are not included in module then we have to insert the record in this table ..
        if (Auth::user('is_gratuate') == 'y') {
            $status_added = UserModuleProgress::where('user_id', $user_id)->where('program_id', $program_id)->where('module_id', $module_id)->where('is_gratuate_module', 'y')->first();
            if (empty($status_added)) {
                UserModuleProgress::create(['user_id' => $user_id, 'program_id' => $program_id, 'module_id' => $module_id, 'is_gratuate_module' => 'y','watch_video'=>'yes','read_material'=>'yes']);
            }
            $userHasCompleted = UserModuleProgress::select('completed_at')->where('module_id', $module_id)->where('user_id', $user_id)->where('program_id', $program_id)->where('is_gratuate_module', 'y')->first();
            // dump(($userHasCompleted->completed_at));
            if (!empty($userHasCompleted->completed_at) && $userHasCompleted->completed_at != '0000-00-00 00:00:00') {
                Flash::warning('Sorry, You can\'t access the exercise because it already been submitted for review.');
                return redirect()->route('client.program_modules.index', ['program_id' => Crypt::encryptString($program_id)]);
            }

        } elseif (Auth::user('is_gratuate') == 'n') {
            $status_added = UserModuleProgress::where('user_id', $user_id)->where('program_id', $program_id)->where('module_id', $module_id)->first();
            if (empty($status_added)) {
                UserModuleProgress::create(['user_id' => $user_id, 'program_id' => $program_id, 'module_id' => $module_id,'watch_video'=>'yes','read_material'=>'yes']);
            }
            $userHasCompleted = UserModuleProgress::select('completed_at')->where('module_id', $module_id)->where('user_id', $user_id)->where('program_id', $program_id)->where('module_exercise_id',$exercise_id)->first();
            // dump(($userHasCompleted->completed_at));
            if (!empty($userHasCompleted->completed_at) && $userHasCompleted->completed_at != '0000-00-00 00:00:00') {
                Flash::warning('Sorry, You can\'t access the exercise because it already been submitted for review.');
                return redirect()->route('client.program_modules.index', ['program_id' => Crypt::encryptString($program_id)]);
            }
           // $input = AppHelper::getTrimmedData($request->all());
        }
        $rules    = [];
        $messages = [];
        $input    = AppHelper::getTrimmedData($request->all());

        if (isset($input['question_answer_format'])) {
            foreach ($input['question_answer_format'] as $key => $value) {

                $min_max_validation = '';
                if (isset($input['min_value'][$key])) {
                    $min_max_validation                  = '|min:' . $input['min_value'][$key];
                    $messages['answer.' . $key . '.min'] = 'The field must have a minimum :min characters.';
                }if (isset($input['max_value'][$key])) {
                    $min_max_validation .= '|max:' . $input['max_value'][$key];
                    $messages['answer.' . $key . '.max'] = 'The field must have a maximum :max characters.';
                }
                $rules['answer.' . $key]    = $min_max_validation;
                $messages['answer.' . $key] = 'This field is required.';

            }
        }
        //dd($input['question_answer_format']);
        $result = $this->validate($request, $rules, $messages);
        if (isset($input['question_answer_format'])) {

            //  print_r($input['question_answer_format']); exit();
            $answer = "";
            foreach ($input['question_answer_format'] as $key => $value) {
                // dd($input['answer']);

                if (isset($input['answer'])) {
                    if (array_key_exists($key, $input['answer'])) {
                        $answer = $input['answer'][$key];
                        if ($answer == "") {
                            $answer = "";
                        } else {
                            $answer = $input['answer'][$key];
                        }
                    } else {
                        $input['answer'][$key] = "";
                        $answer                = $input['answer'][$key];
                        //$answer = $input['answer'][$key];
                    }
                } else {
                    $answer = "";
                }
               // dd($input);
                $userAnswer = [
                    'user_id'            => $user_id,
                    'program_id'         => $program_id,
                    'module_id'          => $module_id,
                    'module_exercise_id' => $exercise_id,
                    'question_id'        => $key,
                    'answer'             => $answer,
                    'is_gratuate_answer' => Auth::user()->is_gratuate,
                    'popup_option'=>$input['popup_option'],
                ];
               // dump($userAnswer);
                $already_submit=UserModuleProgress::where('user_id', $user_id)->where('module_exercise_id', $exercise_id)->where('program_id', $program_id)->where('module_id', $module_id)->first();
                //dd($already_submit);
                if(isset($already_submit) && !empty($already_submit))
                {
                    Flash::success('You have already submitted this excercise');
                }
                else
                {
                $already_added = UserModulesExercisesQuestion::where('user_id', $user_id)->where('module_id', $module_id)->where('module_exercise_id', $exercise_id)->where('is_gratuate_answer', Auth::user()->is_gratuate)->where('question_id', $key)->first();

                if($input['popup_option']=='no')
                {
                    $popup_option=User::where('id',$user_id)->first();
                    $popup_option->update(['dont_show_dialog'=>'1']);
                }
                //dd($already_added);
                if (empty($already_added)) {
                    UserModulesExercisesQuestion::create($userAnswer);
                } else {
                    // if not update then set it
                    $already_added->update($userAnswer);
                }
                }
            }
        }
        if (isset($input['save_exit'])) {

            if (isset($input['answer']) && !empty($input['answer'])) {
            foreach ($input['answer'] as $key => $answer) {
                $userAnswer = [
                    'user_id'            => $user_id,
                    'program_id'         => $program_id,
                    'module_id'          => $module_id,
                    'module_exercise_id' => $exercise_id,
                    'question_id'        => $key,
                    'answer'             => $answer,
                    'is_gratuate_answer' => Auth::user()->is_gratuate,
                    'popup_option'=> $input['popup_option'],
                ];

                $already_added = UserModulesExercisesQuestion::where('user_id', $user_id)->where('module_id', $module_id)->where('module_exercise_id', $exercise_id)->where('is_gratuate_answer', Auth::user()->is_gratuate)->where('question_id', $key)->first();
                if (empty($already_added)) {
                    UserModulesExercisesQuestion::create($userAnswer);
                } else {
                    $already_added->update($userAnswer);
                }
            }
            }
            // dump($uarray);
            //exit;
            return redirect()->route('client.program_modules.index', ['program_id' => Crypt::encryptString($program_id), 'm_id' => Crypt::encryptString($module_id)]);

        } else if (isset($input['submit_for_review'])) {

            $user = Auth::user();

            if($user->addedby=='admin')
            {
                $date=$user->nextpaymentdate;
            }
            else
            {
                $data=CoachTransactionHistory::where('user_id',$user_id)->where('transaction_status','!=','Failure')->where('transaction_response','!=','')->orderBy('id','DESC')->get()->first();
                $date=$data->next_billing_date;
            }

            $completemoduledata = [
                'user_id'               => $user_id,
                'module_id'             => $module_id,
                'end_datetime'          => Carbon::now(),
                'billing_cycle'=>$date,
            ];
            UserNextModuleProgress::create($completemoduledata);

            $exerciseProcess = [
                'user_id'               => $user_id,
                'program_id'            => $program_id,
                'module_id'             => $module_id,
                'module_exercise_id'    => $exercise_id,
                'completed_at'          => Carbon::now(),
                'is_gratuate_excersize' => Auth::user()->is_gratuate,
            ];

            $already_exists = UserModuleExercisesProgress::where('user_id', $user_id)->where('module_exercise_id', $exercise_id)->where('program_id', $program_id)->where('module_id', $module_id)->where('is_gratuate_excersize', Auth::user()->is_gratuate)->first();

            if (isset($already_exists) && !empty($already_exists)) {
                Flash::success('You have already submitted this excercise');
            } else{

                UserModuleExercisesProgress::create($exerciseProcess);

                $exerciseProcess_for = [
                    'user_id'            => $user_id,
                    'program_id'         => $program_id,
                    'module_id'          => $module_id,
                    'module_exercise_id' => $exercise_id,
                    'completed_at'       => Carbon::now(),
                    'is_gratuate_module' => Auth::user()->is_gratuate,
                    'watch_video'=>'yes',
                    'read_material'=>'yes',
                ];
                UserModuleProgress::create($exerciseProcess_for);
                // UserModuleProgress::where('module_id', $module_id)->where('user_id', $user_id)->where('program_id', $program_id)->update(['completed_at' => Carbon::now(),'module_exercise_id'=>$exercise_id,'is_gratuate_module' => Auth::user()->is_gratuate]);
                $client       = Client::with(['user', 'coach.user', 'program'])->where('user_id', $user_id)->first();
                $first_module = Module::where('program_id', $program_id)->first();
                if (isset($first_module)) {
                    if ($first_module->id == $module_id) // if condition is true this is first module submited mail send
                    {
                        $email_template = EmailTemplate::where('slug', 'user-submits-initial-assessment')->first()->toArray();
                        $some_hour      = Carbon::now()->addHour(12);
                        if ($some_hour == Carbon::now()) {
                            if (isset($email_template)) {
                                $link        = "<a href=" . route('mylifestory.create') . "> Life Story </a>";
                                $tag         = ['[client-email]', '[client-name]', '[life-story-link]'];
                                $replace_tag = [$client->user->email, $client->user->name, $link];
                                $to          = str_replace($tag, $replace_tag, $email_template['to']);
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
                            } else {
                                Mail::send('email_template.client_submited_first_module', ['client_name' => $client->user->name], function ($message) use ($client) {
                                    $message->to($client->user->email)
                                        ->subject("It's time to get started with your Life Story…");
                                    $bcc = explode(',', config('srtpl.bccmail'));
                                    if (!empty($bcc)) {
                                        $message->bcc($bcc);
                                    }
                                });
                            }

                        }
                    }
                }
                $last_module = Module::where('program_id', $program_id)->orderBy('id', 'desc')->first();
                if (isset($last_module)) {
                    if ($last_module->id == $module_id) {
                        $email_template = EmailTemplate::where('slug', 'graduate-submit-final-module')->first()->toArray();
                          $excersize_name = ModuleExercise::with('module')->where('module_id', $module_id)->where('id',$exercise_id)->first();
                          $excercisename=$excersize_name->module->module_no.'.'.$excersize_name->exercise_no.'–'.$excersize_name->title.'for module'.$excersize_name->module->module_no.'–'.$excersize_name->module->module_title;
                        if (isset($email_template)) {
                            //dd($email_template);
                            $tag         = ['[client-email]', '[first-name]', '[program-name]'];
                            $replace_tag = [$client->user->email, $client->user->first_name, $client->program->program_name];
                            $to          = str_replace($tag, $replace_tag, $email_template['to']);
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
                        } else {
                            Mail::send('email_template.client_submited_last_module_for_review', ['client_name' => $client->user->first_name, 'program_name' => $client->program->program_name], function ($message) use ($client) {
                                $message->to($client->user->email)
                                    ->subject("Congratulations " . $client->user->first_name . "- You have successfully completed the  Life Process " . $client->program->program_name . " Program");
                                $bcc = explode(',', config('srtpl.bccmail'));
                                if (!empty($bcc)) {
                                    $message->bcc($bcc);
                                }
                            });
                        }
                    }
                }
                // send notification/ Fire notification event for submitted to review.
                $text   = '';
                $text   = '<strong>' . (!empty($client->user) ? $client->user->name : '') . '</strong> has submitted ';
                $module = Module::find($module_id);
                $text .= '<strong>module ' . $module->module_no . '</strong> for review';
                $notification_arr = [
                    'text'        => $text,
                    'receiver_id' => [$client->coach->user->id],
                ];
                //fire event..
                event(new NotificationEvent($notification_arr));
                $email_template_module_submit = EmailTemplate::where('slug', 'user-completes-module')->first()->toArray();
                if (isset($email_template_module_submit)) {
                    //dd($email_template);
                    $tag         = ['[client-email]', '[first-name]', '[module-no]', '[module-name]','[module-number]'];
                    $replace_tag = [$client->user->email, $client->user->first_name, $module->module_no, $module->module_title,$module->module_no];
                    $to          = str_replace($tag, $replace_tag, $email_template_module_submit['to']);
                    $subject     = str_replace($tag, $replace_tag, $email_template_module_submit['subject']);
                    $content     = str_replace($tag, $replace_tag, $email_template_module_submit['content']);
                    Mail::send(
                        'email_template.comman', ['content' => $content], function ($message) use ($to, $subject) {
                            $message->to($to)
                                ->subject($subject);
                            $bcc = explode(',', config('srtpl.bccmail'));
                            if (!empty($bcc)) {
                                $message->bcc($bcc);
                            }
                        });
                } else {
                    Mail::send('email_template.client_submit_module', ['first_name' => $client->user->first_name, 'module' => $module], function ($message) use ($client, $module) {
                        $message->to($client->user->email)
                            ->subject("Congratulations " . $client->user->first_name . " -You have successfully completed the  Module " . $module->module_no);
                        $bcc = explode(',', config('srtpl.bccmail'));
                        if (!empty($bcc)) {
                            $message->bcc($bcc);
                        }
                    });
                }
                //dd($module_id);
                $excersize_name = ModuleExercise::with('module')->where('module_id', $module_id)->where('id',$exercise_id)->first();

                if (isset($excersize_name) && !empty($excersize_name)) {
                    Flash::success('<b> Congratulations – You have successfully completed Exercise '.$excersize_name->module->module_no.'.'.$excersize_name->exercise_no.'–'.$excersize_name->title.' from Module '. $excersize_name->module->module_no.' '.$excersize_name->module->module_title.' . Your coach will review your work shortly and provide you with feedback. You can now proceed to the next exercise</b>');
                    $excercisename=$excersize_name->module->module_no.'.'.$excersize_name->exercise_no.'–'.$excersize_name->title;
                    $modulename=$excersize_name->module->module_no.' '.$excersize_name->module->module_title;
                } else {
                    Flash::success('<b> Congratulations – Exercise for has been submitted </b> <br>  Your coach will review your exercise and will provide feedback soon');
                }
                $email_template = EmailTemplate::where('slug', 'user-submits-exercise')->first()->toArray();
                $excersize_name = ModuleExercise::with('module')->where('module_id', $module_id)->where('id',$exercise_id)->first();

                        $excercisename=$excersize_name->module->module_no.'.'.$excersize_name->exercise_no.'–'.$excersize_name->title.' for module '.$excersize_name->module->module_no.'–'.$excersize_name->module->module_title;
                if (isset($email_template)) {
                    //dd($email_template);
                    $tag         = ['[client-email]', '[first-name]', '[coach-name]', '[exercise-name]'];
                    $replace_tag = [$client->user->email, $client->user->first_name, $client->coach->user->first_name, $excercisename];
                    $to          = str_replace($tag, $replace_tag, $email_template['to']);
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
                } else {
                    //dd($excercisename);
                    Mail::send(
                        'email_template.client_submited_exercise', ['client_name' => $client->user->first_name, 'coach_name' => $client->coach->user->first_name, 'excersize_name' => $excersize_name->title,'excercise_name'=>$excercisename,'module_name'=>$modulename], function ($message) use ($client) {
                            $message->to($client->user->email)
                                ->subject("Congratulation " . $client->user->first_name . "- You have successfully submitted your exercise to " . $client->coach->user->name);
                            $bcc = explode(',', config('srtpl.bccmail'));
                            if (!empty($bcc)) {
                                $message->bcc($bcc);
                            }
                        });
                }

                // email for coach
                 $email_template_coach = EmailTemplate::where('slug', 'user-submit-excercise-coach')->first()->toArray();
                //dd($email_template_coach);
                if(isset($email_template_coach))
                {
                    $tag         = ['[client-name]','[coach-email]','[program-name]','[excercise-name]','[coach-name]'];
                    $replace_tag = [$client->user->first_name, $client->coach->user->email,$client->program->program_name,$excersize_name->title,$client->coach->user->name];


                    $to          = str_replace($tag, $replace_tag, $email_template_coach['to']);
                    $subject     = str_replace($tag, $replace_tag, $email_template_coach['subject']);
                    $content     = str_replace($tag, $replace_tag, $email_template_coach['content']);

                    Mail::send(
                        'email_template.comman', ['content' => $content], function ($message) use ($to, $subject) {
                            $message->to($to)
                                ->subject($subject);
                            $bcc = explode(',', config('srtpl.bccmail'));
                            if (!empty($bcc)) {
                                $message->bcc($bcc);
                            }
                        });
                }  // over here
            }
            return redirect()->route('client.program_modules.index', ['program_id' => Crypt::encryptString($program_id)]);
        } else if (isset($input['finish']))
        {
            $exerciseProcess = [
                'user_id'            => $user_id,
                'program_id'         => $program_id,
                'module_id'          => $module_id,
                'module_exercise_id' => $exercise_id,
                'completed_at'       => Carbon::now(),
                'is_gratuate_module' => Auth::user()->is_gratuate,
                 'read_material'=>'yes',
                       'watch_video'=>'yes',
            ];
            //dd($exerciseProcess);
            $already_exists = UserModuleExercisesProgress::where('user_id', $user_id)->where('module_exercise_id', $exercise_id)->where('program_id', $program_id)->where('module_id', $module_id)->where('is_gratuate_excersize', Auth::user()->is_gratuate)->first();
            // dd($already_exists);
            if (!empty($already_exists)) {
                Flash::success('You have already submitted this excercise');
            } else {

                $already_added = UserModuleExercisesProgress::where('user_id', $user_id)->where('module_exercise_id', $exercise_id)->where('is_gratuate_excersize', Auth::user()->is_gratuate)->first();
                UserModuleProgress::create($exerciseProcess);
                //dd( $already_added);
                if (empty($already_added)) {
                    $exerciseProcess_for = [
                        'user_id'               => $user_id,
                        'program_id'            => $program_id,
                        'module_id'             => $module_id,
                        'module_exercise_id'    => $exercise_id,
                        'completed_at'          => Carbon::now(),
                        'is_gratuate_excersize' => Auth::user()->is_gratuate,
                        'read_material'=>'yes',
                        'watch_video'=>'yes',
                    ];
                    UserModuleExercisesProgress::create($exerciseProcess_for);
                    //UserModuleProgress::create($exerciseProcess);
                        $excersize_name = ModuleExercise::with('module')->where('module_id', $module_id)->where('id',$exercise_id)->first();
                        $excercisename=$excersize_name->module->module_no.'.'.$excersize_name->exercise_no.'–'.$excersize_name->title.' for module '.$excersize_name->module->module_no.'–'.$excersize_name->module->module_title;
                           $program=Program::where('id',$excersize_name->module->program_id)->first();
                            $program_name=$program->program_name;
                   // $modulename=$excersize_name->module->module_no.' '.$excersize_name->module->module_title;
                    //dd($excersize_name);
                    $client            = Client::with(['user', 'coach.user'])->where('user_id', $user_id)->first();
                    $email_template_ex = EmailTemplate::where('slug', 'user-submits-exercise')->first()->toArray();
                    if (isset($email_template_ex)) {
                       // dd($email_template_ex);
                        $tag         = ['[client-email]', '[first-name]', '[coach-name]', '[exercise-name]'];
                        $replace_tag = [$client->user->email, $client->user->first_name, $client->coach->user->first_name, $excercisename];
                        $to          = str_replace($tag, $replace_tag, $email_template_ex['to']);
                        $subject     = str_replace($tag, $replace_tag, $email_template_ex['subject']);
                        $content     = str_replace($tag, $replace_tag, $email_template_ex['content']);
                        Mail::send(
                            'email_template.comman', ['content' => $content], function ($message) use ($to, $subject) {
                                $message->to($to)
                                    ->subject($subject);
                                $bcc = explode(',', config('srtpl.bccmail'));
                                if (!empty($bcc)) {
                                    $message->bcc($bcc);
                                }
                            });

                    } else {

                            $excersize_name = ModuleExercise::with('module')->where('module_id', $module_id)->where('id',$exercise_id)->first();
                            $excercisename=$excersize_name->module->module_no.'.'.$excersize_name->exercise_no.'–'.$excersize_name->title;
                            $modulename=$excersize_name->module->module_no.' '.$excersize_name->module->module_title;

                            // dd($excercisename);
                            Mail::send(
                                'email_template.client_submited_exercise', ['client_name' => $client->user->first_name, 'coach_name' => $client->coach->user->name, 'excersize_name' => $excersize_name->title,'excercise_name'=>$excercisename,'module_name'=>$modulename], function ($message) use ($client) {
                                $message->to($client->user->email)
                                    ->subject("Congratulation " . $client->user->first_name . "- You have successfully submitted your exercise to " . $client->coach->user->name);
                                $bcc = explode(',', config('srtpl.bccmail'));
                                if (!empty($bcc)) {
                                    $message->bcc($bcc);
                                }
                            });
                    }
                        // email for coach
                    $email_template_coach = EmailTemplate::where('slug', 'user-submit-excercise-coach')->first()->toArray();
                //dd($email_template_coach);
                if(isset($email_template_coach))
                {
                    $tag         = ['[client-name]','[coach-email]','[program-name]','[excercise-name]','[coach-name]'];
                    $replace_tag = [$client->user->first_name,$client->coach->user->email,$client->program->program_name,$excersize_name->title,$client->coach->user->name];

                            $to          = str_replace($tag, $replace_tag, $email_template_coach['to']);
                            $subject     = str_replace($tag, $replace_tag, $email_template_coach['subject']);
                            $content     = str_replace($tag, $replace_tag, $email_template_coach['content']);

                            Mail::send(
                                'email_template.comman', ['content' => $content], function ($message) use ($to, $subject) {
                                    $message->to($to)
                                        ->subject($subject);
                                    $bcc = explode(',', config('srtpl.bccmail'));
                                    if (!empty($bcc)) {
                                        $message->bcc($bcc);
                                    }
                                });
                        }  // over here

                }
                $excersize_name = ModuleExercise::with('module')->where('module_id', $module_id)->where('id',$exercise_id)->first();
                //dd($excersize_name);
                if (isset($excersize_name) && !empty($excersize_name)) {
                    Flash::success('<b> Congratulations – You have successfully completed Exercise '.$excersize_name->module->module_no.'.'.$excersize_name->exercise_no.'–'.$excersize_name->title.' from Module '. $excersize_name->module->module_no.' '.$excersize_name->module->module_title.' . Your coach will review your work shortly and provide you with feedback. You can now proceed to the next exercise</b>');
                } else {
                    Flash::success('<b> Congratulations – Exercise for has been submitted </b> <br> Your coach will review your exercise and will provide feedback soon');
                }
            }
            return redirect()->route('client.program_modules.index', ['program_id' => Crypt::encryptString($program_id)]);

        } else if ($input['redirect_to'] == '') {

        }
        return redirect()->to($input['redirect_to']);
        // dump($request->all()); exit();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }

    public function getLastExerciseIdOfModule($module_id)
    {
        $last_exercise = ModuleExercise::where('module_id', $module_id)->orderBy('exercise_no', 'DESC')->first();
        if (!empty($last_exercise)) {
            return $last_exercise->id;
        } else {
            return 0;
        }
    }

    public function createMultilevelQuestionsAndAnswerList($sub_questions, $parent_question_no, $level, $module_id, $exercise_id)
    {
        $arr = [];
        /*foreach ($sub_questions as $sub_question) {
        $question_no = $parent_question_no . '.' . $sub_question->question_no;
        $arr[$sub_question->id] = ' ' . str_repeat('-', $level) . ' ' . $question_no . '. ' . $sub_question->question_title ;
        if(count($sub_question->sub_questions)) {
        $arr += $this->createMultilevelQuestionsList($sub_question->sub_questions, $question_no, $level+1);
        }
        }*/
        foreach ($sub_questions as $sub_question) {
            $submitted_answer = UserModulesExercisesQuestion::where('user_id', Auth::id())->where('module_id', $module_id)->where('module_exercise_id', $exercise_id)->where('question_id', $sub_question->id)->first();
            // dump($submitted_answer);
            $question_no            = $parent_question_no . '.' . $sub_question->question_no;
            $arr[$sub_question->id] = !empty($submitted_answer) ? $submitted_answer->answer : null;

            if (count($sub_question->sub_questions)) {
                $arr += $this->createMultilevelQuestionsAndAnswerList($sub_question->sub_questions, $question_no, $level + 1, $module_id, $exercise_id);
            }
        }
        return $arr;
    }
}
