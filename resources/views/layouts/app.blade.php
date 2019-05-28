<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="{{ elixir('themes/limitless/fonts/OpenSans/stylesheet.css') }}">
    <link rel="stylesheet" href="{{ elixir('themes/limitless/css/client-all.css') }}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

    @yield('css')
    {{--<link href="{{ asset("assets/vendor/spectrum/spectrum.css") }}" rel="stylesheet">--}}
    {{--<link href="{{ asset("assets/css/chatter.css") }}" rel="stylesheet">--}}

</head>
<body>
    <div id="app">
        <header class="layout-boxed">
            <div class="top-header">
                <div class="container-fluid no-padding">
                    <div class="col-md-6 col-sm-6 col-xs-12 logo pull-left">
                        <a href="{{ route('client.dashboard') }}">
                            <img src="{{ asset("themes/limitless/images/client-view/logo.png") }}" alt="Life Process Alcohol Program" class="img-responsive">
                        </a>
                    </div>
                <div class="navbar-header">
                    <div class="col-xs-2">
                  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <i class="fa fa-list" aria-hidden="true"></i>
                  </button>
                  </div>
                </div>


                <div class="col-md-6 col-sm-6 col-xs-10 user-detail pull-right">
                        <a href="javascript:void(0);" class="text-right" data-toggle="dropdown">

                            {{--{{Html::image(AppHelper::size('36x36')->getDefaultImage(),'User Photo',array("class"=>"img-responsive img-circle staff"))}}--}}
                            @if(isset(Auth::user()->image) && !empty(Auth::user()->image))
                                {{Html::image(AppHelper::path('uploads/user/')->size('100x100')->getImageUrl(Auth::user()->image),'User Photo',array("class"=>"img-responsive img-circle staff",'id'=>'profile_image','height'=>'36','width'=>'36'))}}
                            @else
                                <img src="/uploads/default/default.jpg" class="img-responsive img-circle staff" id="profile_image" height="36" width="36" alt="User Photo">
                            @endif
                            <span>{{Auth::user()->name}} <i class="fa fa-chevron-down"></i></span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-right no-margin">
                            <li><a href="{{ route('clients.update.profile') }}"><i class="icon-user-plus"></i> My Profile</a></li>
                            <li><a href="{{ route('clients.mycoach') }}"><i class="fa fa-eye"></i> My Coach</a></li>

                            @if(Auth::user()->user_type == 'client')
                                <li><a href="{{ url('/client/add-read-only-coach') }}"><i class="fa fa-envelope"></i> Share Access</a></li>
                            @endif

                            <hr>
                            <li><a href="{{ route('mylifestory.index') }}"><i class="fa fa-heart-o"></i> Life Story</a></li>
                            <!-- <li><a href="{{-- {{ route('clients.update.profile') }} --}}"><i class="icon-user-plus"></i> Alerts</a></li> -->
                            <li><a href="{{ route('faqs.index') }}"><i class="icon-user-plus"></i> FAQ</a></li>
                            <hr>
                            <li><a href="{{ route('contact') }}"><i class="fa fa-phone"></i> Help</a></li>
                            @if(Auth::user()->hasAccess('refer_friend.create'))
                            <li>
                            <a href="{{ route('referfriend.index') }}"><i class="fa fa-heart-o"></i> Refer a Friend</a>
                            </li>
                            @endif
                            <hr>
                            <li>
                                <a href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"><i class="icon-switch2"></i> Logout</a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                            @if(\Session::has('admin_user_id'))
                                <li>
                                    <a href="{{ route('back.to.admin') }}"><i class="icon-switch2"></i> Back To Admin</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
            <div class="sub-header">
                <div class="container-fluid">
                    @php $status=""; @endphp
                    {{--@foreach(Auth::user()->client->schedule_booked as $freesession)--}}
                        {{--@if($freesession->booked_for=='f')--}}
                           {{--@php $status='free'; break; @endphp--}}
                        {{--@else--}}
                           {{--@php $status='notfree'; @endphp--}}
                        {{--@endif--}}
                    {{--@endforeach--}}
                    @if($status=='notfree' || $status=="")
                    <div class="alert-msg pull-left">
                        <p class="text-white">
                            @if(!isset($agent_theme))
                                <i class="fa fa-exclamation-circle text-yellow"></i>
                                <span class="text-yellow font-bold">Alert:</span> Schedule your <span class="text-yellow font-semibold"><a href="{{ route('clients.dashboard.coaching') }}" style="color: #ffff00;"> Introductory coaching session.</a></span>
                            @endif
                        </p>
                    </div>
                    @endif
                    <div class="credit pull-right">
                     {{--    @if(Auth::user()->hasAccess('refer_friend.create'))
                            <a href="{{ route('referfriend.index') }}" class="text-white "><i class="fa fa-heart-o"></i> Refer a Friend</a>
                        @endif
                        @if(Auth::user()->hasAccess('myCredits.view') && Auth::user()->is_free_session_complete=='y' && Auth::user()->is_free_session_booked==1)

                            <a href="{{ route('client.myCredits') }}" class="buy-credit font-semibold">Buy Credits</a>

                        @endif --}}
                    </div>
                </div>
            </div>
        </header>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    {{--<script src="{{ asset("assets/vendor/tinymce/tinymce.min.js") }}"></script>--}}
    {{--<script src="{{ asset("assets/js/tinymce.js") }}"></script>--}}
    	{{--<script>--}}
    		{{--var my_tinymce = tinyMCE;--}}
    		{{--$('document').ready(function(){--}}
    			{{--$('#tinymce_placeholder').click(function(){--}}
    				{{--my_tinymce.activeEditor.focus();--}}
    			{{--});--}}
    		{{--});--}}
    	{{--</script>--}}
    {{--<script src="{{ asset("assets/vendor/spectrum/spectrum.js") }}"></script>--}}
    {{--<script src="{{ asset("assets/js/chatter.js") }}"></script>--}}
    @yield('js')
</body>
</html>
