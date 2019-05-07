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
			<form accept-charset="UTF-8" method="post" action="{{ route('login') }}" autocomplete="off">
				<input name="_token" value="{{ csrf_token() }}" type="hidden"/>
				<div class="text-center">
					<div class="label " style="margin: 10px;">
						<img src="{{ asset("themes/limitless/images/client-view/logo.png") }}" alt="Life Process Alcohol Program"/>
					</div>
					<h5 class="content-group">
						Login With E-Mail
						<small class="display-block">
							Credentials
						</small>
					</h5>
				</div>
				@include('limitless.partials.notifications')
				<br/>

				<div class="form-group has-feedback has-feedback-left {{ $errors->has('email') ? 'has-error' : '' }}">
					<input type="text" class="form-control" placeholder='E-Mail Address' name="email" value="{{$email}}"/>
						{!! ($errors->
							has('email') ? $errors->
							first('email', '
								<p class="text-danger">
									:message
								</p>
								') : '') !!}
					<div class="form-control-feedback">
						<i class="icon-user-check text-muted">
						</i>
					</div>
				</div>
				<div class="form-group has-feedback has-feedback-left {{ $errors->has('password') ? 'has-error' : '' }}">
					<input type="password" class="form-control" placeholder="Password"  name="password"/>
					{!! ($errors->
						has('password') ? $errors->
						first('password', '
							<p class="text-danger">
							:message
							</p>
							') : '') !!}
					<div class="form-control-feedback">
						<i class="icon-user-lock text-muted">
						</i>
					</div>
				</div>
				<div class="form-group login-options">
					<div class="row">
						<div class="col-sm-6">
							<label class="checkbox-inline">
								<input type="checkbox" name="remember" class="styled" {{ old('remember') ? 'checked="checked"' : '' }}/>
								&nbsp;Remember
							</label>
						</div>
						<div class="col-sm-6 text-right">
							<a href="{{ route('password.request') }}">
								Forgot Password?
							</a>
						</div>
						<div class="col-sm-6 text-right">
						</div>
					</div>
				</div>
				<div class="form-group">
					<div style="clear: both;"></div>
					<div class="form-group col-sm-7">
						<button type="submit" class="btn bg-indigo-400 btn-block" id="sign_in">Sign In <i class="icon-circle-right2"></i></button>
					</div>
				</div>
				<div class="clearfix">
				</div>
				<br>
				<p>If you signed up to the Life Process Program <strong>before 5th January 2018</strong>, please login <a href="https://lpp.lifeprocessprogram.com/login.php">here</a>.</p>
			</form>
		</div>
	</div>
</div>

<div id="SignUp" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-body" style="overflow: hidden; margin-top: 50px;">
				{{-- start here --}}
				<div class="register-form-container">
					<div class="small-12 medium-12  medium-offset-2 large-6 large-offset-3 columns register-form">
						<center>
							<img src="{{ asset("themes/limitless/images//logo-gray.png") }}" alt="Life Process Alcohol Program"/>
						</center>
						<button type="button" class="close" data-dismiss="modal">
							<img src="{{ asset('themes/limitless/images/close.png') }}"/>
						</button>

						<h3 class="">
							Account Setup
						</h3>
						<form method="POST" id="Register" class="row">
							{{ csrf_field() }}
							<div class="small-12 medium-6 columns">
								{!! Form::text('first', null, ['class' =>
									'form-control','placeholder'=>
									'First name', 'id' =>
									'first']) !!}
								<span class="text-danger">
									<strong id="first-error">
									</strong>
								</span>
							</div>
							<div class="small-12 medium-6 columns">
								{!! Form::text('last', null, ['class' =>
									'form-control','placeholder'=>
									'Last name','id'=>
									'last']) !!}
								<span class="text-danger">
									<strong id="last-error">
									</strong>
								</span>
							</div>
							<div class="small-12 columns">
								{!! Form::text('email', null, ['class' =>
									'form-control','placeholder'=>
									'Email Address', 'id' =>
									'email','required']) !!}
								<span class="text-danger">
									<strong id="email-error">
									</strong>
								</span>
							</div>
							<div class="small-12 columns">
								{!! Form::password('password', ['class' =>
									'form-control','placeholder'=>
									'Password', 'id' =>
									'password','required']) !!}
								<span class="text-danger">
									<strong id="password-error">
									</strong>
								</span>
							</div>
							<div class="small-12 columns">
								<input type="hidden" name="program_id" value="{{ $program }}"/>
								<span class="text-danger">
									<strong id="program_id-error">
									</strong>
								</span>
							</div>
							<div class="small-12 columns">
								<p>
									Who will be paying for your subscription?
								</p>
							</div>
							<fieldset class="small-12 columns">
								<input id="myself" type="radio" name="payment" value="Myself" required checked/>
								<label for="myself">
									Myself
								</label>
								<br class="hide-for-medium"/>
								<input id="afriend" type="radio" name="payment" value="A Friend"/>
								<label for="afriend">
									A Friend
								</label>
								<br class="hide-for-medium"/>
								<input id="afamilymember" type="radio" name="payment" value="A Family Member"/>
								<label for="afamilymember">
									A Family Member
								</label>
							</fieldset>
							<div class="small-12 columns">
								<center>
									<a class="form-button"  id="submitForm">
										Continue
									</a>
								</center>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="SignUp_step1" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-body" style="overflow: hidden;">
				<div class="col-md-offset-1 col-md-10">
					<div class="background">
					</div>
					{{-- second step  --}}
					<div class="register-form-container">
						<div class="small-12 medium-12  medium-offset-2 large-6 large-offset-3 columns register-form">
							<center>
								<img src="{{ asset("themes/limitless/images//logo-gray.png") }}" alt="Life Process Alcohol Program"/>
							</center>
							<button type="button" class="close" data-dismiss="modal">
								<img src="{{ asset('themes/limitless/images/close.png') }}"/>
							</button>
							<ul id="payment-tabs" class="tabs" data-tabs>
								<li class="tabs-title {{ (isset($error) && $error=='paypal') ? '' : 'is-active' }} ">
									<a href="#credit-card" aria-selected="true" id="typestripe">
										Credit Card
									</a>
								</li>
								<li class="tabs-title {{ (isset($error) && $error=='paypal') ? 'is-active' : '' }} " id='#paypal_li'><a href="#paypal" id="typepaypal"><img src="{{ asset("themes/limitless/images/paypal.png") }}" alt="paypal" class="form-control"/></a>
								</li>
							</ul>
							@if (session('paymenterror'))
								<div class="row" id='payment_error'>
									<div class="col-sm-12">
										<div class="alert alert-danger">
											<button type="button" class="remove_error">&times;</button>
											{{ session('paymenterror') }}
										</div>
									</div>
								</div>
							@endif
							<div data-tabs-content="payment-tabs">
								<div id="credit-card" class="tabs-panel {{ (isset($error) && $error=='paypal') ? '' : 'is-active' }} ">
									<form class="" id="stripeform">
										<input type="hidden" name="payment_type" id="payment_type" value="stripe"/>
										<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
										<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
										<input type="hidden" name="program" class="program" value="{{$program}}"/>
										<input type="hidden" name="user" class="user" value="{{$user}}"/>
										<div class="row">
											<div class="small-12 columns" id="cardname">
												<div class="input-group">
													<span class="input-group-label">
														<i class="fa fa-credit-card">
														</i>
													</span>
													{!! Form::text('card_name', null ,['class' =>
														'form-control','placeholder'=>
														'Card Name', 'id' =>
														'card_name']) !!}
														{{-- <input type="number" placeholder="Card Number."/>--}}
												</div>
											</div>
											<div class="small-12 columns">
												<div class="input-group">
													<span class="input-group-label">
														<i class="fa fa-credit-card">
														</i>
													</span>
													{!! Form::text('card_number', null ,['class' =>
														'form-control','placeholder'=>
														'Card Number', 'id' =>
														'card_number']) !!}
												</div>
											</div>
											<div class="small-12 medium-6 columns">
												<div class="input-group" >
													<span class="input-group-label" style='margin-bottom:15px;'>
														<i class="fa fa-calendar-o">
														</i>
													</span>
													{!! Form::selectRange('month', 1, 12,array('class' => 'form-control')) !!}
													{!! Form::selectRange('year', 2017, 2031,array('class' => 'form-control')) !!}
												</div>
											</div>
											<div class="small-12 medium-6 columns">
												<div class="input-group">
													<span class="input-group-label">
														<i class="fa fa-lock">
														</i>
													</span>
													{!! Form::text('CVV_number', null ,['class' =>
														'form-control','placeholder'=>
														'CVV Number', 'id' =>
														'CVV_number']) !!}
												</div>
											</div>
											<div class="small-12 columns">
												<p>
													<a href="#">
														Apply Coupon Code
													</a>
												</p>
											</div>
											<div class="small-12 columns">
												<center>
													<a class="form-button" id="stripepay">
														Pay
													</a>
												</center>
												<div class='loader' style='display: none;'>
													Processing your payment...
													<img src='{{ asset("themes/limitless/images/ajax-loader.gif") }}' width='32px' height='32px'/>
												</div>
											</div>
										</div>
									</form>
								</div>
								<div id="paypal" class="tabs-panel {{ (isset($error) && $error=='paypal') ? 'is-active' : '' }} ">
									<form class="" id="paypalform">
										<input type="hidden" name="payment_type" id="payment_type" value="paypal"/>
										<input type="hidden" name="_token" value="{{ csrf_token() }}"/>
										<input type="hidden" name="program" class="program" value="{{$program}}"/>
										<input type="hidden" name="user" class="user" value="{{$user}}"/>
										<center>
											<a class="form-button" id="paypalpay">
												Pay
											</a>
										</center>
										<div class='loader' style='display: none;'>
											Processing your payment...
											<img src='{{ asset("themes/limitless/images/ajax-loader.gif") }}' width='32px' height='32px'/>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div id="SignUp_step3" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">
					×
				</button>
				<h3 class="modal-title text-center primecolor">
					Sign Up
				</h3>
			</div>
			<div class="modal-body" style="overflow: hidden;">
				<div id="success-msg" class="hide">
					<div class="alert alert-info alert-dismissible fade in" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">
								×
							</span>
						</button>
						<strong>
							Success!
						</strong>
						Check your mail for login confirmation!!
					</div>
				</div>
				<div class="col-md-offset-1 col-md-10">
					<fieldset class="fail_stripe">
						<a href="#" class="previous">
							<i class="fa fa-angle-left" aria-hidden="true">
							</i>
						</a>
						<p class="content">
							<img src="{{ asset('themes/limitless/images/excl.png') }}"/>
							<span>
								Unfortunately we have been unable to process your credit card transaction.
								<br/>
								<br/>
								Do you want to try using PayPal?
							</span>
						</p>
						<p class="clearfix">
						</p>
						<p class="text-center">
							Alternatively, you may want to contact your bank and ask them to help
						</p>
					</fieldset>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
	@if(isset($register) && $register=='open')
		$("#SignUp").modal({backdrop: true});
	@endif
	@if($error=='paypal')
		$('#SignUp').modal('hide');
		$("#SignUp_step1").modal({backdrop: true});
	@endif
	jQuery(document).ready(function($) {
		$("#sign_in").on('click',function(e){
			if(grecaptcha.getResponse() == ''){
				$('.captcha_error').html('please verify captcha');
				return false;
			}
			return true;
		});
	});

	jQuery('.remove_error').on('click', function(event) {
		event.preventDefault()
		jQuery('#payment_error').remove();
	});

	jQuery('.app > .app-content > .box').append('<div class="overlay ajax-overlay"><i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i></div>');
	var $loading = jQuery('.ajax-overlay').hide();
	jQuery(document).ajaxStart(function () {
		$loading.show();
	}).ajaxStop(function () {
		$loading.hide();
	});

	jQuery(function() {
		jQuery( "#date_of_birth" ).datetimepicker({
			timepicker:false,
			format:'m/Y',
			formatDate:'m/Y',

		});
	});

	$(document).ready(function() {
		$(document).on('change', '#card_number', function() {
			var number = $(this).val();
			//alert(number);
			var cardtype="";
			// visa
			var re = new RegExp("^4");

			if (number.match(re) != null)
				cardtype="Visa";

			// Mastercard
			// Updated for Mastercard 2017 BINs expansion
			if (/^(5[1-5][0-9]{14}|2(22[1-9][0-9]{12}|2[3-9][0-9]{13}|[3-6][0-9]{14}|7[0-1][0-9]{13}|720[0-9]{12}))$/.test(number))
				cardtype="Mastercard";

			// AMEX

			re = new RegExp("^3[47]");
			if (number.match(re) != null)
				cardtype="AMEX";

			// Discover
			re = new RegExp("^(6011|622(12[6-9]|1[3-9][0-9]|[2-8][0-9]{2}|9[0-1][0-9]|92[0-5]|64[4-9])|65)");
			if (number.match(re) != null)
				cardtype="Discover";

			// Diners
			re = new RegExp("^36");
			if (number.match(re) != null)
				cardtype="Diners";

			// Diners - Carte Blanche
			re = new RegExp("^30[0-5]");
			if (number.match(re) != null)
				cardtype="Diners - Carte Blanche";

			// JCB
			re = new RegExp("^35(2[89]|[3-8][0-9])");
			if (number.match(re) != null)
				cardtype="JCB";

			// Visa Electron
			re = new RegExp("^(4026|417500|4508|4844|491(3|7))");
			if (number.match(re) != null)
				cardtype="Visa Electron";

			//alert(cardtype);
			jQuery('#card_type').val(cardtype);
			document.getElementById("expiry_date").focus();
		});
	});
	jQuery("#typepaypal").click(function(){
		document.getElementById("payment_type").value = "paypal";
	});

	jQuery("#typestripe").click(function(){
		document.getElementById("payment_type").value = "stripe";
	});

	$('body').on('click', '#submitForm', function(){
		var registerForm = $("#Register");
		var formData = registerForm.serialize();
		$( '#first-error' ).html( "" );
		$( '#last-error' ).html( "" );
		$( '#email-error' ).html( "" );
		$( '#password-error' ).html( "" );

		$.ajax({
			url:'/registermodel',
			type:'POST',
			data:formData,
			success:function(data) {
				console.log(data);
				if(data.errors) {
					if(data.errors.name){
						$( '#name-error' ).html( data.errors.name[0] );
					}
					if(data.errors.email){
						$( '#email-error' ).html( data.errors.email[0] );
					}
					if(data.errors.password){
						$( '#password-error' ).html( data.errors.password[0] );
					}
					if(data.errors.last){
						$( '#last-error' ).html( data.errors.last[0] );
					}
					if(data.errors.first){
						$( '#first-error' ).html( data.errors.first[0] );
					}

				}
				if(data.success) {
					//console.log(data.program_id);
					// $('#success-msg').removeClass('hide');
					// console.log(data.success.program_id);
					$('#SignUp').modal('hide');
					var program=data.program_id;
					jQuery('.program').val(program);
					var user=data.user_id;
					jQuery('.user').val(user);
					$('#SignUp_step1').modal('show');
						//$('#success-msg').addClass('hide');
				}
			},
		});
	});
	$('body').on('click', '#stripepay', function(){
		// jQuery('#loader').show();
		var registerForm = $("#stripeform");
		var formData = registerForm.serialize();
		//console.log(formData);
		$('.loader').show();
		payment(formData);

	});

	$('body').on('click', '#paypalpay', function(){
		// jQuery('#loader').show();
		var registerForm = $("#paypalform");
		var formData = registerForm.serialize();
		//console.log(formData);
		$('.loader').show();
		payment(formData);
	});

	function payment(data)
	{
		var formData = data;
		console.log(formData);
			$.ajax({
			url:'/registermodelfirst',
			type:'POST',
			data:data,
			success:function(data) {

					if(data.errors) {

						$('.loader').hide();
						document.getElementById('error').style.display = 'block';
						$('#errormsg').html(data.errors);
						$('#SignUp_step1').modal('show');
						$('#submitForm1').modal('hide');
						$('#SignUp_step4').modal('hide');
						$('#SignUp').modal('hide');
						//jQuery('#SignUp_step1').css("display", "none");
						document.getElementById('SignUp_step1').style.display = 'block';
						$('#SignUp_step3').modal('hide'); // model for show error in stipe
					   // location.reload();
						//$('#SignUp_step1').modal('show');
					}
					if(data.success) {

						$('#SignUp_step4').modal('hide');
						$('#SignUp').modal('hide');
						$('#SignUp_step1').modal('hide');
						$('#SignUp_step3').modal('hide');
						if(data.paypal_link=='')
						{
						 var redirect_link = data.stripe_link;
						 window.location.href = redirect_link;
					 }
					 else
					 {
						var redirect_link = data.paypal_link;
						window.location.href = redirect_link;
					}
				}
			},
		});
	}
	$('body').on('click', '#back', function(){
		$('#SignUp_step1').modal('hide');
	});

	$('body').on('click', '.close', function(){
		location.reload();
		$('#SignUp_step4').modal('hide');
		$('#SignUp').modal('show');
		$('#SignUp_step1').modal('hide');
		$('#SignUp_step3').modal('hide');
	});

	$('body').on('click', '#afriend', function(){
		jQuery('#cardname').show();
		jQuery('#card_name').show();
	});

	$('body').on('click', '#afamilymember', function(){
		jQuery('#cardname').show();
		jQuery('#card_name').show();
	});

</script>
@endpush
