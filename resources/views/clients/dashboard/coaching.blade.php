@extends($theme)
@section('title', $title)
<style type="text/css">
    div.panel-body > div.proof
    {
        padding-bottom:10px;
    }
    hr
    {
            width: 94%;
            margin-left: 3% !important;
    }
    .left-content
    {
        border-right: 1px solid #ddd;
    }
    .client-name
    {
            padding: 0 0 15px 0;
    }
</style>
@section('content')

@if(Auth::user()->is_gratuate=='y' && Auth::user()->gratuate_option=='gs' && $gratuate_session_booked==0)
    <div class="col-md-12 col-sm-12 col-sm-12">
        <div class="book-session gratuate-session">

            <div class="tab-title">
                <h1>
                    Gratuation Complete
                </h1>
                <p style="padding: 6px 25px 0px;">
                    <span>
                        You have
                        <strong class="font-bold">
                        {{ $credits }}
                        </strong>
                        Credits
                    </span>
                </p>
            </div>

            <hr>

            <div class="form-content">
                <div class="row step1 no-margin">
                    <div class="col-md-12 col-sm-12 col-xs-12 left-content" style="">
                        <div class="client-name">
                            Check In session for {{ $client_program }} Gratuates
                        </div>
                            Congratulations as a gratuate of the Life Process Program You are entitled to
                            <b>
                                One complementry 20 minute coaching session
                            </b>
                            with {{ $coach }}.
                            <br/>
                            <br/>
                            <a href="/bookfreeschedule">
                                <button type="submit" name="submit" class="module-feedback" style="background-color:#82cd49; ">
                                    Book Your Complementry Session
                                </button>
                            </a>
                    </div>
                </div>
            </div>
        </div>
        {{-- @endif --}}
