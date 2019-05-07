@extends($theme)
@section('title', $title)
@section('content')
<style type="text/css">
    .invoice-details, .invoice-payment-details>li span {
        float: right;
        text-align: right;
    }
</style>
<div class="row">
    <div class="panel panel-white">
        <div class="panel-heading">
            <h6 class="panel-title">{{ $title }}</h6>            
        </div>

        <div class="panel-body no-padding-bottom">
            <div class="row">
                <div class="col-xs-6 content-group">
                    <img src="{{ asset("images/logo-lpap.png") }}" class="content-group mt-10 bg-grey-800" alt="" style="width: 120px;">
                    <ul class="list-condensed list-unstyled">
                        <li>2355 Fairview Ave. #264 </li>
                        <li>Roseville, </li>
                        <li>MN 55113</li>
                        <li>United States</li>
                    </ul>
                </div>

                <div class="col-xs-6 content-group">
                    <div class="invoice-details">
                        <h5 class="text-uppercase text-semibold">Receipt #{{ $historyRow->id }}</h5>
                        <ul class="list-condensed list-unstyled">
                            <li>Date: <span class="text-semibold">{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $historyRow->created_at)->format('F d, Y') }}</span></li>
                            {{-- <li>Due date: <span class="text-semibold">May 12, 2015</span></li> --}}
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-6 col-md-6 col-lg-9 content-group">
                    <span class="text-muted">Receipt To:</span>
                    <ul class="list-condensed list-unstyled">
                        <li><h5>{{ $client->user->name }}</h5></li>
                        <li><span class="text-semibold">{{ $client->user->address_line_one }}</span></li>
                        <li>{{ $client->user->city }}</li>
                        <li>{{ $client->user->zip_code }}</li>
                        <li>{{ $client->user->state }}</li>
                        <li>{{ $client->user->email }}</li>
                    </ul>
                </div>

                <div class="col-xs-6 col-md-6 col-lg-3 content-group">
                    <span class="text-muted">Payment Details:</span>
                    <ul class="list-condensed list-unstyled invoice-payment-details">
                        <li><h5>Total Due: <span class="text-right text-semibold">${{ $client->program->program_fee }}</span></h5></li>
                        {{-- <li>Bank name: <span class="text-semibold">Profit Bank Europe</span></li>
                        <li>Country: <span>United Kingdom</span></li>
                        <li>City: <span>London E1 8BF</span></li>
                        <li>Address: <span>3 Goodman Street</span></li>
                        <li>IBAN: <span class="text-semibold">KFH37784028476740</span></li>
                        <li>SWIFT code: <span class="text-semibold">BPT4E</span></li> --}}
                    </ul>
                </div>
            </div>
        </div>

        <div class="">
            <table class="table table-lg" >
                <thead>
                    <tr>
                        <th class="col-sm-3">Program</th>
                        <th class="col-sm-1">Fee</th>
                        <th class="col-sm-1">Status</th>
                        <th class="col-sm-7">Reason</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            {{ $client->program->program_name }}
                            {{-- <span class="text-muted">One morning, when Gregor Samsa woke from troubled.</span> --}}
                        </td>
                        <td>${{ $client->program->program_fee }}</td>
                        <td>{{ $historyRow->transaction_status }}</td>
                        <td>{{ $historyRow->transaction_detail }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
{{-- 
        <div class="panel-body">
            <div class="row invoice-payment">
                <div class="col-xs-7 col-sm-7">
                    <div class="content-group">
                        <h6>Authorized person</h6>
                        <div class="mb-15 mt-15">
                            <img src="assets/images/signature.png" class="display-block" style="width: 150px;" alt="">
                        </div>

                        <ul class="list-condensed list-unstyled text-muted">
                            <li>Eugene Kopyov</li>
                            <li>2269 Elba Lane</li>
                            <li>Paris, France</li>
                            <li>888-555-2311</li>
                        </ul>
                    </div>
                </div>
                <div class="col-xs-5 col-sm-5 ">
                    <div class="content-group">
                        <h6>Total due</h6>
                        <div class="table-responsive no-border">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>Subtotal:</th>
                                        <td class="text-right">$7,000</td>
                                    </tr>
                                    <tr>
                                        <th>Tax: <span class="text-regular">(25%)</span></th>
                                        <td class="text-right">$1,750</td>
                                    </tr>
                                    <tr>
                                        <th>Total:</th>
                                        <td class="text-right text-primary"><h5 class="text-semibold">${{ $client->program->program_fee }}</h5></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
</div>
@endsection