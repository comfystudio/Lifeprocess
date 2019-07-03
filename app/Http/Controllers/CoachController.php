<?php
namespace App\Http\Controllers;
use App;
use AppHelper;
use App\Events\NotificationEvent;
use App\Models\Activation;
use App\Models\Coach;
use App\Models\CoachModuleRate;
use App\Models\CoachProgram;
use App\Models\OtherCoachFeedbackList;
use App\Models\User;
use App\Models\Program;
use App\Models\Module;
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
use Mail;
use Cache;
use App\Models\Client;

class CoachController extends Controller {
	public function __construct() {
		parent::__construct();
		$this->middleware('auth', ['except' => ['ajaxCoaches']]);
		$this->middleware('check_for_permission.access:coaches.create', ['only' => ['create', 'store']]);
		$this->middleware('check_for_permission.access:coaches.view', ['only' => ['index', 'show']]);
		$this->middleware('check_for_permission.access:coaches.update', ['only' => ['edit', 'update']]);
		$this->middleware('check_for_permission.access:coaches.delete', ['only' => ['destroy']]);
		$this->title = trans('comman.coaches');
		view()->share('title', $this->title);
		view()->share('timezones', get_timezone_list());
		//$this->ajax = new AjaxController();
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
//			'paypal_id' => 'required|max:50',
			'country_id' => 'required',
			'timezone' => 'required',
			'image' => 'image',
//			'api_key'=>'required',
//			'api_secret' => 'required',
//			'zoom_email'=>'required',
			//'role_id' => 'required',
			'gender' => 'required',
			'program_id' => 'required',
			'one_hour_session' => 'required|numeric|min:0',
			//'promotional_call' => 'required|numeric|min:0',
			'free_20_min_session' => 'required|numeric|min:0',
			'min_slots_availability_per_week' => 'required|integer|min:0',
			'graduate_session'=>'required|numeric|min:0',
			'proxy_coach_id' => 'required_if:available_for_review,no',
		];
		$messages = [
			'image' => 'The avatar/photo field must be an image.',
			'role_id.required' => 'The role field is required.',
			'program_id.required' => 'The program field is required.',
			'timezone.required' => 'The Timezone field is required.',
			'country_id.required' => 'Please Select Country.',
			'password.regex' => 'Password must be combination of number and both uppercase and lowercase letters.',
			'paypal_id.required' => 'The Paypal Id field is required.',
			'proxy_coach_id.required_if' => 'Please select other coach for review.',
		];
		if($mode == 'update_profile'){
			unset($rules['graduate_session']);
		}
		if ($mode == 'edit' || $mode == 'update_profile') {
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

	/*
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {
		$user_type = Auth::user()->user_type;
		$user_id = Auth::id();

		$sort_order = $request->get('sort_order',[]);
		$action_nav = array(
			"add_new" => array("title" => '<b><i class="icon-diff-added"></i></b> ' . trans("comman.addcoach"), "url" => route('coaches.create'),
				"attributes" => array("class" => "btn bg-success btn-labeled heading-btn", 'title' => 'Add New')),
		);
		if (!Auth::user()->hasAccess('coaches.create')) {
			unset($action_nav['add_new']);
		}
		view()->share('module_action', $action_nav);
		view()->share('coaches', $this->get_index($sort_order));

        //Need to get read only coaches.
        $read_only_coaches = User::where('role_id', '=', 15)->where('deleted', 0)->get();
        foreach($read_only_coaches as $key => $coaches){
            $clients = Client::where('invite_coach', $coaches->email)->get();
            $read_only_coaches[$key]['Clients'] = $clients;
        }
        view()->share('read_only_coaches', $read_only_coaches);
		return view('coaches.index');
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
				"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('coaches.index'),
					"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
			));
		}
		$object = App::make("App\Http\Controllers\CountryController");
		view()->share('countries', $object->ajaxCountries($request));
		//Get Roles
		$object = App::make("App\Http\Controllers\RoleController");
		view()->share('roles', $object->getAllRoles());

		$object = App::make("App\Http\Controllers\ProgramController");
		view()->share('programs', $object->ajaxPrograms($request));

		$othercoach = $this->getCoach();
		view()->share('othercoach', $othercoach);

		view()->share('title', trans("comman.coach"));
		return view('coaches.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$input = AppHelper::getTrimmedData($request->all());

        //if add by client manager set the some fields to 0 if not entered by user.
        if(Auth::user()->user_type == 'agent') {
            if ($input['free_20_min_session'] == null) {
                $input['free_20_min_session'] = 0;
            }

            if ($input['one_hour_session'] == null) {
                $input['one_hour_session'] = 0;
            }
            if ($input['graduate_session'] == null) {
                $input['graduate_session'] = 0;
            }
            if ($input['min_slots_availability_per_week'] == null) {
                $input['min_slots_availability_per_week'] = 0;
            }
        }

		$this->validator($input)->validate();
		if (!isset($input['status'])) {
			$input['status'] = 'in_active';
		} else {
			$input['status'] = 'active';
		}
		$file['image'] = '';
		$input['image'] = '';
		if ($request->hasFile('image')) {
			$file['image'] = \AppHelper::getUniqueFilename($request->file('image'), AppHelper::getImagePath());
			$request->file('image')->move(AppHelper::getImagePath(), $file['image']);
			$input['image'] = $file['image'];
		}
		if (isset($input['country_id']) && empty($input['country_id'])) {
			unset($input['country_id']);
		} else {
			$input['country_id'] = $input['country_id'];
		}
		$coach_id = '';
		try {
			DB::beginTransaction();
			$userFields = array();
			$coachFields = array();
			$userFields = [
				'first_name' => $input['first_name'],
				'last_name' => $input['last_name'],
				'name' => $input['first_name'] . ' ' . $input['last_name'],
				'email' => $input['email'],
				'password' => bcrypt($input['password']),
				'country_id' => $input['country_id'],
				'address_line_one' => $input['address_line_one'],
				'address_line_two' => $input['address_line_two'],
				'address_line_three' => $input['address_line_three'],
				'timezone' => $input['timezone'],
				'skype_id' => $input['skype_id'],
				'status' => $input['status'],
				'image' => $input['image'],
				'user_type' => "coach",
				'role_id' => $input['role_id'],
				'gender' => $input['gender'],
				'created_by' => Auth::id(),

			];
			$user = User::create($userFields);
			$code = AppHelper::GenrateCode();
			if ($input['status'] == 'in_active') {
				$create_activation = Activation::create([
					"code" => $code,
					"user_id" => $user->id,
				]);
				$email = $input['email'];
				Mail::send(
					'email_template.welcome', ['code' => $code, 'email' => $input['email'], 'first_name' => $input['first_name']], function ($message) use ($email) {
						$message->to($email)->subject('Welcome to Life Process');
						$bcc = explode(',', config('srtpl.bccmail'));
						if (!empty($bcc)) {
							$message->bcc($bcc);
						}
					});
			} else {
				$create_activation = Activation::create([
					"code" => AppHelper::GenrateCode(),
					"user_id" => $user->id,
					"completed" => '1',
					"completed_at" => Carbon::now()->format('Y-m-d H:i:s'),
				]);
			}
			$coachFields = [
				'user_id' => $user->id,
				'paypal_id' => $input['paypal_id'],
				'zip_code' => $input['zip_code'],
				'biography' => $input['biography'],
				'qualifications' => $input['qualifications'],
				'experience' => $input['experience'],
				// 'hourly_rate' => $input['hourly_rate'],
				// 'program_fee' => ($input['program_fee'] != '') ? : 0 ,
				'graduate_session' => $input['graduate_session'],
				'one_hour_session' => $input['one_hour_session'],
				'free_20_min_session' => $input['free_20_min_session'],
				'min_slots_availability_per_week' => $input['min_slots_availability_per_week'],
				'city' => $input['city'],
				'available' => $input['available'],
				'available_for_review' => $input['available_for_review'],
				'api_key'=>$input['api_key'],
				'api_secret'=>$input['api_secret'],
				'zoom_email'=>$input['zoom_email'],
                'agent_id' => $input['agent_id']
			];
			$model = Coach::create($coachFields);
			$coach_id = $model->id;

            //If the coach is created by a client manager we need to send a different email.
            if (isset(Auth::user()->user_type) && Auth::user()->user_type == 'agent') {
                $email = $input['email'];
                Mail::send(
                    'email_template.client_manager_new_coach', ['password' => $input['password'], 'email' => $input['email'], 'first_name' => $input['first_name']], function ($message) use ($email) {
                        $message->to($email)->subject("Welcome to the Life Process Program");
                    }
                );
            }


			// assign program to coach...
			if (isset($input['program_id'])) {
				foreach ($input['program_id'] as $key => $value) {
					$coachProgram = [
						'coach_id' => $model->id,
						'program_id' => $value,
					];
					CoachProgram::create($coachProgram);
					$modules = Module::where('program_id', $value)->get();
					if($modules->count())
					{
						foreach ($modules as $module) {

							$coach_module_rate = array();
							$coach_module_rate['coach_id'] = $coach_id;
							$coach_module_rate['program_id'] = $module->program_id;
							$coach_module_rate['module_id'] = $module->id;
							$coach_module_rate['rate'] = $module->default_rate;
							$coach_module_rate['deleted'] = '0';
							$coach_module_rate = CoachModuleRate::create($coach_module_rate);
						}
					}
				}
			}
			if (isset($input['proxy_coach_id'])) {
				foreach ($input['proxy_coach_id'] as $key => $value) {
					$othercoach = [
						'coach_id' => $model->id,
						'proxy_coach_id' => $value,
					];
					OtherCoachFeedbackList::create($othercoach);
				}
			}
			if ($request->ajax()) {
				DB::commit();
				return response()->json([
					'success' => 'true',
					'data' => $user,
				]);
			}
			Flash::success(trans("comman.coach_added"));
			DB::commit();
		} catch (Exception $e) {
			Flash::error("Found some error while creating coach. Please Try again!");
			DB::rollBack();
		}

		if ($request->get('save_exit')) {
		} else {
            if (isset(Auth::user()->user_type) && Auth::user()->user_type == 'agent') {
                return redirect()->route('coaches.index');
            }else{
                return redirect()->route('coach-rates.create', ['coach_id' => Crypt::encryptString($coach_id)]);
            }
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function get_programModules($id) {
		//$id = Crypt::decryptString($id);
		// $programsModules = Program::with(['coach_program'=> function ($query) {
		// 	$query->where('coach_program.program_id','=','program.id');
		// },'modules' => function ($query) {
		// 	$query->orderBy('module_no');
		// }])->get();

		$programsModules = CoachProgram::with(['coach_program_detail','coach_program_detail.modules'=> function ($query) {
			$query->orderBy('module_no');
		}])->where('coach_id',$id)->get();
		//dd($programsModules);
		return $programsModules;
	}

	public function show($id, Request $request) {

		$id = Crypt::decryptString($id);
		if (request()->get('_url')) {
			view()->share('module_action', array(
				"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route(request()->get('_url')),
					"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
			));
		} else {
			view()->share('module_action', array(
				"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('coaches.index'),
					"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
			));
		}
		$tmp_coach = Coach::find($id);
		/*coach module rate*/
		/*if (is_null($tmp_coach)) {
			return redirect()->route('coaches.index');
		}
		$coach_module_rates = CoachModuleRate::where('coach_id', $id)->get();
		$module_rates = [];
		$module_rates_id = [];
		foreach ($coach_module_rates as $key => $row) {
			$module_rates['module'][$row->program_id][$row->module_id] = $row->rate;
			$module_rates['module_id'][$row->program_id][$row->module_id] = $row->id;
		}

		view()->share('programs', $this->get_programModules());*/
		//view()->share('coach_id', $coach_id);
		/*coach module rate*/

		$user = User::find($tmp_coach->user_id);
		$coach = $tmp_coach->toArray() + array_only($user->toArray(), [
			'first_name',
			'last_name',
			'email',
			'country_id',
			'address_line_one',
			'address_line_two',
			'address_line_three',
			'timezone',
			'status',
			'image',
			'skype_id',
			'role_id',
			'gender',
		]
		);
		 //sdump($coach); exit();
		$object = App::make("App\Http\Controllers\CountryController");
		view()->share('countries', $object->ajaxCountries($request));
		//Get Roles
		$object = App::make("App\Http\Controllers\RoleController");
		view()->share('roles', $object->getAllRoles());

		$othercoach = $this->getOtherCoach($id);
		view()->share('othercoach', $othercoach);

		$object = App::make("App\Http\Controllers\ProgramController");
		view()->share('programs_name', $object->ajaxPrograms($request));

		$object = App::make("App\Http\Controllers\ModuleController");
		view()->share('modules',$object->ajaxModulesWithProgramName($request));

		$coach_module_rates = CoachModuleRate::where('coach_id', $id)->get();

		$coach_programs = CoachProgram::select(['id', 'program_id'])->where('coach_id', $id)->get();
		$coach['module_rates'] = array();
		$module_rates_id = [];
		foreach ($coach_module_rates as $key => $row) {
			$coach['module'][$row->program_id][$row->module_id] = $row->rate;
			$coach['module_id'][$row->program_id][$row->module_id] = $row->id;
		}

		$coach['program_id'] = array();
		$coach['coach_program_id'] = array();
		if (count($coach_programs) > 0) {
			foreach ($coach_programs as $key => $row) {
				$coach['program_id'][] = $row['program_id'];
				$coach['coach_program_id'][$row['program_id']] = $row['id'];
			}
		}
		$othercoach = OtherCoachFeedbackList::select(['id', 'proxy_coach_id'])->where('coach_id', $id)->get();
		$coach['proxy_coach_id'] = array();
		if (count($othercoach) > 0) {
			foreach ($othercoach as $key => $row) {
				$coach['proxy_coach_id'][] = $row['proxy_coach_id'];
			}
		}
		//coach rated modules
		$modules_rated = CoachModuleRate::where('coach_id', $id)->get()->count();

		view()->share('modules_rated', $modules_rated);
		view()->share('title', trans("comman.coach"));
		view()->share('coach_programs', $this->get_programModules($id));
		return view('coaches.show_details', compact('coach'));

	}

