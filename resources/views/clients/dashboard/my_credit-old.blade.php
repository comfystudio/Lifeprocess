@extends($theme)
@section('title', $title)
@section('content')
<style type="text/css">
    form {
        margin-bottom: 0;
    }
    .table-bordered > tbody > tr > th, .table-bordered > tbody > tr > td {
        padding: 5px 15px;
    }
</style>
<div class="row">
    @if(Auth::user()->user_type == 'client')
        <div class="panel panel-default border-top-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-sm-9">
                        <h6 class="panel-title"> <strong> {{ $module_title }} </strong> 
                        </h6>
                    </div>
                </div>
            </div>    
            <div class="panel-body">
                <p>
                    The Life Process Program was originally designed for use in residential treatment centres where clients would pay upwards of $40,000 to complete the program. Using the power of the internet you can now access all features of the program at a fraction of the cost and from the comfort of your own home as you go about your daily life.
                </p>
                <p>
                    By purchasing credits you will have the opportunity to engage in a detailed telephone coaching session with your dedicated Life Process Coach. Appointments can be scheduled using our online calendar system. 
                </p>
                <p>
                    You currently have {{ $client_credits }} credits remaining.
                </p>
                {{-- <div class="alert alert-success">
                    15% OFF CREDITS - Offer ends 31st Dec 2015
                </div> --}}
                <div class="col-md-6 col-xs-12">
                    <table class="table table-responsive">
                        <tbody>
                            <tr>
                                <th  class="text-center">No of Credits</th>
                                <th  class="text-center">Normal Price</th>
                                {{-- <th  class="text-center" style="color:#1c8222">Sale Price - 15% Off</th> --}}
                            </tr>
                            @if(isset($credits_package) && count($credits_package) > 0)
                                @foreach($credits_package as $package)
                                    <tr>
                                        <td class="text-center">{{ $package->credit }}</td>
                                        <td class="text-center">$ <span id="credit_{{ $package->credit }}">{{ $package->price }}</span></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="2"> No credit package found.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <hr>                
                <div class="col-md-6 col-xs-12">
                    {!! Form::open( array('route' => 'client.myCredits.purchase','class'=>'form-horizontal','role'=>"form",'id'=>'client_purchase_credits')) !!}
                        <h4>Purchase Credits</h4>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-md-3">No. of credits</label>
                                <div class="col-md-3">
                                    {!! Form::select('credits', $credits_dropdown, null, ['class' => 'form-control', "onchange" => "calculate_amount(this.value)"]) !!}
                                    {!! Form::hidden('credits_amount', '') !!}
                                </div>
                                <div class="col-md-4">
                                    <a href="javascript:void(0);" class="btn btn-warning" onclick="submit_toPurchase()">Buy Credits</a>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">Total Cost</label>
                                <div class="col-md-3" id="total_cost">                            
                                </div>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="panel-footer" style="padding: 10px;">
                <strong>How do credits work?</strong>
                <p>Credits are Life Process Program's very own currency. Purchase credits and use them to book sessions with your coach.</p>
            </div>
        </div>
    @endif
    <div class="panel panel-default border-top-warning">
        <div class="panel-heading" id="credits_history">
            <div class="row">
                <div class="col-sm-9">
                    <h6 class="panel-title"> <strong> Credits History </strong> 
                    </h6>
                </div>
            </div>
        </div>    
        <div class="panel-body">
            {{-- {{ dump($client_credits_history->items()) }} --}}
            <table class="table table-responsive table-bordered">
                <tr>
                    <th width="50px">No.</th>
                    <th width="100px">Date</th>
                    <th width="100px">Transaction</th>
                    <th>Credits effect due to action</th>
                    <th>Credit Balance</th>
                </tr>
                @if(isset($client_credits_history) && count($client_credits_history->items()) > 0)
                    @php
                        $credit_balance = $current_credits;
                    @endphp
                    <tr>
                        <th colspan="4" class="text-right">Current Balance : </th>
                        <th class="text-right" width="150px"> {{ $client_credits }} </th>
                    </tr>
                    {{-- {{ dump($client_credits_history->items()) }} --}}
                    @foreach($client_credits_history->items() as $key => $row)
                        {{-- {{ dump($row) }} --}}
                        <tr>
                            <td> {{ $key + 1 }}. </td>
                            <td> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('m/d/Y') }} </td>
                            <td class="text-right">
                                @php
                                    if($row->transaction_type == 'plus') {
                                        $credit_balance -= $row->credit_score;
                                        echo '+' . $row->credit_score;
                                    } else {
                                        $credit_balance += $row->credit_score;
                                        echo '-' . $row->credit_score;
                                    }
                                @endphp
                            </td>
                            <td> 
                                {{-- {{ dump($row) }} --}}
                                @if($row->object_type == 'coach_schedules_booked')
                                    @if($row->transaction_type == 'plus')
                                        Has canceled the booked session 
                                    @else
                                        Has booked session 
                                    @endif
                                    
                                    @if($row->coach_schedules_booked && $row->coach_schedules_booked->coach_schedule)
                                        on <strong>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $row->coach_schedules_booked->coach_schedule->start_datetime)->format('jS F Y \a\t h:i A') }}</strong>
                                    @endif
                                @elseif($row->object_type == 'user_module_progresses')
                                    Has completed module 
                                    @if($row->user_module_progresses)
                                        <strong>{{ $row->user_module_progresses->modules->module_no }}. {{ $row->user_module_progresses->modules->module_title }}</strong>
                                    @endif
                                @elseif($row->object_type == 'clients')
                                    Has purchased credits
                                @endif
                            </td>
                            <td class="text-right">{{ $credit_balance }}</td>
                        </tr>
                    @endforeach
                @else 
                    <tr>
                        <td colspan="5">No history found.</td>
                    </tr>
                @endif
            </table>
            <br>
            <div class="pull-right">
                {{ $client_credits_history->appends(Request::except('page'))->fragment('credits_history')->links() }}
            </div>            
        </div>
    </div>
</div>
@push('scripts')
    {{-- resources/themes/limitless/assets/js/plugins/forms/inputs/touchspin.min.js --}}
    <script src="{{ asset("themes/limitless/js/touchspin.min.js")}}" type="text/javascript"></script>
    <script type="text/javascript">
        jQuery(document).ready(function(){
            $("select[name='credits']").select2({
                minimumResultsForSearch: Infinity
            });
            jQuery('select[name="credits"]').trigger('change');
        });
        
        function calculate_amount(credits) {
            var total_cost = jQuery('#credit_' + credits).html();
            jQuery('#total_cost').html('$' + parseFloat(total_cost).toFixed(2));
        }
        function submit_toPurchase() {
            var credits = jQuery('select[name="credits"]').val();
            var total_cost = jQuery('#credit_' + credits).html();
            jQuery('input[name="credits_amount"]').val(parseFloat(total_cost).toFixed(2));
            jQuery('#client_purchase_credits').submit();
        }
    </script>
@endpush
@endsection