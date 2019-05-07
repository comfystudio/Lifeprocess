<?php //CODE FOR GA CONVERSION TRACKING
                                        // $currPath = $_SERVER['REQUEST_URI'];
                                        // $explodePath = explode("?", $currPath, 2);
                                        // $stripPath = $explodePath[0];
                                        //  echo $stripPath;exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Life Process</title>
    <link rel="stylesheet" href="{{ elixir('themes/limitless/css/bootstrap_register.css') }}">
    <link rel="stylesheet" href="{{ elixir('themes/limitless/css/appv2.css') }}">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
      #SignUp_step1
      {
        display: none;
    }
    #SignUp_step1 .tabs
    {
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
    #card_holder
    {
        display: none;
    }

    #error
    {
        display: none;
    }
    #stripe_error
    {
        display: none;
    }
    .text-danger
    {
        margin-bottom:10px;
    }
    .loader
    {
        margin-top: 10px;
    }
    .cardholdername
    {
        display: none;
    }
    #cardicon
    {
        display: none;
    }
</style>

<!-- Facebook Pixel Code -->
<script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '207390333408397');
  fbq('track', 'PageView');
   fbq('track', 'CompleteRegistration');
fbq('track', 'Purchase', {value: '0.00', currency: 'USD'});
</script>
<noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=207390333408397&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->