	public function getnote($id) {
		$get_note = Coach::where('id', $id)->select('admin_note', 'updated_at')->first();
		$timezone = Auth::user()->timezone;
		view()->share('timezone', $timezone);
		view()->share('get_note', $get_note);
		return view('coaches.admin-note', ['id' => $id]);
	}
	public function savenote($id, Request $request) {
		// dump($theme);
		$coach = Coach::findOrFail($id);
		$input = $request->all();
		$result = $this->validate($request, [
			'admin_note' => "required",
		]);
		$coach->admin_note = $input['admin_note'];
		$coach->save();
		Flash::success(trans("comman.admin_note_update"));
		if ($request->ajax()) {
			return response()->json([
				'success' => 'true',
				'data' => $input,
			]);
		}

	}
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id, Request $request) {
		//print_r($_REQUEST);exit;
		$id = Crypt::decryptString($id);
		if (request()->get('_url')) {
			view()->share('module_action', array(
				"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route(request()->get('_url')),
					"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
			));
		} else {
			view()->share('module_action', array(
				"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('coaches.index'),
					"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
			));
		}
		$tmp_coach = Coach::find($id);
		if (is_null($tmp_coach)) {
			return redirect()->route('coaches.index');
		}
		$user = User::find($tmp_coach->user_id);
		$coach = $tmp_coach->toArray() + array_only($user->toArray(), [
			'first_name',
			'last_name',
			'email',
			'country_id',
			'address_line_one',
			'address_line_two',
			'address_line_three',
			'timezone',
			'status',
			'image',
			'skype_id',
			'role_id',
			'gender',
		]
		);
		// dump($coach); exit();
		$object = App::make("App\Http\Controllers\CountryController");
		view()->share('countries', $object->ajaxCountries($request));
		//Get Roles
		$object = App::make("App\Http\Controllers\RoleController");
		view()->share('roles', $object->getAllRoles());

		$othercoach = $this->getOtherCoach($id);
		view()->share('othercoach', $othercoach);

		$object = App::make("App\Http\Controllers\ProgramController");
		view()->share('programs', $object->ajaxPrograms($request));

		$coach_programs = CoachProgram::select(['id', 'program_id'])->where('coach_id', $id)->get();
		$coach['program_id'] = array();
		$coach['coach_program_id'] = array();
		if (count($coach_programs) > 0) {
			foreach ($coach_programs as $key => $row) {
				$coach['program_id'][] = $row['program_id'];
				$coach['coach_program_id'][$row['program_id']] = $row['id'];
			}
		}
		$othercoach = OtherCoachFeedbackList::select(['id', 'proxy_coach_id'])->where('coach_id', $id)->get();

        $coach['proxy_coach_id'] = array();
		if (count($othercoach) > 0) {
			foreach ($othercoach as $key => $row) {
				$coach['proxy_coach_id'][] = $row['proxy_coach_id'];
			}
		}
		//coach rated modules
		$modules_rated = CoachModuleRate::where('coach_id', $id)->get()->count();
		view()->share('modules_rated', $modules_rated);
		view()->share('title', trans("comman.coach"));
		return view('coaches.edit', compact('coach'));
	}
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update_save(Request $request, $id) {

		$id = Crypt::decryptString($id);
		$coach = Coach::findOrFail($id);
		$user = User::findOrFail($coach->user_id);
		$input = AppHelper::getTrimmedData($request->all());
		// dump($input); exit();
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
		if ($request->has('password') && $request->has('password')!='') {
			$extra_rules['password'] = 'required|min:8|regex:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9!@#$%^&*]+$/';
		}

		$this->validator($request->all(), 'edit', $extra_rules)->validate();
		//echo "dfg";exit;

		$input['name'] = $input['first_name'] . ' ' . $input['last_name'];

		if (empty($input['country_id'])) {
			($input['country_id'] = 0);
		}
		if (!isset($input['status'])) {
			$input['status'] = 'in_active';
		}
		if($input['status']=='in_active')
		{
			$active='1';
		}
		else
		{
			$active='0';
		}

		$file['image'] = '';
		if ($request->hasFile('image')) {
			$file['image'] = \AppHelper::getUniqueFilename($request->file('image'), AppHelper::getImagePath());
			$request->file('image')->move(AppHelper::getImagePath(), $file['image']);
			$input['image'] = $file['image'];
		} else {
			$input['image'] = ($user->image) ?: '';
		}

		$userFields = array();
		$coachFields = array();

		$userFields = [
			'first_name' => $input['first_name'],
			'last_name' => $input['last_name'],
			'name' => $input['first_name'] . ' ' . $input['last_name'],
			'email' => $input['email'],
			'country_id' => $input['country_id'],
			'address_line_one' => $input['address_line_one'],
			'address_line_two' => $input['address_line_two'],
			'address_line_three' => $input['address_line_three'],
			'timezone' => $input['timezone'],
			'skype_id' => $input['skype_id'],
			'status' => $input['status'],
			'image' => $input['image'],
			//'role_id' => $input['role_id'],
			'gender' => $input['gender'],
		];
		// Do we need to update the password as well?
		if ($request->has('password')) {
			$userFields['password'] = bcrypt($input['password']);
		}

		$coachFields = [
			'paypal_id' => $input['paypal_id'],
			'zip_code' => $input['zip_code'],
			'biography' => $input['biography'],
			'qualifications' => $input['qualifications'],
			'experience' => $input['experience'],
			//'promotional_call' => $input['promotional_call'],
			'one_hour_session' => $input['one_hour_session'],
			'free_20_min_session' => $input['free_20_min_session'],
			'min_slots_availability_per_week' => $input['min_slots_availability_per_week'],
			'graduate_session' => $input['graduate_session'],
			// 'program_fee' => ($input['program_fee'] != '') ? : 0 ,
			'city' => $input['city'],
			'available' => $input['available'],
			'api_key'=>$input['api_key'],
			'api_secret'=>$input['api_secret'],
			'zoom_email'=>$input['zoom_email'],
			'active'=>$active,
			//'available_for_review' => $input['available_for_review'],
		];

		DB::transaction(function () use ($coach, $user, $coachFields, $userFields, $input, $id) {
			$coach->update($coachFields);
			$user->update($userFields);
			if (!empty($input['program_id']) && isset($input['program_id'])) {
				CoachProgram::where('coach_id', $id)->update(['deleted' => '1']);
				foreach ($input['program_id'] as $key => $program_id) {
					$sub_array = array();
					$sub_array['coach_id'] = $id;
					$sub_array['program_id'] = $program_id;
					$sub_array['deleted'] = '0';
					if (isset($input['coach_program_id'][$program_id]) && !empty($input['coach_program_id'][$program_id])) {
						// dump('if exist');
						// dump($input['coach_program_id'][$program_id]);
						CoachProgram::withoutGlobalScope('coach_program.deleted')->where('id', $input['coach_program_id'][$program_id])->update($sub_array);
					} else {
						// dump('create new');
						CoachProgram::create($sub_array);
					}
				}
			}
			if (!empty($input['module']) && isset($input['module'])) {

				//CoachModuleRate::where('coach_id', $id)->update(['deleted' => '1']);
				foreach ($input['module'] as $program_id => $modules) {
					foreach ($modules as $module_id => $rate) {

						$sub_array = array();
						$sub_array['coach_id'] = $id;
						$sub_array['program_id'] = $program_id;
						$sub_array['module_id'] = $module_id;
						if($rate=='' || $rate==0)
						{
							$sub_array['rate']=10;
						}
						else
						{
							$sub_array['rate'] = $rate;
						}
						$sub_array['deleted'] = '0';

						$coachrate=CoachModuleRate::where('coach_id',$id)->where('program_id',$program_id)->where('module_id',$module_id)->count();

						if($coachrate>0)
						{
							CoachModuleRate::where('coach_id',$id)->where('program_id',$program_id)->where('module_id',$module_id)->update(array('rate'=>$rate));
						}
						else
						{
							CoachModuleRate::create($sub_array);
						}
					}
				}
			}
			// if (!empty($input['module']) && isset($input['module'])) {
			// CoachModuleRate::where('coach_id', $id)->update(['deleted' => '1']);
			// foreach ($input['module'] as $program_id => $modules) {
			// 	foreach ($modules as $module_id => $rate) {
			// 		$sub_array = array();
			// 		$sub_array['coach_id'] = $id;
			// 		$sub_array['program_id'] = $program_id;
			// 		$sub_array['module_id'] = $module_id;
			// 		$sub_array['rate'] = $rate;
			// 		$sub_array['deleted'] = '0';
			// 		if (isset($input['module_id'][$program_id][$module_id]) && !empty($input['module_id'][$program_id][$module_id])) {
			// 			CoachModuleRate::withoutGlobalScope('coach_module_rates.deleted')->where('id', $input['module_id'][$program_id][$module_id])->update($sub_array);
			// 		} else {
			// 			CoachModuleRate::create($sub_array);
			// 		}
			// 	}
			// 	}
			// }
			if (empty($input['proxy_coach_id'])) {
				OtherCoachFeedbackList::where('coach_id', $id)->delete();
			} else {
				OtherCoachFeedbackList::where('coach_id', $id)->delete();
				foreach ($input['proxy_coach_id'] as $key => $value) {
					$othercoach = [
						'coach_id' => $id,
						'proxy_coach_id' => $value,
					];
					OtherCoachFeedbackList::create($othercoach);
				}
			}

			Flash::success(trans("comman.coach_updated"));
		});
		if ($request->get('save_exit')) {
			return redirect()->route('coaches.index');
		} else {
			return redirect()->route('coaches.show_details', Crypt::encryptString($id));
		}
	}


	public function update(Request $request, $id) {

		$id = Crypt::decryptString($id);
		$coach = Coach::findOrFail($id);
		$user = User::findOrFail($coach->user_id);
		$input = AppHelper::getTrimmedData($request->all());
		// dump($input); exit();
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

		if (empty($input['country_id'])) {
			($input['country_id'] = 0);
		}
		if (!isset($input['status'])) {
			$input['status'] = 'in_active';
		}

		$file['image'] = '';

		if ($request->hasFile('image')) {
			$file['image'] = \AppHelper::getUniqueFilename($request->file('image'), AppHelper::getImagePath());
			$request->file('image')->move(AppHelper::getImagePath(), $file['image']);
			$input['image'] = $file['image'];
		} else {
			$input['image'] = ($user->image) ?: '';
		}
		// dd($input['image']);
		$userFields = array();
		$coachFields = array();

		$userFields = [
			'first_name' => $input['first_name'],
			'last_name' => $input['last_name'],
			'name' => $input['first_name'] . ' ' . $input['last_name'],
			'email' => $input['email'],
			'country_id' => $input['country_id'],
			'address_line_one' => $input['address_line_one'],
			'address_line_two' => $input['address_line_two'],
			'address_line_three' => $input['address_line_three'],
			'timezone' => $input['timezone'],
			'skype_id' => $input['skype_id'],
			'status' => $input['status'],
			'image' => $input['image'],
			'role_id' => $input['role_id'],
			'gender' => $input['gender'],
		];
		// Do we need to update the password as well?
		if ($request->has('password')) {
			$userFields['password'] = bcrypt($input['password']);
		}

		$coachFields = [
			'paypal_id' => $input['paypal_id'],
			'zip_code' => $input['zip_code'],
			'biography' => $input['biography'],
			'qualifications' => $input['qualifications'],
			'experience' => $input['experience'],
			'promotional_call' => $input['promotional_call'],
			'one_hour_session' => $input['one_hour_session'],
			'free_20_min_session' => $input['free_20_min_session'],
			'min_slots_availability_per_week' => $input['min_slots_availability_per_week'],
			// 'program_fee' => ($input['program_fee'] != '') ? : 0 ,
			'city' => $input['city'],
			'available' => $input['available'],
			'available_for_review' => $input['available_for_review'],
			'api_key'=>$input['api_key'],
			'api_secret'=>$input['api_secret'],
			'zoom_email'=>$input['zoom_email'],
		];

		DB::transaction(function () use ($coach, $user, $coachFields, $userFields, $input, $id) {
			$coach->update($coachFields);
			$user->update($userFields);
			if (!empty($input['program_id']) && isset($input['program_id'])) {
				CoachProgram::where('coach_id', $id)->update(['deleted' => '1']);
				foreach ($input['program_id'] as $key => $program_id) {
					$sub_array = array();
					$sub_array['coach_id'] = $id;
					$sub_array['program_id'] = $program_id;
					$sub_array['deleted'] = '0';
					if (isset($input['coach_program_id'][$program_id]) && !empty($input['coach_program_id'][$program_id])) {
						// dump('if exist');
						// dump($input['coach_program_id'][$program_id]);
						CoachProgram::withoutGlobalScope('coach_program.deleted')->where('id', $input['coach_program_id'][$program_id])->update($sub_array);
					} else {
						// dump('create new');
						CoachProgram::create($sub_array);
					}
				}
			}
			if (empty($input['proxy_coach_id'])) {
				OtherCoachFeedbackList::where('coach_id', $id)->delete();
			} else {
				OtherCoachFeedbackList::where('coach_id', $id)->delete();
				foreach ($input['proxy_coach_id'] as $key => $value) {
					$othercoach = [
						'coach_id' => $id,
						'proxy_coach_id' => $value,
					];
					OtherCoachFeedbackList::create($othercoach);
				}
			}
			Flash::success(trans("comman.coach_updated"));
		});
		if ($request->get('save_exit')) {
			return redirect()->route('coaches.index');
		} else {
			return redirect()->route('coaches.edit', Crypt::encryptString($id));
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
		$model = Coach::find($id);
		if ($model) {
			$dependency = $model->deleteValidate($id);
			if (!$dependency) {
				DB::transaction(function () use ($model, $id) {
					$model->deleted = '1';
					$model->save();
					$user_model = User::find($model->user_id);
					$user_model->deleted = '1';
					$user_model->save();
					CoachModuleRate::where('coach_id', $id)->update(['deleted' => '1']);
					OtherCoachFeedbackList::where('coach_id', $id)->update(['deleted' => '1']);
				});
				Flash::success(trans("comman.coach_deleted"));
			} else {
				Flash::error(trans("comman.coach_dependency_error", ['dependency' => $dependency]));
			}
		} else {
			Flash::error(trans("comman.coach_error"));
		}
		return redirect()->route('coaches.index');
	}

	// Get current logged in coach profile
	public function getProfile(Request $request) {
		$user = Auth::user();
		if ($user->user_type != 'coach') {
			return redirect()->route('login');
		}
		$tmp_coach = Coach::where('user_id', $user->id)->first();
		if (is_null($tmp_coach)) {
			return redirect()->route('coach.dashboard');
		}
		$coach = $tmp_coach->toArray() + array_only($user->toArray(), [
			'first_name',
			'last_name',
			'email',
			'country_id',
			'address_line_one',
			'address_line_two',
			'address_line_three',
			'timezone',
			'status',
			'image',
			'skype_id',
			'role_id',
			'gender',
			'welcome_message',
		]);
		// dump($coach); exit();
		$object = App::make("App\Http\Controllers\CountryController");
		view()->share('countries', $object->ajaxCountries($request));
		//Get Roles
		$object = App::make("App\Http\Controllers\RoleController");
		view()->share('roles', $object->getAllRoles());

		$object = App::make("App\Http\Controllers\ProgramController");
		view()->share('programs', $object->ajaxPrograms($request));

		$coach_programs = CoachProgram::select(['id', 'program_id'])->where('coach_id', $tmp_coach->id)->get();
		$coach['program_id'] = array();
		$coach['coach_program_id'] = array();
		if (count($coach_programs) > 0) {
			foreach ($coach_programs as $key => $row) {
				$coach['program_id'][] = $row['program_id'];
				$coach['coach_program_id'][$row['program_id']] = $row['id'];
			}
		}
		view()->share('title', trans("comman.coach"));
		return view('coaches.dashboard.edit_profile', compact('coach'));
	}

	//update current user profile...
	public function updateProfile(Request $request) {
		//dd($request);
		$user = Auth::user();
		$coach = Coach::where('user_id', $user->id)->first();
		$id = $coach->id;
		$input = AppHelper::getTrimmedData($request->all());
		// $extra_rules = array(
		// 	'role_id' => '',
		// 	'one_hour_session' => '',
		// 	'promotional_call' => '',
		// 	'free_20_min_session' => '',
		// 	'min_slots_availability_per_week' => '',
		// 	// 'password' => 'confirmed',
		// );
		// $this->validator($request->all(), 'update_profile', $extra_rules)->validate();

		$input['name'] = $input['first_name'] . ' ' . $input['last_name'];

		$file['image'] = '';
		if ($request->hasFile('image')) {
			$file['image'] = \AppHelper::getUniqueFilename($request->file('image'), AppHelper::getImagePath());
			$request->file('image')->move(AppHelper::getImagePath(), $file['image']);
			$input['image'] = $file['image'];
		} else {
			$input['image'] = ($user->image) ?: '';
		}
		//dd($input['image']);
		$userFields = array();
		$coachFields = array();
		$user->fill([
			'first_name' => $input['first_name'],
			'last_name' => $input['last_name'],
			'name' => $input['first_name'] . ' ' . $input['last_name'],
			'country_id' => $input['country_id'],
			'address_line_one' => $input['address_line_one'],
			'address_line_two' => $input['address_line_two'],
			'address_line_three' => $input['address_line_three'],
			'timezone' => $input['timezone'],
			'skype_id' => $input['skype_id'],
			'image' => $input['image'],
			'gender' => $input['gender'],
			'welcome_message' => $input['welcome_message'],
		]);

		$coach->fill([
			'paypal_id' => $input['paypal_id'],
			'zip_code' => $input['zip_code'],
			'biography' => $input['biography'],
			'qualifications' => $input['qualifications'],
			'experience' => $input['experience'],
			'city' => $input['city'],
			// 'available' => $input['available'],
		]);

		$coachUpdated = '';
		$userUpdated = '';
		$coachProgramCreated = '';
		// DB::transaction(function () use ($coach, $user, $coachFields, $userFields, $input, $id, $request, $coachUpdated, $userUpdated, $coachProgramUpdated) {
		try {
			DB::beginTransaction();
			$coachUpdated = $coach->save();
			// dump($user->isDirty()); exit();
			// $userUpdated = $user->update($userFields);
			$userUpdated = $user->save();
			if (!empty($input['program_id']) && isset($input['program_id'])) {
				CoachProgram::where('coach_id', $id)->update(['deleted' => '1']);
				foreach ($input['program_id'] as $key => $program_id) {
					$sub_array = array();
					$sub_array['coach_id'] = $id;
					$sub_array['program_id'] = $program_id;
					$sub_array['deleted'] = '0';
					if (isset($input['coach_program_id'][$program_id]) && !empty($input['coach_program_id'][$program_id])) {
						CoachProgram::withoutGlobalScope('coach_program.deleted')->where('id', $input['coach_program_id'][$program_id])->update($sub_array);
					} else {
						$coachProgramCreated = CoachProgram::create($sub_array);
					}
				}
			}
			if ($request->get('current_password')) {
				Validator::extend('validateCurrentPassword', 'App\Validators\ChangePasswordValidator@validateCurrentPassword');
				$result = $this->validate($request, [
					'current_password' => 'required|validateCurrentPassword',
					'new_password' => 'required_with:current_password|min:8|regex:/^(?=.*[0-9])[a-zA-Z0-9!@#$%^&*]+$/|confirmed',
					// 'new_password' => 'required_with:current_password|min:8|regex:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]+$/|confirmed',
				],
					[
						'current_password.validate_current_password' => 'Password doesn\'t match with your current password.',
						'new_password.regex' => 'Password must be combination of number and both uppercase and lowercase letters.',
					]);
			}
			if ($request->get('new_password')) {
				$editUser = Auth::user();
				$credentials['password'] = bcrypt($request->get('new_password'));
				$userUpdated = $editUser->update($credentials);
			}
			Flash::success(trans("comman.coach_profile_updated"));
			DB::commit();
			if ($coachUpdated || $userUpdated || $coachProgramCreated) {
				// send notification/ Fire notification event on profile update to Adminuser
				$adminUser = User::where('user_type', 'user')->first();
				$text = $user->name . ' has updated ' . (($user->gender == 'Male') ? 'his' : 'her') . ' profile.';
				$notification_arr = [
					'text' => $text,
					'receiver_id' => [$adminUser->id],
				];
				//fire event..
				event(new NotificationEvent($notification_arr));
			}
		} catch (Exception $e) {
			DB::rollback();
		}
		return redirect()->route('coach.update.profile');
	}

	// function to get the listing for the index page...
	public function get_index($sort_order) {

		//print_r($_REQUEST);exit;

		$auth_user = Auth::user();
		$models = Coach::with(['user' => function ($q) use ($auth_user) {
			$q->where('user_type', '=', "coach");
				if ($auth_user->user_type == 'agent') {
					$q->where('created_by', $auth_user->id);
				}
			}])->where("coaches.deleted", "0");

		$models->select(array(
			"coaches.*"
		));

		//print_r($_REQUEST);exit;

		if (request()->get('fullname', false)) {
            $models->whereHas('user', function ($q) {
                $q->where('name', 'like', "%" . request()->get("fullname") . "%");
            });
        }
        if (request()->get('date_joined', false)){

            $models->where(\DB::raw("DATE_FORMAT(created_at,'%m/%d/%Y')"), 'like', "%" . request()->get("date_joined") . "%");
        }
        if (request()->get('status', false)) {
			$models->whereHas('user', function ($q) {
				$q->where('status', '=', request()->get("status"));
			});
		}

        $models->whereHas('user', function ($q) {
            $q->where('user_type', '=', "coach");
        });


		if (!empty($sort_order) && is_array($sort_order)) {

			foreach ($sort_order as $column => $direction) {
				/*if($column=='clients'){
					$total_clients = 'count(clients)';
					$models->orderBy($total_lients, $direction);
				}*/
				//else{
					if(strpos($column, ".") ==false)
					{
						$models->orderBy($column, $direction);
					}
				//}
			}
		} else {
			$models->orderBy('coaches.id', 'ASC');
		}
		$result = $models->get();

		foreach ($sort_order as $key => $val) {
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
		// filter the result to ignore the coaches which doesn't have user
		$result = $result->filter(function ($item, $key) {
			return !empty($item->user);
		});
		$per_page = config('srtpl.row_per_page');
		if(isset(Cache::get('settings')['per_page'])) {
            $per_page = Cache::get('settings')['per_page'];
        }
        //echo "<pre>";print_r($result);exit(0);
		return $this->collection_paginate($result,$per_page);
		//return $result;
		 //return $models->paginate(Config::get("srcore/core::srtpl.par_page", 10));
	}

	public function ajaxCoaches(Request $request, $param = array()) {
		$coaches = array();
		if (!empty($param['available'])) {
			$coachData = Coach::with(['clients','totalOfClients','user' => function ($query) use ($request, $param) {
				if ($request->get('coach_gender', false)) {
					$query->where('gender', $request->get('coach_gender'));
				} else if ($request->old('coach_gender', false)) {
					$query->where('gender', $request->old('coach_gender'));
				} else if (!empty($param['coach_gender'])) {
					$query->where('gender', $param['coach_gender']);
				}
			}, 'programs' => function ($query) use ($request, $param) {
				if ($request->get('program_id', false)) {
					$query->where('program_id', $request->get('program_id'));
				} else if ($request->old('program_id', false)) {
					$query->where('program_id', $request->old('program_id'));
				} else if (!empty($param['program_id'])) {
					$query->where('program_id', $param['program_id']);
				}
			}]);
		} else {
			$coachData = Coach::with(['clients','totalOfClients','user' => function ($query) use ($request, $param) {
				if ($request->get('coach_gender', false)) {
					$query->where('gender', $request->get('coach_gender'));
				} else if ($request->old('coach_gender', false)) {
					$query->where('gender', $request->old('coach_gender'));
				} else if (!empty($param['coach_gender'])) {
					$query->where('gender', $param['coach_gender']);
				}
			}, 'programs' => function ($query) use ($request, $param) {
				if ($request->get('program_id', false)) {
					$query->where('program_id', $request->get('program_id'));
				} else if ($request->old('program_id', false)) {
					$query->where('program_id', $request->old('program_id'));
				} else if (!empty($param['program_id'])) {
					$query->where('program_id', $param['program_id']);
				}
			}]);
		}
		$coachData = $coachData->get()->toArray();
	    // dump($coachData);

	    $new_coach_array = [];

	    foreach ($coachData as $key => $value) {
	    	$new_coach_array[$key] = $value;
	    	$new_coach_array[$key]['total_of_clients'] = (!empty($value['total_of_clients'])) ? $value['total_of_clients']['total_clients'] : 0;
	    }
	    // Sort Array by the total_of_client field value in asc order
	    $coachData = AppHelper::array_sort($new_coach_array, 'total_of_clients');
		foreach ($coachData as $coach) {
            //We need to hide coaches that come from client managers
            if (isset(Auth::user()->user_type) && Auth::user()->user_type == 'agent'){
                //$creator = User::where('id', '=', $coach['user']['created_by'])->with('coach')->first();
//                dd($coach);
//                if($creator['coach']['agent_id'] == Auth::user()->id || $creator['coach']['agent_id'] == null) {
                if($coach['agent_id'] == Auth::user()->id || $coach['agent_id'] == null) {

                    if ($coach['user'] && !empty($coach['programs'])) {
                        $coaches[$coach['id']] = $coach['user']['name'];
                    }
                }
            }else{
                //if the creator is not a client manager
                $creator = User::where('id', '=', $coach['user']['created_by'])->first();
                if ($coach['user'] && !empty($coach['programs']) && $creator['role_id'] != 5) {
                    $coaches[$coach['id']] = $coach['user']['name'];
                }
            }
			//echo $coach['total_clients'][0]['total_clients'];

			}
		return $coaches;
	}

	public function get_newest_coaches() {
		$models = Coach::with(['user', 'clients']);
		$models->select(array(
			"coaches.*",
		))->whereHas('user', function ($q) {
			$q->where('user_type', '=', "coach");
		})->where(DB::raw('MONTH(created_at)'), Carbon::now()->format('n'))
			->orderBy('created_at', 'DESC');
		return $models->get();
	}

	public function getCoach() {
		$coches = [];
        if (isset(Auth::user()->user_type) && Auth::user()->user_type == 'agent') {
            $object = Coach::with('user')->get()->toArray();
            foreach($object as $key => $obj){
                if($obj['user']['created_by'] != Auth::user()->id){
                    unset($object[$key]);
                }
            }
        }else{
            $object = Coach::with('user')->get()->toArray();
        }
		foreach ($object as $key => $row) {
			$coches[$row['user_id']] = $row['user']['name'];
		}
		return $coches;
	}

	public function getOtherCoach($id) {
		$coches = [];
        $object = Coach::with('user')->where('id', '!=', $id)->get()->toArray();
		foreach ($object as $key => $row) {
			$coches[$row['user_id']] = $row['user']['name'];
		}
		return $coches;
	}

	public function get_coach_clients() {
		$user_id = Auth::id();
		$clients = [];
		$object = Coach::where("coaches.deleted", "0")->where('user_id', '=', $user_id)->with('clients.user')->first();
		foreach ($object->clients as $row) {

			$clients[$row['user_id']] = $row['user']['name'];

		}
		return $clients;
	}
	public function coach_client($id) {
		$user_id = $id;
		$clients = [];
		$object = Coach::where("coaches.deleted", "0")->where('user_id', '=', $user_id)->with('clients.user')->first();
		foreach ($object->clients as $row) {

			$clients[$row['user_id']] = $row['user']['name'];

		}
		return $clients;
	}
	function collection_paginate($items, $per_page)
	{
        //return($items); exit;

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
	public function ajaxCoaches_timezone(Request $request, $param = array())
	{
		$coaches = array();
		if (!empty($param['available'])) {
			$coachData = Coach::where('available', 'yes')->where('active','0')->where('available','yes')->with(['clients','totalOfClients','user' => function ($query) use ($request, $param) {
				if ($request->get('coach_gender', false)) {
					$query->where('gender', $request->get('coach_gender'));
				} else if ($request->old('coach_gender', false)) {
					$query->where('gender', $request->old('coach_gender'));
				} else if (!empty($param['coach_gender'])) {
					$query->where('gender', $param['coach_gender']);
				}
				$query->where('status','active');
			}, 'programs' => function ($query) use ($request, $param) {
				if ($request->get('program_id', false)) {
					$query->where('program_id', $request->get('program_id'));
				} else if ($request->old('program_id', false)) {
					$query->where('program_id', $request->old('program_id'));
				} else if (!empty($param['program_id'])) {
					$query->where('program_id', $param['program_id']);
				}
			},
			'coach_program'=>function($query) use ($request,$param){
				if (!empty($param['program_id'])) {
					//print_r('hello2');
					$query->where('program_id', $param['program_id']);
				}
			}]);
		} else {

			$coachData = Coach::where('available', 'yes')->where('active','0')->where('available','yes')->with(['clients','totalOfClients','user' => function ($query) use ($request, $param) {
				if ($request->get('coach_gender', false)) {
					$query->where('gender', $request->get('coach_gender'));
				} else if ($request->old('coach_gender', false)) {
					$query->where('gender', $request->old('coach_gender'));
				} else if (!empty($param['coach_gender'])) {
					$query->where('gender', $param['coach_gender']);
				}
				$query->where('status','active');
			}, 'programs' => function ($query) use ($request, $param) {
				if ($request->get('program_id', false)) {
					$query->where('program_id', $request->get('program_id'));
				} else if ($request->old('program_id', false)) {
					$query->where('program_id', $request->old('program_id'));
				} else if (!empty($param['program_id'])) {
					$query->where('program_id', $param['program_id']);
				}
			},
			'coach_program'=>function($query) use ($request,$param){
				if (!empty($param['program_id'])) {
					$query->where('program_id', $param['program_id']);
				}
			}]);
		}
		$coachData = $coachData->get()->toArray();

		$new_coach_array = [];

		foreach($coachData as $data)
		{

			if(!empty($data['coach_program']))
			{
				$coachData1[]=$data;

			}
			else
			{
				$coachData1[]=$coachData;
			}

		}

	    //dump($coachData);
		foreach($coachData1 as $key=>$value)
		{
			//$new_coach_array[$key]['user'] = $value;
			if(!empty($value['user']))
			{
				$coach=$value['user']['timezone'];
				$client=$request->get('timezone');

				$client = new \DateTimeZone($client);
	            $time_in_sofia = new \DateTime('now', $client);

	            //echo "timezone".$client->getOffset($time_in_sofia );
	            $client=$client->getOffset($time_in_sofia);

	            $coach = new \DateTimeZone($coach);
	            $time_in_sofia = new \DateTime('now', $coach);

	            //echo "timezone".$coach->getOffset($time_in_sofia );
	            $coach=$coach->getOffset($time_in_sofia);

	            $total=$client-$coach;
	            //echo "Time Diffrence".abs($total);
	            $new_coach_array[$key] = $value;
	           // echo $value->totalOfClients->total_clients;
	            $new_coach_array[$key]['timezonediff']=abs($total);
	            $new_coach_array[$key]['total_of_clients'] = (!empty($value['total_of_clients'])) ? $value['total_of_clients']['total_clients'] : 0;
	        }

		}
		$coaches=$new_coach_array;

		$coachData = AppHelper::array_sort( $new_coach_array, 'timezonediff');
		//$coachData = AppHelper::array_sort( $new_coach_array, 'total_of_clients');

		foreach($coachData as $value)
		{
			$minclient=$value['timezonediff'];
			break;
			//echo $minclient;
		}
		//echo $minclient;
		$client=$request->get('timezone');

		 	$a=0;
		 	$new_coach_data=[];

			foreach($coachData as $key=>$value)
			{
				if($value['timezonediff']==$minclient)
				{
					$new_coach_data[$key]=$value;
				}

			}
			//$new_coach_data = AppHelper::array_sort( $new_coach_data, 'timezonediff');
			$new_coach_data = AppHelper::array_sort( $new_coach_data, 'total_of_clients');

			if(empty($new_coach_data))
			{
				$new_coach_data=$coachData;
			}
			//dd($new_coach_data);
		//}
		//dd($coachData);

		return $new_coach_data;
	}
	public function credithistory($id)
	{
		//dd($request);
		$id = Crypt::decryptString($id);
		$client_details = Client::with('user','user.credit_card_detail','user.credit_history','coach')->where('user_id', $id)->first();
		return view('clients.show_credit_history',compact('client_details'));
	}

}
