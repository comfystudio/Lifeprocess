@extends('limitless.login')
@section('title', 'Login Section')
@section('style')
<style type="text/css">
	.form-wizard-title {
		border-bottom: 0;
		margin: 0;
	}
	#regiration_form fieldset:not(:first-of-type) {
		display: none;
	}
	.paypal
	{
		display: none;
	}
	button.remove_error {
		float: right;
	    font-size: 19.5px;
	    font-weight: 300;
	    line-height: 1;
	    cursor: pointer;
	}
	button.close,
	button.remove_error {
	  position: absolute;
	  right: 20px;
	  top: 25px;
  }
  #SignUp_step1 .tabs {
	border: 0px;
	border-bottom: 1px solid #e6e6e6;
	margin: 20px 30px;
}
#SignUp_step1 .tabs .tabs-title > a {
	padding: 1rem 1.5rem;
	height: 50px;
}
#SignUp_step1 .tabs .tabs-title > a#typepaypal {
	padding: 5px;
}
#SignUp_step1 .tabs .tabs-title > a#typepaypal img {
	border: 0px;
}
#cardname
{
	display: none;
}
#error
{
	display: none;
}
</style>
@endsection
@section('content')
    <div class="tabbable panel login-form width-400">
        <div class="tab-content panel-body">
            <div class="tab-pane fade in active" id="basic-tab1">
                <script src='https://js.stripe.com/v2/' type='text/javascript'></script>

                <form accept-charset="UTF-8" method="post" action="/agent/add-card" autocomplete="off" id="payment-form"  data-cc-on-file="false" data-stripe-publishable-key="{{env('STRIPE_KEY')}}">
                    <input name="_token" value="{{ csrf_token() }}" type="hidden"/>
                    <div class="text-center">
                        <div class="label " style="margin: 10px;">
                            <img src="{{ asset("themes/limitless/images/client-view/logo.png") }}" alt="Life Process Alcohol Program"/>
                        </div>
                        <h5 class="content-group">
                            Please Enter Your Card Details to Continue
                            <small class="display-block">
                                {{--Number--}}
                            </small>
                        </h5>
                    </div>
                    @include('limitless.partials.notifications')
                    <br/>

                    <div class="form-group has-feedback has-feedback-left {{ $errors->has('name') ? 'has-error' : '' }}">
                        <input type="text" class="form-control name" placeholder='Name on Card' name="name"/>
                            {!! ($errors->
                                has('name') ? $errors->
                                first('name', '
                                    <p class="text-danger">
                                        :message
                                    </p>
                                    ') : '') !!}
                        <div class="form-control-feedback">
                        </div>
                    </div>

                    <div class="form-group has-feedback has-feedback-left {{ $errors->has('card_number') ? 'has-error' : '' }}">
                        <input type="text" class="form-control card-number" placeholder='Card Number' name="card_number"/>
                            {!! ($errors->
                                has('card_number') ? $errors->
                                first('card_number', '
                                    <p class="text-danger">
                                        :message
                                    </p>
                                    ') : '') !!}
                        <div class="form-control-feedback">
                            {{--<i class="icon-vcard text-muted">--}}
                            {{--</i>--}}
                        </div>
                    </div>

                    <div class="form-group has-feedback has-feedback-left {{ $errors->has('CVC') ? 'has-error' : '' }}">
                        <input type="text" class="form-control card-cvc" placeholder='CVC Number' name="CVC"/>
                            {!! ($errors->
                                has('CVC') ? $errors->
                                first('CVC', '
                                    <p class="text-danger">
                                        :message
                                    </p>
                                    ') : '') !!}
                        <div class="form-control-feedback">
                        </div>
                    </div>

                    <div class="form-group has-feedback has-feedback-left {{ $errors->has('month') ? 'has-error' : '' }}">
                        <input type="text" class="form-control card-expiry-month" placeholder='MM' size='2'/>
                            {!! ($errors->
                                has('month') ? $errors->
                                first('month', '
                                    <p class="text-danger">
                                        :message
                                    </p>
                                    ') : '') !!}
                        <div class="form-control-feedback">
                        </div>
                    </div>

                    <div class="form-group has-feedback has-feedback-left {{ $errors->has('year') ? 'has-error' : '' }}">
                        <input type="text" class="form-control card-expiry-year" placeholder='YYYY' size='4'/>
                            {!! ($errors->
                                has('year') ? $errors->
                                first('year', '
                                    <p class="text-danger">
                                        :message
                                    </p>
                                    ') : '') !!}
                        <div class="form-control-feedback">
                        </div>
                    </div>

                     <div class='form-row'>
                        <div class='col-md-5 error form-group hide'>
                            <div class='alert-danger alert'>Please correct the errors and try
                                again.</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div style="clear: both;"></div>
                        <div class="form-group col-sm-7">
                            <button type="submit" class="btn bg-indigo-400 btn-block" id="sign_in" value="save">Submit <i class="icon-circle-right2"></i></button>
                        </div>
                    </div>
                    <div class="clearfix">
                    </div>
                    <br>
                </form>
            </div>
        </div>
    </div>
@endsection


<script src="https://code.jquery.com/jquery-1.12.3.min.js"
        integrity="sha256-aaODHAgvwQW1bFOGXMeX+pC4PZIPsvn2h1sArYOhgXQ="
        crossorigin="anonymous"></script>
    <script
        src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"
        integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS"
crossorigin="anonymous"></script>
<script>
    jQuery(function() {
      $('form.require-validation').bind('submit', function(e) {
        var $form         = $(e.target).closest('form'),
            inputSelector = ['input[type=email]', 'input[type=password]',
                             'input[type=text]', 'input[type=file]',
                             'textarea'].join(', '),
            $inputs       = $form.find('.required').find(inputSelector),
            $errorMessage = $form.find('div.error'),
            valid         = true;
        $errorMessage.addClass('hide');
        $('.has-error').removeClass('has-error');
        $inputs.each(function(i, el) {
          var $input = $(el);
          if ($input.val() === '') {
            $input.parent().addClass('has-error');
            $errorMessage.removeClass('hide');
            e.preventDefault(); // cancel on first error
          }
        });
      });
    });

    jQuery(function() {
      var $form = $("#payment-form");
      $form.on('submit', function(e) {
        if (!$form.data('cc-on-file')) {
          e.preventDefault();
          Stripe.setPublishableKey($form.data('stripe-publishable-key'));
          Stripe.createToken({
            name:$('.name').val(),
            number: $('.card-number').val(),
            cvc: $('.card-cvc').val(),
            exp_month: $('.card-expiry-month').val(),
            exp_year: $('.card-expiry-year').val()
          }, stripeResponseHandler);
        }
      });
      function stripeResponseHandler(status, response) {
        if (response.error) {
          $('.error')
            .removeClass('hide')
            .find('.alert')
            .text(response.error.message);
        } else {
          // token contains id, last4, and card type
          var token = response['id'];
          //alert(token);
          // insert the token into the form so it gets submitted to the server
          $form.find('input[type=text]').empty();
          $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
          $form.get(0).submit();
        }
      }
    })
</script>