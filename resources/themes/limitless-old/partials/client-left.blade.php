<style type="text/css">
.navbar-collapse
{
    padding-right: 0px;
    padding-left: 0px;
}
.navbar
{
    margin-left: 10px;
    width: fit-content;
}
.navbar-toggle
{
    border: 1px solid #f44336;
    background-color: white;
    margin-right: 0px;
    margin-left: 20px;
}
</style>

<div class="menu">
    <nav class="navbar navbar-default">
    <!-- Brand and toggle get grouped for better mobile display -->

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav nav-tabs">
                <li class="title">
                     <span>Main Navigation</span>
                </li>
                <li class="{{ (Request::segment(2)=='program' ? 'active' : '' ) }}">
                    <a href="{{ route('client.dashboard') }}">
                        <img src="{{ asset('themes/limitless/images/client-view/home-button.png') }}" class="icon">
                        <span class="font-semibold text-black">Program Modules</span>
                    </a>
                </li>
                @if(Auth::user()->hasAccess('messages.view'))
                <li class="message {{ (Request::segment(1)=='messages' ? 'active' : '' ) }}">
                    <a href="{{ route('messages.index') }}">
                        <img src="{{ asset('themes/limitless/images/client-view/speech-bubble.png') }}" class="icon">
                        <span class="font-semibold text-black">Messages
                            @if($unread_counter != '0')
                            <i class="font-bold msg">{{ $unread_counter }}</i>
                            @endif
                        </span>
                    </a>
                </li>
                @endif
                <li class="{{ (Request::segment(1)=='client_coaching' || Request::segment(1)=='booking' ? 'active' : '' ) }}">
                    <a href="{{ route('clients.dashboard.coaching') }}">
                        <img src="{{ asset('themes/limitless/images/client-view/avatar.png') }}" class="icon">
                        <span class="font-semibold text-black">Coaching</span>
                    </a>
                </li>
                @if(Auth::user()->hasAccess('my_lifestory.view'))
                <li class="{{ (Request::segment(1)=='mylifestory' ? 'active' : '' ) }}">
                    <a href="{{ route('mylifestory.index') }}">
                        <img src="{{ asset('themes/limitless/images/client-view/heart-outline.png') }}" class="icon">
                        <span class="font-semibold text-black">Life Story</span>
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('resource.show') }}">
                        <img src="{{ asset('themes/limitless/images/client-view/book.png') }}" class="icon">
                        <span class="font-semibold text-black">Resource Library</span>
                    </a>
                </li>
                <li class="message {{ (Request::segment(1)=='forums' ? 'active' : '' ) }}">
                    <a href="{{ url('/forums') }}">
                        <img src="{{ asset("images/forum.svg") }}" width="32px" class="icon"/>
                        <span class="font-semibold text-black">Discussions
                        </span>
                    </a>
                </li>
                <li class="message {{ (Request::segment(1)=='group-meeting' ? 'active' : '' ) }}">
                    <a href="{{ url('/group-meeting') }}">
                        <img src="{{ asset("images/group.svg") }}" width="32px" class="icon"/>
                        <span class="font-semibold text-black">Group Meeting
                        </span>
                    </a>
                </li>
                <li class="message {{ (Request::segment(1)=='contact-admin' ? 'active' : '' ) }}">
                    <a href="{{ route('messages.contact-admin') }}">
                        <img src="{{ asset('themes/limitless/images/client-view/paper-plane.png') }}" class="icon">
                        <span class="font-semibold text-black">Contact Admin
                        @if($unread_admin_counter != '0')
                            <i class="font-bold msg">{{ $unread_admin_counter }}</i>
                        @endif
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

</div>

