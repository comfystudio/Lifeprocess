<?php

namespace App\Http\Controllers;

use App;
use App\Models\User;
use App\Models\Client;
use App\Models\Program;
use App\Models\Setting;
use App\Models\CardDetail;
use Carbon\Carbon;
use App\Models\CoachTransactionHistory;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Models\UserModuleProgress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Events\CoachTransactionHistoryEvent;
use PDF;
use Flash;
use PayPal;
use Mail;

class ClientDashboardController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->middleware('auth');
		$this->title = "Client Dashboard";
		ini_set('max_execution_time', 300); //5 minutes
	}
	/**
	 * Show the client application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		// Auth::logout(); exit();
		$user = Auth::user();
		if ($user->user_type != 'client') {
			return redirect()->route('login');
		}
		view()->share('title', $this->title);
		// $creditCard_added = CardDetail::where('user_id', $user->id)->first();
		// if(empty($creditCard_added)) {
		// 	//Flash::warning('Please add/update credit card details to complete the subscription process.');
		// 	return redirect()->route('clients.update.profile');
		// }
		$client = Client::with('program')->where('user_id',$user->id)->first();
		view()->share('client',$client);

		view()->share('timezones', get_timezone_list());
		//dump(request()->get('e_r'));

		// if(request()->get('e_r', false)) {
			//print_r('enter'); exit();
		    if($client->user->addedby=='self')
		    {
			if (!$client->coach_id || $client->user->terms_and_condition=='no') {
				view()->share('dashboard_message', 'Please contact administrator to assign a coach to you.');
				return view('client-dashboard');
			}
			}
		// }
		//$provider = express_checkout();
		if(isset($user->stripe_id) && !empty($user->stripe_id))
		{

			if($user->subscription_plan_status == '')
			{
					view()->share('dashboard_message', 'Please contact admin on info@lifeprocessprogram.com regarding payment for your account.');
					return view('client-dashboard');
			}

		}
		else
		{
        $provider = PayPal::setProvider('express_checkout');

		if ($user->subscription_plan_status == '' || is_null($user->subscription_plan_status || $user->status == 'in_active')) {
			$programName = 'LPAP client';

	        if(!empty($client->program)) {
	            $programName = $client->program->program_name;
	            $programFee = $client->program->program_fee;
	        } else {
	            $programFee = 0;
	        }

				$dataItem['items'] = [
		            [
		                'name'  => "Monthly Subscription for " . $programName,
		                'price' => $programFee,
		                'qty'   => 1,
		            ],
		        ];
		        $dataItem['subscription_desc'] = "Monthly Subscription for " . $programName;
		        $dataItem['invoice_id'] = $user->id;
		        $dataItem['invoice_description'] = "Monthly Subscription for " . $programName;
		        // $dataItem['return_url'] = route('register.subscription', ['user_id' => $user->id]);
		        $dataItem['return_url'] = route('client.subscription');
		        $dataItem['cancel_url'] = route('client.dashboard', ['u_id' => Crypt::encryptString($user->id)]);
		        $total = 0;

		        $dataItem['total'] = $programFee * 1; // itemPrice * itemQty

		        // // Use the following line when creating recurring payment profiles (subscriptions)
		        // $response = $provider->setExpressCheckout($dataItem, true);
		        // if(stripos($response['ACK'], 'success')  !== false ) {
		        //      // This will redirect user to PayPal
		        // 	return redirect($response['paypal_link']);
		        // }
		        $response = $provider->setExpressCheckout($dataItem, true);
                if (stripos($response['ACK'], 'success') !== false) {
                $agreement_response = $provider->createBillingAgreement($response['TOKEN']);
                //\Log::debug($agreement_response);
                // This will redirect user to PayPal
                //dump($agreement_response);
                return redirect($response['paypal_link']);
                }
		        else {
		            //create transaction history
		            $transation_history_arr = [
		                'user_id' => $user->id,
		                'transaction_type' => 'minus',
		                'object_id' => $client->id,
		                'object_type' => 'clients',
		                'transaction_amount' => $programFee,
		                'transaction_detail' => $response['L_LONGMESSAGE0'],
		                'transaction_status' => $response['ACK'],
		                'transaction_response' => json_encode($response)
		            ];
		            //fire event..
		            // dump($response);exit;
		            event(new CoachTransactionHistoryEvent($transation_history_arr));
		            session()->flash('error', $response['L_LONGMESSAGE0']);

		            return redirect()->route('client.dashboard');
		        }
			// \Log::debug($token); exit();
		}else{
			$status = ['Pending','Cancelled','Suspended'];
			if(in_array($user->subscription_plan_status,$status)){
				view()->share('dashboard_message', 'Please contact administrator, your subscription is '.$user->subscription_plan_status);
				return view('client-dashboard');
			} else if($user->subscription_plan_status == "Expired"){
				Flash::warning('Please update your account detail, your subscription is expired');
				return redirect()->route('clients.update.profile');
			}
		}}
		// $history = CoachTransactionHistory::where('user_id', $user->id)->where('paypal_profile_status', 'ActiveProfile')->first();
		// if(!empty($history)) {
		// 	$response = $provider->getRecurringPaymentsProfileDetails($history->paypal_profile_id);
		// 	\Log::debug($response);
		// }

		if (!$client->coach_id ) {
			view()->share('dashboard_message', 'Please contact administrator to assign a coach to you.');
			return view('client-dashboard');
		}
		if(Auth::user()->gratuate_token!='' && Auth::user()->gratuate_option=='um'){
			return redirect()->route('client.program_modules.index', ['program_id' => Crypt::encryptString($client->program_id)]);
		}
		if ($client->program_id) {
			return redirect()->route('client.program_modules.index', ['program_id' => Crypt::encryptString($client->program_id)]);
		} else {
			 view()->share('dashboard_message', 'Please contact administrator to assign a coach to you.');
			 return view('client-dashboard');
		}

	}

	//function to get success response from paypal for the subscription paln request...
    public function paypalSubscriptionResponse()
    {
       // dump($user_id);
        // dump(request()->all());
        $user_id = Auth::id();
        // $provider = express_checkout();
        $provider = PayPal::setProvider('express_checkout');
        // $agreement_response = $provider->createBillingAgreement(request()->get('token'));
        // if (stripos($agreement_response['ACK'], 'success')  !== false) {
        // 	User::where('id', $user_id)->update(['paypal_billingAgreementID' => $agreement_response['BILLINGAGREEMENTID']]);
        // }
        // \Log::debug($agreement_response);
        $checkout_response = $provider->getExpressCheckoutDetails(request()->get('token'));
        //dump($checkout_response);
        $user = User::where('id', $user_id)->with('client.program', 'credit_card_detail')->first();
        $program_fee = $user->client->program->program_fee;
        $client = $user->client;

        if ( stripos($checkout_response['ACK'], 'success')  !== false ) {
            // dump($user);
            $startdate = Carbon::now('UTC');
            $profile_startDate = Carbon::parse($startdate->format('Y-m-d'));
            $profile_desc = "Monthly Subscription for " . $user->client->program->program_name;
            $data = [
                'PROFILESTARTDATE' => $profile_startDate->toAtomString(),
                'DESC' => $profile_desc,
                'BILLINGPERIOD' => 'Day',
                'BILLINGFREQUENCY' => 1,
                //'BILLINGPERIOD' => 'Month', // Can be 'Day', 'Week', 'SemiMonth', 'Month', 'Year'
                //'BILLINGFREQUENCY' => 12, //
                'AMT' => $checkout_response['AMT'], // Billing amount for each billing cycle
                'CURRENCYCODE' => $checkout_response['CURRENCYCODE'], // Currency code
                // 'TRIALBILLINGPERIOD' => 'Month',  // (Optional) Can be 'Day', 'Week', 'SemiMonth', 'Month', 'Year'
                // 'TRIALBILLINGFREQUENCY' => 1, // (Optional) set 12 for monthly, 52 for yearly
                // 'TRIALTOTALBILLINGCYCLES' => 1, // (Optional) Change it accordingly
                // 'TRIALAMT' => $checkout_response['AMT'], // (Optional) Change it accordingly
                // 'ACCT' => $user->credit_card_detail->card_number,
                // 'EXPDATE' => $user->credit_card_detail->expiry_date,
                // 'CVV2' => $user->credit_card_detail->CVV_number
            ];
           // $response = $provider->createRecurringPaymentsProfile($data, '');
            // dump($response); exit();
            $token=request()->get('token');
            //dd($token);
            $response = $provider->createRecurringPaymentsProfile($data,$token);
            //dd($response);
            if(request()->get('PayerID', false)) {
                $payerId = request()->get('PayerID');
            } else {
                $payerId =  $checkout_response['PAYERID'];
            }
            if(stripos($response['ACK'], 'success')  !== false ) {
            	User::where('id', $user_id)->update(['paypal_start_date' => $startdate,'subscription_plan_status'=>'Active']);
                //craete transaction history
                $transation_history_arr = [
                        'user_id' => $user_id,
                        'transaction_type' => 'plus',
                        'object_id' => $client->id,
                        'object_type' => 'clients',
                        'transaction_amount' => $program_fee,
                        'transaction_detail' => 'Started the subscription plan on <strong>' . Carbon::now('UTC')->format('D dS F Y \a\t h:i a') . '</strong>',
                        'paypal_token' => request()->get('token'),
                        'paypal_payerId' => $payerId,
                        'paypal_profile_id' => $response['PROFILEID'],
                        'paypal_profile_status' => $response['PROFILESTATUS'],
                        // 'next_billing_date' => Carbon::createFromFormat(\DateTime::ATOM, $response['NEXTBILLINGDATE'])->format('Y-m-d H:i:s'),
                        'transaction_status' => $response['ACK'],
                        'transaction_response' => json_encode($response)
                    ];
                $profile_response = $provider->getRecurringPaymentsProfileDetails($response['PROFILEID']);
                \Log::debug('profile_response = ');
                \Log::debug($profile_response);

           		if(stripos($profile_response['ACK'], 'success')  !== false) {
           			$transation_history_arr['next_billing_date'] = Carbon::createFromFormat(\DateTime::ATOM, $profile_response['NEXTBILLINGDATE'])->format('Y-m-d H:i:s');
           		}
                //fire event..
                event(new CoachTransactionHistoryEvent($transation_history_arr));
                session()->flash('success', "Your subscription has been completed successfully.");
                return redirect()->route('client.dashboard');
            }
            //craete transaction history
           return redirect()->route('client.dashboard', ['e_r' => Crypt::encryptString('true')]); // found error...
        }
        else
        {
        //craete transaction history
        $transation_history_arr = [
                'user_id' => $user_id,
                'transaction_type' => 'minus',
                'object_id' => $client->id,
                'object_type' => 'clients',
                'transaction_amount' => $program_fee,
                'transaction_detail' => $checkout_response['L_LONGMESSAGE0'],
                'paypal_token' => request()->get('token'),
                'paypal_payerId' => request()->get('PayerID'),
                'transaction_status' => $checkout_response['ACK'],
                'transaction_response' => json_encode($checkout_response)
            ];
            //fire event..
            event(new CoachTransactionHistoryEvent($transation_history_arr));
        \Log::debug($checkout_response);
        session()->flash('error', $checkout_response['L_LONGMESSAGE0']);
        return redirect()->route('client.dashboard', ['e_r' => Crypt::encryptString('true')]); // found error...
    }
    }
    public function getGratuateQuestion(Request $request)
	{
            $user = Auth::user();
			$id=$request->get('id');
			$result = $request->get('result');
			$token = $request->get('_token');
			$program_id = $request->get('program_id');
			$UserModuleProgress=User::findOrFail($id);
			$input = array('is_gratuate_question_asked' => 'y');
			$UserModuleProgress->update($input);
			$admin_email = Setting::where('name','admin_email')->first()->toArray();
			//dd($admin_email['value']);return false;
			$program_name=Program::find($program_id)->program_name;
			$email_template = EmailTemplate::where('slug','gratuate-user-ask-question')->first()->toArray();
					if(isset($email_template))
					{
						$tag = ['[client-email]','[admin-email]','[program-name]','[question]','[client-name]'];
                    	$replace_tag = [$user->email,$admin_email['value'],$program_name,$result,$user->name];
	                    $to = str_replace($tag,$replace_tag,$email_template['to']);
	                    $subject = $email_template['subject'];
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
			//dd(UserGratuate);
			exit;
			return false;
			if ($request->ajax()) {
				return response()->json([
					'status' => 'success',
				]);
			}
	}
	public function getPopupDisable(Request $request)
	{
            $user = Auth::user();
			$module_id=$request->get('id');
			$UserModuleProgress=UserModuleProgress::findOrFail($module_id);
			$input = array('is_submited_popup' => '1');
			$UserModuleProgress->update($input);
			$UserGratuate=User::findOrFail(Auth::user()->id);
			$input = array('is_gratuate' => 'y','gratuate_date' => Carbon::now('UTC'));
			$UserGratuate->update($input);
			//dd(UserGratuate);
			//exit;
			//return false;
			if ($request->ajax()) {
				return response()->json([
					'status' => 'success',
				]);
			}
	}
	public function getGratuatePopupDisable(Request $request)
	{
            $user = Auth::user();
			$id=$request->get('id');
			$result = $request->get('result');
			$token = $request->get('_token');
			$program_id = $request->get('program_id');
			$UserModuleProgress=User::findOrFail($id);
			$input = array('is_gratuate' => 'y','gratuate_token'=>$token,'gratuate_option' => $result,'gratuate_date' => Carbon::now('UTC'));
			$UserModuleProgress->update($input);
			//print_r($_REQUEST);exit;

			if ($request->ajax()) {
				if($result=='um'){
					return response()->json([
						'status' => 'success',
						'message' => 'Unlock Modules',
						'redirect_url' => route('client.program_modules.index', ['program_id' => Crypt::encryptString($program_id)]),
					]);
				}
				if($result=='gs'){
					return response()->json([
						'status' => 'success',
						'message' => 'Book mini coaching session',
						'redirect_url' => route('clients.dashboard.coaching'),
					]);
				}
				if($result=='aq'){
					return response()->json([
						'status' => 'success',
						'message' => 'Ask Question',
						'redirect_url' => route('client.program_modules.index', ['program_id' => Crypt::encryptString($program_id)]),
					]);
				}

			}
	}
	public function getPDF() {
		$client = Client::where('user_id', Auth::id())->first();
		$client_name = Auth::user()->name;
		$program_name = Program::where('id',$client->program_id)->first()->program_name;
		$client_coach = App::make('App\Http\Controllers\ClientController');
        $coach_name = $client_coach->getCoach();
        $review_date = UserModuleProgress::where('user_id',Auth::id())->orderBy('module_id','desc')->first()->reviewed_at;
        if(isset($review_date))
        {
        	$review_date=Carbon::createFromFormat('Y-m-d H:i:s',$review_date)->format('m/d/Y');
        }
		$pdf = PDF::loadView('clients.dashboard.pdf', ['theme' => 'limitless.pdf', 'title' => $this->title,'client_name' => $client_name,'program_name' => $program_name,'coach_name' => $coach_name,'review_date' => $review_date]);
		$pdf->setPaper('a4');
		$pdf->setOrientation('landscape');
		$pdf->setOption('margin-top', 5);
		$pdf->setOption('margin-right', 5);
		$pdf->setOption('margin-bottom', 5);
		$pdf->setOption('margin-left', 5);
		$pdf->setOption('header-right', '');
		return $pdf->stream();
	}
	public function assign_coach(Request $request)
    {
    		//print_r($request); exit;
    	    $object = App::make("App\Http\Controllers\CoachController");
            //$request= request();

            $coach_gender=$request->get('gender');
            $models=$object->ajaxCoaches_timezone($request, array('coach_gender' => (isset($coach_gender)) ? $coach_gender : '', 'program_id' => (isset($data['program_id'])) ? $data['program_id'] : '', 'available' => 'yes'));
            $coach_id="";
            $coach_gender="";
            foreach($models as $value)
            {
                $coach_id=$value['id'];
                $coach_gender=$value['user']['gender'];
            }
            if($coach_id=="" || $coach_gender=="")
            {
                $coach_id="";
                $coach_gender="";
            }
            if(!empty(Auth::id()))
            {
            	 Client::where('user_id', Auth::id())->update(['coach_id'=> $coach_id]);
            }
            else
            {
            	Client::where('user_id', $request->get('userid'))->update(['coach_id'=> $coach_id]);
            }

			return response()->json([
						'success' => 'true',
						'coach_id'=>$coach_id,
						'url' => route('clients.dashboard.coaching'),
			]);
		//return redirect()->route('client-dashboard');
    }
    public function settimezone(Request $request)
    {

    	User::where('id',$request->get('userid'))->update(['timezone'=> $request->get('timezone')]);
			return response()->json([
						'success' => 'true',
			]);
		//return redirect()->route('client-dashboard');
    }
    public function setterms(Request $request)
    {
    	User::where('id', Auth::id())->update(['terms_and_condition'=> $request->get('terms')]);
			return response()->json([
						'success' => 'true',
			]);
		//return redirect()->route('client-dashboard');
    }
}