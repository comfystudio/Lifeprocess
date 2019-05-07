<?php

namespace App\Http\Controllers;

use App;
use AppHelper;
use App\Models\Activation;
use App\Models\Agent;
use App\Models\AgentProgram;
use App\Models\User;
use Auth;
use Carbon\Carbon;
use DB;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Mail;
use Cache;
use Illuminate\Support\Facades\File;
use \Stripe\Customer;

class AgentController extends Controller {

	public function __construct() {
		parent::__construct();
		$this->middleware('auth', ['except' => ['ajaxCoaches']]);
		$this->middleware('check_for_permission.access:agents.create', ['only' => ['create', 'store']]);
		$this->middleware('check_for_permission.access:agents.view', ['only' => ['index', 'show']]);
		$this->middleware('check_for_permission.access:agents.update', ['only' => ['edit', 'update']]);
		$this->middleware('check_for_permission.access:agents.delete', ['only' => ['destroy']]);
		$this->title = trans('comman.client_managers');
		view()->share('title', $this->title);
		view()->share('timezones', get_timezone_list());
		AppHelper::path('uploads/user/');
	}
	protected function validator(array $data, $mode = 'create', $edit_rules = array()) // $mode = create / edit
	{
		$rules = [
			'first_name' => 'required|max:50',
			'last_name' => 'required|max:50',
//			'paypal_id' => 'required|max:50',
			'country_id' => 'required',
			'timezone' => 'required',
			'image' => 'image',
			'role_id' => 'required',
			'gender' => 'required',
			'program_id' => 'required',
		];
		$messages = [
			'image' => 'The avatar/photo field must be an image.',
			'role_id.required' => 'The role field is required.',
			'program_id.required' => 'The program field is required.',
			'timezone.required' => 'Please Select Timezone.',
			'country_id.required' => 'Please Select Country.',
			'password.regex' => 'Password must be combination of number and both uppercase and lowercase letters.',
			'paypal_id.required' => 'The Paypal Id field is required.',
		];
		if ($mode == 'edit') {
			foreach ($edit_rules as $field => $rule) {
				$rules[$field] = $rule;
			}
		} else {
			$rules['password'] = 'required|min:8|regex:/^(?=.*[0-9])[a-zA-Z0-9]+$/|confirmed';
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
	public function index() {
		$action_nav = array(
			"add_new" => array("title" => '<b><i class="icon-diff-added"></i></b> ' . trans("comman.addclient_manager"), "url" => route('agents.create'),
				"attributes" => array("class" => "btn bg-success btn-labeled heading-btn", 'title' => 'Add New')),
		);
		if (!Auth::user()->hasAccess('agents.create')) {
			unset($action_nav['add_new']);
		}
		view()->share('module_action', $action_nav);
		view()->share('agents', $this->get_index(array()));
        //dd($this->get_index(array()));
		return view('agents.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request) {

		view()->share('module_action', array(
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('agents.index'),
				"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		));
		$object = App::make("App\Http\Controllers\CountryController");
		view()->share('countries', $object->ajaxCountries($request));
		//Get Roles
		$object = App::make("App\Http\Controllers\RoleController");
		view()->share('roles', $object->getAllRoles());

		$object = App::make("App\Http\Controllers\ProgramController");
		view()->share('programs', $object->ajaxPrograms($request));

		view()->share('title', trans('comman.client_manager'));
		return view('agents.create');

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
            //$input['status'] = 'active';
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
		$agent_id = '';
		try {
			DB::beginTransaction();
			$userFields = array();
			$agentFields = array();
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
				'user_type' => "agent",
				'role_id' => $input['role_id'],
				'gender' => $input['gender'],
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
			$agentFields = [
				'user_id' => $user->id,
				'paypal_id' => $input['paypal_id'],
				'zip_code' => $input['zip_code'],
//				'biography' => $input['biography'],
//				'qualifications' => $input['qualifications'],
//				'experience' => $input['experience'],
//				'promotional_call' => $input['promotional_call'],
//				'one_hour_session' => $input['one_hour_session'],
				'free_20_min_session' => $input['free_20_min_session'],
				'city' => $input['city'],
                'need_card' => $input['need_card'],
                'module_restriction' => $input['module_restriction'],
                'pp_llpcoach_fast' => $input['pp_llpcoach_fast'],
                'pp_coach_fast' => $input['pp_coach_fast'],
                'pp_llpcoach_normal' => $input['pp_llpcoach_normal'],
                'pp_coach_normal' => $input['pp_coach_normal'],
                'credits_per_month' => $input['credits_per_month'],
                'credits_accumulate' => $input['credits_accumulate']
			];
			$model = Agent::create($agentFields);
			$agent_id = $model->id;

            //Send email to new client manager
            $email = $input['email'];
            Mail::send(
                'email_template.client_manager_new_manager', ['password' => $input['password'], 'email' => $input['email'], 'first_name' => $input['first_name']], function ($message) use ($email) {
                    $message->to($email)->subject("Welcome to the Life Process Program");
                }
            );

			// assign program to coach...
			if (isset($input['program_id'])) {
				foreach ($input['program_id'] as $key => $value) {
					$agentProgram = [
						'agent_id' => $model->id,
						'program_id' => $value,
					];
					AgentProgram::create($agentProgram);
				}
			}

			if ($request->ajax()) {
				DB::commit();
				return response()->json([
					'success' => 'true',
					'data' => $user,
				]);
			}
			Flash::success(trans("comman.client_manager_added"));
			DB::commit();
		} catch (Exception $e) {
			Flash::error("Found some error while creating client manager. Please Try again!");
			DB::rollBack();
		}

		if ($request->get('save_exit')) {
			return redirect()->route('agents.index');
		} else {
			return redirect()->route('agents.create');
		}

	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id, Request $request) {
		$id = Crypt::decryptString($id);
		view()->share('module_action', array(
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('agents.index'),
				"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		));
		$tmp_agent = Agent::find($id);
		if (is_null($tmp_agent)) {
			return redirect()->route('agents.index');
		}
		$user = User::find($tmp_agent->user_id);
		$agent = $tmp_agent->toArray() + array_only($user->toArray(), [
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
            'need_card',
            'module_restriction',
            'pp_llpcoach_fast',
            'pp_coach_fast',
            'pp_llpcoach_normal',
            'pp_coach_normal',
            'credits_per_month',
            'credits_accumulate'
		]
		);
		// dump($agent); exit();
		$object = App::make("App\Http\Controllers\CountryController");
		view()->share('countries', $object->ajaxCountries($request));
		//Get Roles
		$object = App::make("App\Http\Controllers\RoleController");
		view()->share('roles', $object->getAllRoles());

		$object = App::make("App\Http\Controllers\ProgramController");
		view()->share('programs', $object->ajaxPrograms($request));

		$agent_programs = AgentProgram::select(['id', 'program_id'])->where('agent_id', $id)->get();
		$agent['program_id'] = array();
		$agent['agent_program_id'] = array();
		if (count($agent_programs) > 0) {
			foreach ($agent_programs as $key => $row) {
				$agent['program_id'][] = $row['program_id'];
				$agent['agent_program_id'][$row['program_id']] = $row['id'];
			}
		}
		view()->share('title', trans("comman.client_manager"));
		return view('agents.edit', compact('agent'));
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
		$agent = Agent::findOrFail($id);
		$user = User::findOrFail($agent->user_id);
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
			$extra_rules['password'] = 'required|min:8|regex:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]+$/|confirmed';
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
		$userFields = array();
		$agentFields = array();
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
		$agentFields = [
			'paypal_id' => $input['paypal_id'],
			'zip_code' => $input['zip_code'],
//			'biography' => $input['biography'],
//			'qualifications' => $input['qualifications'],
//			'experience' => $input['experience'],
			'promotional_call' => $input['promotional_call'],
			'one_hour_session' => $input['one_hour_session'],
			'free_20_min_session' => $input['free_20_min_session'],
			// 'program_fee' => ($input['program_fee'] != '') ? : 0 ,
			'city' => $input['city'],
            'need_card' => $input['need_card'],
            'module_restriction' => $input['module_restriction'],
            'pp_llpcoach_fast' => $input['pp_llpcoach_fast'],
            'pp_coach_fast' => $input['pp_coach_fast'],
            'pp_llpcoach_normal' => $input['pp_llpcoach_normal'],
            'pp_coach_normal' => $input['pp_coach_normal'],
            'credits_per_month' => $input['credits_per_month'],
            'credits_accumulate' => $input['credits_accumulate']
		];

		DB::transaction(function () use ($agent, $user, $agentFields, $userFields, $input, $id) {
			$agent->update($agentFields);
			$user->update($userFields);

			if (!empty($input['program_id']) && isset($input['program_id'])) {
				AgentProgram::where('agent_id', $id)->update(['deleted' => '1']);
				foreach ($input['program_id'] as $key => $program_id) {
					$sub_array = array();
					$sub_array['agent_id'] = $id;
					$sub_array['program_id'] = $program_id;
					$sub_array['deleted'] = '0';
					if (isset($input['agent_program_id'][$program_id]) && !empty($input['agent_program_id'][$program_id])) {
						// dump('if exist');
						// dump($input['agent_program_id'][$program_id]);
						AgentProgram::withoutGlobalScope('agent_program.deleted')->where('id', $input['agent_program_id'][$program_id])->update($sub_array);
					} else {
						// dump('create new');
						AgentProgram::create($sub_array);
					}
				}
			}

			Flash::success(trans("comman.client_manager_updated"));
		});

		if ($request->get('save_exit')) {
			return redirect()->route('agents.index');
		} else {
			return redirect()->route('agents.edit', Crypt::encryptString($id));
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
		$model = Agent::find($id);
		if ($model) {
			$dependency = $model->deleteValidate($id);
			if (!$dependency) {
				DB::transaction(function () use ($model, $id) {
					$model->deleted = '1';
					$model->save();

					$user_model = User::find($model->user_id);
					$user_model->deleted = '1';
					$user_model->save();
				});
				Flash::success(trans("comman.client_manager_deleted"));
			} else {
				Flash::error(trans("comman.client_manager_dependency_error", ['dependency' => $dependency]));
			}
		} else {
			Flash::error(trans("comman.client_manager_error"));
		}
		return redirect()->route('agents.index');

	}

	// Get current logged in agent profile
	public function getProfile(Request $request) {
		$user = Auth::user();
		$tmp_agent = Agent::where('user_id', $user->id)->first();
		if (is_null($tmp_agent)) {
			return redirect()->route('agent.dashboard');
		}
		$agent = $tmp_agent->toArray() + array_only($user->toArray(), [
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
			'gender',
            'need_card',
            'module_restriction',
            'pp_llpcoach_fast',
            'pp_coach_fast',
            'pp_llpcoach_normal',
            'pp_coach_normal',
            'credits_per_month',
            'credits_accumulate'
		]);
		// dump($agent); exit();
		$object = App::make("App\Http\Controllers\CountryController");
		view()->share('countries', $object->ajaxCountries($request));

		$object = App::make("App\Http\Controllers\ProgramController");
		view()->share('programs', $object->ajaxPrograms($request));

		$agent_programs = AgentProgram::select(['id', 'program_id'])->where('agent_id', $tmp_agent->id)->get();
		$agent['program_id'] = array();
		$agent['agent_program_id'] = array();
		if (count($agent_programs) > 0) {
			foreach ($agent_programs as $key => $row) {
				$agent['program_id'][] = $row['program_id'];
				$agent['agent_program_id'][$row['program_id']] = $row['id'];
			}
		}
		view()->share('title', trans("comman.client_manager_profile"));
		return view('agents.dashboard.edit_profile', compact('agent'));
	}

    /**
     * PAGE: agent/add-card
     * GET: agent/add-card
     * @return Request $request
     */
    public function addCard(Request $request){
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'card_number' => array('required', 'String'),
            ]);

            $user = User::where('id', '=', Auth::user()->id)->where('role_id', '=', '5')->first();
            if(!$user->count()){
                return redirect('/agent-dashboard')->withErrors('Agent User not found');
            }
            $agent = Agent::where('user_id', '=', $user->id)->first();
            $agent->update(array('card_number' => $request->card_number));

            try {
                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

                $customer = \Stripe\Customer::create(array(
                    'email' => $user->email,
                    'source'  => $request->stripeToken
                ));

                $user->update(array('stripe_id' => $customer->id));

            } catch (\Exception $ex){
                return $ex->getMessage();
            }

            return redirect('/agent-dashboard')->with('status', 'Card updated successfully.');
        }
        return view('agents/add-card');
    }

    /**
     * PAGE: agent/edit-theme
     * GET: agent/edit-theme
     * @return Request $request
     */
    public function editTheme(Request $request){
        //if user is not an agent then bounce them out
        $user = User::where('id', '=', Auth::user()->id)->where('role_id', '=', '5')->first();

        if(!$user->count()){
            return redirect('/agent-dashboard')->withErrors('Agent User not found');
        }

        $agent = Agent::where('user_id', '=', $user->id)->first();

        //if a post
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'colour_1' => array('required', 'String'),
                'colour_2' => array('required', 'String'),
                'colour_3' => array('required', 'String'),
                'colour_4' => array('required', 'String')
            ]);
            $data = $request->all();

            if ($request->hasFile('logo')) {
                $path = $request->logo->store('uploads/resource');
                $file = $request->file('logo');
                $file->move('uploads/resource',  $path);
                File::delete($agent->logo);

                $data['logo'] = $path;
            }else{
                $data['logo'] = $agent->logo;
            }

            $agent->update($data);

            return redirect('/agent-dashboard')->with('status', 'Theme edited successfully.');
        }
        return view('agents/edit-theme', compact('agent'));
    }

	//update current user profile...
	public function updateProfile(Request $request) {
		$user = Auth::user();
		$agent = Agent::where('user_id', $user->id)->first();
		$id = $agent->id;
		$input = AppHelper::getTrimmedData($request->all());
		$extra_rules = array(
			'role_id' => '',
			// 'password' => 'confirmed',
		);
		$this->validator($request->all(), 'edit', $extra_rules)->validate();

		$input['name'] = $input['first_name'] . ' ' . $input['last_name'];

		$file['image'] = '';
		if ($request->hasFile('image')) {
			$file['image'] = \AppHelper::getUniqueFilename($request->file('image'), AppHelper::getImagePath());
			$request->file('image')->move(AppHelper::getImagePath(), $file['image']);
			$input['image'] = $file['image'];
		} else {
			$input['image'] = ($user->image) ?: '';
		}

		$userFields = array();
		$agentFields = array();
		$userFields = [
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
		];

		$agentFields = [
			'paypal_id' => $input['paypal_id'],
			'zip_code' => $input['zip_code'],
//			'biography' => $input['biography'],
//			'qualifications' => $input['qualifications'],
//			'experience' => $input['experience'],
			'city' => $input['city'],
            'need_card' => $input['need_card'],
            'module_restriction' => $input['module_restriction'],
            'pp_llpcoach_fast' => $input['pp_llpcoach_fast'],
            'pp_coach_fast' => $input['pp_coach_fast'],
            'pp_llpcoach_normal' => $input['pp_llpcoach_normal'],
            'pp_coach_normal' => $input['pp_coach_normal'],
            'credits_per_month' => $input['credits_per_month'],
            'credits_accumulate' => $input['credits_accumulate']
		];
		DB::transaction(function () use ($agent, $user, $agentFields, $userFields, $input, $id, $request) {
			$agent->update($agentFields);
			$user->update($userFields);

			if (!empty($input['program_id']) && isset($input['program_id'])) {
				AgentProgram::where('agent_id', $id)->update(['deleted' => '1']);
				foreach ($input['program_id'] as $key => $program_id) {
					$sub_array = array();
					$sub_array['agent_id'] = $id;
					$sub_array['program_id'] = $program_id;
					$sub_array['deleted'] = '0';
					if (isset($input['agent_program_id'][$program_id]) && !empty($input['agent_program_id'][$program_id])) {
						// dump('if exist');
						// dump($input['agent_program_id'][$program_id]);
						AgentProgram::withoutGlobalScope('agent_program.deleted')->where('id', $input['agent_program_id'][$program_id])->update($sub_array);
					} else {
						// dump('create new');
						AgentProgram::create($sub_array);
					}
				}
			}
			if ($request->get('current_password')) {
				Validator::extend('validateCurrentPassword', 'App\Validators\ChangePasswordValidator@validateCurrentPassword');
				$result = $this->validate($request, [
					'current_password' => 'required|validateCurrentPassword',
					'new_password' => 'required_with:current_password|min:8|regex:/^(?=.*[0-9])[a-zA-Z0-9]+$/|confirmed',
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
				$editUser->update($credentials);
			}
			Flash::success(trans("comman.agent_profile_updated"));
		});
		return redirect()->route('agent.update.profile');
	}

	public function get_index($sort_order) {
		//$models = Agent::with('user', 'clients')->where("coaches.deleted", "0");
		$models = Agent::with(['user', 'clients' => function ($query) {
			$query->where('user_type', '=', 'client');
		}, 'coaches' => function ($query) {
			$query->where('user_type', '=', 'coach');
		}])->where("agents.deleted", "0");
		$models->select(array(
			"agents.*",
		));

		if (request()->get('fullname', false)) {
			$models->whereHas('user', function ($q) {
				$q->where('name', 'like', "%" . request()->get("fullname") . "%");
			});
		}
		if (request()->get('status', false)) {
			$models->whereHas('user', function ($q) {
				$q->where('status', '=', request()->get("status"));
			});
		}
		if (request()->get('date_joined', false)) {
			$models->where(\DB::raw("DATE_FORMAT(created_at,'%d-%m-%Y')"), 'like', "%" . request()->get("date_joined") . "%");
		}
		if (request()->get('hourly_rate', false)) {
			$models->where('hourly_rate', 'like', "%" . request()->get("hourly_rate") . "%");
		}

		$models->whereHas('user', function ($q) {
			$q->where('user_type', '=', "agent");
		});

		if (!empty($sort_order) && is_array($sort_order)) {
			foreach ($sort_order as $column => $direction) {
				$models->orderBy($column, $direction);
			}
		} else {
			$models->orderBy('agents.id', 'DESC');
		}
		$per_page = config('srtpl.row_per_page');
        if(isset(Cache::get('settings')['per_page'])) {
            $per_page = Cache::get('settings')['per_page'];
        }
        return $models->paginate($per_page);
		// return $models->paginate(Config::get("srcore/core::srtpl.par_page", 10));
	}
	public function ajaxAgents(Request $request, $param = array()) {
		$agents = array();
		$agentData = Agent::with(['user' => function ($query) use ($request, $param) {
			if ($request->get('agent_gender', false)) {
				$query->where('gender', $request->get('agent_gender'));
			} else if ($request->old('agent_gender', false)) {
				$query->where('gender', $request->old('agent_gender'));
			} else if (!empty($param['agent_gender'])) {
				$query->where('gender', $param['agent_gender']);
			}
		},
			'programs' => function ($query) use ($request, $param) {
				if ($request->get('program_id', false)) {
					$query->where('program_id', $request->get('program_id'));
				} else if ($request->old('program_id', false)) {
					$query->where('program_id', $request->old('program_id'));
				} else if (!empty($param['program_id'])) {
					$query->where('program_id', $param['program_id']);
				}
			}]);
		$agentData = $agentData->get()->toArray();
		foreach ($agentData as $agent) {
			if ($agent['user'] && !empty($coach['programs'])) {
				$agents[$agent['id']] = $agent['user']['name'];
			}
		}
		return $agents;
	}
	public function get_newest_agents() {
		$models = Agent::with(['user', 'clients']);
		$models->select(array(
			"agents.*",
		))->whereHas('user', function ($q) {
			$q->where('user_type', '=', "agent");
		})->where(DB::raw('MONTH(created_at)'), Carbon::now()->format('n'))
			->orderBy('created_at', 'DESC');
		return $models->get();
	}

	public function getAgentDashboardStatistics() {
		$models = Agent::withCount(['clients' => function ($query) {
			$query->where('user_type', '=', 'client');
		}, 'coaches' => function ($query) {
			$query->where('user_type', '=', 'coach');
		}])->where("agents.deleted", "0")
			->where('user_id', Auth::id())
			->first();
		return $models;
	}
}
