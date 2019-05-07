<!doctype html>
<html class="no-js" lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Life Process</title>
      <link rel="stylesheet" href="{{ elixir('themes/limitless/css/register_all.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
      #step4,#step5,#step6,#step7
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
                {{--<img src="img/logov2.png" alt="Logo"/>--}}
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
                <form>
                  <div class="welcome-container">
                    <h3>Welcome, {{$user->first_name or ''}}</h3>
                    <p>Thank you for signing up! You are about to begin a remarkable journey!</p>
                    <p>It is important for you to know that the independent, self-motivated cure for {{$program}} addiction is possible. You can fight your own {{$program}} addictions and learn to live an addiction free life once more.</p>
                    <p>Recovery is about purpose and meaning in life, not “sobriety” and meetings. It's time for you to take control of your recovery.</p>
                    {{--    <img src="img/stanton.png" alt="User Thumbnail"/> --}}
                    <img src="{{ asset("themes/limitless/images/stanton.png") }}" alt="Life Process Alcohol Program" class="img-circle staff">
                    <p></p>
                    <p>
                      <strong>Stanton Peele</strong>
                      <br>
                      <strong>Creator of The Life Process Program</strong>
                    </p>
                   {{--  <a class="form-button" href="detailsv2.html">Get Started ></a> --}}
                    <button type="button" id="submitForm" class="btn btn-success btn-lg white btn-flat">Get Started <i class="fa fa-chevron-right"></i></button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid banner" id="step4">
      <div class="row">
        <div class="col-sm-12">
          <div class="container">
            <div class="row">
              <div class="col-sm-12 col-md-8 register">
                <form>
                   <input type="hidden" name="user_id" value="{{$user->id}}" id="userid">
                   <span><strong>Account Setup:</strong>&nbsp;&nbsp;&nbsp;&nbsp;Step 1 of 3</span>
                   <h3>Your Time Zone</h3>
                   <form class="row">
                               <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                <div class="small-12 columns">
                                    <div class="form-group {{ $errors->has('timezone') ? 'has-error' : ''}}">
                                        <label>Please select your Time Zone.
                                        @php
                                        $set_timezone='';
                                        @endphp
                                            {!! Form::select('timezone', ['' => trans('comman.location_timezone')] + $timezones, null, ['class' => 'form-control single-select','onchange'=>'settimezone(this.value)', 'id' => 'timezone']) !!}
                                        </label>
                                        <span class="text-danger">
                                            <strong id="timezone-error"></strong>
                                        </span>
                                        <p>*Required so that we can show your Coach availability in your Time Zone.</p>
                                    </div>
                                </div>
                    </form>
                   {{--   <p>*Required so that we can show your Coach availability in your Time Zone.</p> --}}
                   {{-- <a class="form-button" href="#">Next ></a> --}}
                   <button type="button" id="submittimezone" class="btn btn-success btn-lg white btn-flat">Next  <i class="fa fa-chevron-right"></i></button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid banner" id="step5">
      <div class="row">
        <div class="col-sm-12">
          <div class="container">
            <div class="row">
              <div class="col-sm-12 col-md-8 register">
                <button class="back-button" type="button" id="backtotimezone">
                    <a href="">&lt;</a>
                </button>
                 <form>
                      <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                      <span><strong>Account Setup:</strong>&nbsp;&nbsp;&nbsp;&nbsp;Step 2 of 3</span>
                      <h3>Select your Coach</h3>
                      <label>The Life Process Program is dedicated to putting YOU in control of your recovery. We begin that process now by giving you some control over who to have as your dedicated Life Process Program Coach.</label>

                      <div class="small-12 columns no-padding">
                          {!! Form::select('coach_gender',array(""=>trans("comman.select_coach_gender"), 'No'=>'No Preference','Male' => 'Male', 'Female' => 'Female'),Request::get('coach_gender',null),array('class' => 'form-control single-select','id'=>'coachgender','onchange'=>'assigncoach(this.value)')) !!}
                          {!! $errors->first('coach_gender', '<p class="help-block">:message</p>') !!}
                          <span class="text-danger">
                          <strong id="coach-error"></strong>
                          </span>
                      </div>

                 </form>
                 <br>
                 <button type="button" id="submitcoach" class="btn btn-success btn-lg white btn-flat">Next  <i class="fa fa-chevron-right"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid banner" id="step6">
      <div class="row">
        <div class="col-sm-12">
          <div class="container">
            <div class="row">
              <div class="col-sm-12 col-md-8 register">
                <form>
                  <span><strong>Account Setup:</strong>&nbsp;&nbsp;&nbsp;&nbsp;Step 3 of 3</span>
                  <h3>The Legal Bit</h3>
                  <p>Sorry, but we have to do this.</p>
                  <div class="terms-and-conditions">
                    <p>The information provided within this program or feedback received from your LIFE PROCESS COACH is designed to support, not replace, the relationship that exists between you and your existing physician or other mental health professional if applicable. Information and opinions expressed on this website or by your life process coach are those of the writers or coaches based on their education and experience. The website developers and individual coaches take no responsibility for adverse consequences experienced by clients using the information provided on this website or through reviews or guidance from a life process coach.  We assure you that we and the coaches all offer our best efforts to those who enroll in our LPP online programs. However please note that we do not claim to cure each and every case, nor do we guarantee to do so. The online exercises in this website and the subsequent reviews by a Life Process Coach do not constitute medical advice. They are intended for instruction and guidance in completing the LPP programs. The online exercises are not a substitute for medical advice, diagnosis, or therapy. Never ignore professional or medical advice in seeking treatment because of something you have read on this website. It is considered that the user has read and agreed to this disclaimer when using the information on this site.</p>
                    <p>Our online programs are guided self-help programs. The feedback you receive from exercises is not to be considered as traditional therapy from a therapist/psychologist. It is only intended as instructions in order that you can complete the 8-module self-help LPP programs yourself.</p>
                    <p>Our Life Process Coaches are trained in offering guidance and instruction in the LPP programs. They should not be considered doctors or therapists, notwithstanding their extensive experience and training in guiding clients through the LPP and in giving clients the assistance they need to complete the programs.</p>
                    <p>Who can use the Life Process Program:</p>
                    <ul>
                      <li>You must be 18 years or older.</li>
                      <li>You must not be suicidal or have suicidal thoughts.</li>
                      <li>If you are in need of DETOX please contact a hospital before commencing the program.</li>
                    </ul>
                    <p>You will have been considered to have read and understood these disclaimers when you have enrolled in the Life Process Program.</p>
                    <p>LPP promotes a friendly environment. All our staff are here to help and we will not tolerate any abuse to members of our staff. You are not permitted to use the services if you are under the influence of drugs or alcohol. For either abusing staff or participating in sessions under the influence of drugs or alcohol you could face a ban from LPP. No refund will be given in such cases. If you disagree with your coach’s feedback or have a complaint about any contact you have with a coach please email: <a href="mailto:info@lifeprocessprogram.com?Subject=Complaint" target="_top">info@lifeprocessprogram.com</a>.</p>
                      </div>
                 {{--  <input id="tac" type="radio" name="termsandconditions" value="Terms and Conditions" required> --}}
                  <div class="col-md-12">
                                <br>
                                {!! Form::checkbox('term','',false,array('id'=>'terms')) !!}
                                <label for="terms">
                                    I have read and I accept the Terms and Conditions
                                </label>
                                <span class="has-error hide" id="is_terms_checked"></span>
                  </div>
                 {{--  <label for="tac">&nbsp;I accept the Terms and Conditions</label> --}}
                  {{-- <a class="form-button" href="finishedv2.html">Next ></a> --}}
                  {{-- <a class="form-button" href="#" id="next">Next ></a> --}}
                  <button type="button" id="submitterms" class="btn btn-success btn-lg white btn-flat">Next  <i class="fa fa-chevron-right"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container-fluid banner" id="step7">
      <div class="row">
        <div class="col-sm-12">
          <div class="container">
            <div class="row">
              <div class="col-sm-12 col-md-8 register">
                <form>
                  <div class="finished-container">
                     <div class="finished-container">
                      <h3>Congratulations {{$user->first_name}}, you're all set!!!</h3>
                      <p>Meet  <span class="coach-name"></span> who will be your dedicated Life Process Coach for the duration of your program.</p>
                      <p><span class="coach-name"></span> is looking forward to connecting with you to help support you on your journey.</p>
                      <img id="coach-img" src="{{ asset("themes/limitless/images/avatar.png") }}" alt="User Thumbnail">
                     </div>
                    {{--   <div class="hide" id="without-coach">
                      <p>We will update you soon once we assign you a coach and then you are all set.</p>
                      </div> --}}
                    {{--  <img src="img/avatar.png" alt="User Thumbnail"/> --}}
                    <a href="{{route('login')}}" id="begin" class="btn btn-success btn-lg white btn-flat">Begin ></a>
                  </div>
                </form>
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
    <script src="{{ elixir('themes/limitless/js/async_register.js') }}"></script>
    <script src="{{ elixir('themes/limitless/js/jquery_register.js') }}"></script>
    <script src="{{ elixir('themes/limitless/js/app.js') }}"></script>
    <script src="{{ elixir('themes/limitless/js/bootstrap_register.js') }}"></script>