</head>
<body>
    <div class="container-fluid header">
        <div class="row">
            <div class="col-sm-12">
                <div class="container hide-for-large">
                    <div class="row">
                        <div class="col-10">
                            <img src="{{ asset("themes/limitless/images/logov2.png") }}" alt="Logo"/>
                        </div>
                        <div class="col-2 text-right">
                            <i class="fa fa-align-justify"></i>
                            <div class="navigation-overlay">
                                <div class="navigation-overlay-container">
                                    <!-- <nav>
                                        <ul>
                                            <li><a href="https://lifeprocessprogram.com/">Home</a></li>
                                            <li><a href="https://lifeprocessprogram.com/lp-blog/">Blog</a></li>
                                            <li><a href="https://lifeprocessprogram.com/faqs/">FAQs</a></li>
                                            <li><a href="https://lifeprocessprogram.com/testimonials-2/">Testimonials</a></li>
                                            <li><a href="https://lifeprocessprogram.com/contact/">Contact</a></li>
                                            <li><a href="https://lifeprocessprogram.com/alcohol-addiction/"><strong>Alcohol</strong> Program</a></li>
                                            <li><a href="https://lifeprocessprogram.com/drug-addiction/"><strong>Drugs</strong> Program</a></li>
                                            <li><a href="https://lifeprocessprogram.com/food-addiction/"><strong>Food</strong> Program</a></li>
                                            <li><a href="https://lifeprocessprogram.com/sex-addiction/"><strong>Sex</strong> Program</a></li>
                                            <li><a href="https://lifeprocessprogram.com/porn-addiction/"><strong>Pornography</strong> Program</a></li>
                                            <li><a href="https://lifeprocessprogram.com/gambling-addiction/"><strong>Gambling</strong> Program</a></li>
                                            <li><a href="https://lifeprocessprogram.com/login.php">Login</a></li>
                                            <li><a href="">Close</a></li>
                                        </ul>
                                    </nav> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container show-for-large">
                    <div class="row">
                        <div class="col-sm-4">
                           <img src="{{ asset("themes/limitless/images/logov2.png") }}" alt="Logo"/>
                       </div>
                       <div class="col-sm-6">
                        <!-- <nav>
                            <ul>
                                <li><a href="https://lifeprocessprogram.com/">HOME</a></li>
                                <li><a href="https://lifeprocessprogram.com/lp-blog/">BLOGS</a></li>
                                <li><a href="https://lifeprocessprogram.com/faqs/">FAQS</a></li>
                                <li><a href="https://lifeprocessprogram.com/testimonials-2/">TESTIMONIALS</a></li>
                                <li><a href="https://lifeprocessprogram.com/contact/">CONTACT</a></li>
                            </ul>
                        </nav> -->
                    </div>
                    <div class="col-sm-2 text-right">
                        <!-- <nav>
                            <ul>
                                <li><a href="https://lifeprocessprogram.com/login.php">Login</a></li>
                            </ul>
                        </nav> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid banner" id="SignUp">
    <div class="row">
        <div class="col-sm-12">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-8 register">
                        <form method="POST" id="Register">
                            {{ csrf_field() }}
                            <span>Step 1 of 2</span>
                            <h3>Create your Account</h3>
                            <div class="row">
                                <div class="col-sm-6">
                                    <span class="text-danger">
                                        <strong id="first-error">
                                        </strong>
                                    </span>
                                    {!! Form::text('first', null, ['class' =>
                                        'form-control','placeholder'=>
                                        'First Name', 'id' =>
                                        'first']) !!}
                                </div>
                                <div class="col-sm-6">
                                    <span class="text-danger">
                                        <strong id="last-error">
                                        </strong>
                                    </span>
                                    {!! Form::text('last', null, ['class' =>
                                        'form-control','placeholder'=>
                                        'Last Name','id'=>
                                        'last']) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                      <span class="text-danger">
                                        <strong id="email-error">
                                        </strong>
                                    </span>
                                    {!! Form::text('email', null, ['class' =>
                                        'form-control','placeholder'=>
                                        'Email', 'id' =>
                                        'email','required']) !!}

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                     <span class="text-danger">
                                        <strong id="password-error">
                                        </strong>
                                    </span>

                                    {!! Form::password('password', ['class' =>
                                        'form-control','placeholder'=>
                                        'Password', 'id' =>
                                        'password','required']) !!}

                                </div>
                            </div>
                                <input type="hidden" name="program_id" value="{{ $program }}"/>
                                <p>Who will be paying for your subscription?</p>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input id="myself" type="radio" name="payment" value="Myself" required checked><label for="myself">&nbsp;Myself&nbsp;&nbsp;&nbsp;</label>
                                        <br class="hide-for-medium">
                                        <input id="afriend" type="radio" name="payment" value="A Friend"><label for="afriend">&nbsp;A Friend&nbsp;&nbsp;&nbsp;</label>
                                        <br class="hide-for-medium">
                                        <input id="afamilymember" type="radio" name="payment" value="A Family Member"><label for="afamilymember">&nbsp;A Family Member&nbsp;&nbsp;&nbsp;</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <a class="form-button" id="submitForm" href="#">
                                         Continue
                                     </a>
                                 </div>
                             </div>
                         </form>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </div>
 <div class="container-fluid banner" id="SignUp_step1">
    <div class="row">
        <div class="col-sm-12">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-md-8 register">
                                    <button class="back-button" type="button" id="backtofirst">
                                        <a href="#" >&lt;</a>
                                    </button>
                                    <form class="payment">
                                        <span>Step 2 of 2</span>
                                        <h3>Set up your Payment</h3>
                                        <span class="display-block">Cancel within 30 days to get a full refund.</span>
                                        <span class="margin-bottom-display-block">No commitments. Cancel at any time.</span>
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="credit-card-tab" data-toggle="tab" href="#credit-card" role="tab" aria-controls="credit-card" aria-selected="true">Credit Card</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" id="paypal-tab" data-toggle="tab" href="#paypal" role="tab" aria-controls="paypal" aria-selected="false"><img src="{{ asset('themes/limitless/images/paypal_register.png') }}" alt="PayPal"/></a>
                                            </li>
                                        </ul>
                                    </form>

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

                                    <div class="tab-content" id="myTabContent">
                                     <div class="row" id="stripe_error">
                                        <div class="col-sm-12">
                                            <div class="col-md-12">
                                                <div class="alert alert-danger" id="errormsg">
                                                   <button type="button" id="delete_stripe_error">&times;</button>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                   <?php //CODE FOR GA CONVERSION TRACKING
                                        $currPath = $_SERVER['REQUEST_URI'];
                                        $explodePath = explode("?", $currPath, 2);
                                        $stripPath = $explodePath[0];
                                        // echo $stripPath;
                                        if($stripPath == "/program/drugs"){
                                            $btnTrackClass = "drugbtnsold";
                                        }elseif($stripPath == "/program/alcohol"){
                                            $btnTrackClass = "alcoholbtnsold";
                                        }elseif($stripPath == "/program/food"){
                                            $btnTrackClass = "foodbtnsold";
                                        }elseif($stripPath == "/program/sex"){
                                            $btnTrackClass = "sexbtnsold";
                                        }elseif($stripPath == "/program/pornography"){
                                            $btnTrackClass = "pornographybtnsold";
                                        }elseif($stripPath == "/program/gambling"){
                                            $btnTrackClass = "gamblingbtnsold";
                                        }elseif($stripPath == "/program/love-relationships"){
                                            $btnTrackClass = "loverelationshipsbtnsold";
                                        }elseif($stripPath == "/program/family"){
                                            $btnTrackClass = "familybtnsold";
                                        }elseif($stripPath == "/program/social-media"){
                                            $btnTrackClass = "socialmediabtnsold";
                                        }elseif($stripPath == "/program/shopping"){
                                            $btnTrackClass = "shoppingbtnsold";
                                        }
                                        // echo $stripPath;
                                    ?>
                                   <div class="tab-pane fade show active" id="credit-card" role="tabpanel" aria-labelledby="credit-card-tab">
                                    <form class="payment" id="stripeform">
                                        <input type="hidden" name="payment_type" id="payment_type" value="stripe"/>
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                        <input type="hidden" name="program" class="program" value="{{$program}}"/>
                                        <input type="hidden" name="user" class="user" value="{{$user}}"/>

                                        <div class="row" id="cardname">
                                            <div class="col-sm-12 input-group" >
                                                <span class="input-group-label" id="cardicon"><i class="fa fa-credit-card"></i></span>
                                                  {!! Form::text('card_holder', null ,['class' =>
                                                    'form-control','placeholder'=>
                                                    'Card Holder Name', 'id' =>
                                                    'card_holder']) !!}
                                            </div>
                                        </div>

                                            <br>
                                            <div class="row">
                                                <div class="col-sm-12 input-group">
                                                    <span class="input-group-label"><i class="fa fa-credit-card"></i></span>
                                                    <input type="text" placeholder="Card Number" name="card_number">
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 input-group">
                                                    &nbsp; Expiry Month
                                                    {!! Form::selectRange('month', 1, 12,array('class' => '')) !!}
                                                    {{-- <span class="input-group-label"><i class="fa fa-calendar-o"></i></span> --}}
                                                    {{-- <input type="text" placeholder="Expiry Date"> --}}
                                                </div>
                                                <div class="col-sm-6 input-group">
                                                    &nbsp; Expiry Year
                                                    {!! Form::selectRange('year', 2018, 2031,array('class' => '')) !!}
                                                    {{-- <span class="input-group-label"><i class="fa fa-calendar-o"></i></span> --}}
                                                    {{-- <input type="text" placeholder="Expiry Date"> --}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6 input-group">
                                                    <span class="input-group-label"><i class="fa fa-lock"></i></span>
                                                    <input type="password" placeholder="CCV" name="CVV_number">
                                                </div>
                                            </div>
                                                    {{--   <div class="row">
                                                <div class="col-sm-12">
                                                    <a class="form-button" href="welcomev2.html">Pay</a>
                                                </div>
                                            </div> --}}
                                            <div class="small-12 columns">
                                                <center>
                                                    <a class="form-button <?php echo $btnTrackClass; ?>" id="stripepay" href="#">
                                                        Pay
                                                    </a>
                                                </center>
                                            </div>
                                            <div class='loader' style='display: none;'>
                                                Processing your payment...
                                                <img src='{{ asset("themes/limitless/images/ajax-loader.gif") }}' width='32px' height='32px'/>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="paypal" role="tabpanel" aria-labelledby="paypal-tab">
                                        <form class="payment" id="paypalform">
                                            <input type="hidden" name="payment_type" id="payment_type" value="paypal"/>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                            <input type="hidden" name="program" class="program" value="{{$program}}"/>
                                            <input type="hidden" name="user" class="user" value="{{$user}}"/>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <p>To finish sign-up, click the <strong>Continue to PayPal</strong> button and log into PayPal using your email and password.</p>
                                                    {{-- <a class="form-button" href="welcomev2.html">Continue to PayPal</a> --}}
                                                    <a class="form-button <?php echo $btnTrackClass; ?>" id="paypalpay" href="#">
                                                       Continue to PayPal
                                                   </a>
                                               </div>
                                               <div class='loader' style='display: none;'>
                                                Processing your payment...
                                                <img src='{{ asset("themes/limitless/images/ajax-loader.gif") }}' width='32px' height='32px'/>
                                            </div>
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
    <footer class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12 text-right">
                            <p>Copyright &copy; Life Process Program</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-39642390-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'UA-39642390-1', {
          'linker': {
            'accept_incoming': true
          }
        });
    </script>
    <script src="{{ elixir('themes/limitless/js/jquery_register.js') }}"></script>
    <script src="{{ elixir('themes/limitless/js/app.js') }}"></script>
    <script src="{{ elixir('themes/limitless/js/bootstrap_register.js') }}"></script>
    <script type="text/javascript">

        jQuery(document).ready(function($) {
            jQuery('.drugbtnsold').click(function(ga) {
                gtag('event', 'Drug_Program_Sale', { // Action
                  'name': 'Drug Signup', // Name
                  'event_category': 'Drug Sale', // Category
                  'event_label': 'Drug Program Sold' // Label
                }); 
            });
            jQuery('.alcoholbtnsold').click(function(ga) {
                gtag('event', 'Alcohol_Program_Sale', { // Action
                  'name': 'Alcohol Signup', // Name
                  'event_category': 'Alcohol Sale', // Category
                  'event_label': 'Alcohol Program Sold' // Label
                }); 
                console.log('Alcohol Clicked');
            });
            jQuery('.foodbtnsold').click(function(ga) {
                gtag('event', 'Food_Program_Sale', { // Action
                  'name': 'Food Signup', // Name
                  'event_category': 'Food Sale', // Category
                  'event_label': 'Food Program Sold' // Label
                }); 
            });
            jQuery('.sexbtnsold').click(function(ga) {
                gtag('event', 'Sex_Program_Sale', { // Action
                  'name': 'Sex Signup', // Name
                  'event_category': 'Sex Sale', // Category
                  'event_label': 'Sex Program Sold' // Label
                }); 
            });
            jQuery('.pornographybtnsold').click(function(ga) {
                gtag('event', 'Pornography_Program_Sale', { // Action
                  'name': 'Pornography Signup', // Name
                  'event_category': 'Pornography Sale', // Category
                  'event_label': 'Pornography Program Sold' // Label
                }); 
            });
            jQuery('.gamblingbtnsold').click(function(ga) {
                gtag('event', 'Gambling_Program_Sale', { // Action
                  'name': 'Gambling Signup', // Name
                  'event_category': 'Gambling Sale', // Category
                  'event_label': 'Gambling Program Sold' // Label
                }); 
            });
            jQuery('.loverelationshipsbtnsold').click(function(ga) {
                gtag('event', 'Love_Relationships_Program_Sale', { // Action
                  'name': 'Love Relationships Signup', // Name
                  'event_category': 'Love Relationships Sale', // Category
                  'event_label': 'Love Relationships Program Sold' // Label
                }); 
            });
            jQuery('.familybtnsold').click(function(ga) {
                gtag('event', 'Family_Program_Sale', { // Action
                  'name': 'Family Signup', // Name
                  'event_category': 'Family Sale', // Category
                  'event_label': 'Family Program Sold' // Label
                }); 
            });
            jQuery('.socialmediabtnsold').click(function(ga) {
                gtag('event', 'Social_Media_Program_Sale', { // Action
                  'name': 'Social Media Signup', // Name
                  'event_category': 'Social Media Sale', // Category
                  'event_label': 'Social Media Program Sold' // Label
                }); 
            });
            jQuery('.shoppingbtnsold').click(function(ga) {
                gtag('event', 'Shopping_Program_Sale', { // Action
                  'name': 'Shopping Signup', // Name
                  'event_category': 'Shopping Sale', // Category
                  'event_label': 'Shopping Program Sold' // Label
                }); 
            });
            
        });

        jQuery("#backtofirst").on('click',function(e){
            jQuery('#SignUp').show();
            jQuery("#SignUp_step1").hide();
        });

       jQuery("#first").on('keyup',function(e){
            var name= jQuery('#first').val();
            jQuery('#card_holder').val(name);
        });

      @if(isset($register) && $register=='open')
      // jQuery("#SignUp").modal({backdrop: true});
      @endif
      @if($error=='paypal')
        //jQuery('#SignUp').modal('hide');
        //jQuery("#SignUp_step1").modal({backdrop: true});
        jQuery('#SignUp').hide();
        jQuery("#SignUp_step1").show();
        @endif
        jQuery(document).ready(function($) {
            jQuery("#sign_in").on('click',function(e){
                if(grecaptcha.getResponse() == ''){
                    jQuery('.captcha_error').html('please verify captcha');
                    return false;
                }
                return true;
            });
        });

        jQuery('.remove_error').on('click', function(event) {
              // event.preventDefault()
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
            $( "#date_of_birth" ).datetimepicker({
                timepicker:false,
                format:'m/Y',
                formatDate:'m/Y',

            });
        });

        jQuery(document).ready(function() {
            jQuery(document).on('change', '#card_number', function() {
                var number = jQuery(this).val();
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

        jQuery('body').on('click', '#submitForm', function(){
        //console.log('hello');
        var registerForm = jQuery("#Register");
        var formData = registerForm.serialize();
        if(document.getElementById("myself").checked == true)
        {
            var name= jQuery('#first').val();
        }
        if(document.getElementById("afriend").checked == true)
        {
            var name= '';
        }
        if(document.getElementById("afamilymember").checked == true)
        {
            var name= '';
        }
        // console.log(name);
        jQuery( '#first-error' ).html( "" );
        jQuery( '#last-error' ).html( "" );
        jQuery( '#email-error' ).html( "" );
        jQuery( '#password-error' ).html( "" );

        $.ajax({
            url:'/registermodel',
            type:'POST',
            data:formData,
            success:function(data) {
                //console.log(data);
                if(data.errors) {
                    if(data.errors.name){
                        jQuery( '#name-error' ).html( data.errors.name[0] );
                    }
                    if(data.errors.email){
                        jQuery( '#email-error' ).html( data.errors.email[0] );
                    }
                    if(data.errors.password){
                        jQuery( '#password-error' ).html( data.errors.password[0] );
                    }
                    if(data.errors.last){
                        jQuery( '#last-error' ).html( data.errors.last[0] );
                    }
                    if(data.errors.first){
                        jQuery( '#first-error' ).html( data.errors.first[0] );
                    }
                }
                if(data.success) {

                    jQuery('#SignUp').hide();
                    var program=data.program_id;
                    jQuery('.program').val(program);
                    var user=data.user_id;
                    jQuery('.user').val(user);
                    jQuery('#SignUp_step1').show();
                    jQuery('#card_holder').val(name);
                 }
            },
        });
    });
        jQuery('body').on('click', '#stripepay', function(){
        // jQuery('#loader').show();
        var registerForm = jQuery("#stripeform");
        var formData = registerForm.serialize();
        //console.log(formData);
        jQuery('.loader').show();
        payment(formData);
    });

        jQuery('body').on('click', '#paypalpay', function(){
        // jQuery('#loader').show();
        var registerForm = jQuery("#paypalform");
        var formData = registerForm.serialize();
        //console.log(formData);
        jQuery('.loader').show();
        payment(formData);
    });

        function payment(data)
        {
            var formData = data;
        //console.log(formData);
        $.ajax({
            url:'/registermodelfirst',
            type:'POST',
            data:data,
            success:function(data) {

                if(data.errors) {
                 show_stripe_error(data.errors);
                 jQuery('.loader').hide();
                 jQuery('#SignUp_step1').show();
                 jQuery('#SignUp').hide();
             }
             if(data.success) {
                jQuery('#SignUp').hide();
                jQuery('#SignUp_step1').hide();
                jQuery('#SignUp_step3').show();
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
    jQuery('body').on('click', '#back', function(){
        jQuery('#SignUp_step1').hide();
    });

    jQuery('body').on('click', '.close', function(){
        location.reload();
        jQuery('#SignUp_step4').hide();
        jQuery('#SignUp').show();
        jQuery('#SignUp_step1').hide();
        jQuery('#SignUp_step3').hide();
    });

    jQuery('body').on('click', '#myself', function(){
        jQuery('#cardname').show();
        document.getElementById('cardname').style.display = 'none';
        document.getElementById('cardicon').style.display = 'none';
        var name= jQuery('#first').val();
        jQuery('#card_holder').val(name);
        jQuery('#card_holder').hide();
    });

    jQuery('body').on('click', '#afriend', function(){
        jQuery('#cardname').show();
        document.getElementById('cardname').style.display = 'block';
        document.getElementById('cardicon').style.display = 'block';
        var name='';
        jQuery('#card_holder').val(name);
        jQuery('#card_holder').show();
    });

    jQuery('body').on('click', '#afamilymember', function(){
        var name= '';
        jQuery('#card_holder').val(name);
        jQuery('#cardname').show();
        document.getElementById('cardname').style.display = 'block';
        document.getElementById('cardicon').style.display = 'block';
        jQuery('#card_holder').show();
    });

    function remove_stripe_error()
    {
        jQuery('#errormsg').html('');
        jQuery('#stripe_error').hide();
    }
    function show_stripe_error(error)
    {
        jQuery('#stripe_error').show();
        jQuery('#errormsg').html(error);
    }
    jQuery('#delete_stripe_error').on('click', function(event) {
              // event.preventDefault()
              remove_stripe_error();
          });
      </script>
  </body>
  </html>