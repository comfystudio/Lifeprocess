<?php
namespace App\Http\Controllers\Auth;
use AppHelper;
use App;
use App\Events\CoachTransactionHistoryEvent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\StripeController;
use App\Models\Activation;
use App\Models\Client;
use App\Models\CoachTransactionHistory;
use App\Models\EmailTemplate;
use App\Models\Page;
use App\Models\Program;
use App\Models\ReferFriend;
use App\Models\Role;
use App\Models\User;
use App\Models\Coach;
use App\Models\CardDetail;
use Carbon\Carbon;
use DB;
use Flash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Mail;
use PayPal;
use \Stripe\Token;
use Response;
use Illuminate\Support\Facades\Crypt;
use App\Models\Setting;
//use CardDetect\Detector;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */
    //use RegistersUsers;
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = '/client-dashboard';
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        ini_set('max_execution_time', 300); //5 minutes
    }
    /**
     * Redirect the user based on user type.
     *
     * @return string uri
     */
    protected function redirectTo()
    {
        // user is admin
        if (Auth::user()->user_type == 'user') {
            return '/dashboard';
        } else if (Auth::user()->user_type == 'client') {
            return '/client-dashboard';
        } else if (Auth::user()->user_type == 'coach') {
            return '/coach-dashboard';
        }
    }
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'program_id'           => 'required',
            'first_name'           => 'required|max:255',
            'last_name'            => 'required|max:255',

            'email'                => [
                'required',
                'email',
                'max:190',
                Rule::unique('users')->where(function ($query) {
                    $query->where('deleted', '0');
                }),
            ],
            'password'             => 'required|min:8|regex:/^(?=.*[0-9])[a-zA-Z0-9!@#$%^&*]+$/',
              'password.regex' => 'Password must be combination of number and both uppercase and lowercase letters.',
            // // 'password'             => 'required|min:8|regex:/^(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9]+$/|confirmed',

        ]);
    }
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $code       = "";
        $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
        $max        = count($characters) - 1;
        for ($i = 0; $i < 35; $i++) {
            $rand = mt_rand(0, $max);
            $code .= $characters[$rand];
        }
        $role_id = Role::where('role_name', 'client')->first()->id;
        if (!$role_id) {
            $role_id = 0;
        }
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'name'       => $data['first_name'] . ' ' . $data['last_name'],
            'email'      => $data['email'],
            'password'   => bcrypt($data['password']),
            'user_type'  => 'client',
            'role_id'    => $role_id,
            'timezone'   => $data['timezone'],
        ]);
        $client = Client::create([
            'user_id'    => $user->id,
            'coach_id'   => 0,
            'program_id' => $data['program_id'],

        ]);
        $activation = Activation::create([
            'user_id' => $user->id,
            'code'    => $code,
        ]);
        // Send the activation email
        if (config('app.env') != "local") {
            $email      = $data['email'];
            $first_name = $data['first_name'];
            $program    = Program::where('id', $data['program_id'])->first();
            $program_nm = !empty($program) ? $program->program_name : '';
            Mail::send(
                'email_template.welcome', ['code' => $code, 'email' => $email, 'first_name' => $first_name], function ($message) use ($email) {
                    $message->to($email)
                        ->subject("Welcome to the Life Process $program_nm Program");
                    // $bcc = explode(',', config('srtpl.bccmail'));
                    // if (!empty($bcc)) {
                    //     $message->bcc($bcc);
                    // }
                });
        }
        session()->flash('success', "You are registered successfully. Please check your mailbox for activation link.");
        return redirect()->route('login');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(Request $request)
    {
        $object = App::make("App\Http\Controllers\CoachController");
        view()->share('coaches', $object->ajaxCoaches($request));
        $object          = App::make("App\Http\Controllers\ProgramController");
        $param['status'] = 'published';
        view()->share('programs', $object->ajaxCoachPrograms($request, $param));
        $object = App::make('App\Http\Controllers\CountryController');
        view()->share('countries', $object->ajaxCountries($request));
        $auth_user = Auth::user();
        return view('auth.register');
    }
    public function registerthirdstep(Request $request,$id)
    {
        $id = Crypt::decryptString($id);
        $user=DB::table('users')->where('id',$id)->first();

        if($user->registration_completed){
            return redirect()->route('login');
        }
        $client = Client::with('coach.user','program')->where('user_id', $id)->first();
        view()->share('coach_detail', '');
        if(isset($client->coach) && !empty($client->coach)){
            $coach_detail = $client->coach->user;
            view()->share('coach_detail', $coach_detail);
        }
        $program=$client->program->program_name;
        view()->share('user', $user);
        view()->share('program',$program);
        view()->share('timezones', get_timezone_list());
        return view('auth.register-step-third');
    }
    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registermodel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first' => 'required',
            'last'=>'required',
             'email'                => [
                'required',
                'email',
                'max:190',
                Rule::unique('users')->where(function ($query) {
                    $query->where('deleted', '0');
                }),
            ],
            'password' => 'required|min:6',
            'program_id'  => 'required',
        ]);

        $input = $request->all();

        if ($validator->passes()) {

            // Store your user in database
            $data       = $request->all();
            $user = User::create([
                'first_name'         => $data['first'],
                'last_name'          => $data['last'],
                'name'               => $data['first'] . ' ' . $data['last'],
                'email'              => $data['email'],
                'password'           => bcrypt($data['password']),
                'user_type'          => 'client',
                'status'             => 'active',
                'role_id'            => '4',
                'deleted' => '1',
            ]);
            $user=DB::table('users')->orderBy('id', 'desc')->first();
            $id=$user->id;
            return Response::json(['success' => '1','user_id'=>$id,'program_id'=> $data['program_id']]);
        }
        return Response::json(['errors' => $validator->errors()]);
    }
    public function settimezone(Request $request)
    {
        User::where('id',$request->get('userid'))->update(['timezone'=> $request->get('timezone')]);
            return response()->json([
                        'success' => 'true',
            ]);
    }
    public function setcoach(Request $request)
    {
            $object = App::make("App\Http\Controllers\CoachController");
            $client_details = Client::where('user_id', $request->get('userid'))->first();
            $coach_gender=$request->get('gender');
            if($coach_gender=='No')
            {
                $coach_gender='';
            }
            $models=$object->ajaxCoaches_timezone($request,
                array(
                    'coach_gender' => (isset($coach_gender)) ? $coach_gender : '',
                    'program_id' => $client_details->program_id,
                    'available' => 'yes'
                ));

            $coach_id="";
            $coach_gender="";
            // print_r($models);
            foreach($models as $value)
            {
                $coach_id=$value['id'];
                $coach_gender=$value['user']['gender'];
                break;
            }

            // print_r($coach_id); exit();
            if($coach_id==""){
                $coach_id='5';
            }
            if($coach_gender=="")
            {
                $coach_gender='';
            }

            Client::where('user_id', $request->get('userid'))->update([
                'coach_id'=> $coach_id,
                'coach_gender'=> $request->get('gender'),
            ]);
            return response()->json([
                        'success' => 'true',
                        'coach_id'=>$coach_id,
                        'url' => route('clients.dashboard.coaching'),
            ]);
    }
    public function setterms(Request $request)
    {

        User::where('id', $request->get('userid'))->update(['terms_and_condition'=> $request->get('terms')]);
        $client = Client::with('coach.user')->where('user_id', $request->get('userid'))->first();
        if(isset($client->coach) && !empty($client->coach)){
            $coach_detail = $client->coach->user;
        }

            if(!empty($coach_detail['image']) && \File::exists(\AppHelper::path('uploads/user/')->getImageUrl($coach_detail['image']))){
                $coach_image = \AppHelper::path('/uploads/user/')->getImageUrl($coach_detail['image']);
            }else{
                $coach_image = '';
            }

        if(empty($coach_detail['image']))
        {
            $coach_image='';
        }
        $code       = "";
        $characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
        $max        = count($characters) - 1;
        for ($i = 0; $i < 35; $i++) {
            $rand = mt_rand(0, $max);
            $code .= $characters[$rand];
        }

        $activation = Activation::create([
                    'user_id' =>  $request->get('userid'),
                    'code'    => $code,
                ]);

        $user=User::where('id',$request->get('userid'))->where('deleted','0')->first();
        $email          = $user->email;
        $firstname = $user->first_name;
        $activationlink='';
        $coachname=$client->coach->user->first_name;
        $program        = Program::where('id', $client->program_id)->first();
        $program_nm     = !empty($program) ? $program->program_name : '';
        $email_template = EmailTemplate::where('slug','initial-signup')->first()->toArray();
        if(empty($user->stripe_id))
        {
            $method='paypal';
        }
        else
        {
            $method='stripe';
        }
        if(isset($email_template))
        {
                        $tag = ['[client-email]','[activation-link]','[program-name]','[first-name]','[coach-name]'];
                        $replace_tag = [$email,$activationlink,$program_nm,$firstname,$coachname];
                        $to = str_replace($tag,$replace_tag,$email_template['to']);
                        $subject = str_replace($tag,$replace_tag,$email_template['subject']);
                        $content = str_replace($tag,$replace_tag,$email_template['content']);
                        Mail::send(
                            'email_template.comman', ['content' => $content],function ($message) use($to,$subject){
                                $message->to($to)
                                ->subject($subject);
                                // $bcc = explode(',', config('srtpl.bccmail'));
                                // if (!empty($bcc)) {
                                //     $message->bcc($bcc);
                                // }
                            });

                        //New User has signed up need to add them to mail chimp
                        $this->addMailChimpUser($user);
        }
        $setting=Setting::where('name','admin_email')->first();
        $adminemail=$setting->value;
        $userEmail = $user->email;
        Mail::send('email_template.adminregistermail',['program' => $program_nm,'name' => $firstname,'email'=>$email,'paymentmethod'=>$method], function ($message) use($user,$program_nm,$adminemail, $userEmail){
                            $message->to($adminemail)
                            ->subject("New ".$program_nm." Sign-up");
                            //  $bcc = explode(',', config('srtpl.bccmail'));
                            // if (!empty($bcc)) {
                            //     $message->bcc($bcc);
                            // }
                        });

        return response()->json([
                'success' => 'true', 'coach_detail' => $coach_detail, 'coach_image' => $coach_image,'coach_name'=>$coachname,
        ]);
    }
    public function registermodelfirst(Request $request)
    {
        $data       = $request->all();
        $user =User::withoutGlobalScopes()->where('id',$data['user'])->first();
        view()->share('user', $user);
        view()->share('timezones', get_timezone_list());
        if ($data['payment_type'] == "stripe")
        {

            try
            {
                // make object for use stripController's method
                $StripeController = App::make(StripeController::class);
                $card_input = array(
                    "name" => $data['card_holder'],
                    "number" => $data['card_number'],//$request->get('card_number'),
                    "exp_month"   => $data['month'],
                    "exp_year"    => $data['year'],
                    "cvc"         => $data['CVV_number'],
                );
                // $user->deleteCards(); // For delete the existing card details from the Stripe Account. this method generally use for update the card details for existing user
                // Get selected Program Data
                $program = Program::where('id', $data['program'])->first();

                $program_name = $program->stripe_program_name;

                //$stripePlan = $StripeController->checkAndCreatePlan($program);
                $stripePlan = $StripeController->retrivePlan($program_name);

                $stripeToken = $StripeController->createStripeToken($card_input);

                $response    = $user->newSubscription($program_name, $stripePlan)->create($stripeToken);

                //print_r($response['stripe_id']); exit;
                $stripe_sub_id=$response['stripe_id'];

                // $user=User::where('id',$data['user'])->update(['skype_id'=> $stripe_sub_id]);
                // print_r($user); exit();
                $client = Client::create([
                'user_id'    => $data['user'],
                'program_id' => $data['program'],
                'kindle_email'=>$stripe_sub_id,
                    ]);
                User::withoutGlobalScopes()->where('id', $data['user'])->update(['stripe_sub_id' => $stripe_sub_id,'subscription_plan_status'=>'Active']);
                //User::where('id',$data['user'])->update(['stripe_sub_id'=> $stripe_sub_id]);
                // This code will used when user refer and friend and get the free month subscription
                // $stripe_customer = $user->asStripeCustomer();
                // $user->applyCoupon('1 month free');

            } catch (\Exception $e) {

                $transation_history_arr = [
                    'user_id'              => $data['user'],
                    'transaction_type'     => 'error',
                    'object_id'            =>  0,
                    'object_type'          => 'clients',
                    'transaction_amount'   => 0,
                    'transaction_detail'   => "Internal Error: " . $e->getMessage(),
                    'transaction_status'   => 'Failure',
                    'transaction_response' => 'error',
                ];

                User::where('id',$data['user'])->update(['deleted'=> '1']);
                //fire event..
                event(new CoachTransactionHistoryEvent($transation_history_arr));

                User::withoutGlobalScopes()->where('id', $data['user'])->update(['deleted' => '1']);
               // Client::where('user_id', $data['user'])->update(['deleted' => '1']);
                return Response::json(['errors' =>  $e->getMessage()]);
            }

            if (isset($response) && $response) {
                $retrive= \Stripe\Subscription::retrieve($stripe_sub_id);
                $timestamp = $retrive->current_period_end;
                $next_payment_date = date('Y-m-d',$timestamp);
                $timestamp = $retrive->current_period_start;
                $last_payment_date = date('Y-m-d',$timestamp);

                $transation_history_arr = [
                    'user_id'               => $data['user'],
                    'transaction_type'      => 'plus',
                    'object_id'             => $response->stripe_id,
                    'object_type'           => 'clients',
                    'transaction_amount'    => $program->program_fee,
                    'transaction_detail'    => 'Started the subscription plan on <strong>' . Carbon::now('UTC')->format('D dS F Y \a\t h:i a') . '</strong>',
                    'paypal_token'          => $stripeToken,
                    'paypal_payerId'        => '',
                    'paypal_profile_id'     => 0, //['PROFILEID'],
                    'paypal_profile_status' => 'successfully', //['PROFILESTATUS'],
                    'transaction_status'    => 'success', //['ACK'],
                    'transaction_response'  => json_encode($response),
                    'next_billing_date' =>$next_payment_date,
                    'last_payment_date' =>$last_payment_date,
                    'format'=>'stripe',
                ];
                User::withoutGlobalScopes()->where('id',$data['user'])->update(['deleted'=> '0']);
                Client::where('user_id',$user->id)->update(['LPAP_initial_fee'=> 'paid','deleted'=>'0']);
                //fire event..
                event(new CoachTransactionHistoryEvent($transation_history_arr));
                session()->flash('success', "Registered successfully you may now login");
                $data['user']=Crypt::encryptString($data['user']);
                $stripelink=route('registerthirdstep', ['user_id' => $data['user']]);
                return Response::json(['success' => '1','paypal_link'=>'','stripe_link'=>$stripelink]);
            }
        }
        if ($data['payment_type'] == "paypal") {

            $provider = PayPal::setProvider('express_checkout');
            // code to create subscription plan
            $dataItem    = [];
            $program     = Program::where('id', $data['program'])->first();
            $programName = 'LPAP client';
            if (!empty($program)) {
                $programName = $program->program_name;
                $programFee  = $program->program_fee;
            } else {
                $programFee = 0;
            }
            $dataItem['items'] = [
                [
                    'name'  => "Monthly Subscription for Life Process " . $programName ." Program",
                    'price' => $programFee,
                    'qty'   => 1,
                ],
            ];
            $dataItem['subscription_desc']   = "Monthly Subscription for " . $programName;
            $dataItem['invoice_id']          = $data['user'];
            $dataItem['invoice_description'] = "Monthly Subscription for " . $programName;
            $data['user']=Crypt::encryptString($data['user']);
            $dataItem['return_url']          = route('register.subscription', ['user_id' => $data['user']]);
            $dataItem['cancel_url']          = route('register.subscription.cancel', ['user_id' => $data['user']]);
            $total                            = 0;
            $dataItem['total']               = $programFee * 1; // itemPrice * itemQty
            // Use the following line when creating recurring payment profiles (subscriptions)
            $response = $provider->setExpressCheckout($dataItem, true);
            if (stripos($response['ACK'], 'success') !== false) {
                $data['user']=Crypt::decryptString($data['user']);

                $client = Client::create([
                'user_id'    => $data['user'],
                'program_id' => $data['program'],
                ]);
                $agreement_response = $provider->createBillingAgreement($response['TOKEN']);
                return Response::json(['success' => '1','paypal_link'=>$response['paypal_link']]);
            } else {

                //create transaction history
                $transation_history_arr = [
                    'user_id'              => $data['user'],
                    'transaction_type'     => 'minus',
                    'object_id'            => $data['user'],
                    'object_type'          => 'clients',
                    'transaction_amount'   => $programFee,
                    'transaction_detail'   => $response['L_LONGMESSAGE0'],
                    'transaction_status'   => $response['ACK'],
                    'transaction_response' => json_encode($response),
                ];
                //fire event..
                $data['user']=Crypt::decryptString($data['user']);
                User::withoutGlobalScopes()->where('id',$data['user'])->update(['deleted'=> '1']);
                Client::where('user_id', $data['user'])->update(['deleted' => '1']);
                event(new CoachTransactionHistoryEvent($transation_history_arr));
                session()->flash('paymenterror', $response['L_LONGMESSAGE0']);
                return Response::json(['errors' => $response['L_LONGMESSAGE0']]);
            }
        }

    }
    // this fundtion is not in use . it is for one page register process

    //function to get success response from paypal for the subscription paln request...
    public function paypalSubscriptionResponse($user_id)
    {
        $user_id = Crypt::decryptString($user_id);
        User::withoutGlobalScopes()->where('id',$user_id)->update(['deleted'=> '0']);
        $provider           = PayPal::setProvider('express_checkout');
        $checkout_response = $provider->getExpressCheckoutDetails(request()->get('token'));

        if(!empty($user->client->program))
        {
            Client::where('user_id',$user_id)->update(['program_id'=> '10']);
        }

        $user        = User::where('id', $user_id)->with('client.program', 'credit_card_detail','client.coach')->first();
        $program_fee = $user->client->program->program_fee;
        $client      = $user->client;
        if (stripos($checkout_response['ACK'], 'success') !== false) {

             $startdate         = Carbon::now();
             $profile_startDate = Carbon::parse($startdate->format('Y-m-d'));
             $profile_desc      = "Monthly Subscription for " . $user->client->program->program_name;
             $data              = [
                'PROFILESTARTDATE' => $profile_startDate->toAtomString(),
                'DESC'             => $profile_desc,
                'BILLINGPERIOD'    => 'Day', // Can be 'Day', 'Week', 'SemiMonth', 'Month', 'Year'
                'BILLINGFREQUENCY' => 30, //
                'AMT'              => $checkout_response['AMT'], // Billing amount for each billing cycle
                'CURRENCYCODE'     => $checkout_response['CURRENCYCODE'], // Currency code
            ];
            $token=request()->get('token');
            $response = $provider->createRecurringPaymentsProfile($data,$token);
            if (request()->get('PayerID', false)) {
                $payerId = request()->get('PayerID');
            } else {
                $payerId = $checkout_response['PAYERID'];
            }
            if (stripos($response['ACK'], 'success') !== false) {
                User::where('id', $user_id)->update(['paypal_start_date' => $startdate]);
                //craete transaction history
                $transation_history_arr = [
                    'user_id'               => $user_id,
                    'transaction_type'      => 'plus',
                    'object_id'             => $client->id,
                    'object_type'           => 'clients',
                    'transaction_amount'    => $program_fee,
                    'transaction_detail'    => 'Started the subscription plan on <strong>' . Carbon::now('UTC')->format('D dS F Y \a\t h:i a') . '</strong>',
                    'paypal_token'          => request()->get('token'),
                    'paypal_payerId'        => $payerId,
                    'paypal_profile_id'     => $response['PROFILEID'],
                    'paypal_profile_status' => $response['PROFILESTATUS'],
                    'transaction_status'    => $response['ACK'],
                    'transaction_response'  => json_encode($response),
                    'format'=>'paypal',
                ];

                $profile_response = $provider->getRecurringPaymentsProfileDetails($response['PROFILEID']);
                if (stripos($profile_response['ACK'], 'success') !== false) {
                    $transation_history_arr['next_billing_date'] = Carbon::createFromFormat(\DateTime::ATOM, $profile_response['NEXTBILLINGDATE'])->format('Y-m-d H:i:s');

                    User::where('id', $user_id)->update(['subscription_plan_status' => $profile_response['STATUS'],'stripe_sub_id'=>$response['PROFILEID']]);

                    Client::where('user_id',$user_id)->update(['LPAP_initial_fee'=> 'paid','deleted'=>'0']);
                    $user  = User::where('id', $user_id)->first();
                    $email = '';

                    if (isset($user) && !empty($user)) {
                        $email = $user->email;
                    }
                    $referfriend = ReferFriend::with('user.client.program','user.transactionHistories')->where('friends_email', $email)->first();

                    if (isset($referfriend) && !empty($referfriend)) {

                        if (isset($referfriend->user) && !empty($referfriend->user) && $referfriend->user->user_type === 'client') {

                            Mail::send(
                                'email_template.refer_friendsignup', ['friend_email' => $referfriend['friends_email']], function ($message) use ($referfriend) {
                                    $message->to($referfriend->email)
                                        ->subject("Congratulation you got 1 month free subscription in Life Process");
                                    // $bcc = explode(',', config('srtpl.bccmail'));
                                    // if (!empty($bcc)) {
                                    //     $message->bcc($bcc);
                                    // }
                                });
                            $input =
                                [
                                'user_id'            => $referfriend->user->id,
                                'object_id'          => $referfriend->user->client->id,
                                'object_type'        => 'clients',
                                'transaction_type'   => 'plus',
                                'transaction_amount' => $referfriend->user->client->program->program_fee,
                                'transaction_detail' => 'Refer a friend',
                            ];
                            $add_subscription = CoachTransactionHistory::create($input);
                        }
                    }
                }
                //fire event..
                event(new CoachTransactionHistoryEvent($transation_history_arr));
                session()->flash('success', "You are registered successfully. Check Your Mail Activation Link Send to Your Mail.");
                view()->share('user', $user);
                view()->share('timezones', get_timezone_list());
                $client = Client::with('coach.user','program')->where('user_id', $user_id)->first();
                $program=$client->program->program_name;
                if($program=='')
                {
                    $program='';
                }
                view()->share('program',$program);
                return view('auth.register-step-third');

            }
            //craete transaction history
            $transation_history_arr = [
                'user_id'              => $user_id,
                'transaction_type'     => 'minus',
                'object_id'            => $client->id,
                'object_type'          => 'clients',
                'transaction_amount'   => $program_fee,
                'transaction_detail'   => $response['L_LONGMESSAGE0'],
                'paypal_token'         => request()->get('token'),
                'paypal_payerId'       => $payerId,
                'transaction_status'   => $response['ACK'],
                'transaction_response' => json_encode($response),
            ];
            //fire event..
            event(new CoachTransactionHistoryEvent($transation_history_arr));
            \Log::debug($response);
            session()->flash('error', $response['L_LONGMESSAGE0']);
            $timezone = $user->timezone;
                 view()->share('timezones', get_timezone_list());
             view()->share('user', $user);
             $client = Client::with('coach.user','program')->where('user_id', $user_id)->first();
                $program=$client->program->program_name;
                if($program=='')
                {
                    $program='';
                }
               view()->share('program',$program);
            return view('auth.register-step-third');
        }
        //craete transaction history
        $transation_history_arr = [
            'user_id'              => $user_id,
            'transaction_type'     => 'minus',
            'object_id'            => $client->id,
            'object_type'          => 'clients',
            'transaction_amount'   => $program_fee,
            'transaction_detail'   => $checkout_response['L_LONGMESSAGE0'],
            'paypal_token'         => request()->get('token'),
            'paypal_payerId'       => request()->get('PayerID'),
            'transaction_status'   => $checkout_response['ACK'],
            'transaction_response' => json_encode($checkout_response),
            'format'=>'stripe',
        ];
        //fire event..
        event(new CoachTransactionHistoryEvent($transation_history_arr));
//        \Log::debug($checkout_response);
        session()->flash('error', $checkout_response['L_LONGMESSAGE0']);
//        $timezone = Auth::user()->timezone;
        view()->share('timezones', get_timezone_list());
        view()->share('user', $user);
        $client = Client::with('coach.user','program')->where('user_id', $user_id)->first();
                $program=$client->program->program_name;
                if($program=='')
                {
                    $program='';
                }
        return view('auth.register-step-third');
    }
    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        //
        // Flash::success('You are registered successfully. Please contact administrator to activate account.');
        // dump($user); exit();
    }
    public function getActivate(Request $request, $code)
    {
        // Attempt the registration
        $result = Activation::where('code', $code)->where('completed', '0')->first();
        if ($result) {
            $update_user       = User::where('id', $result->user_id)->update(array("status" => "active"));
            $update_activation = Activation::where('code', $code)->update(array("completed" => '1', "completed_at" => Carbon::now()->format('Y-m-d H:i:s')));
            $get_client_detail = User::find($result->user_id);
            $email_template    = EmailTemplate::where('slug', 'complete-setup')->first()->toArray();
            if (isset($email_template)) {
                $tag         = ['[client-email]', '[client-name]'];
                $replace_tag = [$get_client_detail->email, $get_client_detail->name];
                $to          = str_replace($tag, $replace_tag, $email_template['to']);
                $subject     = str_replace($tag, $replace_tag, $email_template['subject']);
                $content     = str_replace($tag, $replace_tag, $email_template['content']);
                Mail::send(
                    'email_template.comman', ['content' => $content], function ($message) use ($to, $subject) {
                        $message->to($to)
                            ->subject($subject);
                        // $bcc = explode(',', config('srtpl.bccmail'));
                        // if (!empty($bcc)) {
                        //     $message->bcc($bcc);
                        // }
                    });
            } else {
                Mail::send(
                    'email_template.client_is_activate', ['client_name' => $get_client_detail->name], function ($message) use ($get_client_detail) {
                        $message->to($get_client_detail->email)
                            ->subject("Congratulations - You are ready to start");
                        // $bcc = explode(',', config('srtpl.bccmail'));
                        // if (!empty($bcc)) {
                        //     $message->bcc($bcc);
                        // }
                    });
            }
            session()->flash('success', "Email is verified successfully. You may now login.");
            return redirect()->route('login');
        } else {
            session()->flash('error', "Your account activation link is expired. Please contact administrator to activate account.");
            return redirect()->route('login');
        }
    }
    // Terms and condition function
    public function terms()
    {
        $page = Page::where('slug', 'terms-conditon')->first();
        view()->share('page', $page);
        return view('auth.terms');
    }
    public function registrationCompleted(Request $request)
    {
        User::where('id', $request->get('id'))->update([
            'registration_completed'=> '1',
        ]);
        Auth::loginUsingId($request->get('id'));
        $user = Auth::user()->update(['is_login' => 1,'last_login' => Carbon::now()]);
        return redirect()->route('client.dashboard');
        return ['status' => 'success'];
    }
    public function paypalSubscriptioncancel($user_id)
    {
        $user_id = Crypt::decryptString($user_id);

        $user=User::withoutGlobalScopes()->with('client','client.program')->where('id',$user_id)->orderBy('id','DESC')->get()->first();
        Client::where('user_id', $user_id)->update(['deleted' => '1']);
        $program=$user->client->program->id;
        view()->share('program', $program);
        view()->share('user',$user_id);
        session()->flash('paymenterror', 'Cancel Paypal Payment');
        view()->share('error','paypal');
        return view('auth.register');
    }

}
