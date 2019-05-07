@extends($theme)
@section('title', $title)
@section('content')
<div class="col-md-12 col-sm-12 ">

    <div class="panel panel-default">
        <div class="tab-title">
            <h1>
                 &nbsp; View Credit History
            </h1>
        </div>

        <div class="panel-body row">
        <div class="col-md-12" style="overflow: scroll;">
            <table class="table table-bordered table-responsive">
                <thead>
                <tr>
                    <td><b>Date</b></td>
                    <td><b>Description</b></td>
                    <td><b>Credits Purchased</b></td>
                    <td><b>Credits Used</b></td>
                    <td><b>Balance</b></td>

                    {{-- <td><b>Format</b></td>
                    <td><b>Credit Purchased</b></td>
                    <td><b>Total Paid</b></td>
                    <td><b>Result</b></td> --}}
                </tr>
                </thead>
                <tbody>
                @php $cr=0; @endphp
                @if(isset($client_details->user->credit_history)
                    && !empty($client_details->user->credit_history))
                     @php $total_credit = 0;$total_debit = 0; $balance=0; @endphp
                @foreach($client_details->user->credit_history as $credit_history)

                @php
                if($credit_history->transaction_type == 'plus')
                                {
                                    $credit = $credit_history->credit_score;
                                    $debit = '-';
                                    $total_credit += $balance + $credit;
                                }
                                else{
                                    $debit = $credit_history->credit_score;
                                    $credit = '-';
                                    $total_debit += $balance - $debit;
                                }
                                $balance += ($credit - $debit);
                if($credit_history->object_type=='coach_schedules_booked')
                {
                    if($credit_history->transaction_type=='plus')
                    {
                        $type='cancel Session for '.\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $credit_history->created_at)->format('d-m-Y').' at '.\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $credit_history->created_at)->format('H:i:s');
                    }
                    else
                    {

                        $type='Booked 1-1 Session for '.\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $credit_history->coach_booked_schedule->coach_schedule->start_datetime)->format('d-m-Y').' at '.\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $credit_history->coach_booked_schedule->coach_schedule->start_datetime)->format('H:i:s');
                    }
                }
                else if($credit_history->object_type=='clients')
                {
                    $type='Purchase Credits';
                }
                else
                {
                    $type=$credit_history->object_type;
                }
                @endphp
                @if(isset($credit_history->creditpackage) && !empty($credit_history->creditpackage))
                <tr>
                    <td>
                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $credit_history->created_at)->format('d-m-Y')  }}
                    </td>
                    <td>{{ $type }}</td>
                    {{--       <td>{{ number_format($credit) }}</td>
                    <td>{{ number_format($debit) }}</td>
                    <td>{{ number_format($balance) }}</td> --}}
                     <td>{{$credit}}</td>
                    <td>{{ $debit}}</td>
                    <td>{{ $balance}}</td>
                    {{--  <td>{{ $credit_history->payment_type }}</td>
                    <td>{{ $credit_history->credit_score }}</td>
                    <td>${{ number_format($credit_history->creditpackage->price ,2) * $credit_history->credit_score }}</td>
                    <td> Success </td> --}}
                </tr>
                @php $cr+=$credit_history->creditpackage->price * $credit_history->credit_score; @endphp
                @endif
                @endforeach
                @endif
                </tbody>
            </table>
            <p class="spacer"></p>
        </div>
        </div>
    </div>
    {{--     <div class="panel panel-default">
        <div class="panel-heading bg-white">
            <h5 class="panel-title">Membership Payment History:</h5>
        </div>
        <div class="panel-body row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr class="table-info">
                        <td><b>Date</b></td>
                        <td><b>Format</b></td>
                        <td><b>Type</b></td>
                        <td><b>Result</b></td>
                    </tr>
                </thead>
                <tbody>
                    @php $total=0; @endphp
                    @if(isset($client_details->user->transactionHistories)
                    && !empty($client_details->user->transactionHistories))
                    @foreach($client_details->user->transactionHistories as $key=>$history)
                    <tr>
                    <td>
                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $history->created_at)->format('d-m-Y')  }}
                    </td>
                    <td> {{$history->format}} </td>
                    <td>{{ $history->transaction_type }}</td>
                    <td>{{ $history->transaction_status }}</td>
                    </tr>
                @if($history->transaction_status!='Failure')
                    @php $total+=$history->transaction_amount; @endphp
                @endif
                @endforeach
                @endif
                </tbody>
            </table>
            <p class="spacer"></p>
            <label for=""><b>Revenue To Date: ${{ $total }}</b></label>
        </div>
        </div>
    </div> --}}
</div>
@include('clients.popup')
@endsection
@push('scripts')
<script type="text/javascript">
   jQuery(document).ready(function() {
   jQuery('.cancle').click(function(){
     var a=jQuery(this).attr('data_id');
     var b=jQuery(this).attr('data_clientid');
     jQuery('#bookedid').val(a);
     jQuery('#clientid').val(b);
     jQuery('#error').html('');
    });
    });
    jQuery(document).ready(function() {
    jQuery('#save').click(function(){
     var a=jQuery(this).attr('data_id');
     var bookedid= jQuery('#bookedid').val();
     var clientid= jQuery('#clientid').val();
     var reason= jQuery('#reason').val();
     var userid= jQuery('#userid').val();
     // var bookedid= jQuery('#bookedid').val();
     if(!reason.trim())
     {
        jQuery('#error').html('<div class="text-danger">* Add reason for cancel</div>'); exit;
     }
     console.log(bookedid);
     console.log(userid);
     console.log(reason);
      $.ajax({
        url: '{!! route("ajax.canclesession") !!}',
        type: 'POST',
        data: {
        bookedid:bookedid,
        clientid:clientid,
         _token:jQuery('input[name="_token"]').val(),
        reason:reason,
        userid:userid,
        },
        })
       .done(function(data) {
        console.log(data);
         window.location.reload();
    });
    });
});
   jQuery(document).ready(function() {
   jQuery('#coachid').change(function(){
   var coachid=jQuery('#coachid').val();
   var userid=jQuery('#userid').val();
   console.log(coachid);
   console.log(userid);
    $.ajax({
        url: '{!! route("ajax.notes") !!}',
        type: 'POST',
        data: {
        _token:jQuery('input[name="_token"]').val(),
        coach_id:coachid,
        client_id:userid,
        },
        })
        .done(function(data) {
        var j=data.length;
        jQuery('#coachnote').html('');
        for(var i=0;i<j;i++)
        {
            jQuery('#coachnote').append('<label>'+data[i].updated_at+'</label><textarea class=form-control name=coachnote['+data[i].id+']>'+data[i].note+'</textarea>');
        }
    });
   });
   });
</script>
{!! ajax_fill_dropdown('program_id','coach_id',route('ajax.coach')) !!}
{!! ajax_fill_dropdown('coach_id','module_id',route('ajax.ajaxcoachmodule')) !!}
@endpush
