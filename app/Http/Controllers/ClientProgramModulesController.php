<?php

namespace App\Http\Controllers;

use App;
use AppHelper;
use App\Models\Module;
use App\Models\ModuleExercise;
use App\Models\Page;
use App\Models\Program;
use App\Models\Coach;
use App\Models\Client;
use App\Models\User;
use App\Models\UserProgram;
use App\Models\UserModuleExercisesProgress;
use App\Models\GratuateModuleExercisesProgress;
use App\Models\UserModuleProgress;
use App\Models\GratuateModuleProgress;
use App\Models\CoachTransactionHistory;
use App\Models\EmailTemplate;
use App\Events\CoachTransactionHistoryEvent;
use App\Events\CreditHistoryEvent;
use Auth;
use Carbon\Carbon;
use Config;
use DB;
use Html;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use PDF;
use Mail;
use App\Models\UserNextModuleProgress;
use App\Models\Setting;
use PayPal;


class ClientProgramModulesController extends Controller {

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->title = "Client Dashboard";
		$this->module_title = " ";
	}

	public function test(Request $request, $program_id){
		$program_id = Crypt::decryptString($program_id);
		$user_id = Auth::id();
		if($program_id=='')
		{
			$client=Client::where('user_id',$user_id)->first();
			$program_id=$client->program_id;
		}
		$program=Program::where('id',$program_id)->first()->toArray();
		$intro_video_watch=UserProgram::where('program_id',$program_id)->where('user_id',$user_id)->get()->first();
		view()->share('intro_video_watch',$intro_video_watch);

		$object = App::make('App\Http\Controllers\ModuleController');
		$modules = $object->get_index($program_id, array());
		$total_reviewd_module_count = UserModuleProgress::where('program_id',$program_id)->where('user_id',Auth::id())->where('status','!=','unlock')->WhereNotNull('reviewed_at')->get()->count();
		$last_module = $object->get_index($program_id,$orderBy = 'desc')->last();
		if(isset($last_module))
		{
			$user = Auth::user();
			$last_module_progress = UserModuleProgress::where('user_id', $user->id)->where('module_id',$last_module->id)->orderBy('id', 'desc')->first();
			if(isset($last_module_progress))
			{
				if((count($modules) == $total_reviewd_module_count) && ($last_module_progress->is_last_module_review_email_send == 0))
				{
					$last_module_progress->update(['is_last_module_review_email_send' => '1']);
					$program_name=Program::find($program_id)->program_name;
					$email_template = EmailTemplate::where('slug','graduate-submit-final-module')->first()->toArray();
					if(isset($email_template))
					{
						$tag = ['[client-email]','[first-name]','[program-name]'];
						$replace_tag = [$user->email,$user->first_name,$program_name];
						$to = str_replace($tag,$replace_tag,$email_template['to']);
						$subject = str_replace($tag,$replace_tag,$email_template['subject']);
						$content = str_replace($tag,$replace_tag,$email_template['content']);
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
						Mail::send('email_template.client_graduate',['client_name' => $user->first_name,'program' => $program_name], function ($message) use($user,$program_name){
							$message->to($user->email)
							->subject("Congratulations " .$user->first_name ." -You have successfully completed the  Life Process ". $program_name . " Program");
							$bcc = explode(',', config('srtpl.bccmail'));
							if (!empty($bcc)) {
								$message->bcc($bcc);
							}
						});
					}
				}
			}
			$gratuate_video_watch=UserProgram::where('user_id',$user->id)->where('is_gratuate_video_watch','1')->first();
			if($gratuate_video_watch=='')
			{
				$gratuate_video_watch=0;
			}
			else
			{
				$gratuate_video_watch=1;
			}
			view()->share('last_module_progress',$last_module_progress);
			view()->share('gratuate_video',$gratuate_video_watch);
		}
		$page = Page::where('slug', 'client-module-completed')->first();

		view()->share('page', $page);
		view()->share('total_reviewd_module_count',$total_reviewd_module_count);
		view()->share('modules', $modules);
		view()->share('completed_modules', $this->getCompletedModules($program_id));
		view()->share('completedmoduleexcercise',$this->getCompletedExcercise($program_id));
		view()->share('total_completed_excercise',$this->getCompletedExcercise($program_id));
		view()->share('completed_exercise', $this->getCompletedModuleExercise($program_id));
		view()->share('completed_gratuate_exercise', $this->getGratuateCompletedModuleExercise($program_id));
		view()->share('getPageNoToResumeExercise', $this->getPageNoToResumeExercise($program_id));
		view()->share('counter', 0);
		view()->share('program', Program::find($program_id));
		view()->share('program_id', $program_id);
		view()->share('m_id', ($request->get('m_id', false) ?: ''));
		view()->share('title', $this->title);
		view()->share('canAccessNextExercise', $this->noOfExercisePerDay());
		return view('clients.dashboard.program_modules_test');
	}

	/**
	 * Show the client application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, $program_id) {
		$program_id = Crypt::decryptString($program_id);
		$user_id = Auth::id();
		if($program_id=='')
		{
			$client=Client::where('user_id',$user_id)->first();
			$program_id=$client->program_id;
		}
		$program=Program::where('id',$program_id)->first()->toArray();
		$intro_video_watch=UserProgram::where('program_id',$program_id)->where('user_id',$user_id)->get()->first();
//        echo $program_id;
//        echo '<br/>';
//        echo $user_id;
//        dd($intro_video_watch);
		view()->share('intro_video_watch',$intro_video_watch);

		$object = App::make('App\Http\Controllers\ModuleController');
		$modules = $object->get_index($program_id, array());
		$total_reviewd_module_count = UserModuleProgress::where('program_id',$program_id)->where('user_id',Auth::id())->where('status','!=','unlock')->WhereNotNull('reviewed_at')->get()->count();
		$last_module = $object->get_index($program_id,$orderBy = 'desc')->last();
		if(isset($last_module))
		{
			$user = Auth::user();
			$last_module_progress = UserModuleProgress::where('user_id', $user->id)->where('module_id',$last_module->id)->orderBy('id', 'desc')->first();
			if(isset($last_module_progress))
			{
				if((count($modules) == $total_reviewd_module_count) && ($last_module_progress->is_last_module_review_email_send == 0))
				{
					$last_module_progress->update(['is_last_module_review_email_send' => '1']);
					$program_name=Program::find($program_id)->program_name;
					$email_template = EmailTemplate::where('slug','graduate-submit-final-module')->first()->toArray();
					if(isset($email_template))
					{
						$tag = ['[client-email]','[first-name]','[program-name]'];
						$replace_tag = [$user->email,$user->first_name,$program_name];
						$to = str_replace($tag,$replace_tag,$email_template['to']);
						$subject = str_replace($tag,$replace_tag,$email_template['subject']);
						$content = str_replace($tag,$replace_tag,$email_template['content']);
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
						Mail::send('email_template.client_graduate',['client_name' => $user->first_name,'program' => $program_name], function ($message) use($user,$program_name){
							$message->to($user->email)
							->subject("Congratulations " .$user->first_name ." -You have successfully completed the  Life Process ". $program_name . " Program");
							$bcc = explode(',', config('srtpl.bccmail'));
							if (!empty($bcc)) {
								$message->bcc($bcc);
							}
						});
					}
				}
			}
			$gratuate_video_watch=UserProgram::where('user_id',$user->id)->where('is_gratuate_video_watch','1')->first();
			if($gratuate_video_watch=='')
			{
				$gratuate_video_watch=0;
			}
			else
			{
				$gratuate_video_watch=1;
			}
			view()->share('last_module_progress',$last_module_progress);
			view()->share('gratuate_video',$gratuate_video_watch);
		}
		$page = Page::where('slug', 'client-module-completed')->first();

		view()->share('page', $page);
		view()->share('total_reviewd_module_count',$total_reviewd_module_count);
		view()->share('modules', $modules);
		view()->share('completed_modules', $this->getCompletedModules($program_id));
		view()->share('completedmoduleexcercise',$this->getCompletedExcercise($program_id));
		view()->share('total_completed_excercise',$this->getCompletedExcercise($program_id));
		view()->share('completed_exercise', $this->getCompletedModuleExercise($program_id));
		view()->share('completed_gratuate_exercise', $this->getGratuateCompletedModuleExercise($program_id));
		if($user->addedby=='admin')
        {
                $date=$user->nextpaymentdate;
                $olddate='';
        }
        else
        {
            $data=CoachTransactionHistory::where('user_id',$user_id)->orderBy('id','DESC')->get()->first();
            $date=$data->next_billing_date;
            //$olddate=$data->last_payment_date;
        }

        $user_complete_module=UserNextModuleProgress::where('user_id',$user->id)->get();
        $max_number_module=Setting::where('name','max_modules_per_bill_cycle')->get()->first();
        $max_number_module=$max_number_module->value;

        if($user->paypal_start_date!='')
        {
            $provider = PayPal::setProvider('express_checkout');
            $profile_response = $provider->getRecurringPaymentsProfileDetails($user->stripe_sub_id);
            $total_module=$profile_response['NUMCYCLESCOMPLETED']*$max_number_module;
            if($total_module==0)
            {
                $total_module=$max_number_module;
            }
            $user_complete_module=UserNextModuleProgress::where('user_id',$user->id)->count();
            $remain=$total_module-$user_complete_module;

        }
        else
        {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $retrive= \Stripe\Invoice::all(array('customer'=>$user->stripe_id));
            $total_module=count($retrive['data'])*$max_number_module;
            if($total_module==0)
            {
                $total_module=$max_number_module;
            }
            $user_complete_module=UserNextModuleProgress::where('user_id',$user->id)->count();
            $remain=$total_module-$user_complete_module;
        }
        $max_number_module=$total_module;

        if($remain<0)
        {
            $max_number_module=$user_complete_module;
        }

		view()->share('total_complete_module_by_user',$user_complete_module);
		view()->share('max_number_module_per_billing_cycle',$max_number_module);
		//view()->share('max_number_module_per_billing_cycle',$total_module);
		$data=Client::where('user_id',Auth::id())->get()->first();
		view()->share('max_number_module_per_client',$data->module_restriction);
		view()->share('getPageNoToResumeExercise', $this->getPageNoToResumeExercise($program_id));
		view()->share('counter', 0);
		view()->share('program', Program::find($program_id));
		view()->share('program_id', $program_id);
		view()->share('m_id', ($request->get('m_id', false) ?: ''));
		view()->share('title', $this->title);
		view()->share('canAccessNextExercise', $this->noOfExercisePerDay());
		return view('clients.dashboard.program_modules');

	}
	public function gratuateModules(Request $request, $program_id) {
		$program_id = Crypt::decryptString($program_id);
		$object = App::make('App\Http\Controllers\ModuleController');
		$modules = $object->get_index($program_id, array());
		$total_reviewd_module_count = GratuateModuleProgress::where('program_id',$program_id)->where('user_id',Auth::id())->where('status','!=','unlock')->WhereNotNull('reviewed_at')->get()->count();
		$last_module = $object->get_index($program_id,$orderBy = 'desc')->last();
		if(isset($last_module))
		{
			$user = Auth::user();
			$last_module_progress = GratuateModuleProgress::where('user_id', $user->id)->where('module_id',$last_module->id)->first();
			if(isset($last_module_progress))
			{
				if((count($modules) == $total_reviewd_module_count) && ($last_module_progress->is_last_module_review_email_send == 0))
				{
					$last_module_progress->update(['is_last_module_review_email_send' => '1']);
					$program_name=Program::find($program_id)->program_name;
					$email_template = EmailTemplate::where('slug','graduate-submit-final-module')->first()->toArray();
					if(isset($email_template))
					{
						$tag = ['[client-email]','[first-name]','[program-name]'];
						$replace_tag = [$user->email,$user->first_name,$program_name];
						$to = str_replace($tag,$replace_tag,$email_template['to']);
						$subject = str_replace($tag,$replace_tag,$email_template['subject']);
						$content = str_replace($tag,$replace_tag,$email_template['content']);
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
						Mail::send('email_template.client_graduate',['client_name' => $user->first_name,'program' => $program_name], function ($message) use($user,$program_name){
							$message->to($user->email)
							->subject("Congratulations " .$user->first_name ." -You have successfully completed the  Life Process ". $program_name . " Program");
							$bcc = explode(',', config('srtpl.bccmail'));
							if (!empty($bcc)) {
								$message->bcc($bcc);
							}
						});
					}
				}
			}
			view()->share('last_module_progress',$last_module_progress);
		}
		$page = Page::where('slug', 'client-module-completed')->first();
		view()->share('page', $page);
		view()->share('total_reviewd_module_count',$total_reviewd_module_count);
		view()->share('modules', $modules);

		view()->share('completed_modules', $this->getGratuateCompletedModules($program_id));
		view()->share('completed_exercise', $this->getGratuateCompletedModuleExercise($program_id));
		view()->share('getPageNoToResumeExercise', $this->getPageNoToResumeExercise($program_id));
		view()->share('counter', 0);
		view()->share('program', Program::find($program_id));
		view()->share('program_id', $program_id);
		view()->share('m_id', ($request->get('m_id', false) ?: ''));
		view()->share('title', $this->title);
		// view()->share('max_exercise_can_complete_per_day', $NoOfExercise);
		view()->share('canAccessNextExercise', $this->noOfExercisePerDay());
		return view('clients.dashboard.gratuate_modules');
	}

	// pass user_id only if the current logged in user is not a client...
	public function viewCoachFeedback(Request $request, $module_id,$excercise_id) {

		$update_view_feedback_at_flag = false;
		if (request()->get('user_id', false)) {
			$user_id = Crypt::decryptString(request()->get('user_id'));
		} else {
			$user_id = Auth::id();
			$update_view_feedback_at_flag = true;
		}
		$module_id = Crypt::decryptString($module_id);
		$excercise_id = Crypt::decryptString($excercise_id);
		$client_module_exercises = $this->getSubmittedExerciseFeedback($module_id, $user_id,$excercise_id);


		$total_exercise = count($client_module_exercises->get());
		$client_module_exercises = $client_module_exercises->paginate(1);
		view()->share('title', 'View Feedback');
		view()->share('total_exercise', $total_exercise);
		view()->share('client_module_exercises', $client_module_exercises);
		$module = head($client_module_exercises->items());
		$module_description = '';
		if (!empty($module)) {
			$module = !empty($module) ? $module : '';
			$module_exercise = $module->module_exercise;
			$this->module_title = $module->module->module_no . '.' . $module_exercise->exercise_no . ' - ' . $module_exercise->title;
			$module_description = $module_exercise->sort_description;
		}

		//update view_feedback_at field in table.. only if the logged in user is client..
		if($update_view_feedback_at_flag) {
			$update_view_feedback_at = UserModuleProgress::where('user_id', $user_id)->where('module_id', $module_id)->whereNull('view_feedback_at')->first();
			if (!empty($update_view_feedback_at)) {
				$update_view_feedback_at->update(['view_feedback_at' => Carbon::now()->format('Y-m-d H:i:s')]);
			}
		}
		view()->share('module_title', $this->module_title);
		view()->share('module_description', $module_description);
		view()->share('module_id', $module_id);
		view()->share('excercise_id', $excercise_id);
		return view('clients.dashboard.view_feedback');
	}

	public function downloadCoachFeedback(Request $request, $module_id, $excercise_id, $user_id = null) {
		$module_id = Crypt::decryptString($module_id);
		$excercise_id = Crypt::decryptString($excercise_id);
        if(!isset($user_id) || $user_id == null){
            $user_id = Auth::id();
        }
		$client_module_exercises = $this->getSubmittedExerciseFeedback($module_id, $user_id,$excercise_id)->get();
		$client_module = $this->getSubmittedModuleFeedback($module_id, $user_id,$excercise_id)->get();
		$total_exercise = count($client_module_exercises);

        $update_view_feedback_at = UserModuleProgress::where('user_id', $user_id)->where('module_id', $module_id)->where('module_exercise_id',$excercise_id)->whereNull('view_feedback_at')->first();
		if (!empty($update_view_feedback_at)) {
			$update_view_feedback_at->update(['view_feedback_at' => Carbon::now()->format('Y-m-d H:i:s')]);
		}

		$pdf = PDF::loadView('clients.dashboard.download_feedback', ['total_exercise' => $total_exercise, 'client_module_exercises' => $client_module_exercises, 'module_id' => $module_id,'excercise_id'=>$excercise_id, 'theme' => 'limitless.pdf', 'title' => 'Download Feedback','client_module'=>$client_module]);
		$pdf->setPaper('a4');
		$pdf->setOrientation('portrait');
		// $pdf->setOption('margin-top', 20);
		// $pdf->setOption('margin-right', 15);
		// $pdf->setOption('margin-bottom', 15);
		// $pdf->setOption('margin-left', 15);
		$pdf->setOption('header-right', '');
		return $pdf->stream('client-' . $user_id . '-module-' . $module_id . '.pdf');
	}

	public function loadVideo_and_updateModuleProgress() {
		$module_id = request()->get('module_id');
		$module = Module::select(['introduction_video', 'program_id', 'reading_material'])->where('id', $module_id)->first();
		$intro_video = $module->introduction_video;
		$reading_material = $module->reading_material;
		$program_id = $module->program_id;
		$user_id = Auth::id();
		$material_link = Html::decode(link_to_asset(AppHelper::path('uploads/module/reading_materials/')->getImageUrl($module->reading_material), '<i class="icon-file-eye2"> </i> &nbsp;' . trans('comman.view'), ['class' => 'btn btn-default', 'target' => '_blank', 'onclick' => "load_module_video_orRead(" . $module_id . " , 'material')"]));
		$material_link = Html::decode(link_to_asset(AppHelper::path('uploads/module/reading_materials/')->getImageUrl($module->reading_material), '<i class="icon-file-eye2"> </i> &nbsp;' . trans('comman.view'), ['class' => 'btn btn-default', 'target' => '_blank', 'onclick' => "load_module_video_orRead(" . $module_id . " , 'material')"]));
		// Add status that client has watched the video...
		$status_added = UserModuleProgress::where('user_id', $user_id)->where('program_id', $program_id)->where('module_id', $module_id)->where('is_gratuate_module', Auth::User()->is_gratuate);
		// if(request()->get('type') == 'video') {
		//     $status_added->whereNotNull('watch_video');
		// } else {
		//     $status_added->whereNotNull('read_material');
		// }
		$status_added = $status_added->first();
		$success = false;
		if (empty($status_added)) {
			if (request()->get('type') == 'video') {
				$data_toInsert = [
				'user_id' => $user_id,
				'program_id' => $program_id,
				'module_id' => $module_id,
				'watch_video' => 'yes',
				'is_gratuate_module'=>Auth::user()->is_gratuate,
				];
			} else {
				$data_toInsert = [
				'user_id' => $user_id,
				'program_id' => $program_id,
				'module_id' => $module_id,
				'read_material' => 'yes',
				'is_gratuate_module'=>Auth::user()->is_gratuate,
				];
			}
			UserModuleProgress::create($data_toInsert);
			$success = true;
		} else {
			//record exists
			if (request()->get('type') == 'video') {
				if (empty($status_added->watch_video)) {
					$data_toUpdate = [
					'watch_video' => 'yes',
					];
					UserModuleProgress::where('user_id', $user_id)->where('program_id', $program_id)->where('module_id', $module_id)->update($data_toUpdate);
				}
			} else {
				if (empty($status_added->read_material)) {
					$data_toUpdate = [
					'read_material' => 'yes',
					];
					UserModuleProgress::where('user_id', $user_id)->where('program_id', $program_id)->where('module_id', $module_id)->update($data_toUpdate);
				}
			}
			$success = true;
		}

		if (request()->ajax()) {
			if (request()->get('type') == 'video') {
				if (empty($reading_material)) {
					// if not inserted the reading material...
					$exercise_id = $this->getNextExerciseId($module_id);
					// $onclickFunction = "return check_delay_between_modules('{{ $previous_module_id }}', '{{$module->id}}', '{{ $module->delay_btw_chapter_exercise }}');";
					$onclickFunction = [];
					$canAccessNextExercise = $this->noOfExercisePerDay();
					if ($canAccessNextExercise['remaining_exercise'] <= 0) {
						$exercisePerDay = $canAccessNextExercise['exercise_to_be_completed_per_day'];
						$completedToday = $canAccessNextExercise['total_exercise_completed_today'];
						$onclickFunction = ["onclick" => "return alert_forExerciseLimitExceedForDay('$exercisePerDay', '$completedToday');"];
					}
					$exercise_link = Html::decode(link_to_route('client-exercises.create', 'Incomplete', ['module_id' => Crypt::encryptString($module_id), 'exercise_id' => Crypt::encryptString($exercise_id)], ['class' => 'btn btn-warning'] + $onclickFunction));
					$data = [
					'video_content' => $intro_video,
					'reading_material' => $reading_material,
					'exercise_id' => $exercise_id,
					'end_of_exercise' => ($exercise_id) ? 'no' : 'yes',
					'exercise_link' => $exercise_link,
					];
				} else {
					$data = [
					'video_content' => $intro_video,
					'reading_material' => $reading_material,
					'material_link' => $material_link,
					];
				}
			} else {
				$exercise_id = $this->getNextExerciseId($module_id);
				$onclickFunction = [];
				$canAccessNextExercise = $this->noOfExercisePerDay();
				if ($canAccessNextExercise['remaining_exercise'] <= 0) {
					$exercisePerDay = $canAccessNextExercise['exercise_to_be_completed_per_day'];
					$completedToday = $canAccessNextExercise['total_exercise_completed_today'];
					$onclickFunction = ["onclick" => "return alert_forExerciseLimitExceedForDay('$exercisePerDay', '$completedToday');"];
				}
				$exercise_link = Html::decode(link_to_route('client-exercises.create', 'Incomplete', ['module_id' => Crypt::encryptString($module_id), 'exercise_id' => Crypt::encryptString($exercise_id)], ['class' => 'btn btn-warning'] + $onclickFunction));
				$data = [
				'exercise_id' => $exercise_id,
				'end_of_exercise' => ($exercise_id) ? 'no' : 'yes',
				'exercise_link' => $exercise_link,
				];
			}
			return response()->json([
				'success' => 'true',
				'data' => $data,
				]);
		}
	}
	/*
		     * @param $module_id
		     * Return Module
	*/
	public function getNextExerciseId($module_id) {
		$completedExercises = UserModuleExercisesProgress::select(['module_exercise_id'])
		->where('module_id', $module_id)->where('is_gratuate_excersize', Auth::user()->is_gratuate)->where('user_id', Auth::id())->get()->toArray();
		$exerciseIds = count($completedExercises) > 0 ? $completedExercises : [0];
		$getNextExerciseId = ModuleExercise::whereNotIn('id', $exerciseIds)->where('module_id', $module_id)->select(['id'])->orderBy('exercise_no')->first();
		$getNextExerciseId = !empty($getNextExerciseId) ? $getNextExerciseId->id : 0;
		return $getNextExerciseId;
	}

	/*
		     * @param $program_id
		     * Return Array
	*/
	public function getCompletedModulesexcercie($program_id) {

		$completed = UserModuleProgress::select(['watch_video', 'read_material', 'module_id', 'completed_at', 'status','is_gratuate_module','module_exercise_id'])->where('program_id', $program_id)->where('user_id', Auth::id())->get();

		//create modulewise array...
		$CompletedModules = [];
		if (isset($completed) && count($completed) > 0) {
			foreach ($completed as $key => $row) {

				$CompletedModules[$row['module_id']][]=array('completed_at'=>$row['completed_at'],'status'=>$row[
					'status'],'module_exercise_id'=>$row['module_exercise_id']);
			}
		}
		//dump($CompletedModules);
		return $CompletedModules;
	    //return $completed;
	}
	public function getCompletedModules($program_id) {
		$completed = UserModuleProgress::select(['watch_video', 'read_material', 'module_id', 'completed_at', 'status','is_gratuate_module','module_exercise_id'])->where('program_id', $program_id)->where('is_gratuate_module', Auth::user()->is_gratuate)->where('user_id', Auth::id())->get();

		$CompletedModules = [];
		if (isset($completed) && count($completed) > 0) {
			foreach ($completed as $key => $row) {

				if (!empty($row['watch_video'])) {
					$CompletedModules[$row['module_id']]['watch_video'] = 'yes';
				}
				if (!empty($row['read_material'])) {
					$CompletedModules[$row['module_id']]['read_material'] = 'yes';
				}

				$CompletedModules[$row['module_id']]['completed_at'] = $row['completed_at'];
				$CompletedModules[$row['module_id']]['status'] = $row['status'];
				//$CompletedModules[$row['module_id']]['id'] = $row['id'];
				$CompletedModules[$row['module_id']]['is_gratuate_module'] = $row['is_gratuate_module'];
				$CompletedModules[$row['module_id']]['module_exercise_id'] = $row['module_exercise_id'];
			}
		}
		return $CompletedModules;
	}
	public function getCompletedExcercise($program_id) {
//		$completed = UserModuleProgress::select(['id','watch_video', 'read_material', 'module_id', 'completed_at', 'status','is_gratuate_module','module_exercise_id'])->where('program_id', $program_id)->where('user_id', Auth::id())->where('is_gratuate_module', Auth::user()->is_gratuate)->get()->toArray();
        $completed = UserModuleProgress::select(['id','watch_video', 'read_material', 'module_id', 'completed_at', 'status','is_gratuate_module','module_exercise_id'])->where('program_id', $program_id)->where('user_id', Auth::id())->get()->toArray();
        return $completed;
	}
	public function getGratuateCompletedModules($program_id) {
		$completed = GratuateModuleProgress::select(['watch_video', 'read_material', 'module_id', 'completed_at', 'status'])->where('program_id', $program_id)->where('user_id', Auth::id())->get();

		// create modulewise array...
		$CompletedModules = [];
		if (isset($completed) && count($completed) > 0) {
			foreach ($completed as $key => $row) {
				if (!empty($row['watch_video'])) {
					$CompletedModules[$row['module_id']]['watch_video'] = 'yes';
				}
				if (!empty($row['read_material'])) {
					$CompletedModules[$row['module_id']]['read_material'] = 'yes';
				}
				$CompletedModules[$row['module_id']]['completed_at'] = $row['completed_at'];
				$CompletedModules[$row['module_id']]['status'] = $row['status'];
			}
		}
		return $CompletedModules;
	}

	/*
		     * @param $program_id
		     * Return Array
	*/
	public function getCompletedModuleExercise($program_id) {

		$completed = UserModuleProgress::select(['watch_video', 'read_material', 'module_id', 'completed_at', 'status','is_gratuate_module','module_exercise_id'])->where('program_id', $program_id)->where('is_gratuate_module', Auth::user()->is_gratuate)->where('user_id', Auth::id())->get();
		// create modulewise completed exercise array...
		$completedExercises = [];
		if (isset($completed) && count($completed) > 0) {
			foreach ($completed as $key => $row) {
				$completedExercises[$row->module_id][] = $row->module_exercise_id;
			}
		}
		return $completedExercises;
	}
	public function getGratuateCompletedModuleExercise($program_id) {
		$completed = UserModuleExercisesProgress::select(['module_id', 'module_exercise_id'])->where('program_id', $program_id)->where('is_gratuate_excersize', 'y')->where('user_id', Auth::id())->get();

		// create modulewise completed exercise array...
		$completedExercises = [];
		if (isset($completed) && count($completed) > 0) {
			foreach ($completed as $key => $row) {
				$completedExercises[$row->module_id][] = $row->module_exercise_id;
			}
		}
		return $completedExercises;
	}
	/*
		     * @param $program_id
		     * Return Array
	*/
	public function getPageNoToResumeExercise($program_id) {
		$resumeFrom = [];

		$userModulesExerciseQuestionProgress = DB::table('user_modules_exercises_questions')->where('user_modules_exercises_questions.deleted', DB::raw('"0"'))
		->select(['user_modules_exercises_questions.module_exercise_id', DB::raw('count(user_modules_exercises_questions.question_id) AS answered_question')])
		->where('user_modules_exercises_questions.user_id', Auth::id())
		->where('user_modules_exercises_questions.is_gratuate_answer', Auth::user()->is_gratuate)
		->where('user_modules_exercises_questions.program_id', $program_id)
		->groupBy('user_modules_exercises_questions.module_exercise_id')
		->join('modules_exercises_questions', function ($join) {
			$join->on('modules_exercises_questions.id', '=', 'user_modules_exercises_questions.question_id');
			$join->on('modules_exercises_questions.deleted', '=', DB::raw('"0"'));
			$join->on('modules_exercises_questions.parent_question_id', '=', DB::raw('0'));
		})->get();

		if (!empty($userModulesExerciseQuestionProgress)) {
			foreach ($userModulesExerciseQuestionProgress as $key => $row) {
				$resumeFrom[$row->module_exercise_id] = $row->answered_question;
			}
		}
		return $resumeFrom;
	}

	public function getSubmittedExerciseFeedback($module_id, $client_id,$module_exercise_id) {
		$client_module_exercises = UserModuleExercisesProgress::with(['module', 'module_exercise' => function ($query) {
			$query->orderBy('exercise_no');
		}, 'user_module_exercise_questions' => function ($query) {
			$query->orderBy('question_no');
			$query->where('parent_question_id', DB::raw('0'));
		}, 'user_module_exercise_questions.question_answer' => function ($query) use ($client_id) {
			$query->where('user_id', $client_id);
		}, 'user_module_exercise_questions.sub_questions' => function ($query) {
			$query->orderBy('question_no');
		}, 'user_module_exercise_questions.sub_questions.question_answer' => function ($query) use ($client_id) {
			$query->where('user_id', $client_id);
		}])
		->select(['user_id', 'module_id', 'module_exercise_id'])
		->where('user_id', $client_id)
		->where('module_id', $module_id)
		->where('module_exercise_id',$module_exercise_id)
		->distinct();

		return $client_module_exercises;

	}

	public function check_delay_between_modules() {
		$inputs = AppHelper::getTrimmedData(request()->all());
		$previous_module_id = $inputs['previous_module_id'];
		$next_module_id = $inputs['next_module_id'];
		$delay_btw_chapter_exercise = $inputs['delay_btw_chapter_exercise'];
		$last_module_completion_time = UserModuleProgress::where('module_id', $previous_module_id)->where('user_id', Auth::id())->first();
		$last_module_completion_time = (empty($last_module_completion_time)) ? '0000-00-00 00:00:00' : $last_module_completion_time->completed_at;

		$timezone = 'UTC';
		if (\Config::get('srtpl.current_user_timeZone')) {
			$timezone = \Config::get('srtpl.current_user_timeZone');
		}
		$now = Carbon::now($timezone);
		$time_to_access_module = Carbon::parse($last_module_completion_time)->setTimezone($timezone)->addDays($delay_btw_chapter_exercise);
		if ($now >= $time_to_access_module) {
			if (request()->ajax()) {
				return response()->json([
					'success' => 'true',
					]);
			}
		}
		$date_interval = date_diff($now, $time_to_access_module)->format('%d Day %h Hours %i Minute');
		$days_left = $time_to_access_module->diffInDays($now);
		$hours_left = $time_to_access_module->diffInHours($now);
		$minutes_left = $time_to_access_module->diffInMinutes($now);
		if (request()->ajax()) {
			return response()->json([
				'success' => 'false',
				'message' => "Sorry you cannot start this module's exercises yet. There is a delay of $delay_btw_chapter_exercise day(s) between modules. There is still <strong>$date_interval</strong> left.",
				]);
		}
	}
	public function unlock_module() {
		$inputs = AppHelper::getTrimmedData(request()->all());
		User::where('id', Auth::id())->update(['unlocked_module' => $inputs['module_id']]);
		if (request()->ajax()) {
			return response()->json([
				'success' => 'true','message' => 'module unlocked successfully'
				]);
		}
	}
	public function watch_video() {
		$inputs = AppHelper::getTrimmedData(request()->all());
		$user_id = Auth::id();
		if(!isset($inputs['intro_video']))
		{
			$inputs['intro_video']='';
		}
		if(!isset($inputs['graduate_video']))
		{
			$inputs['graduate_video']='';
		}
		Program::where('id', $inputs['program_id'])->update(['watch_video' => 'yes']);
		//Program::where()
		$data=['program_id'=>$inputs['program_id'],
		'user_id'=>$user_id,
		'is_intro_video_watch'=>$inputs['intro_video'],
		'is_gratuate_video_watch'=>$inputs['graduate_video'],
		];
		UserProgram::create($data);

		if (request()->ajax()) {
			return response()->json([
				'success' => 'true','message' => 'video watched','intro_video'=>'yes',
				]);
		}
	}
	// Max number of exercises user can complete per day
	public function noOfExercisePerDay() {
		$NoOfExercise = 0;
		if (isset(Config::get('srtpl.settings')['max_exercise_can_complete_per_day'])) {
			$NoOfExercise = Config::get('srtpl.settings')['max_exercise_can_complete_per_day'];
		}
		$today = Carbon::now()->format('Y-m-d');
		// $DB_server_timeZone = dump(head(json_decode(json_encode(DB::select("SELECT @@@system_time_zone")), true))['@@@system_time_zone']);

		$userCompletedExercises = UserModuleExercisesProgress::where('user_id', Auth::id())->orderBy('completed_at', 'DESC')->limit($NoOfExercise * 2)->get();
		$getConvertedDates = [];
		if (!empty($userCompletedExercises)) {
			$getConvertedDates = $userCompletedExercises->mapWithKeys(function ($item, $key) {
				return [$item['module_exercise_id'] => Carbon::parse($item['completed_at'])->format('Y-m-d')];
			});
		}
		$totalExerciseCompletedToday = 0;
		if (!empty($getConvertedDates)) {
			$value_count = array_count_values($getConvertedDates->all());
			$totalExerciseCompletedToday = isset($value_count[$today]) ? $value_count[$today] : 0;
		}
		$res = [
		'exercise_to_be_completed_per_day' => $NoOfExercise,
		'remaining_exercise' => $NoOfExercise - $totalExerciseCompletedToday,
		'total_exercise_completed_today' => $totalExerciseCompletedToday,
		];
		//dd($res);
		return ($res);
	}

	public function getModulesReviewedWithIn90Days(Request $request, $coach_id)
	{
		$coach_id = Crypt::decryptString($coach_id);
		view()->share('module_action', array(
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => request()->get("_url", route('coaches.index')),
				"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
			));
		$dateBefore90Days = Carbon::now()->subDay(90)->format('Y-m-d');
		$modulesReviewedWithIn90Days = UserModuleProgress::with(['modules.program', 'submittedBy'])->where('reviewed_user_id', $coach_id)->where(DB::raw('reviewed_at'), '>=', $dateBefore90Days)->where('status', 'reviewed')->get();
		view()->share('title', 'Unlock Coach Module Feedback');
		view()->share('modulesReviewedWithIn90Days', $modulesReviewedWithIn90Days);
		return view('modules.unlock-module');
	}

	public function unlockModuleToEditFeedback(Request $request, $reviewed_module_id)
	{

		$reviewed_module_id = Crypt::decryptString($reviewed_module_id);
		$coach_id = Crypt::decryptString(request()->get('coach_id'));  // is coach table's user_id field value...
		$client_id = Crypt::decryptString(request()->get('client_id'));  // is client table's user_id field value...
		try {
			DB::beginTransaction();
			UserModuleProgress::where('id', $reviewed_module_id)->update(['status' => 'unlock']);
			$transaction_forSubmitted_review = CoachTransactionHistory::where('object_id', $reviewed_module_id)->where('object_type', 'user_module_progresses')->where('transaction_type', 'plus')->orderBy('id','desc')->first();
			$coach = Coach::where('user_id', $coach_id)->first();

			/*  process to add reverse entry for the transaction...  */
			//  debit the transaction amount from the current balance ..
			$coach_balance = $coach->balance;
			$prev_transaction_amount = isset($transaction_forSubmitted_review) ? $transaction_forSubmitted_review->transaction_amount : 0 ;
			$coach_balance -= $prev_transaction_amount;
			//update balance in coach table...
			$coach->update(['balance' => $coach_balance]);
			$msg = isset($transaction_forSubmitted_review) ? $transaction_forSubmitted_review->transaction_detail : ' submitted module Feedback.';

			//add reverse transaction entry to unload the reviewed module...
			$transation_history_arr = [
			'user_id' => $coach_id,
			'transaction_type' => 'minus',
			'object_id' => $reviewed_module_id,
			'object_type' => 'user_module_progresses',
			'transaction_amount' => $prev_transaction_amount,
			'transaction_detail' => 'Admin has unlocked ' . $msg
			];
            //fire event..
			event(new CoachTransactionHistoryEvent($transation_history_arr));

			$client = Client::with('user')->where('user_id', $client_id)->first();
            // client credit deduct
			if(!empty($client)) {
				$client_credits = $client->credits;
				$client->update(['credits' => $client_credits - config('srtpl.credit') ]);
			}
            // event to increse client's credit by config('srtpl.credit')..
			$credit_history_arr = [
                'user_id' => $client_id, // clients table  user_id field...
                'object_id' => $reviewed_module_id,
                'object_type' => 'user_module_unlock',
                'transaction_type' => 'minus',
                'credit_score' => config('srtpl.credit')
                ];
                //fire event..
                event(new CreditHistoryEvent($credit_history_arr));

                \Flash::success('Module Feedback unlocked successfully');
                DB::commit();
                return redirect()->back();
            } catch(Exception $e) {
            	\Log::warning($e->getMessage());
            	DB::rolback();
            }
            Flash::error('Module feedback is not unlocked, due to some internal error. Please Ty again!');
            return redirect()->back();
        }
        public function getSubmittedModuleFeedback($module_id, $client_id,$module_exercise_id) {
        	$client_module_exercises = UserModuleExercisesProgress::with(['module', 'module_exercise' => function ($query) {
        		$query->orderBy('exercise_no');
        	}, 'user_module_exercise_questions' => function ($query) {
        		$query->orderBy('question_no');
        		$query->where('parent_question_id', DB::raw('0'));
        	}, 'user_module_exercise_questions.question_answer' => function ($query) use ($client_id) {
        		$query->where('user_id', $client_id);
        	}, 'user_module_exercise_questions.sub_questions' => function ($query) {
        		$query->orderBy('question_no');
        	}, 'user_module_exercise_questions.sub_questions.question_answer' => function ($query) use ($client_id) {
        		$query->where('user_id', $client_id);
        	}])
        	->select(['user_id', 'module_id', 'module_exercise_id'])
        	->where('user_id', $client_id)
        	->where('module_id', $module_id)
        	->distinct();
        	return $client_module_exercises;
        }
        public function viewexcercise(Request $request, $module_id,$excercise_id) {

        	$update_view_feedback_at_flag = false;
        	if (request()->get('user_id', false)) {
        		$user_id = Crypt::decryptString(request()->get('user_id'));
        	} else {
        		$user_id = Auth::id();
        		$update_view_feedback_at_flag = true;
        	}
        	$module_id = Crypt::decryptString($module_id);
        	$excercise_id = Crypt::decryptString($excercise_id);
        	$client_module_exercises = $this->getSubmittedExerciseFeedback($module_id, $user_id,$excercise_id);

        	$total_exercise = count($client_module_exercises->get());
        	$client_module_exercises = $client_module_exercises->paginate(1);
        	view()->share('title', 'View Feedback');
        	view()->share('total_exercise', $total_exercise);
        	view()->share('client_module_exercises', $client_module_exercises);
        	$module = head($client_module_exercises->items());
        	$module_description = '';
        	if (!empty($module)) {
        		$module = !empty($module) ? $module : '';
        		$module_exercise = $module->module_exercise;
        		$this->module_title = $module->module->module_no . '.' . $module_exercise->exercise_no . ' - ' . $module_exercise->title;
        		$module_description = $module_exercise->sort_description;
        	}

		//update view_feedback_at field in table.. only if the logged in user is client..
        	if($update_view_feedback_at_flag) {
        		$update_view_feedback_at = UserModuleProgress::where('user_id', $user_id)->where('module_id', $module_id)->whereNull('view_feedback_at')->first();
        		if (!empty($update_view_feedback_at)) {
        			$update_view_feedback_at->update(['view_feedback_at' => Carbon::now()->format('Y-m-d H:i:s')]);
        		}
        	}
        	view()->share('module_title', $this->module_title);
        	view()->share('module_description', $module_description);
        	view()->share('module_id', $module_id);
        	view()->share('excercise_id', $excercise_id);
        	return view('clients.dashboard.view_excercise');
        }

        public function downloadcertificate(Request $request) {

        	$user = Auth::user();
        	$client=Client::with('program','program.modules')->where('user_id',$user->id)->first();

        	$finish_date=UserModuleProgress::where('user_id',Auth::id())->orderBy('id','DESC')->first();
			$date1=Carbon::parse($finish_date->completed_at)->format('dS M Y');


        	$pdf = PDF::loadView('clients.dashboard.download_certificate', ['user_name' => $user->name, 'program_name' => $client->program->program_name, 'theme' => 'limitless.pdf', 'title' => 'Download Certificate','client'=>$client,'date'=>$date1]);
        	$pdf->setPaper('a4');
        	$pdf->setOrientation('landscape');
		// $pdf->setOption('margin-top', 20);
		// $pdf->setOption('margin-right', 15);
		// $pdf->setOption('margin-bottom', 15);
		// $pdf->setOption('margin-left', 15);
        	$pdf->setOption('header-right', '');
		// return $pdf->stream('client-' . $user_id . '-module-' . $module_id . '.pdf');
        	return $pdf->stream('gradution_certificate.pdf');
        }
    }
