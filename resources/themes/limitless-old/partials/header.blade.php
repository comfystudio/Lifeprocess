<!-- Main navbar -->
<div class="navbar navbar-inverse">
    <div class="navbar-header col-xs-12 col-sm-2">
        @php
            $dashboard_route = route('dashboard');
            if (Auth::user()->user_type == 'client') {
                $dashboard_route = route('client.dashboard');
            } else if (Auth::user()->user_type == 'coach') {
                $dashboard_route = route('coach.dashboard');
            } else if (Auth::user()->user_type == 'agent') {
                $dashboard_route = route('agent.dashboard');
            }
        @endphp
        <a class="navbar-brand logo" href="{{ $dashboard_route }}">
            <div class="logo-middle">
                {{-- <img src="{{ asset("images/logo-lpap.png") }}" alt="Life Process Alcohol Program"> --}}
                <img src="{{ asset("themes/limitless/images/client-view/logo.png") }}" alt="Life Process Program" >
            </div>
        </a>
        
    </div>
    <ul class="col-xs-2 nav navbar-nav visible-xs-block nav-toggle">
            {{-- <li><a data-toggle="collapse" data-target="#navbar-mobile" href=""><i class="icon-tree5"></i></a></li> --}}
            <li><a class="sidebar-mobile-main-toggle" href=""><i class="icon-paragraph-justify3"></i></a></li>
        </ul>
    <ul class="col-xs-10 col-sm-9 nav navbar-nav navbar-right pull-right user-dropdown">
        <li class="dropdown dropdown-user admin-user">
            <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);">
            {{-- {{ dump(AppHelper::getDefaultImage() )}} --}}
                @if(isset(Auth::user()->image) && !empty(Auth::user()->image))
                    {{Html::image(AppHelper::path('uploads/user/')->size('36x36')->getImageUrl(Auth::user()->image),'User Photo',array("class"=>"img-circle"))}}
                @else
                    {{Html::image(AppHelper::size('36x36')->getDefaultImage(),'User Photo',array("class"=>"img-circle staff"))}}
                @endif
                {{-- {{Html::image(AppHelper::path('uploads/user/')->size('36x36')->getImageUrl(Auth::user()->image),'User Photo',array("class"=>"img-circle"))}} --}}
                <span class='text-capitalize'>{{Auth::user()->name}}</span>
                <i class="caret"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
                {{-- @if($current_user->hasAccess(['user_profiles.view'])) --}}
                    @if(Auth::user()->user_type == 'client')
                        <li><a href="{{ route('clients.update.profile') }}"><i class="icon-user-plus"></i> My Profile</a></li>
                    @elseif(Auth::user()->user_type == 'coach')
                        <li><a href="{{ route('coach.update.profile') }}"><i class="icon-user-plus"></i> My Profile</a></li>
                    @elseif(Auth::user()->user_type == 'agent')
                        <li><a href="{{ route('agent.update.profile') }}"><i class="icon-user-plus"></i> My Profile</a></li>
                    @else
                        <li><a href="{{ route('users.update.profile') }}"><i class="icon-user-plus"></i> My Profile</a></li>
                    @endif
                {{-- @endif --}}
                {{-- Refer Friend --}}
                    @if(Auth::user()->hasAccess('refer_friend.create'))
                        <li class="{{ (Request::segment(1)=='referfriend' ? 'active' : '' ) }}"><a href="{{ route('referfriend.index') }}"><i class="fa fa-user-plus"></i> <span>Refer Friends</span></a></li>
                    @endif
                {{-- Refer Friend End --}}
                 {{-- Life story  --}}
                    @if(Auth::user()->hasAccess('my_lifestory.create'))
                        <li class="{{ (Request::segment(1)=='mylifestory' ? 'active' : '' ) }}"><a href="{{ route('mylifestory.index') }}"><i class="fa fa-heartbeat"></i> <span>My Life Story</span></a></li>
                    @endif
                {{-- life story end --}}
                @if(Auth::user()->hasAccess('notifications.view'))
                        @php
                            $alert = App::make('App\Http\Controllers\NotificationController');
                            $alert_counter = $alert->getUnreadNotificatoinCounter();
                        @endphp
                        <li>
                            <a href="{{ route('coach.alerts') }}">
                                @if($alert_counter)
                                    <span class="badge badge-danger pull-right">{{ $alert_counter }}</span>
                                @endif<i class="icon-notification2"></i>
                                Alerts
                            </a>
                        </li>
                        <li class="divider"></li>
                    @endif
                {{-- FAQ --}}
                    @if(Auth::user()->hasAccess('faqs.view'))
                        <li>
                            <a href="{{ route('faqs.index') }}"><i class="fa fa-question"></i> <span>FAQ</span></a>
                        </li>
                    @endif
                {{-- end FAQ --}}
                {{-- Contact us --}}
                    @if(Auth::user()->hasAccess('contact.create'))
                        <li>
                                <a href="{{ route('contact') }}"><i class="fa fa-phone"></i> <span>Contact us</span></a>
                        </li>
                        <li class="divider"></li>
                    @endif
                {{-- end Contact us --}}

                {{-- Site Setting --}}
                    @if(Auth::user()->user_type == 'user')
                        <li>
                            <a href="{{ route('users.settings') }}"><i class="icon-gear"></i> <span>Settings</span></a>
                        <li>
                    @endif
                {{-- Site setting over --}}
                    <li>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"><i class="icon-switch2"></i> Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                       <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                        </form>
                    </li>
                    @if(\Session::has('admin_user_id') && Auth::user()->user_type == 'coach')
                        <li>
                            <a href="{{ route('back.to.admin') }}"><i class="icon-switch2"></i> Back To Admin</a>
                        </li>
                    @endif
            </ul>
        </li>
    </ul>
    
</div>
{{-- <div class="sub_header"></div> --}}

<!-- /main navbar -->
<!-- Page header -->
<div class="page-header hide">
    <!-- Module Title -->
    <div class="page-header-content">
        <div class="page-title">
            <div class="col-sm-7 set-height hide">
                @if(!empty($title))
                <h4><i class="icon-arrow-left52 position-left"></i> <span class="text-semibold">{{ $title }}</span></h4>
                @if(!empty($module_sub_title))
                | {{ $module_sub_title }}
                @endif
                @if(!empty($module_help))
                | {{ $module_help }}
                @endif
                @endif
            </div>
            <div class="col-sm-5 pull-right">
                @if(!empty($module_action))
                <div class="text-right">
                    @foreach($module_action as $key=>$action)
                    {!! Html::decode(Html::link($action['url'],$action['title'],$action['attributes'])) !!}
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Module Title End -->
</div>
<!-- /page header -->
<div class="clearfix"></div>