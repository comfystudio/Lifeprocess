@extends($theme)
@section('title', $title)
@section('content')
<div class="col-md-12 col-sm-12 ">
    <div class="panel">
        <div class="tab-title">
            <h1>
               Payment on stripe
            </h1>
        </div>

        <div class="panel-body">

        <script src='https://js.stripe.com/v2/' type='text/javascript'></script>
        <form accept-charset="UTF-8" action="/stripepayment" class="require-validation"
          data-cc-on-file="false"
          data-stripe-publishable-key="{{env('STRIPE_KEY')}}"
          id="payment-form" method="post">
          {{ csrf_field() }}
          <div class='col-md-12'>
        <div class='col-md-4 form-group required'>
            <label class='control-label'>Name on Card</label> <input
                class='form-control name' size='4' type='text' name="name">
        </div>

        <div class='col-md-4 form-group card required'>
            <label class='control-label'>Card Number</label> <input
                autocomplete='off' class='form-control card-number' size='20'
                type='text'>
        </div>
         <div class='col-xs-4 form-group cvc required'>
            <label class='control-label'>CVC</label> <input autocomplete='off'
                class='form-control card-cvc' placeholder='ex. 311' size='4'
                type='text'>
        </div>
    </div>
    <div class='col-md-12'>

        <div class='col-xs-4 form-group expiration required'>
            <label class='control-label'>Expiration Month</label> <input
                class='form-control card-expiry-month' placeholder='MM' size='2'
                type='text'>
        </div>
        <div class='col-xs-4 form-group expiration required'>
            <label class='control-label'>Expiration Year</label> <input
                class='form-control card-expiry-year' placeholder='YYYY' size='4'
                type='text'>
        </div>

    </div>

        <div class='col-md-12'>
           <div class='col-xs-4 form-group expiration required'>
            <label class='control-label'>Amount</label>
             <input type="text" name="amount" value="{{round($credits_amount)}}" readonly="readonly" class="form-control">
            </div>

            {{-- <div class='col-xs-4 form-group expiration required'>
            <label class='control-label'>credit score</label>
            <input type="text" name="score" value="{{round($credits)}}" readonly="readonly">
            </div> --}}
            <input type="hidden" name="score" value="{{round($credits)}}">

        </div>
        <div class="col-md-2">
        <button class='form-control btn btn-primary submit-button'
                type='submit' style="margin-top: 10px; ">Pay »</button>
    </div>
 <div class='form-row'>
        <div class='col-md-5 error form-group hide'>
            <div class='alert-danger alert'>Please correct the errors and try
                again.</div>
        </div>


    </div>


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