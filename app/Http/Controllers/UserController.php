<?php

namespace App\Http\Controllers;

use App;
use AppHelper;
use App\Models\Activation;
use App\Models\Country;
use App\Models\User;
use Auth;
use Cache;
use Carbon\Carbon;
use Flash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Mail;

class UserController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->middleware('check_for_permission.access:users.create', ['only' => ['create', 'store']]);
		$this->middleware('check_for_permission.access:users.view', ['only' => ['index', 'show']]);
		$this->middleware('check_for_permission.access:users.update', ['only' => ['edit', 'update']]);
		$this->middleware('check_for_permission.access:users.delete', ['only' => ['destroy']]);
		$this->middleware('check_for_permission.access:auto_login.can_login', ['only' => ['destroy']]);
		$this->title = "Users";
		view()->share('title', $this->title);
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
			'middle_name' => 'required|max:50',
			'mobile_no' => 'required',
			'country_id' => 'required',
			'terms_and_condition' => 'required',
			'role_id' => 'required',
			'image' => 'image',
		];

		$messages = [
			'role_id.required' => 'The role field is required.',
			'program_id.required' => 'The program field is required.',
			'timezone.required' => 'The Timezone field is required.',
			'image' => 'The avatar/photo field must be an image.',
			'password.regex' => 'Password must be combination of number and both uppercase and lowercase letters.',
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
			$rules['username'] = [
				'required',
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
			"add_new" => array("title" => '<b><i class="icon-diff-added"></i></b> ' . trans("comman.add_user"), "url" => route('users.create'), "attributes" => array("class" => "btn bg-success btn-add btn-labeled heading-btn", 'title' => 'Add New')),
		);
		if (!Auth::user()->hasAccess('users.create')) {
			unset($action_nav['add_new']);
		}
		view()->share('module_action', $action_nav);
		view()->share('users', $this->get_index(array(), array()));
		view()->share('counter', 0);
		return view('users.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create(Request $request) {
		view()->share('module_action', array(
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('users.index'),
				"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		));
		/* Get Countries */
		view()->share('countries', Country::where('deleted', '=', '0')->pluck('country', 'id')->toArray());
		/* Get States */
		$object = App::make("App\Http\Controllers\StateController");
		view()->share('states', $object->ajaxAllStates($request, array('country_id' => (isset($user_profile)) ? $user_profile->country_id : '')));

		//Get Roles
		$object = App::make("App\Http\Controllers\RoleController");
		view()->share('roles', $object->getAllRoles());
		view()->share('title', trans("comman.users"));
		return view('users.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		$input = $request->all();
		$this->validator($request->all())->validate();

		$input['name'] = $input['first_name'] . ' ' . $input['last_name'];
		$input['password'] = bcrypt($input['password']);

		$file['image'] = '';
		if ($request->hasFile('image')) {
			$file['image'] = \AppHelper::getUniqueFilename($request->file('image'), AppHelper::getImagePath());
			$request->file('image')->move(AppHelper::getImagePath(), $file['image']);
			$input['image'] = $file['image'];
		}
		if (empty($input['state_id'])) {
			unset($input['state_id']);
		}
		if (empty($input['country_id'])) {
			unset($input['country_id']);
		}

		$user = User::create($input);
		$code = AppHelper::GenrateCode();
		if (!isset($input['status'])) {
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

		if ($request->ajax()) {
			return response()->json([
				'success' => 'true',
				'data' => $user,
			]);
		}
		Flash::success(trans("comman.user_added"));

		if ($request->get('save_exit')) {
			return redirect()->route('users.index');
		} else {
			return redirect()->route('users.create');
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		$user = User::findOrFail($id);

		return view('users.show', compact('user'));
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
			"back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('users.index'),
				"attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
		));

		$user = User::find($id);
		if (is_null($user)) {
			return redirect()->route('users.index');
		}

		$tmp = explode(' ', trim($user->name));
		$user->first_name = head($tmp);
		$user->last_name = (count($tmp) > 1) ? last($tmp) : '';

		/* Get Countries */
		view()->share('countries', Country::where('deleted', '=', '0')->pluck('country', 'id')->toArray());

		/* Get States */
		$object = App::make("App\Http\Controllers\StateController");
		view()->share('states', $object->ajaxAllStates($request, array('country_id' => (isset($user)) ? $user->country_id : '')));
		//Get Roles
		$object = App::make("App\Http\Controllers\RoleController");
		view()->share('roles', $object->getAllRoles());
		view()->share('title', trans("comman.users"));
		return view('users.edit', compact('user'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		//dd($request->role_id);
		$id = Crypt::decryptString($id);

		$user = User::findOrFail($id);
		$input = $request->all();
		$extra_rules = array(
			'email' => [
				'required',
				'email',
				'max:190',
				Rule::unique('users')->where(function ($query) {
					$query->where('deleted', '0');
				})->ignore($id),
			],
			'username' => [
				'required',
				'max:190',
				Rule::unique('users')->where(function ($query) {
					$query->where('deleted', '0');
				})->ignore($id),
			],
		);
		// Do we need to update the password as well?
		if ($request->has('password')) {
			$extra_rules['password'] = 'required|min:8|regex:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9!@#$%^&*]+$/|confirmed';
		}
		$this->validator($request->all(), 'edit', $extra_rules)->validate();

		$input['name'] = $input['first_name'] . ' ' . $input['last_name'];

		// Do we need to update the password as well?
		if ($request->has('password')) {
			$input['password'] = bcrypt($input['password']);
		} else {
			unset($input['password']);
		}
		if (empty($input['state_id'])) {
			($input['state_id'] = 0);
		}
		if (empty($input['country_id'])) {
			($input['country_id'] = 0);
		}
		if (!isset($input['status'])) {
			$input['status'] = 'in_active';
		}
		if (!isset($input['terms_and_condition'])) {
			$input['terms_and_condition'] = 'no';
		}

		$file['image'] = '';
		if ($request->hasFile('image')) {
			$file['image'] = \AppHelper::getUniqueFilename($request->file('image'), AppHelper::getImagePath());
			$request->file('image')->move(AppHelper::getImagePath(), $file['image']);
			$input['image'] = $file['image'];
		} else {
			unset($input['image']);
		}
		// dump($input); die();
		$user->update($input);
		Flash::success(trans("comman.user_updated"));

		if ($request->get('save_exit')) {
			return redirect()->route('users.index');
		} else {
			return redirect()->route('users.edit', Crypt::encryptString($id));
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

		$model = User::find($id);
		if ($model) {
			$dependency = $model->deleteValidate($id);
			if (!$dependency) {
				$model->deleted = '1';
				$model->save();
				Flash::success(trans("comman.user_deleted"));
			} else {
				Flash::error(trans("comman.user_dependency_error", ['dependency' => $dependency]));
			}
		} else {
			Flash::error(trans("comman.user_error"));
		}
		return redirect()->route('users.index');
	}

	// Get current logged in user profile
	public function getProfile(Request $request) {
		$user = Auth::user();
		$tmp = explode(' ', trim($user->name));
		$user->first_name = head($tmp);
		$user->last_name = (count($tmp) > 1) ? last($tmp) : '';
		// dd($user);
		/* Get Countries */
		view()->share('countries', Country::where('deleted', '=', '0')->pluck('country', 'id')->toArray());
		/* Get States */
		$object = App::make("App\Http\Controllers\StateController");
		view()->share('states', $object->ajaxAllStates($request, array('country_id' => (isset($user)) ? $user->country_id : '')));
		view()->share('title', 'User Profile');
		view()->share('user', $user);
		return view('users.edit_profile');
	}

	//update current user profile...
	public function updateProfile(Request $request) {
		$user = Auth::user();

		$result = $this->validate($request, [
			'first_name' => 'required|max:255',
			'last_name' => 'required|max:255',
			'middle_name' => 'required|max:255',
			'mobile_no' => 'required',
			'country_id' => 'required',
			'terms_and_condition' => 'required',
			'image' => 'image',
			'username' => [
				'required',
				'max:190',
				Rule::unique('users')->where(function ($query) {
					$query->where('deleted', '0');
				})->ignore($user->id),
			],
		],
			[
				'image' => 'The avatar/photo field must be an image.',
			]
		);

		$input = $request->except('_token', 'email');

		$input['name'] = $input['first_name'] . ' ' . $input['last_name'];
		if (empty($input['state_id'])) {
			($input['state_id'] = 0);
		}
		if (empty($input['country_id'])) {
			($input['country_id'] = 0);
		}

		if (!isset($input['terms_and_condition'])) {
			$input['terms_and_condition'] = 'no';
		}

		$file['image'] = '';
		if ($request->hasFile('image')) {
			$file['image'] = \AppHelper::getUniqueFilename($request->file('image'), AppHelper::getImagePath());
			$request->file('image')->move(AppHelper::getImagePath(), $file['image']);
			$input['image'] = $file['image'];
		} else {
			unset($input['image']);
		}
		$user->update($input);

		if($request->get('current_password')){
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

			if($request->get('new_password')){
			    $editUser = Auth::user();
			    $credentials['password']=bcrypt($request->get('new_password'));
			    $editUser->update($credentials);
			}
		}

		Flash::success(trans("comman.user_profile_updated"));

		return redirect()->route('users.update.profile');
	}

	// function to autologin user
	public function autoLoginUserById(Request $request, $user_id) {
		$admin_user = \Auth::user();
        \Session::put('admin_user_id',$admin_user->id);
		$user_id = Crypt::decryptString($user_id);
		Auth::loginUsingId($user_id);
		$user = Auth::user()->update(['is_login' => 1,'last_login' => Carbon::now()]);
		// $login = App::make('App\Http\Controllers\Auth\LoginController');
		\Cache::forget('current_user_timeZone');
		if (Auth::user()->user_type == 'user') {
			return redirect()->route('dashboard');
		} else if (Auth::user()->user_type == 'client') {
			return redirect()->route('client.dashboard');
		} else if (Auth::user()->user_type == 'coach') {
			return redirect()->route('coach.dashboard');
		} else if (Auth::user()->user_type == 'agent') {
			return redirect()->route('agent.dashboard');
		}
	}

	// function to get the listing for the index page...
	public function get_index($filters, $sort_order) {
		$models = User::where("users.user_type", "user");
		$models->select(array(
			"users.*",
		));
		if (request()->get('name', false)) {
			$models->where('name', 'like', "%" . request()->get("name") . "%");
		}
		if (request()->get('email', false)) {
			$models->where('email', 'like', "%" . request()->get("email") . "%");
		}
		if (request()->get('mobile_no', false)) {
			$models->where('mobile_no', 'like', "%" . request()->get("mobile_no") . "%");
		}
		if (request()->get('status', false)) {
			$models->where('status', '=', request()->get("status"));
		}
		if (!empty($sort_order) && is_array($sort_order)) {
			foreach ($sort_order as $column => $direction) {
				$models->orderBy($column, $direction);
			}
		} else {
			$models->orderBy('users.id', 'DESC');
		}
		$per_page = config('srtpl.row_per_page');
		if(isset(Cache::get('settings')['per_page'])) {
            $per_page = Cache::get('settings')['per_page'];
        }
		return $models->paginate($per_page);
		// return $models->get();
		// return $models->paginate(Config::get("srcore/core::srtpl.par_page", 10));
	}

	public function ajaxAllUsers(Request $request) {

		$user_type = Auth::user()->user_type;
		$uid = Auth::id();

		if ($request->get('role_id', false)) {
			$role_id = $request->get('role_id');
			$slug = App\Models\Role::where('id', '=', $role_id)->
				select('slug')->first()->slug;
			if ($user_type == 'client' && $slug == 'coach') {

				$current = [];
				$client = App\Models\Client::with('user', 'coach.user')->
					where('user_id', '=', $uid)->first();
				$coach = $client->coach->user->name;
				$id = $client->coach->user->id;
				$current = [$id => $coach];
				return $current;

			} elseif ($user_type == 'coach' && $slug == 'client') {

				$current = [];
				$coach_user_id = App\Models\Coach::where('user_id', '=', $uid)->first()->id;
				$client = App\Models\Client::with('user')->where('coach_id', '=', $coach_user_id)->first();
				$clientname = $client->user->name;
				$id = $client->user->id;
				$current = [$id => $clientname];
				return $current;

			} else {

				$user = User::where('role_id', $request->get('role_id'))->pluck('name', 'id')->toArray();
				return $user;

			}
		} elseif ($request->old('role_id', false) !== false) {
			$user = User::where('role_id', $request->old('role_id'))->pluck('name', 'id')->toArray();
			return $user;

		}

		return array();

	}
	public function getCountClientLogin()
	{
		$client = User::where('user_type','client')->where('status','active');
		$client_30day = $client->where(\DB::raw("DATE(last_active)"), '>=', Carbon::now()->subDays(30)->toDateTimeString())->count();
		$client = User::where('user_type','client')->where('status','active');
		$client_60day = $client->where(\DB::raw("DATE(last_active)"), '>=', Carbon::now()->subDays(60)->toDateTimeString())->count();
		$client_count = ['client_login_in_30_day'=>$client_30day,'client_login_in_60_day'=>$client_60day];
		return $client_count;

	}
}
