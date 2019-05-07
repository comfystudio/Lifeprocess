<?php

namespace App\Http\Controllers;

use App\Models\CoachTransactionHistory;
use App\Models\Client;
use App\Models\User;
use App\Models\UserCreditsHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use PDF;
use App;
use DB;
use Excel;
use AppHelper;
use Flash;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Coach;

class ClientTransactionController extends Controller
{

    protected function validator(array $data) // $mode = create / edit
    {

/* In the rules there should validation like either credit_balance or either debit_balance are required */

        if(empty($data['debit_balance'])) {

              $rules = [
                 'credit_balance' => 'required|Integer|Min:1',
                 'transaction_detail' => 'required',
             ];
             $messages = [
                 'credit_balance.required' => 'Credit Balance or Debit Balance Required aa',
                 'transaction_detail.required' => 'Notes Required',
                ];

             if($data['debit_balance']==0)
             {
                    if(empty($data['credit_balance']))
                    {

                        $rules = [
                        'credit_balance' => 'required|Integer|Min:1',
                        'transaction_detail' => 'required',
                        'debit_balance' => 'required|Integer|Min:1',
                        ];

                        $messages = [
                         'transaction_detail.required' => 'Notes Required',
                        ];
                    }
             }
         }
         else{

                 $rules = [
                'debit_balance' => 'required|Integer|Min:1',
                 'transaction_detail' => 'required',
                ];
                $messages = [
                'debit_balance.required' => 'Credit Balance or Debit Balance Required',
                'transaction_detail.required' => 'Notes Required',
                ];
         }


        return Validator::make($data, $rules, $messages);
    }

    public function getTransaction($id)
    {
        if(request()->get('_url') == 'client')
        {
            view()->share('module_action', array(
                "back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('clients.index'),
                    "attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
            ));

            $id = Crypt::decryptString($id);
            $data = $this->getindex($id);
            $total = $data->sum('transaction_amount');

            $credit= UserCreditsHistory::where('user_id',$id)->orderBy('id','ASC')->get();
            $user=User::where('id',$id)->first();
            view()->share('user',$user);
            view()->share('id',$id);
            view()->share('data',$data);
            view()->share('count',1);
            view()->share('total',$total);
            view()->share('credit',$credit);
            view()->share('title',trans('comman.transaction_report'));
            return view('client-transaction-history.index');
        }
        else
        {
            view()->share('module_action', array(
                "back" => array("title" => '<b><i class="icon-arrow-left52"></i></b> ' . trans("comman.back"), "url" => route('coaches.index'),
                    "attributes" => array("class" => "btn bg-blue btn-labeled heading-btn", 'title' => 'Back')),
            ));

            $id = Crypt::decryptString($id);
            $name = User::where('id',$id)->first()->name;
            $data = $this->getindex($id);
            view()->share('id',$id);
            view()->share('coach_name',$name);
            view()->share('data',$data);
            //dd($data);
            view()->share('count',1);
            view()->share('title',trans('comman.transaction_report'));
            return view('client-transaction-history.coach-transaction-report');
        }

    }
    public function addManualTransaction($id)
    {
        $coach_id = Crypt::decryptString($id);
        $coach_name = User::where('id',$coach_id)->first()->name;
        view()->share('title',trans('comman.transaction_report'));
        return view('client-transaction-history.add-manual-transaction',compact('coach_id','coach_name'));
    }

    public function storeManualTransaction(Request $request,$id)
    {
            $this->validator($request->all())->validate();

            $coach_id = Crypt::decryptString($id);
            $coach=Coach::where('user_id',$coach_id)->first();
            $balance_coach=$coach->balance;
            $input = AppHelper::getTrimmedData($request->all());
            if($input['credit_balance'] > 0){
                $input['transaction_amount'] = $input['credit_balance'];
                $input['transaction_type'] = 'plus';
                $total_balance=$balance_coach+$input['transaction_amount'];
                Coach::where('user_id', $coach_id)->update(['balance' => $total_balance]);
            }
            else{
                $input['transaction_amount'] = $input['debit_balance']; $input['transaction_type'] = 'minus';
                $total_balance=$balance_coach-$input['transaction_amount'];
                Coach::where('user_id', $coach_id)->update(['balance' => $total_balance]);
            }
            $input['user_id'] = $coach_id;
            $input['deleted'] = 0;


            $model = CoachTransactionHistory::create($input);

            Flash::success(trans("comman.admin_coach_add_manual_transaction_message"));

            if ($request->get('save_exit')) {
                  return redirect(request()->get('_url', route('client.transaction', ['coach_id' => Crypt::encryptString($coach_id)])));
            } else {
            return redirect()->route('add.manual.transaction', ['coach_id' => Crypt::encryptString($coach_id), '_url' => request()->get('_url', route('add.manual.transaction', ['coach_id' => Crypt::encryptString($coach_id)]))]);
            }

    }

