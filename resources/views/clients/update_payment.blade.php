
@extends($theme)
@section('title', $title)

@section('content')
<div class="content-wrapper">
<div class="panel panel-default">
                <div class="panel-heading">
                    <h5 class=""><b> Credit/Debit Card </b></h5>
                </div>
                <div class="panel-body">
                    <div class="col-md-12">
                    <div class="col-md-4">
                    Card Type:
                    <div class="form-group">
                    {{ Form::text('full_name','',['class' =>'form-control']) }}
                    </div>
                    </div>
                    </div>
                    <div class="col-md-12">
                    <div class="col-md-4">
                    Cardholder Name:
                    <div class="form-group">
                    {{ Form::text('full_name','',['class' =>'form-control']) }}
                    </div>
                    </div>
                    </div>
                    <div class="col-md-12">
                    <div class="col-md-4">
                    Card Number:
                    <div class="form-group">
                    {{ Form::text('full_name','',['class' =>'form-control']) }}
                    </div>
                    </div>
                    </div>
                    <div class="col-md-12">
                    <div class="col-md-4">
                    Expiry Date:
                    <div class="form-group">
                    {{ Form::text('full_name','',['class' =>'form-control']) }}
                    </div>
                    </div>
                    </div>
                    <div class="col-md-12">
                    <div class="col-md-4">
                    {!! Form::submit('Save Details',['name' =>
                                    'save','class' =>
                                    'btn btn-primary']) !!}
                    </div>
                    </div>
                </div>
              </div>
                <div class="panel panel-default">
                <div class="panel-heading">
                    <h5 class=""><b> Billing Address</b> </h5>
                </div>
                <div class="row panel-body">
                    <div class="col-md-12">
                    <div class="col-md-4">Full name:
                    <div class="form-group">
                    {{ Form::text('full_name','',['class' =>'form-control']) }}
                    </div>
                    </div>
                     <div class="col-md-4">Zip/Postal Code:
                      <div class="form-group">
                    {{ Form::text('full_name','',['class' =>'form-control']) }}
                      </div>
                      </div>
                      </div>
                    <div class="col-md-12">
                    <div class="col-md-4">Address Line 1:
                    <div class="form-group">
                    {{ Form::text('full_name','',['class' =>'form-control']) }}</div>
                    </div>
                    <div class="col-md-4">Country:
                     <div class="form-group">
                    {{ Form::text('full_name','',['class' =>'form-control']) }}</div>
                    </div></div>

                    <div class="col-md-12">
                     <div class="col-md-4">Address Line 2:
                    <div class="form-group">
                    {{ Form::text('full_name','',['class' =>'form-control']) }}</div>
                    </div>
                    <div class="col-md-4">Email:
                    {{ Form::text('full_name','',['class' =>'form-control']) }}</div>

                    </div>
                    <div class="col-md-12">
                    <div class="col-md-4">City:
                    <div class="form-group">
                    {{ Form::text('full_name','',['class' =>'form-control']) }}</div>
                    </div>
                    <div class="col-md-4">Telephone:
                    {{ Form::text('full_name','',['class' =>'form-control']) }}</div>
                    </div>

                    <div class="col-md-12">
                    <div class="form-group">
                    {!! Form::submit('Save Details',['name' =>
                                    'save','class' =>
                                    'btn btn-primary']) !!}
                    {!! Form::submit('Add New Address',['name' =>
                                    'save','class' =>
                                    'btn btn-primary']) !!}
                    </div>
                    </div>
                </div>
                </div>
                <div class="panel panel-default">
                <div class="panel-heading">
                    <h5 class=""><b> Paypal </b></h5>
                </div>
                <div class="panel-body">
                    <div class="col-md-12">
                    Click the Continue to paypal using your email and password.
                    <br><br>
                    {!! link_to(URL::full(),'Paypal',array('class' =>
                                    'btn btn-primary')) !!}
                    </div>
                </div>
</div>

 </div>

@push('scripts')
<script>

</script>
@endpush
@endsection{!! Form::submit('Save Details',['name' =>
                                    'save','class' =>
                                    'btn btn-primary']) !!}