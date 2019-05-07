@extends($theme)
@section('title', $title)
@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ trans('comman.transaction_report') }}</h3>
            <div class="heading-elements">
                @if(!empty($module_action))
                    <div class="text-right">
                        @foreach($module_action as $key=>$action)
                        {!! Html::decode(Html::link($action['url'],$action['title'],$action['attributes'])) !!}
                        @endforeach
                    </div>
                @endif
            </div>
            @if(isset($data) && count($data) > 0)
                <div class="heading-elements">
                    <a  href=" {!! route('client.transaction.pdf',Crypt::encryptString($id)) !!}" type="button" class="btn bg-info btn-labeled heading-btn"><b><i class="fa fa-file-pdf-o"></i></b>{{ trans('comman.saveaspdf')}}</a>
                </div>
            @endif
        </div>
        <div class="panel-body">
            <table class="table table-hover no-footer">
            <thead>
                <tr>
                <th style="border-bottom: 0px;padding: 18px 0 0 0;font-size: 14px;"> Client:{{ $user->name }}</th>
                <td style="padding: 8px 0 32px;"><a href="{!! route('client.add.manual.transaction',Crypt::encryptString($id)) !!}" type="button" class=" btn btn-success pull-right" style="font-size: 13px;"> Adjust coaching Session <b><i class="fa fa-pencil-square-o fa-1x"></i></b></a> </td>
                </tr>
            </thead>
            </table>
            <table class="table table-bordered table-hover no-footer">
                <thead>
                    <tr>
                        <th style="width: 50px;">{{ trans('comman.no') }}</th>
                        <th>Activity</th>
                        <th>Date/Time</th>
                        <th>Transaction no</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($data) && count($data) > 0)
                        @php $total_credit = 0;$total_debit = 0; $balance=0; @endphp
                            @foreach($data as $data)
                                @php
                                if($data->transaction_type == 'plus')
                                {
                                        $credit1 = $data->transaction_amount;
                                        $debit1 = '-';
                                        $total_credit +=  $credit1;
                                }
                                else
                                {
                                        $debit1 = $data->transaction_amount;
                                        $credit1 = '-';
                                        $total_debit +=  $debit1;
                                }
                                @endphp
                                <tr>
                                    <td>{{$count++}}</td>
                                    <td>{{$data->transaction_detail}}</td>
                                    <td>{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->created_at)->format('m/d/Y H:i')}}</td>
                                    <td>{{$data->id}}</td>
                                    <td>{{$data->transaction_amount}}</td>
                                </tr>
                            @endforeach
                            @php
                                $total= $total_credit-$total_debit;
                            @endphp
                    @endif
                    @php $credittotal=0;@endphp
                    @if(isset($credit) && count($credit) > 0)
                        @php $total_credit1 = 0;$total_debit1 = 0; $balance=0; @endphp
                        @foreach($credit as $credit)
                                @php
                                    if($credit->transaction_type == 'plus')
                                    {
                                        $credit2 =$credit->creditpackage->price;
                                        $debit2 = '-';
                                        $total_credit1 +=  $credit2;
                                    }
                                    else
                                    {
                                        $debit2 =$credit->creditpackage->price;
                                        $credit2 = '-';
                                        $total_debit1 +=  $debit2;
                                    }
                                @endphp
                                    <tr>
                                        <td>{{$count++}}</td>
                                        <td>@if($credit->object_type=='coach_schedules_booked')Used Credit for Booked session  @else Purchase Credit  @endif </td>
                                        <td>{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$credit->created_at)->format('m/d/Y H:i')}}</td>
                                        <td>{{$credit->id}}</td>
                                        <td>{{$credit->creditpackage->price}}</td>
                                    </tr>
                        @endforeach
                        @php
                            $credittotal=$total_credit1- $total_debit1;
                        @endphp
                    @endif
                        <tr>
                            <td colspan="4" class="text-right"><strong> Total </strong></td>
                            <td><strong>{{$total+$credittotal}}</strong></td>
                        </tr>
                        @if(empty($credit) && empty($data))
                        <tr>
                            <td colspan="4"> {{ trans('comman.no_data_found') }} </td>
                        </tr>
                        @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection