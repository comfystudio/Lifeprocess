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

</style>
@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title">{{ trans('comman.admin_coach_transaction_report') }}</h5>
            <div class="heading-elements">

            </div>
            <!-- @if(isset($data) && count($data) > 0)
                <div class="heading-elements">
                    <a  href=" {!! route('client.transaction.pdf',Crypt::encryptString($id)) !!}" type="button" class="btn bg-info btn-labeled heading-btn"><b><i class="fa fa-file-pdf-o"></i></b>{{ trans('comman.saveaspdf')}}</a>
                </div>
            @endif -->
        </div>
        <div class="panel-body">


            <table class="table table-hover no-footer">
            <thead>
                <tr>
                <th style="border-bottom: 0px;padding: 18px 0 0 0;font-size: 14px;"> Coach:{{ $coach_name }} </th>
                <td style="padding: 8px 0 32px;"><a href="{!! route('add.manual.transaction',Crypt::encryptString($id)) !!}" type="button" class="send pull-right" style="font-size: 13px;">Add Manual Transaction <b><i class="fa fa-pencil-square-o fa-1x"></i></b></a> </td>
                 </tr>
             </thead>
             </table>



            <table class="table table-bordered table-hover no-footer">
                <thead>
                    <tr>
                        <th>Coach ID</th>
                        <th>Activity</th>
                        <!--<th>Exercise</th>-->
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

                    @php $total_credit = 0;$total_debit = 0; $balance=0; @endphp

                        @foreach($data as $data)

                        @php

// In transaction detail if there is transaction details is internal error then debit and credit should be 0.00.

                        if($data->transaction_detail != 'Internal Error')
                        {
                               if($data->transaction_type == 'plus')
                                 {
                                    if (is_numeric($data->transaction_amount)){
                                        $credit = $data->transaction_amount;
                                        {{--$debit = '-';--}}
                                        $debit = 0;
                                        $total_credit +=  $credit;
                                    }
                                 }
                                 else{
                                    if (is_numeric($data->transaction_amount)){
                                        $debit = $data->transaction_amount;
                                        {{--$credit = '-';--}}
                                        $credit = 0;
                                        $total_debit +=  $debit;
                                    }
                                 }
                        }
                        else{
                                 $credit = 0.00;
                                 $debit = 0.00;
                        }

                        $balance += ($credit - $debit);

                        if(substr($data->transaction_detail, 0,3) == 'Fee'){  $activity = 'Module Feedback';   }
                        elseif(strpos($data->transaction_detail, 'One hour') !== false){ $activity = '1-1 Coaching';  }
                        elseif(strpos($data->transaction_detail, 'Free session') !== false)
                        {
                            $activity='Free Session';
                        }
                        elseif(strpos($data->transaction_detail, 'gratuate') !== false)
                        {
                              $activity='Gratuate Session';
                        }
                        else{ $activity = htmlspecialchars_decode($data->transaction_detail);  }


                        @endphp
                        <tr>
                            <td>{{ 'T'.$data->id }}</td>
                            <td> {{ $activity }} </td>
                            @if($activity=='Free Session' || $activity=='1-1 Coaching')
                            <td>{{$data->object_coach->user->name or ''}}</td>

                            <td>
                            {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->created_at ,'UTC')->setTimezone($data->user->timezone)->format('Y-m-d') }}
                            </td>
                            <td>
                            {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->updated_at ,'UTC')->setTimezone($data->user->timezone)->format('H:i') }}
                            </td>

                            @elseif(isset($data->module_progress->user_id))
                            <td> {{ $data->module_progress->submittedBy->name or '' }} </td>
                            <td>

                            {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->module_progress->reviewed_at ,'UTC')->setTimezone($data->user->timezone)->format('Y-m-d') }}
                            </td>
                            <td>
                            {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->module_progress->reviewed_at ,'UTC')->setTimezone($data->user->timezone)->format('H:i') }}
                            {{--  {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->module_progress->reviewed_at,$data->user->timezone)->format('H:i')}} --}}</td>
                            @else
                            <td></td>
                            <td> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->created_at ,'UTC')->setTimezone($data->user->timezone)->format('Y-m-d') }} </td>
                            <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->created_at ,'UTC')->setTimezone($data->user->timezone)->format('H:i') }}  </td>
                            @endif

                            <td>{{ $credit }}</td>
                            <td>{{ $debit }}</td>
                            <td>{{ number_format($balance,2) }}</td>

                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" class="text-right"><strong> Total </strong></td>
                            <td><strong>{{ number_format($total_credit) }}</strong></td>
                            <td><strong>{{ number_format($total_debit) }}</strong></td>
                            <td><strong>{{ number_format($total_credit - $total_debit) }}</strong></td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="8"> {{ trans('comman.no_data_found') }} </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection