<?php
namespace App\Http\Controllers;
use App;
use AppHelper;
use App\Models\Activation;
use App\Models\BroadcastEmail;
use App\Models\CardDetail;
use App\Models\Client;
use App\Models\CoachTransactionHistory;
use App\Models\User;
use App\Models\Program;
use Auth;
use Carbon\Carbon;
use DB;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Models\CoachNote;
use App\Models\CoachSceduleBooked;
use App\Models\CoachFreeSessionBooked;
use App\Models\CoachSchedule;
use App\Models\UserModuleProgress;
use Session;
use Mail;
use PayPal;
use Cache;
use \Stripe\Plan;
use \Stripe\Token;
use \Stripe\Coupon;
use App\Models\Setting;
use App\Models\Agent;

class ClientController extends Controller {
	protected $current_user;
	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->middleware('check_for_permission.access:clients.create', ['only' => ['create', 'store']]);
		$this->middleware('check_for_permission.access:clients.view', ['only' => ['index', 'show']]);
		$this->middleware('check_for_permission.access:clients.update', ['only' => ['edit', 'update']]);
		$this->middleware('check_for_permission.access:clients.delete', ['only' => ['destroy']]);
		$this->title = trans('comman.clients');
		view()->share('title', $this->title);
		view()->share('timezones', get_timezone_list());
		AppHelper::path('uploads/user/');
	}
	/**
	 * Get a validator for an incoming creating/updating request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data, $mode = 'create', $edit_rules = array()) // $mode = create / edit
	{
		$rules = [
			'first_name' => 'required|max:50',
			'last_name' => 'required|max:50',
			'skype_id' => 'max:190',
			'password' => 'max:190',
			'image' => 'image',
		];
		$messages = [
			'coach_id.required' => 'The coach field is required.',
			'role_id.required' => 'The role field is required.',
			'program_id.required' => 'The program field is required.',
			'timezone.required' => 'The Timezone field is required.',
			'password.regex' => 'Password must be combination of number and both uppercase and lowercase letters.',
			'image' => 'The avatar/photo field must be an image.',
		];
		if ($mode == 'edit') {
			foreach ($edit_rules as $field => $rule) {
				$rules[$field] = $rule;
			}
		} else {
			$rules['password'] = 'required|min:8|regex:/^(?=.*[0-9])[a-zA-Z0-9!@#$%^&*]+$/|confirmed';
			// $rules['password'] = 'required|min:8|regex:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]+$/|confirmed';
			$rules['email'] = [
				'required',
				'email',
				'max:190',
				Rule::unique('users')->where(function ($query) {
					$query->where('deleted', '0');
				}),
			];
		}
		return Validator::make($data, $rules, $messages);
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {
		$user_type = Auth::user()->user_type;
		$user_id = Auth::id();
		$sort_order = $request->get('sort_order',[]);
		$action_nav = array(
			"add_new" => array("title" => '<b><i class="icon-diff-added"></i></b> ' . trans("comman.addclient"), "url" => route('clients.create'),
				"attributes" => array("class" => "btn bg-success btn-labeled heading-btn", 'title' => 'Add New')),
		);
		if (!Auth::user()->hasAccess('clients.create')) {
			unset($action_nav['add_new']);
		}
		if ($user_type == 'coach') {
			$object = App::make("App\Http\Controllers\ModuleController");
			view()->share('modules', $object->ajaxModulesWithProgramName($request));
			$object = App::make("App\Http\Controllers\ProgramController");
		    view()->share('programs', $object->ajaxCoachPrograms($request));
			view()->share('clients', $this->get_index(array()));
			$object = App::make("App\Http\Controllers\ModuleController");
		    view()->share('modules', $object->ajaxModulesWithProgramName($request));
			view()->share('title', $this->title);
			return view('clients.coach-index');
		}
		view()->share('module_action', $action_nav);
		$object = App::make("App\Http\Controllers\CoachController");
		view()->share('coaches', $object->ajaxCoaches($request));
		$object = App::make("App\Http\Controllers\ProgramController");
		view()->share('programs', $object->ajaxCoachPrograms($request));
		$object = App::make("App\Http\Controllers\ModuleController");
		view()->share('modules', $object->ajaxModulesWithProgramName($request));
		view()->share('months', config('srtpl.months'));
		view()->share('clients', $this->get_index($sort_order));
		view()->share('title', $this->title);
		return view('clients.index');

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request) {
	    if (request()->get('_url')) {
			view()->share('module_action', array(
				"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route(request()->get('_url')),
					"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
			));
		} else {
			view()->share('module_action', array(
				"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('clients.index'),
					"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
			));
		}
		$object = App::make("App\Http\Controllers\CoachController");
		view()->share('coaches', $object->ajaxCoaches($request));
		$param['status'] = 'published';
		$object = App::make("App\Http\Controllers\ProgramController");
		view()->share('programs', $object->ajaxCoachPrograms($request, $param));
		//Get Roles
		$object = App::make("App\Http\Controllers\RoleController");
		view()->share('roles', $object->getAllRoles());
		view()->share('contact_methods_list', config('srtpl.contact_methods'));
		view()->share('title', trans("comman.client"));
		return view('clients.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$input = AppHelper::getTrimmedData($request->all());
		$this->validator($request->all())->validate();
		if (!isset($input['status'])) {
			$input['status'] = 'in_active';
		} else {
			$input['status'] = 'active';
		}
		try {
			// DB::transaction(function () use ($input, $request) {
			DB::beginTransaction();
			$userFields = array();
			$clientFields = array();
			$userFields = [
				'first_name' => $input['first_name'],
				'last_name' => $input['last_name'],
				'name' => $input['first_name'] . ' ' . $input['last_name'],
				'email' => $input['email'],
				'password' => bcrypt($input['password']),
				'timezone' => $input['timezone'],
				'skype_id' => $input['skype_id'],
				'mobile_no' => $input['mobile_no'],
				'status' => $input['status'],
				'user_type' => "client",
				'role_id' => $input['role_id'],
				'created_by' => Auth::id(),
				'subscription_plan_status' => 'Active',
				'addedby'=>'admin',
				'nextpaymentdate'=>$input['nextpaymentdate'],
				'registration_completed'=>'1',
			];
			$user = User::create($userFields);
			$code = AppHelper::GenrateCode();

			$clientFields = [
				'user_id' => $user->id,
				'coach_id' => $input['coach_id'],
				'LPAP_initial_fee' => $input['LPAP_initial_fee'],
				'program_id' => $input['program_id'],
				'coach_gender' => $input['coach_gender'],
                'agent_id' => $input['agent_id']
			];

            //if client is created by client manager we need clients module_restriction to be defaulted to their managers settings
            if (isset(Auth::user()->user_type) && Auth::user()->user_type == 'agent') {
                $agent = Agent::where('user_id', '=', Auth::user()->id)->first();
                if($agent['module_restriction'] == 1){
                    $clientFields['module_restriction'] = 'Yes';
                }else{
                    $clientFields['module_restriction'] = 'No';
                }

                //add credits based on agent
                if(isset($agent['credits_per_month']) && $agent['credits_per_month'] != null){
                    $clientFields['credits'] = $agent['credits_per_month'];
                }else{
                    $clientFields['credits'] = 0;
                }

                $clientFields['is_free_session_complete'] = 'y';
            }

            if (isset($input['contact_methods'])) {
				$clientFields['contact_methods'] = $input['contact_methods'];
			}
			$model = Client::create($clientFields);

			if ($input['status'] == 'in_active') {
				$create_activation = Activation::create([
					"code" => $code,
					"user_id" => $user->id,
				]);
				$email = $input['email'];
				$program = Program::where('id', $input['program_id'])->first();

				$program_nm = !empty($program) ? $program->program_name : '';
				Mail::send(
					'email_template.welcome', ['code' => $code, 'email' => $input['email'], 'first_name' => $input['first_name']], function ($message) use ($email,$program_nm) {
						$message->to($email)->subject("Welcome to the Life Process $program_nm Program");
						// $bcc = (!empty(config('srtpl.bccmail'))) ? explode(',', config('srtpl.bccmail')) : '';
						// if (!empty($bcc)) {
						// 	$message->bcc($bcc);
						// }
					});
			} else {
				$create_activation = Activation::create([
					"code" => AppHelper::GenrateCode(),
					"user_id" => $user->id,
					"completed" => '1',
					"completed_at" => Carbon::now()->format('Y-m-d H:i:s'),
				]);
			}

            //If the client is created by an client manager we need to send a different email.
            if (isset(Auth::user()->user_type) && Auth::user()->user_type == 'agent') {
                $email = $input['email'];
                Mail::send(
                    'email_template.client_manager_new_client', ['password' => $input['password'], 'email' => $input['email'], 'first_name' => $input['first_name']], function ($message) use ($email) {
                        $message->to($email)->subject("Welcome to the Life Process Program");
                    }
                );
            }

			DB::commit();

			if ($request->ajax()) {
				return response()->json([
					'success' => 'true',
					'data' => $user,
				]);
			}
			Flash::success(trans("comman.client_added"));

		} catch (Exception $e) {
			DB::rollback();
			Flash::error("Error found while creating client. Please, try again!");
		}
		if ($request->get('save_exit')) {
			return redirect()->route('clients.index');
		} else {
			return redirect()->route('clients.create');
		}
	}
	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		$id = Crypt::decryptString($id);
		$client_details = Client::with('user','user.credit_card_detail','user.credit_history','coach')->where('user_id', $id)->first();
       	$request= request();
        $param['status'] = 'published';
		$object = App::make("App\Http\Controllers\ProgramController");
		view()->share('programs', $object->ajaxCoachPrograms($request, $param));
		$object = App::make("App\Http\Controllers\CoachController");
		view()->share('coaches', $object->ajaxCoaches($request, array('coach_gender' => (isset($client)) ? $client['coach_gender'] : '', 'program_id' => (isset($client)) ? $client['program_id'] : '', 'available' => 'yes')));
		$object = App::make("App\Http\Controllers\ModuleController");
        view()->share('modules', array());
        $coach_note = CoachNote::where('client_id',$id)->orderBy('id', 'desc')->get();
        view()->share('coach_note', $coach_note);
        $coach_booked_session=CoachSceduleBooked::with('coach_schedule')->where('booked_user_id',$id)->get();
        $coach_cancle_session=CoachSceduleBooked::withoutGlobalScopes()->with('coach_schedule','coach_schedule.user')->where('booked_user_id',$id)->where('deleted','1')->where('session_status','cancelled')->get();
        $today = Carbon::now()->format('Y-m-d H:i:s');

        $moduleremain=UserModuleProgress::where('user_id',$id)->where('status','=',NULL)->where('module_exercise_id','!=',0)->get();
        $moduleremain=count($moduleremain);
        view()->share('module_remain', $moduleremain);

        $bookedSchedule = CoachSchedule::with(['coachschedulebooked' => function ($query) use($id) {
			$query->where('booked_user_id','=',$id)->where('session_status','=',null);
		}])->where('start_datetime','>=',$today)->orderBy('start_datetime','ASC')->get();
        view()->share('upcoming_coach_sessions', $bookedSchedule);
        view()->share('coach_booked_session', $coach_booked_session);
        view()->share('coach_cancle_session', $coach_cancle_session);
        $timezone = Auth::user()->timezone;
		view()->share('timezone', $timezone);
        return view('clients.show_details',compact('client_details'));
	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id, Request $request) {
		$id = Crypt::decryptString($id);
		if (request()->get('_url')) {
			view()->share('module_action', array(
				"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route(request()->get('_url')),
					"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
			));
		} else {
			view()->share('module_action', array(
				"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('clients.index'),
					"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
			));
		}
		$tmp_client = Client::find($id);
		if (is_null($tmp_client)) {
			return redirect()->route('clients.index');
		}
		$user = User::find($tmp_client->user_id);
		$client = $tmp_client->toArray() + array_only($user->toArray(), [
			'first_name',
			'last_name',
			'email',
			'timezone',
			'skype_id',
			'mobile_no',
			'status',
			'role_id',
			'address_line_one']
		);
		// dump($client); exit();
		$object = App::make("App\Http\Controllers\CoachController");
		view()->share('coaches', $object->ajaxCoaches($request, array('coach_gender' => (isset($client)) ? $client['coach_gender'] : '', 'program_id' => (isset($client)) ? $client['program_id'] : '', 'available' => 'yes')));

		$object = App::make("App\Http\Controllers\ProgramController");
		view()->share('programs', $object->ajaxCoachPrograms($request));
		//Get Roles
		$object = App::make("App\Http\Controllers\RoleController");
		view()->share('roles', $object->getAllRoles());

		view()->share('contact_methods_list', config('srtpl.contact_methods'));
		view()->share('title', trans("comman.client"));
		return view('clients.edit', compact('client'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {

		$id = Crypt::decryptString($id);
		$client = Client::findOrFail($id);
		$user = User::findOrFail($client->user_id);
		$input = AppHelper::getTrimmedData($request->all());
		$extra_rules = array(
			'email' => [
				'required',
				'max:190',
				Rule::unique('users')->ignore($user->id)->where(function ($query) {
					$query->where('deleted', '0');
				}),
			],
		);
		// Do we need to update the password as well?
		if ($request->has('password')) {
			$extra_rules['password'] = 'required|min:8|regex:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9!@#$%^&*]+$/|confirmed';
		}
		$this->validator($request->all(), 'edit', $extra_rules)->validate();

		$input['name'] = $input['first_name'] . ' ' . $input['last_name'];
		if (!isset($input['status'])) {
			$input['status'] = 'in_active';
		}
		$userFields = array();
		$clientFields = array();
		$userFields = [
			'first_name' => $input['first_name'],
			'last_name' => $input['last_name'],
			'name' => $input['first_name'] . ' ' . $input['last_name'],
			'email' => $input['email'],
			'timezone' => $input['timezone'],
			'skype_id' => $input['skype_id'],
			'mobile_no' => $input['mobile_no'],
			'status' => $input['status'],
			'role_id' => $input['role_id'],
		];
		// Do we need to update the password as well?
		if ($request->has('password')) {
			$userFields['password'] = bcrypt($input['password']);
		}

		$clientFields = [
			'user_id' => $user->id,
			'coach_id' => $input['coach_id'],
			'LPAP_initial_fee' => $input['LPAP_initial_fee'],
			'program_id' => $input['program_id'],
			'coach_gender' => $input['coach_gender'],
            'agent_id' => $input['agent_id']
		];
		if (isset($input['contact_methods'])) {
			$clientFields['contact_methods'] = $input['contact_methods'];
		} else {
			$clientFields['contact_methods'] = [];
		}
		DB::transaction(function () use ($client, $user, $clientFields, $userFields) {
			$client->update($clientFields);
			$user->update($userFields);
			Flash::success(trans("comman.client_updated"));
		});

		if ($request->get('save_exit')) {
			return redirect()->route('clients.index');
		} else {
			return redirect()->route('clients.edit',$id);
		}
	}
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		$id = Crypt::decryptString($id);
		$model = Client::find($id);
		$user=User::where('id',$model->user_id)->first();
		$stripe_sub_id=$user->stripe_sub_id;
		if($stripe_sub_id!='')
		{

			if($user->paypal_start_date=='')
			{
				\Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

			    try {

			        $subscription = \Stripe\Subscription::retrieve('sub_CXAlP3TxLohibZ');
			        $response=$subscription->cancel(['at_period_end' => true]);

			    } catch (\Stripe\Error\InvalidRequest $e) {
			    	$response='';
					  // Invalid parameters were supplied to Stripe's API
				}

			}
			else
			{
				$provider = PayPal::setProvider('express_checkout');
	       		$profilecancel=$provider->suspendRecurringPaymentsProfile($stripe_sub_id);
			}
		}
		if ($model) {
			$dependency = $model->deleteValidate($id);
			if (!$dependency) {
				DB::transaction(function () use ($model) {
					$model->deleted = '1';
					$model->save();
					$user_model = User::find($model->user_id);
					$user_model->deleted = '1';
					$user_model->status='in_active';
					$user_model->save();
					DB::table('activations')->where('user_id', $model->user_id)->delete();
					CardDetail::where('user_id', $model->user_id)->update(['deleted' => '1']);
				});
				Flash::success(trans("comman.client_deleted"));
			} else {
				Flash::error(trans("comman.client_dependency_error", ['dependency' => $dependency]));
			}
		} else {
			Flash::error(trans("comman.client_error"));
		}
		return redirect()->route('clients.index');
	}

	// Get current logged in client profile
	public function getProfile(Request $request) {
		$user = Auth::user();
		if ($user->user_type != 'client') {
			return redirect()->route('login');
		}
		$tmp_client = Client::where('user_id', $user->id)->first();
		$card_details = CardDetail::where('user_id', $user->id)->first();
		if (!empty($card_details)) {
			$card_details = $card_details->toArray();
		} else {
			$card_details = [];
		}
		$client = $tmp_client->toArray() + array_only($user->toArray(), [
			'first_name',
			'middle_name',
			'last_name',
			'email',
			'timezone',
			'mobile_no',
			'status',
			'role_id',
			'image',
			'country_id',
			'city',
			'zip_code'
			]
		) + $card_details;

		$object = App::make("App\Http\Controllers\CoachController");
		view()->share('coaches', $object->ajaxCoaches($request, array('available' => 'yes')));
		//Get Roles
		$object = App::make("App\Http\Controllers\RoleController");
		view()->share('roles', $object->getAllRoles());

		$object = App::make("App\Http\Controllers\CountryController");
		view()->share('countries', $object->ajaxCountries($request));

		view()->share('contact_methods_list', config('srtpl.contact_methods'));
		view()->share('title', trans("comman.client"));
		view()->share('client', $client);
		return view('clients.dashboard.edit_profile');
	}

	//update current user profile...
	public function updateProfile(Request $request) {

		$user = Auth::user();
		$client = Client::where('user_id', $user->id)->first();
		$card_details = CardDetail::where('user_id', $user->id)->first();
		$input = AppHelper::getTrimmedData($request->all());

		$extra_rules = array(

		);
		$this->validator($request->all(), 'edit', $extra_rules)->validate();
		$userFields = array();
		$clientFields = array();
		if ($request->hasFile('image')) {
			$file['image'] = \AppHelper::getUniqueFilename($request->file('image'), AppHelper::getImagePath());
			$request->file('image')->move(AppHelper::getImagePath(), $file['image']);
			$input['image'] = $file['image'];
		} else {
			$input['image']=Auth::user()->image;
		}
		$userFields = [
			'first_name' => $input['first_name'],
			'last_name' => $input['last_name'],
			'middle_name' => $input['middle_name'],
			'name' => $input['first_name'] . ' ' . $input['last_name'],
			'email' => $input['email'],
			'timezone' => $input['timezone'],
			'image' => $input['image'],
		];

		$clientFields = [
			'user_id' => $user->id,
		];

		try {
			DB::beginTransaction();
			$client->update($clientFields);
			$user->update($userFields);

			if ($request->get('current_password')) {
				Validator::extend('validateCurrentPassword', 'App\Validators\ChangePasswordValidator@validateCurrentPassword');
				$result = $this->validate($request, [
					'current_password' => 'required|validateCurrentPassword',
					'new_password' => 'required_with:current_password|min:8|regex:/^(?=.*[0-9])[a-zA-Z0-9!@#$%^&*]+$/|confirmed',
				],
					[
						'current_password.validate_current_password' => 'Password doesn\'t match with your current password.',
						'new_password.regex' => 'Password must be combination of number and both uppercase and lowercase letters.',
					]);
			}
			if ($request->get('new_password')) {
				$editUser = Auth::user();
				$credentials['password'] = bcrypt($request->get('new_password'));
				$editUser->update($credentials);
			}
			Flash::success(trans("comman.client_profile_updated"));
			DB::commit();
		} catch (Exception $e) {
			DB::rollback();
			\Log::error($e->getMessage());
		}
		// Flash::success(trans("comman.client_profile_updated"));

		return redirect()->route('clients.update.profile');
	}




	// Open popup to Send/broadcast mail to the filtered clients list...
	public function createSendMail(Request $request) {
		return view('clients.send_mail');
	}

	// store all users and mail content to send/broadcast...
	public function broadcastMail(Request $request) {
		$input = AppHelper::getTrimmedData($request->all());

		$result = Validator::make($input, [
			'subject' => 'required',
			'message' => 'required',
		])->validate();
		$to = explode(",", $input['to']);
		foreach ($to as $to_email) {
			$detail = [
				'to' => $to_email,
				'subject' => $input['subject'],
				'message' => $input['message'],
			];
			$broadcast = BroadcastEmail::create($detail);
		}
		if ($request->ajax()) {
			return response()->json([
				'success' => 'true',
				'data' => '',
			]);
		}
	}
	// function to get the listing for the index page...
	public function get_index($sort_order) {
		$models = Client::with(['user' => function ($q) {
			if(Auth::user()->user_type == 'agent') {
				$q->where('created_by', Auth::id());
			}
			if(Auth::user()->user_type == 'coach') {
				$q->where('status','active');
			}
			if (request()->get('status')==null) {
				$q->where('status','active');
			}


		}, 'user.latest_module', 'coach.user' => function ($q) {
			$q->where('user_type', '=', "coach");
		}, 'program', 'user.completed_modules', 'user.send', 'user.receive'])->where("clients.deleted", "0");
		$models->select(array(
			"clients.*",
		))->leftJoin('users', function ($join) {
			$join->on('users.id','=', 'clients.user_id');
		});
		if (request()->get('name_or_email', false)) {
			$models->whereHas('user', function ($q) {
				$q->where('name', 'like', "%" . request()->get("name_or_email") . "%")
					->orWhere('email', 'like', "%" . request()->get("name_or_email") . "%");
			});
		}
		if (request()->get('status', false)) {
			$models->whereHas('user', function ($q) {
				$q->where('status', '=', request()->get("status"));
			});
		}

		if (request()->get('program_id', false)) {
			$models->whereHas('program', function ($q) {
				$q->where('programs.id', '=', request()->get("program_id"));
			});
		}
		if (request()->get('module_completed', false)) {
			$models->whereHas('user.completed_modules', function ($q) {
				$q->where('modules.id', '=', request()->get("module_completed"));
			});
		}if (request()->get('module_progress', false)) {
			$models->whereHas('user.latest_module', function ($q) {
				$q->where('modules.id', '=', request()->get("module_progress"));
				$q->where('user_module_progresses.completed_at', '=', null);
			});
		}
		if (request()->get('read_only_coach', false)) {
            $models->where('invite_coach', '=', request()->get("read_only_coach"));
        }
        if (request()->get('coach', false)) {
            $models->where('coach_id', '=', request()->get("coach"));
        }
		if (request()->get('date_joined', false) ) {
			$models->where(\DB::raw("DATE_FORMAT(created_at,'%d-%m-%Y')"), 'like', "%" . request()->get("date_joined") . "%");
		}
		if (request()->get('month_joined', false)) {
			$models->where(\DB::raw("MONTH(created_at)"), '=', request()->get("month_joined"));
		}
		if (request()->get('not_logged_in', false)) {
			$models->whereHas('user', function ($q) {
				$q->where(\DB::raw("DATE(last_active)"), '<', Carbon::now()->subDays(request()->get("not_logged_in"))->toDateString());
				$q->orWhereNull('last_active');
			});
		}
		if (Auth::user()->user_type == 'coach') {
			$models->whereHas('coach', function ($q) {
				$q->where('user_id', '=', Auth::id());
			});
		}

		if (!empty($sort_order) && is_array($sort_order)) {
			$sortByRelation = true;
			foreach ($sort_order as $column => $direction) {


					if(strpos($column, ".") ==false)
					{
						$models->orderBy($column, $direction);
						$sortByRelation = false;
					}
					else
					{

						if($column=='users.last_active')
						{
							$models->orderBy('users.updated_at', $direction);
						}
						if($column!='user.latest_module' && $column!='program.program_name' && $column!='coach.user.name')
						{
							if(strtolower($direction)=="asc"){

								$result = $models->orderBy($column,$direction);
							}else{
								$result = $models->orderBy($column,$direction);
							}
						}
					}

			}
		} else {
			$models->orderBy('clients.id', 'DESC');
		}
		$result = $models->get();

        //If we are read only coach then remove coaches that aren't related to them.
        if(Auth::user()->user_type == 'read-only-coach'){
            foreach($result as $key => $res){
                if($res->invite_coach != Auth::user()->email){
                    unset($result[$key]);
                }
            }
        }


		foreach ($sort_order as $key => $val) {
			if($key=='user.latest_module' || $key=='program.program_name' || $key=='coach.user.name')
			{
				if(strpos($key, ".") !==false)
				{
					if(strtolower($val)=="asc"){
						$result = $result->sortBy($key);
					}else{
						$result = $result->sortByDesc($key);
					}
			    	$result->values()->all();
				}
			}
		}
		// // filter the result to ignore the coaches which doesn't have user
		$result = $result->filter(function ($item, $key) {
			return !empty($item->user);
		});

        $per_page = config('srtpl.row_per_page');
		if(isset(Cache::get('settings')['per_page'])) {
            $per_page = Cache::get('settings')['per_page'];
        }
   		return $this->collection_paginate($result,$per_page);
	}

	public function get_newest_clients() {
		$models = Client::with(['user.latest_module', 'coach.user', 'program'])->where("clients.deleted", "0");
		$models->select(array(
			"clients.*",
		))->whereHas('user', function ($q) {
			$q->where('user_type', '=', "client");
		})->where(DB::raw('MONTH(created_at)'), Carbon::now()->format('n'))
			->orderBy('created_at', 'DESC');
		return $models->get();
	}
	// get total credits, client has...
	public function getClientTotalCredits() {
		$credits = Client::where('user_id', Auth::id())->select('credits')->first();
		if (!empty($credits)) {
			return $credits['credits'];
		}
		return 0;
	}

	//ajax check client's available credit and can book the coaching session schedule...
	public function ajaxCheckClientCredit() {
		$credit_available = $this->getClientTotalCredits();
		if ($credit_available < config('srtpl.credit')) {
			return ['is_available' => 'false', 'my_total_credits' => $credit_available];
		}
		return ['is_available' => 'true', 'my_total_credits' => $credit_available];
	}

	public function getClient() {
		$clients = [];
		$object = client::with('user')->get()->toArray();

		foreach ($object as $key => $row) {
			$clients[$row['user_id']] = $row['user']['name'];
		}

		return $clients;
	}

	public function getnote($id) {

		$get_note = Client::where('id', $id)->select('admin_note', 'updated_at')->first();
		$timezone = Auth::user()->timezone;
		view()->share('timezone', $timezone);
		view()->share('get_note', $get_note);
		return view('clients.admin-note', ['id' => $id]);
	}
	public function savenote($id, Request $request) {

		$client = Client::findOrFail($id);
		$input = $request->all();
		$result = $this->validate($request, [
			'admin_note' => "required",
		]);
		$client->admin_note = $input['admin_note'];
		$client->save();
		Flash::success(trans("comman.admin_note_update"));
		if ($request->ajax()) {
			return response()->json([
				'success' => 'true',
				'data' => $input,
			]);
		}

	}
	public function ajaxContactByContactMethod() {
		$method = request()->get('contact_method');
		$user_id = request()->get('booked_user_id');
		$client = Client::where('user_id', $user_id)->with('user')->first();
		if ($method == 'phone') {
			return $client->user->mobile_no;
		} else if ($method == 'skype') {
			return $client->user->skype_id;
		} else if ($method == 'chat') {
			return $client->user->email;
		}
	}
	public function getCoach() {
		$user_id = Auth::id();
		$coach = Client::where("clients.deleted", "0")->where('user_id', '=', $user_id)->with('coach.user')->first();
		if (isset($coach->coach)) {
			return $coach->coach->user->first_name;
		} else {
			return null;
		}

	}

	public function coaching() {

		$session_id = CoachSceduleBooked::where('session_status', null)->where('deleted', '0')->get(['coach_schedules_id']);
		$user = Auth::user();
		$user_id = Auth::id();
		$timezone = 'UTC';
		if (isset($user->timezone)) {
			$timezone = $user->timezone;
		}
		//dd($timezone);


		$client_detail = Client::where('user_id', '=', $user_id)->with('coach.user','program')->first();
		$client = Client::with('program')->where('user_id',$user->id)->first();

		if(!empty($client_detail->coach_id))
		{
		    $coach_timezone = $client_detail->coach->user->timezone;
		}
		else
		{
		    $coach_timezone='';
		}
		if(request()->get('e_r', false)) {
			if (!$client->coach_id) {
				view()->share('dashboard_message', 'Please contact administrator to assign a coach to you.');
			   $coach_timezone = '';
			}
			return view('client-dashboard');
		}
		$client_program = $client_detail->program->program_name;
		$client_timezone = $client_detail->user->timezone;
		$client = Auth::user()->name;
		$coach = $this->getCoach();
		$credits = Client::where('clients.user_id', $user_id)->where("clients.deleted", "0")->value('credits');
		$free_session_booked = User::where('users.id', $user_id)->where("users.deleted", "0")->value('is_free_session_booked');
		$gratuate_session_booked = User::where('users.id', $user_id)->where("users.deleted", "0")->value('is_gratuate_session_booked');

		//dd($gratuate_session_booked);
		$gratuate_session_details = CoachSceduleBooked::with(['coach_schedule'=> function ($query){
			$query->where('status','booked');
		}])->where('booked_user_id','=',$user_id)->where('booked_for','g')->whereNull('session_status')->first();

		if(!empty($gratuate_session_details))
		{
			$booked_slot_val = $gratuate_session_details->booked_slot;

			if($booked_slot_val>0)
			{
					if($booked_slot_val==1){
						$start_time = Carbon::createFromFormat('Y-m-d H:i:s',$gratuate_session_details->coach_schedule->start_datetime)->format('H:i a');
						$start = Carbon::createFromFormat('Y-m-d H:i:s',$gratuate_session_details->coach_schedule->start_datetime)->format('H:i');
						$end_time = Carbon::parse($start)->addMinutes(20)->format('H:i a');
						$total_time = $start_time."-".$end_time;
						$endtime=Carbon::parse($start)->addMinutes(20)->format('H:i');
					}
					elseif($booked_slot_val==2){
						$start_time = Carbon::createFromFormat('Y-m-d H:i:s',$gratuate_session_details->coach_schedule->start_datetime)->format('H:i a');
						$start = Carbon::parse($gratuate_session_details->coach_schedule->start_datetime)->addMinutes(20)->format('H:i');
						$end_time = Carbon::parse($start)->addMinutes(20)->format('H:i a');
						$endtime=Carbon::parse($start)->addMinutes(20)->format('H:i');
						$total_time = $start_time."-".$end_time;

					}
					elseif ($booked_slot_val==3) {
						$start_time = Carbon::parse($gratuate_session_details->coach_schedule->start_datetime)->addMinutes(40)->format('H:i a');
						$start = Carbon::parse($gratuate_session_details->coach_schedule->start_datetime)->addMinutes(40)->format('H:i');
						$end_time = Carbon::createFromFormat('Y-m-d H:i:s',$gratuate_session_details->coach_schedule->start_datetime)->format('H:i a');
						$endtime=Carbon::createFromFormat('Y-m-d H:i:s',$$gratuate_session_details->coach_schedule->start_datetime)->format('H:i');
						$total_time = $start_time."-".$end_time;
					}
			}
		}
		$client_user_time = Carbon::now()->setTimezone($timezone)->toDateTimeString();

		$free_session_details = CoachSceduleBooked::with(['coach_schedule'=> function ($query){
			//$query->where('status','booked');
			$query->where('end_datetime','>=',Carbon::now()->toDateTimeString());
		}])->where('booked_user_id','=',$user_id)->where('booked_for','f')->whereNull('session_status')->first();
        //dd($free_session_details);
	    $endtime='';
		if($free_session_details!=null){
		$booked_slot_val = $free_session_details->booked_slot;

		if(!empty($free_session_details->coach_schedule))
		{
		if($booked_slot_val>0){
					if($booked_slot_val==1){
						$start_time = Carbon::createFromFormat('Y-m-d H:i:s',$free_session_details->coach_schedule->start_datetime)->format('H:i a');
						$start = Carbon::createFromFormat('Y-m-d H:i:s',$free_session_details->coach_schedule->start_datetime)->format('H:i');
						$end_time = Carbon::parse($start)->addMinutes(20)->format('H:i a');
						$total_time = $start_time."-".$end_time;
						$endtime=Carbon::parse($start)->addMinutes(20)->format('H:i');
					}
					elseif($booked_slot_val==2){
						$start_time = Carbon::parse($free_session_details->coach_schedule->start_datetime)->addMinutes(20)->format('H:i a');
						$start = Carbon::parse($free_session_details->coach_schedule->start_datetime)->addMinutes(20)->format('H:i');
						$end_time = Carbon::parse($start)->addMinutes(20)->format('H:i a');
						$endtime=Carbon::parse($start)->addMinutes(20)->format('H:i');
						$total_time = $start_time."-".$end_time;
					}
					elseif ($booked_slot_val==3) {
						$start_time = Carbon::parse($free_session_details->coach_schedule->start_datetime)->addMinutes(40)->format('H:i a');
						$start = Carbon::parse($free_session_details->coach_schedule->start_datetime)->addMinutes(40)->format('H:i');
						$end_time = Carbon::createFromFormat('Y-m-d H:i:s',$free_session_details->coach_schedule->end_datetime)->format('H:i a');
						$endtime=Carbon::createFromFormat('Y-m-d H:i:s',$free_session_details->coach_schedule->end_datetime)->format('H:i');
						$total_time = $start_time."-".$end_time;
					}
				}
			}
		}
		if($endtime=='')
		{

		}

		view()->share('free_session_endtime', $endtime);
		$coach_session_details = CoachSceduleBooked::with(['coach_schedule'=> function ($query) use ($client_user_time){
			$query->where('end_datetime','>=',$client_user_time);
			$query->orderBy('start_datetime','ASC');
		}])->where('booked_user_id','=',$user_id)->where('coach_schedules_booked.booked_for','s')->whereNull('session_status')
		->join('coach_schedules', function ($join) {
			$join->on('coach_schedules_booked.coach_schedules_id', '=', 'coach_schedules.id');
		})->orderBy('coach_schedules.start_datetime', 'ASC')->get();
		//dd($coach_session_details);
		if (is_null($credits)) {
			$credits = 0;
		} else {
			$credits = $credits;
		}
		$user_type = Auth::user()->user_type;
		$timezone=Auth::user()->timezone;
        $currenttime =  Carbon::now()->format('Y-m-d H:i:s');
        $id = Auth::id();

        if($user_type=='client')
        {
            $request = DB::table('meeting')->where('client_id',$id)->where('end_datetime','>=',$currenttime)->get();
        }
        else
        {
            $request = DB::table('meeting')->where('coach_id',$id)->where('start_datetime','<=',$currenttime)->where('end_datetime','>=',$currenttime)->get();
        }

        if(empty($request))
        {
            $request="";
        }
        view()->share('request', $request);
		return view('clients.dashboard.coaching', compact('client', 'coach', 'credits','free_session_booked','free_session_details','coach_session_details','client_timezone','coach_timezone','total_time','client_program','gratuate_session_booked','gratuate_session_details'));
	}
	public function updatePayment() {
		$user_id = Auth::id();
		return view('clients.update_payment');
	}

	/* Temp Code*/
	function collection_paginate($items, $per_page)
	{

	    $page   = \Request::get('page', 1);
	    $offset = ($page * $per_page) - $per_page;

	    return new LengthAwarePaginator(
	        $items->forPage($page, $per_page)->values(),
	        $items->count(),
	        $per_page,
	        Paginator::resolveCurrentPage(),
	        ['path' => Paginator::resolveCurrentPath()]
	    );
	}
	public function credithistory(Request $request)
	{
		$id = Auth::id();
		$client_details = Client::with('user','user.credit_card_detail','user.credit_history','user.credit_history.coach_booked_schedule','user.credit_history.coach_booked_schedule.coach_schedule','coach')->where('user_id', $id)->first();
		return view('clients.show_credit_history',compact('client_details'));

	}

    /**
     * PAGE: client/addReadOnlyCoach
     * GET: client/addReadOnlyCoach
     * @return Request $request
     */
    public function addReadOnlyCoach(Request $request){
        //if not a client bounce them back
        if(Auth::user()->user_type != 'client'){
            return redirect('/agent-dashboard')->withErrors('Only clients can invite coaches');
        }
        $client = Client::where('user_id', '=', Auth::user()->id)->first();

        if($client->invite_coach != null && !empty($client->invite_coach)){
            if ($request->isMethod('post')) {
                //if user has selected revoke.
                if($request->input('revoke') == 1){
                    //we need to update the user to remove the invite_coach.
                    $client->update(array('invite_coach' => NULL));
                    return redirect()->back()->with('status', 'Read Only Coach has been revoked');

                }
            }
            return view('clients/dashboard/revoke-read-only-coach', compact('client'));

        }else{
            if ($request->isMethod('post')) {
                $this->validate($request, [
                    'first_name' => array('required', 'String'),
                    'last_name' => array('required', 'String'),
                    'invite_coach' => array('required', 'Email'),
                ]);

                //need to check if user with email already exists
                if (User::where('email', '=', $request->invite_coach)->where('deleted', '=', 0)->exists()) {
                    $client->update(array('invite_coach' => $request->invite_coach));
                    return redirect()->back()->with('status', 'Coach has been updated.');

                }else{
                    //Add invite coach email to this user.
                    $client->update(array('invite_coach' => $request->invite_coach));

                    //Need to create new user with role type Read Only Coach
                    $tempPassword = 'temp-'.rand();
                    $userFields = [
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'name' => $request->first_name . ' ' . $request->last_name,
                        'email' => $request->invite_coach,
                        'password' => bcrypt($tempPassword),
                        'status' => 'active',
                        'user_type' => "read-only-coach",
                        'role_id' => '15',
                        'created_by' => Auth::id(),
                        'registration_completed'=>'1',
                    ];
                    $user = User::create($userFields);
                    $email = $request->invite_coach;

                    //We now want to send an email to the read only coach so they can login
                    Mail::send(
                        'email_template.read_only_coach_invite', ['first_name' => $request->first_name, 'email' => $email, 'tempPassword' => $tempPassword],  function ($message) use ($email) {
                            $message->to($email)->subject("You've been add as a coach in the Life Process program");
                        }
                    );
                }
                return redirect()->back()->with('status', 'Email invite has been sent.');
            }
        }
        return view('clients/dashboard/add-read-only-coach', compact('client'));
    }


}