@elseif(Auth::user()->is_gratuate=='y' && Auth::user()->gratuate_option=='gs' && $gratuate_session_booked==1 && $gratuate_session_details!=null && $gratuate_session_details->coach_schedule!=null )
        <div class="col-md-12 col-sm-12 col-sm-12">
            <div class="book-session gratuate-session">

                <div class="tab-title">
                    <h1>
                        Gratuation Complete
                    </h1>
                    <div class="col-md-6 col-sm-6 col-xs-6">
                        <p>
                            <span>
                                You have
                                <strong class="font-bold">
                                    {{ $credits }}
                                </strong>
                                Credits
                            </span>
                        </p>
                    </div>
                </div>

                <hr>

                <div class="form-content">
                    <div class="row step1 no-margin">
                        <div class="col-md-6 col-sm-6 col-xs-6 left-content" style="padding: 2px 120px 101px 41px;margin: 42px 0 0 0;">
                            <p>
                                <b>
                                    Congratulations
                                </b>
                            </p>
                            <span>
                                You have now scheduled Your free monthly coaching session for
                                <?php echo date('M'); ?>
                                with {{ $coach }}.
                            </span>
                            <br/>
                            <p>
                                Here are the details for your session:
                            </p>
                        </div>

                        @if($gratuate_session_details->coach_schedule!=null)
                            <div class="col-md-6 col-sm-6 col-xs-6" style="padding: 25px 75px 0 70px;margin: 15px 0 0 0;">
                            <p>
                                You are scheduled for the following session:
                            </p>
                            <p>
                                <strong class="font-bold">
                                    Date :
                                </strong>
                                {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$gratuate_session_details->coach_schedule->start_datetime)->format('Y-m-d') }}
                            </p>
                            <p>
                                <strong class="font-bold">
                                    Time :
                                </strong>
                                {{ $total_time }}
                            </p>
                            <p>
                                <strong class="font-bold">
                                    Format :
                                </strong>
                                {{ $gratuate_session_details->meeting_type }}
                            </p>
                            <p>
                                <strong class="font-bold">
                                    Session :
                                </strong>
                                @foreach($coach_session_details as $session)
                                @if($session->booked_for=='s')
                                One hour session
                                @elseif ($session->booked_for=='f')
                                Introductory Session(20 mins)
                                @else
                                Gratuate session
                                @endif
                                @endforeach
                            </p>
                            <p>
                                <strong class="font-bold">
                                    Your Coach :
                                </strong>
                                {{ $coach }}
                            </p>
                                @php
                                    $array=sizeof($request);
                                    $array=$array-1;
                                @endphp

                                @if($array>=0)
                                    @for($i=0;$i<=$array;$i++)
                                        @if($request[$i]->coach_schedule_id==$session->coach_schedules_id)
                                            @php
                                                $date = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$request[$i]->start_datetime)->format('Y-m-d');
                                                    $currentdate = Carbon\Carbon::now()->format('Y-m-d');
                                                    $currentdatetime = Carbon\Carbon::now()->format('Y-m-d H:i:s');
                                            @endphp
                                            @if($date==$currentdate)
                                                @if($request[$i]->start_datetime<$currentdatetime)
                                                    <p>
                                                        {{  link_to($request[$i]->meeting_id, 'Connect with '.$coach, ['target'=>'_blank','class' =>'btn btn-primary']) }}
                                                    </p>
                                                @endif
                                            @endif
                                        @else
                                            <p>
                                                A Link to your online meeting will appear here 10 minutes before the start time.
                                            </p>
                                            <a href="{{ route('clients.dashboard.coaching') }}">
                                                Refresh page
                                            </a>
                                             <br><br>
                                        @endif
                                    @endfor
                                @else
                                    <p>
                                        <div class="col-md-8">
                                            A Link to your online meeting will appear here 10 minutes before the start time.
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('clients.dashboard.coaching') }}">
                                                Refresh page
                                            </a><br><i class="fa fa-refresh" aria-hidden="true"></i> <br><br>
                                        </div>
                                    </p>
                                    <div class="col-md-12">

                                         You will need to download and install
                                        <a href="https://zoom.us/download" target="_blank">
                                        zoom Application
                                        </a>
                                         to take part in this meeting.

                                    </div>
                                    <br><br>
                                @endif
                                    <br>
                                    <a href="/bookfreeschedule/edit/{{ $gratuate_session_details->coach_schedule->id }}">
                                    <button type="submit" name="submit" class="module-feedback" style="background-color:#82cd49;">
                                    Change Format
                                    </button>
                                    </a>
                                    <button type="button" class="btn btn-danger cancle" data-toggle="modal" data-target="#myModal" data_id="{{ $gratuate_session_details->coach_schedule->id }}" >
                                    Cancel Session
                                    </button>
                        @endif
                    </div>
                </div>
            </div>

            {{-- @endif --}}
@elseif($free_session_booked==0)
        <div class="book-session">
            <div class="tab-title">
                    <h1>
                        Coaching Credits
                    </h1>
            </div>
            <hr/>
            <div class="form-content">
                <div class="row step1 no-margin">
                        <div class="col-md-6 col-sm-6 col-xs-6 left-content" style="padding: 2px 120px 101px 41px;margin: 42px 0 0 0;">
                            <div class="client-name">
                                Hi {{ $client }},
                            </div>
                                You are entitled to
                                <b>
                                free introductory 1-1 coaching session
                                </b>
                                with {{ $coach }}.
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-6" style="padding: 5px 75px 0 70px;">
                           {{--  @if($last_booking_hour>=$allow_booking_hour) --}}
                            This 20 minute session is a great way to get to know your  coach. Clients who avail of this session usually end up having a much richer experience of the Life Process Program. And best of all,
                            <strong class="font-bold">
                                It's  FREE
                            </strong>
                            - so why not schedule your session now.
                            <br>
                            <br>

                            <a href="/bookfreeschedule">
                            <button type="submit" name="submit" class="module-feedback" style="background-color:#82cd49;">
                                Schedule Your Session Now
                            </button>
                            </a>
                           {{--  @else
                              you can book your Free Session after
                              @php echo $allow_booking_hour; @endphp
                              hours from your registration
                            @endif --}}
                        </div>
                </div>
            </div>
        </div>
    {{-- @endif --}}
