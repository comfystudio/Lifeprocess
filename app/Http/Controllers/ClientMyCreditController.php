<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Client;
use App\Models\UserCreditsHistory;
use App\Models\EmailTemplate;
use Flash;
use App;
use PayPal;
use Mail;
use Session;
use \Stripe\Plan;
use \Stripe\Token;
use \Stripe\Coupon;
use Carbon\Carbon;
use App\Models\CreditPackage;

class ClientMyCreditController extends Controller
{
    protected $provider;
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth');
        $this->middleware('check_for_permission.access:myCredits.view', ['only' => ['index', 'purchaseCredits', 'purchaseCredits_confirm']]);
        $this->title = "My credits";
        $this->module_title = 'Upgrade to one-to-one coaching sessions';
        $this->client_module_title = 'Purchase Credits';
        view()->share('title', $this->title);
        view()->share('module_title', $this->module_title);
        view()->share('client_module_title', $this->client_module_title);
    }

    /**
     * Show the client application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->user_type != 'client' && Auth::user()->user_type != 'user') {
            return redirect()->route('login');
        }
        // forgot some of the session data...
        session()->forget('credits');
        session()->forget('credits_amount');
        session()->forget('token');
        session()->forget('PayerID');
        $client_id = request()->get('cid', false);
        if($client_id) {
            $client_id = Crypt::decryptString($client_id);
        } else {
            $client_id = Auth::id();
        }
        $client = Client::where('user_id', $client_id)->first();
        $credits = 0;
        if (!empty($client)) {
            $credits = $client->credits;
        }
        view()->share('client_credits', $credits);
        // dump($this->getMyCreditsHistory()->items()); exit();
        $creditHistory = $this->getMyCreditsHistory($client_id, $credits);
        view()->share('client_credits_history', $creditHistory['client_credits_history']);
        view()->share('current_credits', $creditHistory['current_credits']);

        $credits = App::make('App\Http\Controllers\CreditPakageController');
        $filters = [
            'status' => [
                'operator' => '=',
                'value' => 'public'
            ]
        ];
        $sort_order = [
            'credit' => 'ASC'
        ];
        $credits_package = $credits->get_index($filters, $sort_order);
        $credits_dropdown = [];
        foreach ($credits_package as $key => $value) {
            $credits_dropdown[$value->credit] = $value->credit;
        }
        view()->share('credits_package', $credits->get_index($filters, $sort_order));
        view()->share('credits_dropdown', $credits_dropdown);
        view()->share('title', $this->title);
        view()->share('module_title', $this->module_title);
        return view('clients.dashboard.my_credit');
    }
    //
    public function purchaseCredits(Request $request)
    {

        $user=Auth::user();
        if($request->get('credit_payment')=='stripe')
        {
                $credits_amount = $request->get('credit_price');
                $credits = $request->get('credit_score');
                $credit=CreditPackage::where('id',$credits)->first();
                $credits_amount=$credit->price*$credit->credit;
                $credits=$credit->credit;
                $StripeController = App::make(StripeController::class);
                return view('clients.stripe_payment',compact('credits','credits_amount'));
        }
        else
        {
            $provider = PayPal::setProvider('express_checkout');
            $creditsid = $request->get('credit_score');
            $credits_amount = $request->get('credit_price');
            // dd($credit);
            $credit=CreditPackage::where('id',$creditsid)->first();

            $credits_amount=$credit->price*$credit->credit;
            $credit=$credit->credit;

            if(!session()->has('credits')) {
                $request->session()->push('credits', $credit);
            }
            if(!session()->has('credits_amount')) {
                $request->session()->push('credits_amount', $credits_amount);
            }
            $data = [];
            $data['items'] = [
                [
                    'name' => 'Life Process Program Coaching Credits',
                    'price' => head(session()->get('credits_amount')),
                    'qty' => 1
                ]
            ];
            $data['invoice_id'] = Auth::id() . rand(0, 99);
            $data['invoice_description'] = "Order #" . $data['invoice_id'] . " Invoice";
            $data['return_url'] = route('client.myCredits.confirmation');
            $data['cancel_url'] = route('client.myCredits');
            $data['total'] = head(session()->get('credits_amount'));
            $response = $provider->setExpressCheckout($data);
        }
        return redirect($response['paypal_link']);
    }

    public function purchaseCredits_confirm(Request $request)
    {
         //dump(\Session::all());
        // session()->flush();
        // ["token" => "EC-1YN74208GH731434D", "PayerID" => "MY335A8HEH98N"]
        // $provider = express_checkout();
        $provider = PayPal::setProvider('express_checkout');
        // post request method
         //dump($request->all());
         //exit; return false;
        if ($request->get('confirm', false)) {

        } else {

            $credits = $request->get('credit_score');
            $credits_amount = $request->get('credit_price');

            $token = $request->get('token');

            $PayerID = $request->get('PayerID');
            $payment_type = $request->get('credit_payment');
            $response = $provider->getExpressCheckoutDetails($token);

            $data['items'] = [
                [
                    'name' => $response['L_NAME0'],
                    'price' => $response['L_AMT0'],
                    'qty' => $response['L_QTY0']
                ]
            ];
            // $data['TOKEN'] = $request->get('_token');
            $data['invoice_id'] = $response['INVNUM'];
            $data['invoice_description'] = $response['DESC'];
            $data['total'] = $response['AMT'];

            $response = $provider->doExpressCheckoutPayment($data, $token, $PayerID);

            if($response['ACK'] == 'Success' && $response['PAYMENTINFO_0_PAYMENTSTATUS'] == 'Completed') {
                $client = Client::where('user_id', Auth::id())->first();
                UserCreditsHistory::create(['user_id' => Auth::id(), 'transaction_type' => 'plus', 'credit_score' => head(session()->get('credits')), 'object_id' => $client->id, 'object_type' => 'clients','payment_type' => 'paypal']);
                $client_credits = $client->credits + head(session()->get('credits'));
                $client->update(['credits' => $client_credits]);
                Flash::success('Credits purchased successfully!');
                $email_template = EmailTemplate::where('slug','user-buys-credits')->first()->toArray();
                $today         = Carbon::now();
                $type='Paypal';
                if(isset($email_template))
                {
                        $tag = ['[client-email]','[client-name]','[buy-credit]','[total-credit]','[date]','[type]'];
                        $replace_tag = [Auth::user()->email,Auth::user()->name,head(session()->get('credits')),$client_credits,$today,$type];
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
                    Mail::send(
                        'email_template.user_buy_credit_alert', ['buy_credit' => head(session()->get('credits')),'total_credit' => $client_credits],function ($message){
                            $message->to(Auth::user()->email)
                                ->subject("Purchase Confirmation - Coaching Credits");
                            $bcc = explode(',', config('srtpl.bccmail'));
                            if (!empty($bcc)) {
                                $message->bcc($bcc);
                            }
                        });
                }
            } else {
                Flash::error('Error found while processing payment, Please try again!');
            }
            return redirect()->route('clients.dashboard.coaching');
        }
            return view('clients.dashboard.my_credit_confirmation');
    }

    public function purchaseCredits_success(Request $request)
    {
    }
    public function getMyCreditsHistory($client_id, $current_credits)
    {
        $rows_per_page = 10;
        $client_credits_history = UserCreditsHistory::with(['user_module_progresses.modules', 'coach_schedules_booked.coach_schedule'])->where('user_id', $client_id)->orderBy('id', 'DESC')->paginate($rows_per_page);

        //calculate last row credits balance based on pagination...
        $prev_page = request()->get('page', '1') - 1 ;
        $client_credits = UserCreditsHistory::with(['user_module_progresses.modules', 'coach_schedules_booked.coach_schedule'])->where('user_id', $client_id)->orderBy('id', 'DESC')->select(['transaction_type', 'credit_score'])->limit($prev_page * $rows_per_page)->get();

        $credit_balance = $current_credits;
        if(!empty($client_credits)) {
            foreach ($client_credits as $key => $row) {
                if($row->transaction_type == 'plus') {
                    $credit_balance -= $row->credit_score;
                } else {
                    $credit_balance += $row->credit_score;
                }
            }
        }
        return ['current_credits' => $credit_balance, 'client_credits_history' => $client_credits_history];
    }
    public function buy_credit_bystripe(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        try
        {
            \Stripe\Charge::create ( array (
                    "amount" => $request->get('amount')*100,
                    "currency" => "usd",
                    "source" => $request->get('stripeToken'), // obtained with Stripe.js
                    "description" => $request->get('name')." Purchase Coaching Credits"
            ));
            $user=Auth::user();

            $client = Client::where('user_id', Auth::id())->first();

            UserCreditsHistory::create(['user_id' => Auth::id(), 'transaction_type' => 'plus', 'credit_score' =>   $request->get( 'score' ), 'object_id' => $client->id, 'object_type' => 'clients']);
            $client_credits = $client->credits +  $request->get( 'score' );
                //print_r($client_credits); exit();
            $client->update(['credits' => $client_credits]);

                // $email_template = EmailTemplate::where('slug','user-buys-credits')->first()->toArray();
                // $today         = Carbon::now();
                // $type='Stripe';
                // if(isset($email_template))
                // {
                //         $tag = ['[client-email]','[client-name]','[buy-credit]','[total-credit]','[date]','[type]'];
                //         $replace_tag = [Auth::user()->email,Auth::user()->name,$client->credits,'',$today,$type];
                //         //dd($replace_tag);
                //         $to = str_replace($tag,$replace_tag,$email_template['to']);
                //         $subject = str_replace($tag,$replace_tag,$email_template['subject']);
                //         $content = str_replace($tag,$replace_tag,$email_template['content']);
                //         Mail::send(
                //             'email_template.comman', ['content' => $content],function ($message) use($to,$subject){
                //              $message->to($to)
                //             ->subject($subject);
                //             $bcc = explode(',', config('srtpl.bccmail'));
                //             if (!empty($bcc)) {
                //             $message->bcc($bcc);
                //             }
                //         });
                // }

            Session::flash ( 'success-message', 'Payment done successfully !' );
            Flash::success('Credits purchased successfully!');

            return redirect()->route('clients.dashboard.coaching');
        }
        catch ( \Exception $e )
        {
            Session::flash ( 'fail-message', "Error! Please Try again." );
            return \Redirect::back ();
        }
    }
}