<script type="text/javascript">
    var coach_name='';
    jQuery(document).ready(function(){
        $('body').on('click', '#backtotimezone', function(){
         $('#step4').show();
         $('#step5').hide();
         $('#step6').hide();
         $('#step7').hide();
        });
    });

    $('body').on('click', '#submitForm', function(){
        var registerForm = $("#Register");
         $('#step4').show();
         $('#step5').hide();
         $('#step6').hide();
         $('#step7').hide();
         jQuery("#SignUp").hide();
    });
    function assigncoach(val)
    {
         var gender=jQuery('#coachgender').val();
         var timezone=jQuery('#timezone').val();
         var user_id=jQuery('#userid').val();
         //console.log(timezone);
         //alert(gender);
         jQuery.ajax({
                                    type: "GET",
                                    url: '{{ route('setcoach') }}',
                                    data: 'gender=' + gender+'&timezone='+timezone+'&userid='+user_id,
                                    success: function(response,result)
                                    {

                                        //alert(response.status);
                                        // console.log(response);
                                        // jQuery('#step4').hide();
                                        // jQuery('#step5').hide();
                                        // jQuery('#step6').show();
                                        // jQuery('#step7').hide();
                                        // jQuery("#SignUp").hide();
                                    },

                });
    }
    jQuery('#submitcoach').click(function(event) {
        var gender=jQuery('#coachgender').val();
        console.log(gender);
        if(gender=='' || gender=='No')
        {
          assigncoach(gender);
        }

          jQuery('#step4').hide();
          jQuery('#step5').hide();
          jQuery('#step6').show();
          jQuery('#step7').hide();
          jQuery("#SignUp").hide();

    });
   function settimezone(val)
   {
         var timezone=jQuery('#timezone').val();
         var user_id=jQuery('#userid').val();
         //console.log(user_id);
         //alert(gender);
         jQuery.ajax({
                                    type: "GET",
                                    url: '{{ route('settimezonestep') }}',
                                    data: 'timezone='+timezone+'&userid='+user_id,
                                    success: function(response,result)
                                    {
                                        //alert(response.status);
                                       // console.log(response);

                                    },

                });
    }
    jQuery('#submittimezone').click(function(event) {
          var timezone=jQuery('#timezone').val();
          if(timezone=='')
          {
            jQuery('#step4').show();
            jQuery('#timezone-error').html('*');
          }
          else
          {
          jQuery('#timezone-error').html('');
          jQuery('#step4').hide();
          jQuery('#step5').show();
          jQuery('#step6').hide();
          jQuery('#step7').hide();
          jQuery("#SignUp").hide();
        }
    });
    jQuery('#terms').click(function(event) {
        if($('#terms').prop("checked") == true){
            jQuery('#terms').val('yes');
            jQuery('#next').show();
            var terms=jQuery('#terms').val();
            var user_id=jQuery('#userid').val();
            //console.log(terms);
            //alert(gender);
            jQuery('#is_terms_checked').empty();
            jQuery('#is_terms_checked').addClass('hide');
            jQuery.ajax({
                type: "GET",
                url: '{{ route('setterms') }}',
                data: 'terms='+terms+'&userid='+user_id ,
                success: function(response,result)
                {

                    //console.log(response);
                    jQuery('.coach-name').text(response.coach_name);
                    if(response.coach_image){
                      jQuery('#coach-img').attr('src', response.coach_image);
                      jQuery('#with-coach').removeClass('hide');
                    }
                    else
                    {
                      jQuery('#without-coach').removeClass('hide');
                    }
                    //alert(response.status);
                    //console.log(response);

                    jQuery('#step4').hide();
                    jQuery('#step5').hide();
                    jQuery('#step6').hide();
                    jQuery('#step7').show();
                    jQuery("#SignUp").hide();

                },
                headers:
                {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        }
        else
        {
            var message = "Please accept the terms and conditions to continue!";
            jQuery('#is_terms_checked').html(message);
            jQuery('#is_terms_checked').removeClass('hide');
            jQuery('#terms').val('');
            jQuery('#next').hide();
        }
    });
    jQuery('#submitterms').click(function(event) {
        jQuery('#step4').hide();
        jQuery('#step5').hide();
        jQuery('#step6').hide();
        jQuery('#step7').show();
        jQuery("#SignUp").hide();
    });
    jQuery('#begin').click( function (event) {
      event.preventDefault();
      var user_id=jQuery('#userid').val();

      jQuery.ajax({
          type: "GET",
          url: '{{ route('registration-completed') }}',
          data: {
            id : user_id
          },
          success: function(response,result)
          {
              window.location.href = '/';
          },
          headers:
          {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
    });
</script>
</body>
</html>