@elseif($free_session_booked==1 && $free_session_details!=''
            && $free_session_details->coach_schedule!=null
            && Carbon\Carbon::parse($free_session_details->coach_schedule->start_datetime)->format('Y-m-d H:i:s') >= Carbon\Carbon::now()->setTimezone($client_timezone)->addHour(-1)->format('Y-m-d H:i:s')
            //&& (Carbon\Carbon::parse($free_session_endtime)->format('Y-m-d H:i:s') >= Carbon\Carbon::now()->setTimezone($client_timezone)->format('Y-m-d H:i:s'))
        )


        <div class="book-session">
            <div class="tab-title">
                <h1>
                    Coaching Credits
                </h1>
            </div>
                <hr/>
            <div class="form-content">
                    <div class="row step1 no-margin">
                        <div class="col-md-6 col-sm-6 col-xs-6 left-content" style="padding: 2px 120px 101px 41px;margin: 2px 0 0 0;">
                            <div class="client-name">
                                Congratulations {{ $client }},
                            </div>
                            You have now scheduled your free introductory session with {{ $coach }}.
                            <p>
                                Here are the details of your free session.
                            </p>
                        </div>
                        @if($free_session_details->coach_schedule!=null)
                            <div class="col-md-6 col-sm-6 col-xs-6" style="padding: 8px 75px 0 70px;">
                                <p>
                                    You are scheduled for the following session:
                                </p>
                                <p>
                                    <strong class="font-bold">
                                        Date :
                                    </strong>
                                    {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$free_session_details->coach_schedule->start_datetime)->format('Y-m-d') }}
                                </p>
                                <p>
                                    <strong class="font-bold">
                                        Time :
                                    </strong>
                                    {{ $total_time }}
                                </p>
                                <p>
                                    <strong class="font-bold">
                                        Format :
                                    </strong>
                                    {{ $free_session_details->meeting_type }}
                                </p>
                                <p>
                                    <strong class="font-bold">
                                        Session :
                                    </strong>
                                    @if($free_session_details->
                                    booked_for=='s')
                                    One hour session
                                    @elseif ($free_session_details->
                                    booked_for=='f')
                                    Introductory Session(20 mins)
                                    @else
                                    Gratuate session
                                    @endif
                                </p>
                                <p>
                                    <strong class="font-bold">
                                        Your Coach :
                                    </strong>
                                    {{ $coach }}
                                </p>
                                @php
                                    $array=sizeof($request);
                                    $array=$array-1;
                                @endphp

                                 @php
                                $array=sizeof($request);
                                $array=$array-1;

                                {{--print_r($array);--}}
                                {{--print_r("<br/>");--}}
                                {{--print_r($request);--}}
                            @endphp
                            @if($array>=0)
                                @for($i=0;$i<=$array;$i++)
                                    @if($request[$i]->coach_schedule_id==$free_session_details->coach_schedules_id)
                                        @php
                                            $date = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$request[$i]->start_datetime)->format('Y-m-d');
                                            $currentdate = Carbon\Carbon::now()->format('Y-m-d');
                                            $currentdatetime = Carbon\Carbon::now()->format('Y-m-d H:i:s');
                                        @endphp
                                        @if($date==$currentdate)
                                            @php
                                                // echo $request[$i]->start_datetime.'/';
                                                // echo $currentdatetime;
                                                {{--echo $request[$i]->start_datetime;--}}
                                                {{--echo "<br/>";--}}
                                                {{--echo $currentdatetime;--}}

                                            @endphp
                                            @if($request[$i]->start_datetime < $currentdatetime)
                                                <p>
                                                    {{  link_to($request[$i]->meeting_id, 'Connect with '.$coach, ['target'=>'_blank','class' =>'btn btn-primary']) }}
                                                </p>
                                            @else
                                                <p>
                                                    <div class="col-md-8">
                                                    A Link to your online meeting will appear here 10 minutes before the start time.
                                                    </div>
                                                    <div class="col-md-4"> <a href="{{ route('clients.dashboard.coaching') }}">
                                                        Refresh page
                                                    </a><br><i class="fa fa-refresh" aria-hidden="true"></i> <br><br>
                                                    </div>
                                                </p>
                                                <button type="submit" name="submit" class="module-feedback" style="background-color:#82cd49;">
                                                    Change Format
                                                </button>

                                                <button type="button" class="btn btn-danger cancle" data-toggle="modal" data-target="#myModal" data_id="{{ $free_session_details->coach_schedule->id }}" class="cancel">
                                                Cancel Session
                                                </button>

                                            @endif
                                        @else
                                            <p>
                                                <div class="col-md-8">
                                                A Link to your online meeting will appear here 10 minutes before the start time.
                                                </div>
                                                <div class="col-md-4"> <a href="{{ route('clients.dashboard.coaching') }}">
                                                    Refresh page
                                                </a><br><i class="fa fa-refresh" aria-hidden="true"></i> <br><br>
                                                </div>
                                            </p>
                                                <button type="submit" name="submit" class="module-feedback" style="background-color:#82cd49;">
                                                    Change Format
                                                </button>

                                                <button type="button" class="btn btn-danger cancle" data-toggle="modal" data-target="#myModal" data_id="{{ $free_session_details->coach_schedule->id }}" class="cancel">
                                                Cancel Session
                                                </button>
                                        @endif

                                    @endif
                                @endfor
                            @else


                                    <p>
                                        <div class="col-md-8">
                                            A Link to your online meeting will appear here 10 minutes before the start time.
                                        </div>

                                        <div class="col-md-4">
                                            <a href="{{ route('clients.dashboard.coaching') }}">
                                            Refresh page
                                            </a><br><i class="fa fa-refresh" aria-hidden="true"></i> <br><br>
                                        </div>
                                    </p>
                                     <button type="submit" name="submit" class="module-feedback" style="background-color:#82cd49;">
                                                    Change Format
                                                </button>

                                                <button type="button" class="btn btn-danger cancle" data-toggle="modal" data-target="#myModal" data_id="{{ $free_session_details->coach_schedule->id }}" class="cancel">
                                                Cancel Session
                                                </button>

                            @endif


                                <div class="col-md-12">
                                    <br>
                                     You will need to download and install
                                    <a href="https://zoom.us/download" target="_blank">
                                    zoom Application
                                     </a>
                                     to take part in this meeting.
                                    <br><br>
                                </div>
                                    <br><br>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            {{-- @endif --}}
