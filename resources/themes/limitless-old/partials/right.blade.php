{{-- <div class="modal fade" id="user" data-backdrop="false">
    <div class="right w-xl bg-white md-whiteframe-z2">
        <div class="box">
            <div class="p p-h-md">
                <a data-dismiss="modal" class="pull-right text-muted-lt text-2x m-t-n inline p-sm">&times;</a>
                <strong>Masters</strong>
            </div>
            <div id="account" class="show m-v-xs">
                <nav>

                </nav>
            </div>
        </div>
    </div>
</div> --}}
<style type="text/css">
    #calendar2 {
    width: 230px !important;
    margin: 10px !important;
    font-size: 14px !important;
}
#calendar2 table{
font-size: 14px !important;
}
#calendar2 .fc-toolbar {
    text-align: left ;
    margin-bottom: 5px;
}
#calendar2 .fc-toolbar .fc-right {
    width:auto;
}
#calendar2 .fc-toolbar .fc-left {
    float: left;
    width: 40%;
}
#calendar2.fc th {
    padding: 8px 2px !important;
}
#calendar2 .fc-view > table {
    min-width: 200px;
}
#calendar2 .fc-scroller {
    overflow-y: scroll;
    overflow-x: hidden;
    min-height: 225px;
}
#calendar2 .fc-basic-view tbody .fc-row {
    min-height: 30px;
    min-width: 30px;
}

