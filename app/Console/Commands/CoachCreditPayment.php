<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Coach;
use App\Models\Setting;
use PayPal;
use PaypalMassPayment;
use Carbon\Carbon;
use App\Models\CoachTransactionHistory;

class CoachCreditPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:coaching-credit-payment-using-paypal-to-coach';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'coach getting paid into their paypal account when there credit goes to up then Coach Credit Threshold';

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
        $user = User::with('coach')->where('user_type', 'coach')->get();

        foreach($user as $user)
        {
            $total_credit=$user->coach->balance;
            $min_credit_balance=Setting::where('name','coach_credit_threshold')->first();
            if($total_credit>=$min_credit_balance->value)
            {
                $id=$user->id;
                $receivers = array(
                  0 => array(
                    'ReceiverEmail' => $user->coach->paypal_id,
                     'Amount'        => $total_credit,

                    'UniqueId'      => "id_001",
                    'Note'          => "Send Coach"),
                );
                $response   = PaypalMassPayment::executeMassPay('Coaching Payment', $receivers);
                $input['user_id'] = $id;
                $input['deleted'] = 0;
                $input['transaction_amount']=$total_credit;
                $input['transaction_type'] = 'minus';
                $input['transaction_detail']='Coach credit Threshold';
                $input['last_payment_date']=Carbon::now();
                $model = CoachTransactionHistory::create($input);
                Coach::where('user_id', $id)->update(['balance' => '0']);
            }
        }
    }
}