@elseif($credits==0)

    <div class="book-session">
        <div class="tab-title">
            <h1>
                Coaching Credits
            </h1>
            <p style="padding: 6px 25px 0px;">
                            <span>
                                You have
                                <strong class="font-bold">
                                    {{ $credits }}
                                </strong>
                                Credits
                            </span>
            </p>
         </div>
                        <hr/>

        <div class="form-content">
            <div class="row step1 no-margin">
                <div class="col-md-6 col-sm-6 col-xs-6 left-content" style="padding: 2px 45px 101px 41px;margin: 2px 0 0 0;">
                    <div class="client-name">
                                        {{--   Use Your credit to book a session with your coach. --}}
                    </div>
                        You will need to purchase credits before you can book your further coaching sessions.
                    <br>
                    <br>
                    <a href="/my-credits">
                            <button type="submit" name="submit" class="module-feedback" style="background-color:#82cd49;">
                                Purchase Credit
                            </button>
                    </a>
                        <br>
                    <div class="panel-body" style="float:right;">
                        <a href="{{ route('client.credithistory') }}" class="pull-right">
                                        View Credit History
                        </a>
                    </div>
                </div>


                <div class="col-md-6 col-sm-6 col-xs-6" style="margin: 15px 0 0 0;">

                    @if(empty($coach_session_details))
                        <p>
                            <b>
                                You will need to purchase credits before you can schedule a coaching session.
                            </b>
                        </p>

                    @elseif(!empty($coach_session_details))
                    @foreach($coach_session_details as $session)
                        @if($session->coach_schedule)
                            <p>
                                You are scheduled for the following session:
                            </p>
                            <p>
                                <strong class="font-bold">
                                    Date :
                                </strong>
                                {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$session->coach_schedule->start_datetime)->format('Y-m-d') }}
                            </p>
                            <p>
                                <strong class="font-bold">
                                    Time :
                                </strong>
                                {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$session->coach_schedule->start_datetime)->format('H:i a') }} - {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$session->coach_schedule->end_datetime)->format('H:i a') }}
                            </p>
                            <p>
                                <strong class="font-bold">
                                    Format :
                                </strong>
                                {{ $session->meeting_type }}
                            </p>
                            <p>
                                <strong class="font-bold">
                                    Session :
                                    @if($session->booked_for=='s')
                                    One hour session
                                    @endif
                                    @if($session->booked_for=='f')
                                    Introductory Session(20 mins)
                                    @endif
                                    @if($session->booked_for=='g')
                                    Gratuate session  {{ $session->booked_for }}
                                    @endif
                                </strong>
                            </p>
                            <p>
                                <strong class="font-bold">
                                    Your Coach :
                                </strong>
                                {{ $coach }}
                            </p>
                            @php
                                $array=sizeof($request);
                                $array=$array-1;
                            @endphp
                            @if($array>=0)
                                @for($i=0;$i<=$array;$i++)
                                    @if($request[$i]->coach_schedule_id==$session->coach_schedules_id)
                                        @php
                                            $date = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$request[$i]->start_datetime)->format('Y-m-d');
                                            $currentdate = Carbon\Carbon::now()->format('Y-m-d');
                                            $currentdatetime = Carbon\Carbon::now()->format('Y-m-d H:i:s');
                                        @endphp
                                        @if($date==$currentdate)
                                            @if($request[$i]->start_datetime<$currentdatetime)
                                                <p>
                                                    {{  link_to($request[$i]->meeting_id, 'Connect with '.$coach, ['target'=>'_blank','class' =>'btn btn-primary']) }}
                                                </p>
                                            @else
                                                <p>
                                                <div class="col-md-8">
                                                A Link to your online meeting will appear here 10 minutes before the start time.
                                                </div>
                                                <div class="col-md-4"> <a href="{{ route('clients.dashboard.coaching') }}">
                                                    Refresh page
                                                </a><br><i class="fa fa-refresh" aria-hidden="true"></i> <br><br>
                                                </div>
                                                </p>
                                                <button type="submit" name="submit" class="module-feedback" style="background-color:#82cd49;">
                                                    Change Format
                                                </button>

                                                <button type="button" class="btn btn-danger cancle" data-toggle="modal" data-target="#myModal" data_id="{{ $session->coach_schedule->id }}" class="cancel">
                                                Cancel Session
                                                </button>

                                            @endif
                                        @else
                                             <p>
                                                <div class="col-md-8">
                                                A Link to your online meeting will appear here 10 minutes before the start time.
                                                </div>
                                                <div class="col-md-4"> <a href="{{ route('clients.dashboard.coaching') }}">
                                                    Refresh page
                                                </a><br><i class="fa fa-refresh" aria-hidden="true"></i> <br><br>
                                                </div>
                                                </p>
                                                <button type="submit" name="submit" class="module-feedback" style="background-color:#82cd49;">
                                                    Change Format
                                                </button>

                                                <button type="button" class="btn btn-danger cancle" data-toggle="modal" data-target="#myModal" data_id="{{ $session->coach_schedule->id }}" class="cancel">
                                                Cancel Session
                                                </button>
                                        @endif

                                    @endif
                                @endfor
                            @else
                                    <p>
                                        <div class="col-md-8">
                                            A Link to your online meeting will appear here 10 minutes before the start time.
                                        </div>
                                        <br>
                                        <button type="submit" name="submit" class="module-feedback" style="background-color:#82cd49;">
                                                    Change Format
                                        </button>

                                        <button type="button" class="btn btn-danger cancle" data-toggle="modal" data-target="#myModal" data_id="{{ $session->coach_schedule->id }}" class="cancel">
                                                Cancel Session
                                        </button>
                                        <div class="col-md-4">
                                            <a href="{{ route('clients.dashboard.coaching') }}">
                                            Refresh page
                                            </a><br><i class="fa fa-refresh" aria-hidden="true"></i> <br><br>
                                        </div>
                                    </p>

                            @endif

                                <br>
                                You will need to download and install
                                <a href="https://zoom.us/download" target="_blank">
                                    zoom Application
                                </a>
                                to take part in this meeting.
                                <br>
                                <br>
                        @endif
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
        {{-- @endif --}}
