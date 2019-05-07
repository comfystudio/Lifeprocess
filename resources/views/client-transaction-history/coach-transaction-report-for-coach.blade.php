@extends($theme)
@section('title', $title)
<style type="text/css">
.send{
background-color: #82cd49;
border: 0 none;
border-radius: 0;
color: #fff;
padding: 10px 18px;
}

.panel-body > .heading-elements {
    top: 200px !important;
}

.table {
    margin: 35px 0 20px 0;
}
</style>
@section('content')

<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Coach Transaction Report</h3>
            <div class="heading-elements">
            </div>
        </div>
        <div class="panel-body">
                    @if(Auth::user()->user_type=='user')
                    <div class="panel-body no-print">
                        {{ Form::open(array('route' => ['coach.transaction',Crypt::encryptString($id)],'method'=>'GET','class'=>'form-filter','role'=>"form")) }}
                            <div class="row">
                            <div class="col-md-4">
                                            <div class="form-group">
                                                {!! Html::decode(Form::label('coach', trans("comman.coach"). ':', ['class' => 'control-label'])) !!}
                                                {!! Form::select('coach', $coach,Request::get('coach',null), ['class' => 'form-control single-select','placeholder'=> trans("comman.coach") ]) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                        <div class="form-group">
                                        <label class="control-label">&nbsp;</label><br>
                                        {!! Form::submit(trans('comman.search'), ['name' => 'search','class' => 'btn btn-primary btn-xs']) !!}
                                         <a href="{{ route('coach.transaction',['id'=> Crypt::encryptString(Auth::user()->id)]) }}" class="btn btn-default  btn-xs">Cancel </a>
                                        </div>
                                        </div>
                                        </div>
                                        {!! Form::close() !!}
                    </div>
                    @else
                    <div class="panel-body no-print">
                        {{ Form::open(array('route' => ['coach.transaction',Crypt::encryptString($id)],'method'=>'GET','class'=>'form-filter','role'=>"form")) }}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('trans_from_date', trans("comman.from_date"). ':', ['class' => 'control-label'])) !!}
                                        {!! Form::text('trans_from_date', Request::get('trans_from_date',null), ['class' => 'form-control datepicker','placeholder'=> trans("comman.from_date"), 'autocomplete' => 'off' ]) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('trans_to_date', trans("comman.to_date"). ':', ['class' => 'control-label'])) !!}
                                        {!! Form::text('trans_to_date', Request::get('trans_to_date',null), ['class' => 'form-control datepicker','placeholder'=> trans("comman.to_date") , 'autocomplete' => 'off']) !!}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('all_transaction', trans("comman.transaction"). ':', ['class' => 'control-label'])) !!}
                                        {!! Form::select('transaction', $transaction,Request::get('transaction',null), ['class' => 'form-control single-select','placeholder'=> trans("comman.transaction") ]) !!}
                                       {{--  {!! Form::text('transaction', Request::get('transaction',null), ['class' => 'form-control','placeholder'=> trans("comman.transaction")]) !!} --}}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {!! Html::decode(Form::label('client', trans("comman.client"). ':', ['class' => 'control-label'])) !!}
                                        {!! Form::select('client', $client,Request::get('client',null), ['class' => 'form-control single-select','placeholder'=> trans("comman.client") ]) !!}
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">&nbsp;</label><br>
                                        {!! Form::submit(trans('comman.search'), ['name' => 'search','class' => 'btn btn-primary btn-xs']) !!}
                                         <a href="{{ route('coach.transaction',['id'=> Crypt::encryptString(Auth::user()->id)]) }}" class="btn btn-default  btn-xs">Cancel </a>

                                    </div>
                                </div>

                            </div>
                        {!! Form::close() !!}
                    </div>
                    @endif

             <div class="heading-elements no-print">

                  <a href="{!! route('coach.transaction.pdf',['id' => Crypt::encryptString($id)] + request()->all()) !!}" type="button" >  {{Html::image(AppHelper::path('images/')->getImageUrl('pdf.svg'),'pdf ',array('id'=>'friend','height'=>'35px','width'=>'65px','style'=>'margin: 0px -18px 0 0;'))}}  </a>

                  <a href="{!! route('coach.transaction.xls',['id' => Crypt::encryptString($id)] + request()->all()) !!}" type="button" >  {{Html::image(AppHelper::path('images/')->getImageUrl('xls.svg'),'xls',array('id'=>'friend','height'=>'35px','width'=>'65px','style'=>'margin: 0px -18px 0 0;'))}}  </a>

                  <a href="{!! route('coach.transaction.csv',['id' => Crypt::encryptString($id)] + request()->all()) !!}" type="button" >  {{Html::image(AppHelper::path('images/')->getImageUrl('csv.svg'),'help a friend',array('id'=>'friend','height'=>'35px','width'=>'65px','style'=>'margin: 0px -18px 0 0;'))}}  </a>

                  <a href="#" id="print" onclick="javascript:window.print();" type="button" >  {{Html::image(AppHelper::path('images/')->getImageUrl('print.svg'),'help a friend',array('id'=>'friend','height'=>'35px','width'=>'65px','style'=>'margin: 0px -18px 0 0;'))}}  </a>
            </div>


            <table class="table table-bordered table-hover no-footer">
                <thead>
                    <tr>
                        <th>Activity</th>
                        <th>Exercise</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Credit($)</th>
                        <th>Debit($)</th>
                        <th>Balance($)</th>
                    </tr>
                </thead>
                <tbody>

                    @if(isset($data) && count($data) > 0)


                    @php $total_credit = 0;$total_debit = 0; $balance=0;@endphp

                        @foreach($data as $data)

                        @php

                        // In transaction detail if there is transaction details is internal error then debit and credit should be 0.00.

                        if($data->transaction_detail != 'Internal Error')
                        {
                             if($data->transaction_type == 'plus')
                             {
                                $credit = $data->transaction_amount;
                                $debit = 0.00;
                                $total_credit += $credit;
                             }
                             else
                             {
                                $debit = $data->transaction_amount;
                                $credit = 0.00;
                                $total_debit += $debit;
                             }
                        }
                        else{
                                $credit = 0.00;
                                $debit = 0.00;
                        }

                        $balance += ($credit - $debit);

                        if(substr($data->transaction_detail, 0,3) == 'Fee'){  $activity = 'Module Feedback';   }
                        elseif(strpos($data->transaction_detail, 'One hour session')!== false){ $activity = '1-1 Coaching';  }
                        elseif(strpos($data->transaction_detail, 'free session') !== false)
                        {
                            $activity='Introductory Session';
                        }
                        elseif(strpos($data->transaction_detail, 'gratuate') !== false)
                        {
                            $activity='Gratuate Session';
                        }
                        elseif(strpos($data->transaction_detail, 'Coach credit Threshold') !== false)
                        {
                            $activity='Withdraw';
                        }
                        else{ $activity = htmlspecialchars_decode($data->transaction_detail);  }

                        @endphp

                        <tr>
                            <td>{{ $activity }} </td>
                            @if($activity=='Introductory Session' || $activity=='1-1 Coaching')
                            <td>{{$data->transaction_detail}}</td>
                            <td> {{ $data->object_coach->user->name or '' }} </td>
                            <td>
                            {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->created_at ,'UTC')->setTimezone($data->user->timezone)->format('m/d/Y') }}
                            </td>
                            <td>
                            {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->updated_at ,'UTC')->setTimezone($data->user->timezone)->format('H:i') }}
                            </td>
                            @elseif($activity=='Module Feedback')

                            <td>{{$data->transaction_detail}}</td>
                            <td>{{ $data->object_coach->user->name or '' }} </td>
                            <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->created_at ,'UTC')->setTimezone($data->user->timezone)->format('m/d/Y') }}</td>
                            <td> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->created_at ,'UTC')->setTimezone($data->user->timezone)->format('H:i') }}</td>
                            @elseif(isset($data->module_progress->user_id))
                            <td>{{ $data->module_progress->modules->module_title }}</td>
                            <td>{{ $data->module_progress->submittedBy->name or '' }} </td>
                            <td>
                            {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->module_progress->reviewed_at)->format('m/d/Y')}}
                            </td>
                            <td>
                            {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->module_progress->reviewed_at)->format('H:i')}}
                            </td>
                            @elseif($activity=='Withdraw')
                            <td></td>
                            <td></td>
                            <td> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->created_at ,'UTC')->setTimezone($data->user->timezone)->format('m/d/Y') }}</td>
                            <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->updated_at ,'UTC')->setTimezone($data->user->timezone)->format('H:i') }}</td>
                            @else
                            <td>manual transaction</td>
                            <td></td>
                            <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->created_at ,'UTC')->setTimezone($data->user->timezone)->format('m/d/Y') }}</td>
                            <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->created_at ,'UTC')->setTimezone($data->user->timezone)->format('H:i') }}</td>
                            @endif

                            <td>{{ number_format($credit,2) }}</td>
                            <td>{{ number_format($debit,2) }}</td>
                            <td>{{ number_format($balance,2) }}</td>

                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" class="text-right"><strong> Total </strong></td>
                            <td><strong>{{ number_format($total_credit,2) }}</strong></td>
                            <td><strong>{{ number_format($total_debit,2) }}</strong></td>
                            <td><strong>{{ number_format($total_credit - $total_debit,2) }}</strong></td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="8"> {{ trans('comman.no_data_found') }} </td>
                        </tr>
                    @endif
                </tbody>
            </table>
           {{--  @if(isset($data) && count($data) > 0)
            <table class="table table-bordered">
            <div class="col-md-12">
            {!! Form::open(array('route' => 'coach.payment.withdraw','class'=>'form-horizontal','role'=>"form",'method'=>'post')) !!}

            <div class="form-group {{ ($errors->has('role_name')) ? 'has-error' : '' }}">
            {!! Html::decode(Form::label('amount','Amount To Withdraw:<span class="has-stik">*</span>', ['class' => 'col-lg-3 control-label'])) !!}
            <div class="col-lg-6">
                {!! Form::text('amount', null, ['class' => 'form-control text-capitalize','id'=>'role_name','autofocus'=>'true', 'autocomplete' => 'off']) !!}
                {!! ($errors->has('role_name') ? $errors->first('amount', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            </div>
            <div class="form-group">
                            <div class="col-sm-2">
                                {!! Form::submit("Withdraw", ['name'=>'save','class' => 'btn btn-primary']) !!}
                            </div>
            </div>
            {!! Form::close() !!}
            </div>
            </table>
            @endif --}}
        </div>
    </div>
</div>
@push('scripts')
<script type="text/javascript">
 jQuery(function(){
        jQuery('.datepicker').datetimepicker({
           timepicker:false,
           format: "m-d-Y",
        });
    });
 </script>
 @endpush

 @endsection