    public function getindex($id)
    {

        $user_type = User::where('id',$id)->first()->user_type;
        if($user_type == 'client')
        {
           $input = CoachTransactionHistory::with('user','object_coach')->where('user_id',$id)->where('transaction_status','Success')->orderBy('created_at','ASC')->get();

        }
        else
        {
            /* This filter is for in coach account for financial report otherwise in admin account in coach transaction report have normal query there is no any filter for admin account  */
            //dd(request()->all());
            if (request()->all()) {
                $request['from_date'] = request()->get('trans_from_date');
                $request['to_date'] =  request()->get('trans_to_date');
                $request['client_id'] = request()->get('client');
                $request['transaction'] = request()->get('transaction');
                $request = request()->all();
                $request = AppHelper::getTrimmedData($request);
            }
            if(!empty($request['client_id']))
            {
                $client_id = $request['client_id'];
            }
            // $input = CoachTransactionHistory::with(['module_progress' => function($query,$client_id = '') use ($id){
            //         $query->where('status','=','reviewed')->where('deleted','=','0')->where('reviewed_user_id','=',$id);
            //         if(!empty($client_id)) { $query->where('user_id','=',$client_id);  }
            //         }])->where('deleted','=','0')->get()->toArray();
            $input = CoachTransactionHistory::with(['user','object_coach','module_progress' => function($query,$client_id = '') use ($id){
                    $query->where('status','=','reviewed')->where('deleted','=','0')->where('reviewed_user_id','=',$id);
                    if(!empty($client_id)) { $query->where('user_id','=',$client_id);  }
                    }],'module_progress.modules')->where('format','=','')->where('user_id','=',$id)->where('deleted','=','0');

            /* Date Filter */

            if(!empty(request()->get('trans_from_date')))
            {
                //dd($request['from_date']);
                $from_date = Carbon::createFromFormat('m-d-Y',request()->get('trans_from_date'))->format('Y-m-d').' 00:00:00';
                //dd($from_date);
                $input = $input->where(DB::raw("coach_transaction_histories.created_at"), '>=', $from_date);
            }
            if(!empty(request()->get('trans_to_date')))
            {
                $to_date = Carbon::createFromFormat('m-d-Y', request()->get('trans_to_date'))->format('Y-m-d').' 23:59:00';
                //dd($to_date);
                $input = $input->where(DB::raw("coach_transaction_histories.created_at"), '<=', $to_date);
            }
            if(!empty($request['transaction']))
            {

                if($request['transaction'] == 'Module')
                {
                    $input = $input->where('transaction_detail','like','Feedback %');
                }
                elseif($request['transaction'] == '1-1 Coaching')
                {
                   $input = $input->where('transaction_detail','like','%One hour session%');
                }
                elseif($request['transaction']=='free session')
                {
                    $input = $input->where('transaction_detail','like','%free session%');
                }
                elseif($request['transaction']=='Gratuate')
                {
                    $input = $input->where('transaction_detail','like','%gratuate%');
                }
                else
                {
                    $input = $input->where('transaction_detail','like','%'.$request['transaction'].'%');
                }
            }
           //  echo $from_date;
           //  echo $to_date;

            $input = $input->get();
        }

        return $input;
    }
    public function getCoachTransaction($id)
    {
        $id = Crypt::decryptString($id);
        $data = $this->getindex($id);
        $object = App::make("App\Http\Controllers\CoachController");
        view()->share('client', $object->get_coach_clients());
        // $client = Client::with('user')->where('coach_id',$id)->get()->pluck('user.name', 'id')->toArray();
        $transaction=array('Module' => 'Module Feedback','1-1 Coaching' =>
        '1-1 Coaching','Free Session' => 'Free Session','Gratuate'=>'Gratuate');
        view()->share('id',$id);
        view()->share('data',$data);
        //view()->share('client',$client);
        view()->share('transaction',$transaction);
        view()->share('title',trans('comman.transaction_report'));
        return view('client-transaction-history.coach-transaction-report-for-coach');
    }
    public function getPDF($id)
    {
        $id = Crypt::decryptString($id);
        $data = $this->getindex($id);
        $total_credit = $data->sum('credit');
        $total_debit = $data->sum('debit');
        $total = $total_credit - $total_debit;
        $pdf = PDF::loadView('client-transaction-history.pdf-report', ['data' => $data, 'total_credit' => $total_credit, 'total_debit' => $total_debit, 'total' => $total, 'count' => '1','theme' => 'limitless.pdf','title' => trans('comman.transaction_report')]);
        $pdf->setPaper('a4');
        $pdf->setOrientation('portrait');
        $pdf->setOption('margin-top', 10);
        $pdf->setOption('margin-right', 10);
        $pdf->setOption('margin-bottom', 10);
        $pdf->setOption('margin-left', 10);
        $pdf->setOption('header-right', '');
        return $pdf->stream();
    }

    /* Export Csv Report */

public function getCsv($id){
    $data = $this->getReportData($id);
    $this->exportExcelOrCsv($data['data'],$data['row'],'csv');
}

/* Export Xls Report */

public function getXls($id) {
    $data = $this->getReportData($id);
    $this->exportExcelOrCsv($data['data'],$data['row'],'xls');
}