@elseif($credits>0)

    <div class="book-session">
        <div class="tab-title">
            <h1>
                Coaching Credits
            </h1>
            <p style="padding: 6px 25px 0px;">
                <span>
                    You have
                    <strong class="font-bold">
                        {{ $credits }}
                    </strong>
                    Credits
                </span>
            </p>
        </div>
        <hr/>
        <div class="form-content">
            <div class="row step1 no-margin">
                <div class="col-md-6 col-sm-6 col-xs-6 left-content" style="margin: 10px 0 0 0;">
                    <div class="client-name">
                        Use Your credit to book a session with your coach.
                    </div>
                        You can also purchase more credits if you want to top up your account.
                    <br>
                    <br>
                    <a href="/my-credits">
                            <button type="submit" name="submit" class="module-feedback" style="background-color:#82cd49;">
                                Purchase Credit
                            </button>
                    </a>
                   {{--  @if($last_booking_hour>=$allow_booking_hour) --}}
                        <a href="/bookschedule">
                                <button type="submit" name="submit" class="module-feedback" style="background-color:#82cd49; ">
                                    Book a Session
                                </button>
                        </a>
                  {{--   @endif --}}

                    <div class="panel-body">
                            <a href="{{ route('client.credithistory') }}" class="pull-right">
                                View Credit History
                            </a>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-6" style="margin: 15px 0 0 0;">
                    @foreach($coach_session_details as $session)
                        @if($session->coach_schedule)
                            <p>
                                You are scheduled for the following session:
                            </p>
                            <p>
                                <strong class="font-bold">
                                    Date :
                                </strong>
                                {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$session->coach_schedule->start_datetime)->format('Y-m-d') }}
                            </p>
                            <p>
                                <strong class="font-bold">
                                    Time :
                                </strong>
                                {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$session->coach_schedule->start_datetime)->format('H:i a') }} - {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$session->coach_schedule->end_datetime)->format('H:i a') }}
                            </p>
                            <p>
                                <strong class="font-bold">
                                    Format :
                                </strong>
                                {{ $session->meeting_type }}
                            </p>
                            <p>
                                <strong class="font-bold">
                                    Session :
                                    @if($session->booked_for=='s')
                                    One hour session
                                    @endif
                                    @if($session->booked_for=='f')
                                    Introductory Session(20 mins)
                                    @endif
                                    @if($session->booked_for=='g')
                                    Gratuate session  {{ $session->booked_for }}
                                    @endif
                                </strong>
                            </p>
                            <p>
                                <strong class="font-bold">
                                    Your Coach :
                                </strong>
                                {{ $coach }}
                            </p>
                            @php
                                $array=sizeof($request);
                                $array=$array-1;
                            @endphp
                            @if($array>=0)
                                @for($i=0;$i<=$array;$i++)
                                    @if($request[$i]->coach_schedule_id==$session->coach_schedules_id)
                                        @php
                                            $date = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$request[$i]->start_datetime)->format('Y-m-d');
                                            $currentdate = Carbon\Carbon::now()->format('Y-m-d');
                                            $currentdatetime = Carbon\Carbon::now()->format('Y-m-d H:i:s');
                                        @endphp
                                        @if($date==$currentdate)
                                            @if($request[$i]->start_datetime<$currentdatetime)
                                                <p>
                                                    {{  link_to($request[$i]->meeting_id, 'Connect with '.$coach, ['target'=>'_blank','class' =>'btn btn-primary']) }}
                                                </p>
                                            @else
                                                <p>
                                                    <div class="col-md-8">
                                                    A Link to your online meeting will appear here 10 minutes before the start time.
                                                    </div>
                                                    <div class="col-md-4"> <a href="{{ route('clients.dashboard.coaching') }}">
                                                        Refresh page
                                                    </a><br><i class="fa fa-refresh" aria-hidden="true"></i> <br><br>
                                                    </div>
                                                </p>
                                                     <br>
                                                <button type="submit" name="submit" class="module-feedback" style="background-color:#82cd49;">
                                                    Change Format
                                                </button>

                                                <button type="button" class="btn btn-danger cancle" data-toggle="modal" data-target="#myModal" data_id="{{ $session->coach_schedule->id }}" class="cancel">
                                                Cancel Session
                                                </button>

                                            @endif
                                        @else
                                            <p>
                                                <div class="col-md-8">
                                                A Link to your online meeting will appear here 10 minutes before the start time.
                                                </div>
                                                <div class="col-md-4"> <a href="{{ route('clients.dashboard.coaching') }}">
                                                    Refresh page
                                                </a><br><i class="fa fa-refresh" aria-hidden="true"></i> <br><br>
                                                </div>
                                            </p>
                                                <button type="submit" name="submit" class="module-feedback" style="background-color:#82cd49;">
                                                    Change Format
                                                </button>

                                                <button type="button" class="btn btn-danger cancle" data-toggle="modal" data-target="#myModal" data_id="{{ $session->coach_schedule->id }}" class="cancel">
                                                Cancel Session
                                                </button>
                                        @endif

                                    @endif
                                @endfor
                            @else
                                    <p>
                                        <div class="col-md-12">
                                            A Link to your online meeting will appear here 10 minutes before the start time.
                                        </div>
                                        <div class="col-md-12">
                                        <button type="submit" name="submit" class="module-feedback" style="background-color:#82cd49;">
                                                    Change Format
                                        </button>

                                        <button type="button" class="btn btn-danger cancle" data-toggle="modal" data-target="#myModal" data_id="{{ $session->coach_schedule->id }}" class="cancel">
                                        Cancel Session
                                        </button>
                                        </div>
                                        <div class="col-md-4">
                                            <a href="{{ route('clients.dashboard.coaching') }}">
                                            Refresh page
                                            </a><br><i class="fa fa-refresh" aria-hidden="true"></i> <br><br>
                                        </div>
                                    </p>

                            @endif

                                <br>
                                You will need to download and install
                                <a href="https://zoom.us/download" target="_blank">
                                    zoom Application
                                </a>
                                to take part in this meeting.
                                <br>
                                <br>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            &times;
                        </button>
                        <h4 class="modal-title">
                        </h4>
                    </div>
                    <div class="modal-body">
                        Give Reason For Cancel
                        <input type="hidden" name="coach_booked_session_id" id="bookedid"/>
                        <textarea id="reason" class="form-control" required></textarea>
                        <div id="error">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" id="save">
                            Save
                        </button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @push('scripts')
        <script type="text/javascript">
            jQuery(document).ready(function() {
            jQuery('.cancle').click(function(){
             var a=jQuery(this).attr('data_id');
             jQuery('#bookedid').val(a);
             jQuery('#error').html('');
            });
            });
            jQuery(document).ready(function() {
            jQuery('#save').click(function(){
             var a=jQuery(this).attr('data_id');
             var bookedid= jQuery('#bookedid').val();
             var reason= jQuery('#reason').val();
             if(!reason.trim())
             {
                jQuery('#error').html('<div class="btn btn-danger">Add reason for cancel</div>'); exit;
             }
              $.ajax({
                url: '{!! route("ajax.canclesession") !!}',
                type: 'POST',
                data: {
                bookedid:bookedid,
                 _token:jQuery('input[name="_token"]').val(),
                reason:reason,
                },
                })
               .done(function(data) {
               window.location.reload();
            });
            });
            });

        </script>
    @endpush
