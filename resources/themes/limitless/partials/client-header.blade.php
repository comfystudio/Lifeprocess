<header class="layout-boxed">
    <div class="top-header" @if(isset($agent_theme) && !empty($agent_theme['colour_1'])) style="background: {{$agent_theme['colour_1']}}"@endif>
        <div class="container-fluid no-padding">
            <div class="col-md-6 col-sm-6 col-xs-12 logo pull-left">
                <a href="{{ route('client.dashboard') }}">
                    @if(isset($agent_theme) && !empty($agent_theme['logo']))
                        <img src="/{{$agent_theme['logo']}}" alt="Life Process Program" width="150px">
                    @else
                        <img src="{{ asset("themes/limitless/images/client-view/logo.png") }}" alt="Life Process Alcohol Program" class="img-responsive">
                    @endif
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

                    {{Html::image(AppHelper::size('36x36')->getDefaultImage(),'User Photo',array("class"=>"img-responsive img-circle staff"))}}
                    <span>{{Auth::user()->name}} <i class="fa fa-chevron-down"></i></span>
                </a>

                <ul class="dropdown-menu dropdown-menu-right no-margin">
                    <li><a href="{{ route('clients.update.profile') }}"><i class="icon-user-plus"></i> My Profile</a></li>
                    <li><a href="{{ route('clients.mycoach') }}"><i class="fa fa-eye"></i> My Coach</a></li>

                    @if(Auth::user()->user_type == 'client' && !isset($agent_theme))
                        <li><a href="{{ url('/client/add-read-only-coach') }}"><i class="fa fa-envelope"></i> Share Access</a></li>
                    @endif

                    <hr>
                    <li><a href="{{ route('mylifestory.index') }}"><i class="fa fa-heart-o"></i> Life Story</a></li>
                    <!-- <li><a href="{{-- {{ route('clients.update.profile') }} --}}"><i class="icon-user-plus"></i> Alerts</a></li> -->
                    @if(!isset($agent_theme))
                        <li><a href="{{ route('faqs.index') }}"><i class="icon-user-plus"></i> FAQ</a></li>
                        <hr>
                    @endif
                    <li><a href="{{ route('contact') }}"><i class="fa fa-phone"></i> Help</a></li>
                    @if(Auth::user()->hasAccess('refer_friend.create') && !isset($agent_theme))
                    <li>
                    <a href="{{ route('referfriend.index') }}"><i class="fa fa-heart-o"></i> Refer a Friend</a>
                    </li>
                    @endif
                    <hr>
                    <li>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"><i navbarclass="icon-switch2"></i> Logout</a>
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
    <div class="sub-header" @if(isset($agent_theme) && !empty($agent_theme['colour_2'])) style="background: {{$agent_theme['colour_2']}}"@endif>
        <div class="container-fluid">
            @php $status=""; @endphp
            @foreach(Auth::user()->client->schedule_booked as $freesession)
                @if($freesession->booked_for=='f')
                   @php $status='free'; break; @endphp
                @else
                   @php $status='notfree'; @endphp
                @endif
            @endforeach
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