</style>
<div class="sidebar sidebar-main sidebar-default" style="padding-left: 20px;padding-right:0">
    <div class="sidebar-content">
        <!-- Latest updates -->
        @if(Auth::user()->user_type == 'client')
            @php
                        $client_coach = App::make('App\Http\Controllers\ClientController');
                        $coach_name = $client_coach->getCoach();
            @endphp
        <div class="sidebar-category">
            <div class="category-title h6">
                <span>Your coach is {{$coach_name or 'Not Assign'}}</span>
                <ul class="icons-list">
                    <li><a href="#" data-action="collapse"></a></li>
                </ul>
            </div>
            <hr>
            <div class="category-content no-padding">
                <ul class="navigation navigation-main navigation-accordion">
                    @if(Auth::user()->hasAccess('myCredits.view') && Auth::user()->user_type == 'client')
                            @php
                                $client_credits = App::make('App\Http\Controllers\ClientController');
                                $total_credits = $client_credits->getClientTotalCredits();
                            @endphp
                            <li class="{{ (Request::segment(1)=='my-credits' ? 'active' : '' ) }}">
                                <a href="{{ route('client.myCredits') }}">
                                    <i class="icon-piggy-bank"></i>
                                    <span>My Credits</span>
                                    @php
                                        $style = 'display:none';
                                        if($total_credits){
                                            $style = 'display:block';
                                        }
                                    @endphp
                                    <span class="badge badge-warning" id="my_total_credits" style="{{ $style }}">
                                        {{ $total_credits }}
                                    </span>
                                </a>
                            </li>
                    @endif
                    @if(Auth::user()->hasAccess('book_schedule.view'))
                            <li class="{{ (Request::segment(1)=='bookschedule' ? 'active' : '' ) }}"><a href="{{ route('bookschedule.index') }}"><i class="fa fa-calendar"></i> <span>Book Coach Schedules</span></a></li>
                    @endif
                </ul>
            </div>
        </div>
        @endif
        @if(Auth::user()->user_type == 'coach')
            <div id='calendar2'></div>
            <hr>
            @php
                $coach_schedule = App::make('App\Http\Controllers\CoachScheduleController');
                $total_schedule = $coach_schedule->getCalenderTotalSession();
                $timezone = $coach_schedule->getusertimezone();
            @endphp
            <div class="category-content text-center" style="padding: 5px;">
                <ul class="list-inline no-margin">
                    <li><label class="label label-primary">Available</label></li>
                    <li><label class="label label-danger">Booked</label></li>
                    <li><label class="label bg-orange-400">Partial Book</label></li>
                </ul>
            </div>
            <hr>
            @if(Auth::user()->hasAccess('schedule.view'))
            <div class="category-content text-center" style="padding: 5px;">
                <div class="mb-10">
                    <a href="{{route('adjust_schedule.index')}}" class="btn bg-pink-400 btn-labeled"><b><i class="fa fa-calendar"></i></b>Manage My Availability</a>
                </div>
            </div>
           @endif
        <!-- /latest updates -->

    </div>
                @php
                    $upcoming_sessions = App::make('App\Http\Controllers\AllSessionController');
                    $upcoming_sessions = $upcoming_sessions->get_index($limit = 5, array());
                @endphp
                    @if(Auth::user()->hasAccess('all_session.view'))
                        <div class="panel panel-default">
                            <div class="panel-heading" >
                                <h6 class="panel-title">
                                    <i class="icon-folder6 position-left"></i>
                                    Upcoming Sessions
                                </h6>
                                {{-- <hr style="margin-bottom: 0"> --}}
                            </div>
                            @if(isset($upcoming_sessions) && count($upcoming_sessions) > 0)
                                <div class="list-group no-border" style="padding-top: 0">
                                    @foreach($upcoming_sessions as  $all_session)
                                    @php

                                    if($all_session->booked_for=='f'){
                                        $for='Free';
                                    }
                                    if($all_session->booked_for=='g'){
                                        $for='Gratuate';
                                    }
                                    if($all_session->booked_for=='s'){
                                        $for='standard';
                                    }
                                    if($all_session->booked_slot>0){
                                        if($all_session->booked_slot==1){
                                            $start_time = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$all_session->coach_schedule->start_datetime)->format('l dS F \- H.i a');
                                            $start = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$all_session->coach_schedule->start_datetime)->format('H:i');
                                            $end_time = Carbon\Carbon::parse($start)->addMinutes(20)->format('l dS F \- H.i a');
                                            $total_time = $start_time."-".$end_time;
                                        }
                                        elseif($all_session->booked_slot==2){
                                            $start_time = Carbon\Carbon::parse($all_session->coach_schedule->start_datetime)->addMinutes(20)->format('l dS F \- H.i a');
                                            $start = Carbon\Carbon::parse($all_session->coach_schedule->start_datetime)->addMinutes(20)->format('H:i');
                                            $end_time = Carbon\Carbon::parse($start)->addMinutes(20)->format('l dS F \- H.i a');
                                            $total_time = $start_time."-".$end_time;
                                        }
                                        elseif ($all_session->booked_slot==3) {
                                            $start_time = Carbon\Carbon::parse($all_session->coach_schedule->start_datetime)->addMinutes(40)->format('l dS F \- H.i a');
                                            $end_time = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$all_session->coach_schedule->end_datetime)->format('l dS F \- H.i a');
                                            $total_time = $start_time."-".$end_time;
                                        }

                                    }
                                     else{
                                            $start_time = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $all_session->coach_schedule->start_datetime)->format('l dS F \- H.i a');
                                            $end_time = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $all_session->coach_schedule->end_datetime)->format('l dS F \- H.i a');
                                            $total_time = $start_time."-".$end_time;
                                        }


                                @endphp
                                        <li class="list-group-item" style="color: #333333;">
                                            <i class="icon-calendar52"></i>{{ $all_session->coach_schedule ? $start_time : ''}}
                                        </li>
                                    @endforeach
                                </div>
                            @else
                                <div class="list-group">
                                    <div class="list-group-item">
                                        <i class=""></i> No upcoming session
                                    </div>
                                </div>
                            @endif
                            <hr style="margin: 0">
                                <li class="list-group-item" style="color: #333333;">
                                    <a href="allsession" class="btn btn-primary btn-xs"><i class=" icon-arrow-right22"></i>  View all sessions</a>
                                </li>
                        </div>
                    @endif

                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                 @php
                                    $total_balance_of_coach = App::make('App\Http\Controllers\CoachTransactionLogController');
                                    $total_balance_of_coach = $total_balance_of_coach->get_total_balance();
                                @endphp
                                <div class="col-md-9" style="line-height: 31px;">Balance - $ {{$total_balance_of_coach or 0}}</div>
                                <div class="col-md-3" >

                                    {{-- <a href="" class="btn btn-primary btn-xs">View</a> --}}
                                    {!! link_to_route('transaction.history', 'View', [], ['class' => 'btn btn-primary btn-xs']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
            @endif
</div>
@if(Auth::user()->user_type == 'coach')
@push('scripts')
<script type="text/javascript">
    jQuery(document).ready(function(){
        // jQuery("body").addClass("sidebar-opposite-visible");
        jQuery('#calendar2').fullCalendar({
               //defaultDate:Date(),
                defaultDate:Date(),
                fixedWeekCount:false,
                dayRender: function (date, cell) {
                @if(isset($total_schedule) && count($total_schedule) > 0)
                    @foreach($total_schedule as $schedule)
                    if(date.format('YYYY/MM/DD') == '{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$schedule['start_datetime'])->format('Y/m/d')}}')
                    {
                       // alert(date.format('YYYY/MM/DD'));
                       @if(($schedule['total_scedule'] == $schedule['total_booked_scedule']) && ($schedule['total_cancel'] == 0))
                            cell.css("background-color", "#F44336"); //booked red
                        @elseif(($schedule['total_booked_scedule'] == $schedule['total_cancel']) || ($schedule['total_booked_scedule'] == 0))
                            cell.css("background-color", "#2196F3"); // blue full available
                        @else
                            cell.css("background-color", "#FFA726"); // partial book
                        @endif
                    }
                    @endforeach
                @endif
                },
                editable: true,
                eventLimit: true, // allow "more" link when too many events
                events: [
                {
                    start: '2017-15-05',
                    end: '2014-05-06',
                    rendering: 'background'
                }
            ]
            });
    });
</script>
@endpush
@endif
