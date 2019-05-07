@extends($theme)
@section('title', 'Payment')
@section('content')

    <!-- Dashboard content -->
    <div class="panel panel-white">
        <div class="panel-heading">
            <div class="row">
                <h3 class="panel-title">Payment</h3>
            </div>
        </div>
        <div class="panel-body">
        </div>

    <div class="panel-body">
        <div class="row">
            @if(session('error'))
                                <div class="row" id='payment_error'>
                                    <div class="col-sm-12">
                                        <div class="alert alert-danger">
                                            <button type="button" class="remove_error">&times;</button>
                                            {{ session('paymenterror') }}
                                        </div>
                                    </div>
                                </div>
            @endif
            <div class="col-lg-12">
            {!! Form::open(array('route' => 'coach.payment.withdraw','class'=>'form-horizontal','role'=>"form",'method'=>'post')) !!}
            <div class="form-group {{ ($errors->has('role_name')) ? 'has-error' : '' }}">
            {!! Html::decode(Form::label('amount','Amount:<span class="has-stik">*</span>', ['class' => 'col-lg-1 control-label'])) !!}
            <div class="col-lg-9">
                {!! Form::text('amount', null, ['class' => 'form-control text-capitalize','id'=>'role_name','autofocus'=>'true', 'autocomplete' => 'off']) !!}
                {!! ($errors->has('role_name') ? $errors->first('amount', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            </div>
            <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-4 text-center">
                                {!! Form::submit("Withdraw", ['name'=>'save','class' => 'btn btn-primary']) !!}
                            </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    </div>
    </div>
@endsection
