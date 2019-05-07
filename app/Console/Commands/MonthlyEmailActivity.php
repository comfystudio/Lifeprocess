<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\UserCreditsHistory;
use App\Models\CoachTransactionHistory;
use App\Models\User;
use Carbon\Carbon as Carbon;
use Mail;

class MonthlyEmailActivity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:monthly-email-activity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email alert for activity of previous month';

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
        $subscription = CoachTransactionHistory::select(\DB::raw("SUM(transaction_amount) as subscription"))
            ->where('object_type','clients')
            ->where('created_at', '>=', Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'))
           ->where('created_at', '<', Carbon::now()->startOfMonth())
            ->where('deleted',\DB::raw("'0'"))
            ->get()->toArray();
        //dd($subscription);
        $session_credit_spend = UserCreditsHistory::select(\DB::raw("SUM(credit_score) as session_credit_spend"))
            ->where('object_type','coach_schedules_booked')
            ->whereIn('transaction_type',['minus'])
            ->where('created_at', '>=', Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'))
            ->where('created_at', '<', Carbon::now()->startOfMonth())
            ->where('deleted',\DB::raw("'0'"))
            ->get()->toArray();

        $payment_module_review = CoachTransactionHistory::select(\DB::raw("SUM(transaction_amount) as payment_module_review"))
            ->where('object_type','user_module_progresses')
            ->where('created_at', '>=', Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'))
           ->where('created_at', '<', Carbon::now()->startOfMonth())
            ->where('deleted',\DB::raw("'0'"))
            ->get()->toArray();

        $session_payments = CoachTransactionHistory::select(\DB::raw("SUM(transaction_amount) as session_payments"))
            ->where('object_type','coach_schedules_booked')
            ->where('created_at', '>=', Carbon::now()->subMonth()->startOfMonth()->format('Y-m-d'))
           ->where('created_at', '<', Carbon::now()->startOfMonth())
            ->where('deleted',\DB::raw("'0'"))
            ->get()->toArray();

        $user = User::select(['name','email'])
            ->where('role_id',\DB::raw("'2'"))
            ->where('deleted',\DB::raw("'0'"))
            ->get()->toArray();

        $data = array();

        $data['subscription'] = isset($subscription[0]['subscription'])?$subscription[0]['subscription']:'0';

        $data['session_credit_spend'] = isset($session_credit_spend[0]['session_credit_spend'])?$session_credit_spend[0]['session_credit_spend']:'0';

        $data['payment_module_review'] = isset($payment_module_review[0]['payment_module_review'])?$payment_module_review[0]['payment_module_review']:'0';

        $data['session_payments'] = isset($session_payments[0]['session_payments'])?$session_payments[0]['session_payments']:'0';

        foreach ($user as $key => $value) {
            $data['user'] = $value['name'];
            $data['email'] = $value['email'];

            $response = Mail::send('email_template.emailForPreviousMonthActivity', $data, function ($mail) use ($data){
                $mail->to($data['email'])
                    ->subject('Alert For Previous Month Activity');
            });
            \Log::info($response);
        }
        $this->info('success');

    }
}
