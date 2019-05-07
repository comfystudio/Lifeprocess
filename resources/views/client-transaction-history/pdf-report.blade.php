@extends($theme)
@section('title',$title)
@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title">{{ trans('comman.admin_coach_transaction_report') }}</h5>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-hover no-footer">
                <thead>
                    <tr>

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

                    @php $total_credit = 0;$total_debit = 0; @endphp

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
                            if(substr($data->transaction_detail, 0,3) == 'Fee'){  $activity = 'Module Feedback';   }
                            elseif(substr($data->transaction_detail, 0,3) == 'Has'){ $activity = '1-1 Coaching';  }
                            else{ $activity = htmlspecialchars_decode($data->transaction_detail);  }

                        @endphp
                        <tr>

                            <td> {{ $activity }} </td>
                            @if(isset($data->module_progress->user_id))
                            <td> {{ $data->module_progress->submittedBy->name }} </td>
                            <td>{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->module_progress->reviewed_at)->format('m/d/Y')}}</td>
                             <td>{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->module_progress->reviewed_at)->format('H:i')}}</td>
                            @else
                            <td>  </td>
                            <td>  </td>
                            <td>  </td>
                            @endif

                            <td>{{ number_format($credit,2) }}</td>
                            <td>{{ number_format($debit,2) }}</td>
                            <td>{{ number_format($balance,2) }}</td>

                        </tr>
                        @endforeach
                        <tr>
                            <td colspan="4" class="text-right"><strong> Total </strong></td>
                             <td><strong>{{ number_format($total_credit,2) }}</strong></td>
                              <td><strong>{{ number_format($total_debit,2) }}</strong></td>
                              <td><strong>{{ number_format($total_credit - $total_debit,2) }}</strong></td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="7"> {{ trans('comman.no_data_found') }} </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection