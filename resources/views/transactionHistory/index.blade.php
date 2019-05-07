@extends($theme)
@section('title', $title)
@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-body">
            {{ Form::open(array('route' => 'transaction.history','method'=>'GET','class'=>'form-filter','role'=>"form")) }}
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('from_date', trans("comman.from_date"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::text('from_date', Request::get('from_date',null), ['class' => 'form-control datepicker','placeholder'=> trans("comman.from_date") ]) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('to_date', trans("comman.to_date"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::text('to_date', Request::get('to_date',null), ['class' => 'form-control datepicker','placeholder'=> trans("comman.to_date") ]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label><br>
                        {!! Form::submit(trans('comman.search'), ['name' => 'search','class' => 'btn btn-primary btn-xs']) !!}
                        {!! link_to_route('transaction.history', trans('comman.cancel'), [], array('class' => 'btn btn-default btn-xs cancel')) !!}
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title">Transactions</h5>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-hover no-footer">
                <thead>
                    <tr>
                        <th width="50px">No.</th>
                        <th>Date</th>
                        <th>Details</th>
                        <th>Transaction</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($transactionLogs) && count($transactionLogs) > 0)
                        @php
                            $counter = 0;
                            $total = $transactionLogs->sum('transaction');
                        @endphp
                        <tr>
                            <th colspan="4" class="text-right">Current Balance : </th>
                            <th class="text-right"> {{ $total }} </th>
                        </tr>
                        @foreach($transactionLogs as $key => $transaction)

                            <tr>
                                <td> {{ $key + 1 }}. </td>
                                <td> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $transaction['created_at'])->format('m/d/Y') }} </td>
                                <td> {!! Html::decode($transaction['transaction_detail']) !!} </td>
                                <td class="text-right">
                                    @php
                                        $total -= $transaction['transaction'];
                                        echo  $transaction['transaction']; 
                                    @endphp
                                </td>
                                <td class="text-right"> {{ $total }} </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6"> {{ trans('comman.no_data_found') }} </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection