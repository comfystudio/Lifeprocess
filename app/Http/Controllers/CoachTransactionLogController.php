<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use App\Models\CoachTransactionHistory;
use App\Models\Client;
use Carbon\Carbon;
use AppHelper;
use DB;
use Auth;
use PayPal;

class CoachTransactionLogController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        // $this->middleware('auth');
        $this->title = "Coach Transaction Logs";
        $this->module_title = "Coach Transaction Log";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        view()->share('title', $this->title);
        view()->share('module_title', $this->module_title);
        view()->share('transactionLogs', $this->get_index());
        return view('transactionHistory.index');
    }

    public function get_index($fillter = array(), $sortOrder = array())
    {

        $logs = CoachTransactionHistory::with('user')->where('user_id', Auth::id())
            ->select(['id',
                    'transaction_type',
                    'transaction_amount',
                    'created_at',
                    'transaction_detail',
                    DB::raw('CONCAT(IF(coach_transaction_histories.transaction_type = "plus","+","-"),transaction_amount) As transaction')])
            ->orderBy('id','desc');
        $inputs = AppHelper::getTrimmedData(request()->all());
        if(isset($inputs['from_date']) && $inputs['from_date']) {
            $from_date = Carbon::createfromFormat('m/d/Y', $inputs['from_date'])->format('Y-m-d');
            $logs->where(\DB::raw('DATE(created_at)'), '>=', $from_date);
        }
        if(isset($inputs['to_date']) && $inputs['to_date']) {
            $to_date = Carbon::createfromFormat('m/d/Y', $inputs['to_date'])->format('Y-m-d');
            $logs->where(\DB::raw('DATE(created_at)'), '<=', $to_date);
        }

        return $logs->get();
    }

    public function get_transactionReceipt($token)
    {
        // $provider = express_checkout();
        $provider = PayPal::setProvider('express_checkout');
        $checkout_response = $provider->getExpressCheckoutDetails($token);
        // dump($checkout_response);
        $client = Client::where('user_id', request()->get('u_id'))->with(['program', 'user'])->first();
        $data = [
            'paypal_token' => $token,
            'transaction_response' => json_encode($checkout_response),
            'object_type' => 'clients',
            'object_id' => isset($client) ? $client->id : 0 ,
            'transaction_status' => $checkout_response['ACK'],
            'transaction_type' => 'minus',
            'transaction_amount' => isset($client) ? $client->program->program_fee : 0 ,
            'user_id' => request()->get('u_id')
        ];
        if ($checkout_response['ACK'] == 'Success') {
            $data['paypal_profile_status'] = $checkout_response['CHECKOUTSTATUS'];
            $data['transaction_amount'] = $checkout_response['AMT'];
            $data['transaction_status'] = 'Cancelled';
            $data['transaction_detail'] = 'Cancelled the subscription checkout process';
        } else {
            $data['transaction_detail'] = $checkout_response['L_LONGMESSAGE0'];
        }
        // The firstOrCreate method will attempt to locate a database record using the given column / value pairs. If the model can not be found in the database, a record will be inserted with the given attributes.
        $historyRow = CoachTransactionHistory::firstOrCreate($data);
        // view()->share('title', 'Transaction Receipt');
        // view()->share('theme', 'limitless.pdf');
        // return view('transactionHistory.receipt');

        $pdf = \PDF::loadView('transactionHistory.receipt', ['theme' => 'limitless.pdf', 'title' => 'Transaction Receipt', 'client' => $client, 'historyRow' => $historyRow]);
        $pdf->setPaper('a4');
        $pdf->setOrientation('portrait');
        // $pdf->setOption('margin-top', 20);
        // $pdf->setOption('margin-right', 15);
        // $pdf->setOption('margin-bottom', 15);
        // $pdf->setOption('margin-left', 15);
        $pdf->setOption('header-right', '');
        return $pdf->stream('Transaction-History-' . $historyRow->id . '.pdf');
    }
    public function get_total_balance()
    {
        $total = 0;
        $plus_tran = CoachTransactionHistory::where('user_id', Auth::id())->where('transaction_type','plus')->select(DB::raw('sum(coach_transaction_histories.transaction_amount) as amount'))->first();
        $minus_tran = CoachTransactionHistory::where('user_id', Auth::id())->where('transaction_type','minus')->select(DB::raw('sum(coach_transaction_histories.transaction_amount) as amount'))->first();
        $total = ($plus_tran->amount) - ($minus_tran->amount);
        return $total;
    }
}