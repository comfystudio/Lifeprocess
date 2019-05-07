<!-- Main sidebar -->
<style type="text/css">
    .navigation > li.active > a .badge-danger {
        background-color: #F44336;
        border-color: #F44336;
    }.navigation > li.active > a .badge-warning {
        background-color: #FF5722;
        border-color: #FF5722;
    }
    .badge1 {
   position:relative;
}
.badge1[data-badge]:after {
   content:attr(data-badge);
   position:absolute;
   top: 2px;
   font-size:.7em;
   background:#91CC33;
   color:white;
   width:18px;height:18px;
   text-align:center;
   line-height:18px;
   border-radius:50%;
   box-shadow:0 0 1px #333;
}
</style>

<div class="sidebar sidebar-main sidebar-default">
    <div class="sidebar-content">
        <!-- Main navigation -->
        <div class="sidebar-category sidebar-category-visible">
            <div class="category-title h6 " style="">
                <span class="main-nav-title-font-style">Main Navigation</span>
                <ul class="icons-list">
                    <li><a href="#" data-action="collapse"></a></li>
                </ul>
            </div>
            <div class="category-content sidebar-user">
                <div class="media">
                    <a class="media-left">
                        @if(isset(Auth::user()->image) && !empty(Auth::user()->image))
                            {{Html::image(AppHelper::path('uploads/user/')->size('36x36')->getImageUrl(Auth::user()->image),'User Photo',array("class"=>"img-circle"))}}
                        @else
                            {{Html::image(AppHelper::size('36x36')->getDefaultImage(),'User Photo',array("class"=>"img-circle"))}}
                        @endif
                    </a>
                    <div class="media-body">
                        <span class="media-heading text-capitalize text-bold">{{Auth::user()->name}}</span>
                        @if(!empty($cityname) || !empty($statename))
                        <div class="text-size-mini text-muted">
                            <i class="icon-pin text-size-small"></i> &nbsp;{{$cityname}} {{!empty($cityname)? ',' : ''}}{{$statename}}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="category-content no-padding">
                <ul class="navigation navigation-main navigation-accordion">
                    <!-- Main -->
                        @if(Auth::user()->user_type == 'client')
                            <li class="{{ (Request::segment(2)=='dashboard' ? 'active' : '' ) }}"><a href="{{ route('client.dashboard') }}"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
                        @elseif(Auth::user()->user_type == 'coach')
                            <li class="{{ (Request::segment(1)=='client' ? 'active' : '' ) }}"><a href="{{ route('coach.dashboard') }}"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
                           {{--  <li class="{{ (Request::segment(1)=='client' ? 'active' : '' ) }}"><a href="{{ route('coach.payment') }}"><i class="fa fa-cc-paypal"></i> <span>Coach Payment</span></a></li> --}}
                        @elseif(Auth::user()->user_type == 'agent')
                            <li class=""><a href="{{ route('agent.dashboard') }}"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
                        @else
                            <li class="{{ (Request::segment(1)=='client' ? 'active' : '' ) }}"><a href="{{ route('dashboard') }}"><i class="icon-home4"></i> <span>Dashboard</span></a></li>
                        @endif
                        @if(Auth::user()->hasAccess('users.view'))
                            <li class="{{ (Request::segment(1)=='users' ? 'active' : '' ) }}"><a href="{{ route('users.index') }}"><i class="icon-users"></i> <span>Users</span></a></li>
                        @endif
                        @if(Auth::user()->hasAccess('roles.view'))
                            <li class="{{ (Request::segment(1)=='roles' ? 'active' : '' ) }}"><a href="{{ route('roles.index') }}"><i class="fa fa-users"></i> <span>Roles</span></a></li>
                        @endif
                        @if(Auth::user()->hasAccess('countries.view'))
                            <li class="{{ (Request::segment(1)=='countries' ? 'active' : '' ) }}"><a href="{{ route('countries.index') }}"><i class="icon-cc"></i> <span>Countries</span></a></li>
                        @endif
                        {{-- @if(Auth::user()->hasAccess('states.view'))
                            <li class="{{ (Request::segment(1)=='states' ? 'active' : '' ) }}"><a href="{{ route('states.index') }}"><i class="icon-lastfm2"></i> <span>States</span></a></li>
                        @endif --}}
                        @if(Auth::user()->hasAccess('programs.view'))
                            <li class="{{ (Request::segment(1)=='programs' || Request::segment(1)=='program'  ? 'active' : '' ) }}"><a href="{{ route('programs.index') }}"><i class="fa fa-folder-open"></i> <span>Programs</span></a></li>
                        @endif
                        @if(Auth::user()->hasAccess('coaches.view'))
                            <li class="{{ (Request::segment(1)=='coaches' ? 'active' : '' ) }}"><a href="{{ route('coaches.index') }}"><i class="fa fa-eye"></i> <span>Coaches</span></a></li>
                        @endif
                        @if(Auth::user()->hasAccess('clients.view'))
                            <li class="{{ (Request::segment(1)=='clients' ? 'active' : '' ) }}"><a href="{{ route('clients.index') }}"><i class="fa fa-user"></i> <span>Clients</span></a></li>
                        @endif
                        @if(Auth::user()->hasAccess('messages.view') && Auth::user()->user_type == 'coach')
                            {{-- <li class="{{ (Request::segment(1)=='messages' ? 'active' : '' ) }}"><a href="{{ route('messages.getrole') }}"><i class="fa fa-wechat"></i> <span>Messages</span></a></li> --}}
                            <li class="{{ (Request::segment(1)=='messages' ? 'active' : '' ) }}">
                            <a href="{{ route('messages.getrole', ['role'=>'coach-client']) }}" class="badge1" {{ $unread_counter != '0' ? 'data-badge='.$unread_counter.'' : '' }}><i class="fa fa-wechat"></i>Messages</a>
                            </li>
                        @endif
                        @if(Auth::user()->hasAccess('messages.view') && Auth::user()->user_type != 'coach')
                            <li class="{{ (Request::segment(1)=='messages' ? 'active' : '' ) }}"><a href="{{ route('messages.index') }}" class="badge1" {{ $unread_counter != '0' ? 'data-badge='.$unread_counter.'' : '' }}><i class="fa fa-wechat"></i> <span>Messages</span></a></li>
                        @endif
                        @if(Auth::user()->hasAccess('agents.view'))
                            <li class="{{ (Request::segment(1)=='agents' ? 'active' : '' ) }}"><a href="{{ route('agents.index') }}"><i class="fa fa-eye"></i> <span>Client Manager</span></a></li>
                        @endif
                         @if(Auth::user()->hasAccess('pages.view'))
                            <li class="{{ (Request::segment(1)=='pages' ? 'active' : '' ) }}"><a href="{{ route('pages.index') }}"><i class="fa fa-file-text-o"></i> <span>Site Pages</span></a></li>
                        @endif
                        @if(Auth::user()->hasAccess('credit_package.create'))
                            <li class="{{ (Request::segment(1)=='creditpackage' ? 'active' : '' ) }}"><a href="{{ route('creditpackage.index') }}"><i class="fa fa-money"></i> <span>Credit Package</span></a></li>
                        @endif
                        @if(Auth::user()->hasAccess('all_session.view'))
                            <li class="{{ (Request::segment(1)=='allsession' ? 'active' : '' ) }}"><a href="{{ route('allsession') }}"><i class="fa fa-list"></i> <span>Coaching Session</span></a></li>
                        @endif
                        @if(Auth::user()->hasAccess('email_template.view'))
                            <li class="{{ (Request::segment(1)=='email_template' ? 'active' : '' ) }}"><a href="{{ route('email-template.index') }}"><i class="fa fa-envelope-o"></i> <span>Email Template</span></a></li>
                        @endif
                        @if(Auth::user()->hasAccess('schedule.view'))
                            <li class="{{ (Request::segment(1)=='week' ? 'active' : '' ) }}"><a href="{{ route('week.index') }}"><i class="fa fa-calendar-o"></i> <span>My Week</span></a></li>
                             <li class="{{ (Request::segment(1)=='adjust_schedule' ? 'active' : '' ) }}"><a href="{{ route('adjust_schedule.index') }}"><i class="fa fa-calendar"></i> <span>Adjust Schedules</span></a></li>
                             {{-- <li class="{{ (Request::segment(1)=='free_session' ? 'active' : '' ) }}"><a href="{{ route('free_session.index') }}"><i class="fa fa-calendar"></i> <span>Free Session</span></a></li> --}}
                        @endif
                        @if(Auth::user()->user_type=='user')
                            <li class="{{ (Request::segment(1)=='resource_library' ? 'active' : '' ) }}"><a href="{{ route('resource_library.index') }}"><i class="fa fa-book"></i> <span>Resource Library</span></a></li>
                        @endif
                        @if(Auth::user()->hasAnyAccess(['report.coach_report','report.financial_report.view','report.signup_report.view','report.refer_friend_report.view']))
                            <li>
                                <a href="#">
                                    <i class="fa fa-list"></i> <span>Reports</span>
                                </a>
                                <ul>
                                    @if(Auth::user()->hasAccess('report.coach_report'))
                                        <li class="{{ (Request::segment(1)=='coachingreport' ? 'active' : '' ) }}"><a href="{{ route('report.coaching') }}"><i class="fa fa-life-ring"></i> <span>Coaching</span></a></li>
                                    @endif
                                    @if(Auth::user()->hasAccess('report.financial_report.view'))
                                        <li class="{{ (Request::segment(1)=='financialreport' ? 'active' : '' ) }}">
                                            @if(Auth::user()->user_type == 'coach')
                                                <a href="{{ route('coach.transaction',['id'=> Crypt::encryptString(Auth::user()->id)]) }}">
                                            @else
                                                <a href="{{ route('financialreport') }}">
                                            @endif
                                        <i class="icon-cash3"></i> <span>Financial Report</span></a>
                                        </li>
                                    @endif
                                    @if(Auth::user()->hasAccess('report.signup_report.view'))
                                        <li class="{{ (Request::segment(1)=='signupreport' ? 'active' : '' ) }}"><a href="{{ route('signupreport') }}"><i class="fa fa-sign-in"></i> <span>Signup Report</span></a></li>
                                    @endif
                                    @if(Auth::user()->hasAccess('report.refer_friend_report.view'))
                                        <li class="{{ (Request::segment(1)=='referfriendreport' ? 'active' : '' ) }}"><a href="{{ route('referfriendreport') }}"><i class="fa fa-user-plus"></i> <span>Refer Friend Report</span></a></li>


                                    @endif
                                </ul>
                            </li>
                            @if(Auth::user()->user_type=='user')

                            @else
                                <li class="">
                                    <a href="{{ route('messages.meeting') }}">
                                            <img src="{{ asset('themes/limitless/images/client-view/speech-bubble.png') }}" class="icon">
                                        <span class="font-semibold text-black">Meeting</span>
                                    </a>
                                </li>
                            @endif
                        @endif

                        @if(Auth::user()->hasAccess('forum.create'))
                            <li>
                                <a href="#">
                                    <i class="fa fa-comment"></i> <span>Forum</span>
                                </a>
                                <ul>
                                    <li class="{{ (Request::segment(1)=='forum-categories' ? 'active' : '' ) }}"><a href="{{ url('/forum-categories') }}"><i class="fa fa-star"></i> <span>Categories</span></a></li>
                                    <li class="{{ (Request::segment(1)=='forum-topics' ? 'active' : '' ) }}"><a href="{{ url('/forum-topics') }}"><i class="fa fa-gavel"></i> <span>Threads</span></a></li>
                                    <li class="{{ (Request::segment(1)=='forum-posts' ? 'active' : '' ) }}"><a href="{{ url('/forum-posts') }}"><i class="fa fa-comment"></i> <span>Posts</span></a></li>
                                </ul>
                            </li>
                        @endif
                </ul>
            </div>
        </div>
        <!-- /main navigation -->
    </div>
</div>
<!-- /main sidebar -->
