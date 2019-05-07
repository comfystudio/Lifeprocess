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
           {{--  <p>
                You currently have {{ $client_credits }} credits remaining.
            </p> --}}
            <br>
            {{-- {{ dump(Session::all()) }} --}}
            {{-- {{ dump() }} --}}
            <div class="col-md-12 col-xs-12">
                {!! Form::open( array('route' => 'client.myCredits.confirmation','class'=>'form-horizontal','role'=>"form",'id'=>'client_purchase_credits')) !!}
                    <fieldset>
                        <legend> <strong>Payment Confirmation</strong> </legend>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-md-2">No. of credits</label>
                                <div class="col-md-2">
                                    <input type="number" name="credits" class="form-control" min="0" value="{{ head(Session::get('credits')) }}" readonly="">
                                    {!! Form::hidden('credits_amount', head(Session::get('credits_amount'))) !!}
                                </div>
                                <div class="col-md-4">
                                    {{-- <a href="javascript:void(0);" class="btn btn-warning">Confirm</a> --}}
                                    {!! Form::submit('Confirm', ['name' => 'confirm', 'class' => 'btn btn-warning']) !!}
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="form-group">
                                <label class="col-md-2">Total Cost</label>
                                <div class="col-md-2">
                                    ${{ head(Session::get('credits_amount')) }}
                                </div>
                            </div>
                        </div>
                    </fieldset>
                {!! Form::close() !!}
            </div>
        </div>
        <div class="panel-footer" style="padding: 10px;">
            <strong>How do credits work?</strong>
            <p>Credits are Life Process Program's very own currency. Purchase credits and use them to book sessions with your coach.</p>
        </div>
    </div>
</div>
@push('scripts')
    
@endpush
@endsection