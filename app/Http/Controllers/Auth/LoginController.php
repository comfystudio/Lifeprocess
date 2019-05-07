<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CoachTransactionHistory;
use Flash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App;
use App\Models\Program;
use Illuminate\Support\Facades\Crypt;

class LoginController extends Controller {
	/*
		    |--------------------------------------------------------------------------
		    | Login Controller
		    |--------------------------------------------------------------------------
		    |
		    | This controller handles authenticating users for the application and
		    | redirecting them to your home screen. The controller uses a trait
		    | to conveniently provide its functionality to your applications.
		    |
	*/

	use AuthenticatesUsers;

	/**
	 * Where to redirect users after login.
	 *
	 * @var string
	 */
	protected $redirectTo = '/dashboard';

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware('guest', ['except' => 'logout']);
	}

	/**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm(Request $request)
    {
        $email=$request->session()->pull('email');
    	if (request()->get('token', false)) {
	    	return redirect()->route('transaction.receipt', ['token' => request()->get('token'), 'u_id' => request()->get('u_id')]);
	    }
	    $object = App::make("App\Http\Controllers\CoachController");
        view()->share('coaches', $object->ajaxCoaches($request));
        $object          = App::make("App\Http\Controllers\ProgramController");
        $param['status'] = 'published';

        if(isset($request->program) && !empty($request->program))
        {
        	$program=Program::where('program_name',$request->program)->get()->first();
        	if(empty($program)){
        		return \Redirect::to(env('MAIN_SITE_URL'));
        	}
        	view()->share('register','open');
        	view()->share('program', $program->id);
            view()->share('error', '');
            view()->share('user','');
            //view()->share('programs', $object->ajaxCoachPrograms($request, $param));
            return view('auth.register');
        }
        else
        {
        	$program='1';
        	view()->share('register','close');
        	view()->share('program', $program);
        }
        view()->share('error', '');
         view()->share('user','');
          view()->share('email',$email);
        //view()->share('programs', $object->ajaxCoachPrograms($request, $param));
        return view('auth.login');
    }

	/**
	 * Handle a login request to the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
	 */
	public function login(Request $request) {

		$this->validateLogin($request);
        if ($this->hasTooManyLoginAttempts($request)) {

            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        if ($this->attemptLogin($request)) {

            $check_user_active = User::select('id',"status", 'user_type', 'registration_completed')->where("email", $request->get('email'))->first();
            if ($check_user_active->status == "in_active") {
                Flash::error("Your account is not activated. Please contact administrator.");
                Auth::logout();
                return redirect()->route('login');
            }
            if ($check_user_active->registration_completed == "0" && $check_user_active->user_type == 'client') {
                Flash::error("Your registration process is incomplete, please complete all the steps of registration.");
                Auth::logout();
                $check_user_active_id=Crypt::encryptString($check_user_active->id);
                return redirect()->route('registerthirdstep', $check_user_active_id);
            }
            $user = Auth::user()->update(['is_login' => 1,'last_login' => Carbon::now()]);
            return $this->sendLoginResponse($request);
        }
        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        $useremail=User::select('id','email')->where("email", $request->get('email'))->first();

        if(!empty($useremail))
        {
            $link="<a href=http://program.lifeprocessprogram.com/password/request
>reset your password</a>";
            $errors=["email"=>"Sorry, password error we can’t find an account with this email address. Please try again"];
            $request['error'] = ["password"=>"Please try again or you can ".$link];
            $request->session()->put('email', $useremail->email);
            return $this->sendFailedLoginResponse($request);
        }
        else
        {

            $request['error'] =["email"=>"Sorry, we can’t find an account with this email address. Please try again"];
            return $this->sendFailedLoginResponse($request);
        }
    }


	/**
	 * Attempt to log the user into the application.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return bool
	 */
	protected function attemptLogin(Request $request) {
		return $this->guard()->attempt(
			$this->credentials($request), $request->has('remember')
		);
	}

	/**
	 * Get the needed authorization credentials from the request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array
	 */
	protected function credentials(Request $request) {
		return $request->only($this->username(), 'password');
	}

	/**
	 * Redirect the user based on user type.
	 *
	 * @return string uri
	 */
	protected function redirectTo() {
		// user is admin
		if (Auth::user()->user_type == 'user') {
			return route('dashboard');
		} else if (Auth::user()->user_type == 'client') {
			return route('client.dashboard');
		} else if (Auth::user()->user_type == 'coach') {
			return route('coach.dashboard');
		} else if (Auth::user()->user_type == 'agent') {
            return route('agent.dashboard');
        } else if (Auth::user()->user_type == 'read-only-coach') {
            return url('/clients');
        }
	}

	/**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
    	//dump(Auth::user()); exit();
    	// update the last active date.. of the user.. to keep log...
    	$user = User::find(Auth::id());
    	$user->update(['is_login'=> 0, 'last_active' => Carbon::now()->format('Y-m-d H:i:s'),'dont_show_dialog'=> '0']);
		\Cache::forget('current_user_timeZone');
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        return redirect('/');
    }

	/*protected function validateLogin(Request $request){
		        $this->validate($request, [
		            $this->username() => 'required|exists:users,' . $this->username() . ',status,active',
		            'password' => 'required',
		        ], [
		            $this->username() . '.exists' => 'Your account is not activated. Please contact administrator.'
		        ]);
	*/

    public function backToAdmin(Request $request)
    {
        if(\Session::has('admin_user_id')){
            if (Auth::check()) {
                $user = Auth::user();
                Auth::logout();
                Auth::loginUsingId(\Session::get('admin_user_id'));
                if($user->user_type =='coach'){
                    return \Redirect::to('/coaches');
                }
                if($user->user_type=='client'){
                    return \Redirect::to('/clients');
                }
                return \Redirect::to('/dashboard');
            }
        }
    }
}
