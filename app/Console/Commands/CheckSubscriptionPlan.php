<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\CardDetail;
use App\Models\CoachTransactionHistory;
use App\Events\CoachTransactionHistoryEvent;
use Carbon\Carbon;
use PayPal;

class CheckSubscriptionPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscribe:check-subscription-plan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check subscription of user for recurring profile';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // $provider = express_checkout();
        $provider = PayPal::setProvider('express_checkout');
        $subscribedUsers = User::with(['transactionHistories' => function($query) {
            // $query->where('paypal_profile_status', 'ActiveProfile');
            $query->where('object_type', 'clients')->where('transaction_status', 'like', '%success%')->whereNotNull('paypal_profile_id')->orderBy('id', 'DESC');
        }])->where('status', 'active')->where('user_type', 'client')->whereNotNull('paypal_start_date')->get();


        if (!empty($subscribedUsers))
        {
            foreach ($subscribedUsers as $key => $value)
            {

                if(count($value->transactionHistories) > 0)
                {
                    // \Log::debug($value->transactionHistories);
                    foreach ($value->transactionHistories->take(1) as $transaction)
                    {
                        $paypal_profile_id = $transaction->paypal_profile_id;
                        //echo $paypal_profile_id.' ';

                            $curr_date = Carbon::now()->format('Y-m-d');
                            if (Carbon::createFromFormat('Y-m-d H:i:s', $transaction->next_billing_date)->format('Y-m-d') <= $curr_date)
                            {
                                $profile_response = $provider->getRecurringPaymentsProfileDetails($paypal_profile_id);

                                //echo $profile_response['ACK'];
                                if(isset($profile_response['ACK']) && stripos($profile_response['ACK'], 'success')  !== false )
                                {
                                    //echo $value->id.$profile_response['STATUS']."/n";
                                    if($profile_response['STATUS']=='Active')
                                    {
                                        //echo $value->id." ";
                                        $next_billing_date = Carbon::createFromFormat( \DateTime::ATOM , $profile_response['NEXTBILLINGDATE'])->format('Y-m-d');

                                        if($curr_date < $next_billing_date && isset($profile_response['LASTPAYMENTDATE']))
                                        {
                                            $transaction_arr = [
                                            'user_id' => $value->id,
                                            'object_id' => $transaction->object_id,
                                            'object_type' => 'clients',
                                            'paypal_payerId' => $transaction->paypal_payerId,
                                            'paypal_profile_id' => $profile_response['PROFILEID'],
                                            'transaction_status' => $profile_response['ACK'],
                                            'next_billing_date' => Carbon::createFromFormat( \DateTime::ATOM , $profile_response['NEXTBILLINGDATE'])->format('Y-m-d H:i:s'),
                                            'last_payment_date' => Carbon::createFromFormat( \DateTime::ATOM , $profile_response['LASTPAYMENTDATE'])->format('Y-m-d H:i:s'),
                                            'transaction_detail' => 'Payment is made on ' . Carbon::createFromFormat( \DateTime::ATOM , $profile_response['LASTPAYMENTDATE'])->format('m/d/|Y') . ' for the next month.',
                                            'transaction_response' => json_encode($profile_response),
                                            'transaction_type' => 'plus',
                                            'transaction_amount' => $profile_response['REGULARAMTPAID'],
                                            'format'=>'paypal'
                                            ];
                                            //fire event..
                                            User::where('id',$value->id)->update(array('subscription_plan_status'=>'Active'));
                                            event(new CoachTransactionHistoryEvent($transaction_arr));

                                        }

                                    }
                                    else
                                    {
                                        User::where('id',$value->id)->update(array('subscription_plan_status'=>''));
                                    }
                                }
                            }
                        }
                    }
                }
            }

        $subscribedstripeUsers = User::with(['transactionHistories' => function($query) {
            $query->where('object_type', 'clients')->where('transaction_status', 'like', '%success%')->orderBy('id', 'DESC');
        }])->where('status', 'active')->where('user_type', 'client')->whereNotNull('stripe_sub_id')->whereNotNull('stripe_id')->where('deleted','0')->where('id','!=','319')->where('id','!=','221')->get();

        if (!empty($subscribedstripeUsers))
        {
            foreach ($subscribedstripeUsers as $value)
            {

                try {
                        $stripe_sub_id=$value->stripe_sub_id;
                        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

                        try {
                           $retrive= \Stripe\Subscription::retrieve($stripe_sub_id);

                        } catch (\Stripe\Error\InvalidRequest $e) {
                           $retrive='';
                              // Invalid parameters were supplied to Stripe's API
                        }
                        if(isset($retrive) && !empty($retrive))
                        {
                            $curr_date = Carbon::now()->format('Y-m-d');
                            $timestamp = $retrive->current_period_end;
                            $next_payment_date = date('Y-m-d',$timestamp);
                            $timestamp = $retrive->current_period_start;
                            $last_payment_date = date('Y-m-d',$timestamp);
                            // $next_payment_data_date=
                            $coach_schedule=CoachTransactionHistory::where('user_id',$value->id)->where('next_billing_date',$next_payment_date)->get()->count();
                            if($coach_schedule<1)
                            {
                                if($curr_date < $next_payment_date && $retrive->status=='active')
                                {
                                        $transaction_arr = [
                                                        'user_id' => $value->id,
                                                        'object_id' => '',
                                                        'object_type' => 'clients',
                                                        'paypal_payerId' => '',
                                                        'paypal_profile_id' => '',
                                                        'transaction_status' => $retrive->status,
                                                        'next_billing_date' => $next_payment_date,
                                                        'last_payment_date' => $last_payment_date,
                                                        'transaction_detail' => '',
                                                        'transaction_response' => $retrive->status,
                                                        'transaction_type' => 'plus',
                                                        'transaction_amount' => $retrive->plan->amount/100,
                                                        'format'=>'stripe'
                                                        ];
                                                        //fire event..
                                                        User::where('id',$value->id)->update(array('subscription_plan_status'=>'Active'));
                                                        event(new CoachTransactionHistoryEvent($transaction_arr));
                                }
                            }
                        }
                        else
                        {
                            User::where('id',$value->id)->update(array('subscription_plan_status'=>''));
                        }
                            // Use Stripe's library to make requests...
                        } catch (Exception $e) {

                            // Something else happened, completely unrelated to Stripe
                    }


            }
        }
    }
}