    public function exportExcelOrCsv($data,$row,$type)
    {
       Excel::create('Financial Report', function($excel) use($data,$row) {
            $excel->sheet('Sheet1', function($sheet) use($data,$row) {
            $total_row  = $row + 3;

            $sheet->cells('A2:G2', function($cells) {
                 $cells->setFontWeight('bold');
             });
            $sheet->fromArray($data, null, 'A1', false, false);
        });

        })->export($type);
    }

    public function getReportData($id){

        $id = Crypt::decryptString($id);
        $data = $this->getindex($id);

        $total_credit = 0;$total_debit = 0;
        $report[] = array('Activity','Client','Date','Time','Credit($)','Debit($)','Balance($)');

        foreach ($data as $data) {
            if($data->transaction_detail != 'Internal Error')
                        {
                            if($data->transaction_type == 'plus')
                             {
                                $credit = $data->transaction_amount;
                                $debit = 0.00;
                                $total_credit += $credit;
                             }
                             else{
                                $debit = $data->transaction_amount;
                                $credit = 0.00;
                                $total_debit += $debit;
                             }
                        }
                        else{
                                $credit = 0.00;
                                $debit = 0.00;
                        }

            $balance = ($credit - $debit);
            if(substr($data->transaction_detail, 0,3) == 'Fee')
            {  $activity = 'Module Feedback';   }
            elseif(strpos($data->transaction_detail, 'One Hour') !== false){ $activity = 'aaa';  }
            elseif(strpos($data->transaction_detail, 'free session') !== false)
            {
                            $activity='Free Session';
            }
            else{ $activity = htmlspecialchars_decode($data->transaction_detail);  }
            // if(substr($data->transaction_detail, 0,3) == 'Fee'){  $activity = 'Module Feedback';   }
            // elseif(substr($data->transaction_detail, 0,3) == 'Has'){ $activity = '1-1 Coaching';  }
            // else{ $activity = htmlspecialchars_decode($data->transaction_detail);  }

             if(isset($data->module_progress->user_id))
             {
                $reviewed_at = $data->module_progress->reviewed_at;
                $date = Carbon::createFromFormat('Y-m-d H:i:s',$reviewed_at)->format('m/d/Y');
                $time = Carbon::createFromFormat('Y-m-d H:i:s',$reviewed_at)->format('H:i');
                $client_name = $data->module_progress->submittedBy->name;
                $report[] = array($activity,$client_name,$date,$time,$credit,$debit,$balance);
             }
             else{
                $report[] = array($activity,'','','',$credit,$debit,$balance);
             }

        }
        $total = $total_credit - $total_debit;
        $report[] = array('','','','Total',number_format($total_credit,2),number_format($total_debit,2),number_format($total,2));

        return $value = [
            'data'=>$report,
            'row' => count($data),
            ];
    }
    public function addclientManualTransaction($id)
    {
        $coach_id = Crypt::decryptString($id);
        $coach_name = User::where('id',$coach_id)->first()->name;
        view()->share('title',trans('comman.transaction_report'));
        return view('client-transaction-history.add-client-manual-transaction',compact('coach_id','coach_name'));
    }

    public function storeclientManualTransaction(Request $request,$id)
    {

            $this->validator($request->all())->validate();
            $coach_id = Crypt::decryptString($id);
            $input = AppHelper::getTrimmedData($request->all());
             $client=Client::where('clients.user_id',$coach_id)->where("clients.deleted", "0")->first();
            if($input['credit_balance'] > 0){ $input['transaction_amount'] = $input['credit_balance'];$input['transaction_type'] = 'plus';
                    $input['transaction_detail']='Coaching Credits Added by Admin';
                      $credit=$client->credits+$input['credit_balance']; }
            else{ $input['transaction_amount'] = $input['debit_balance']; $input['transaction_type'] = 'minus';
                $input['transaction_detail']='Coaching Credits Debit by Admin';
                 $credit=$client->credits-$input['debit_balance']; }


            $input['user_id'] = $coach_id;
            $input['deleted'] = 0;
            $input['transaction_status']='Success';
            $input['object_type']='clients';


            $model = CoachTransactionHistory::create($input);


            //dd($credit);
            Client::where('user_id', $coach_id)->update(['credits' => $credit]);
            Flash::success(trans("comman.admin_coach_add_manual_transaction_message"));

            if ($request->get('save_exit')) {
                  return redirect()->route('client.transaction', [Crypt::encryptString($coach_id),'_url' => 'client']);
                  // return redirect('client', route('client.transaction', ['id' => Crypt::encryptString($coach_id)]));
            } else {
            return redirect()->route('client.add.manual.transaction', ['coach_id' => Crypt::encryptString($coach_id), '_url' => request()->get('_url', route('client.add.manual.transaction', ['coach_id' => Crypt::encryptString($coach_id)]))]);
            }
    }
}

