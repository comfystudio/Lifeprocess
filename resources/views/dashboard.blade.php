@extends($theme)
@section('title', 'Dashboard')
@section('content')
<!-- Main content -->
<div class="content-wrapper">
    <style type="text/css">
        .panel-heading,
        .panel-body {
            padding: 15px 20px;
        }
        .spacer-10 { min-width: 10px; }
    </style>
    <div class="panel panel-white">
        <div class="panel-heading">
           <h3 class="panel-title">Dashboard</h3>
        </div>
        <div class="panel-body">
            <div class="">
                <div class="col-md-4 no-padding">
                    <h5><strong> Recent Signups</strong></h5>
                </div>
                <div class="col-md-6">
                    {{ Form::open(array('route' => 'clients.index','method'=>'GET','class'=>'form-filter','role'=>"form")) }}
                    <div class="row">
                        <div class="col-md-12">
                        <div class="form-group">
                            <div class="inner-addon right-addon ">
                                <i class="fa fa-search"></i>
                                {!! Form::text('name_or_email', Request::get('name_or_email',null), ['class' => 'form-control ','placeholder'=> trans("comman.name_or_email") ]) !!}
                            </div>
                        </div>
                        </div>
                    </div>
                    {{Form::close()}}
                </div>
                <div class="col-md-2 no-padding">
                    {!! link_to_route('clients.create', 'Add New Client', [], ['class' => 'btn btn-xs btn-primary pull-right', 'style' => 'color: #FFF;']) !!}
                    <span class="pull-right spacer-10">&nbsp;</span>
                    {{--
                    {!! link_to_route('clients.index', 'View all clients', [], ['class' => 'btn btn-xs btn-primary pull-right', 'style' => 'color: #FFF;']) !!}
                    --}}
                </div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 no-padding">
                <div class="clerfix"></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Client ID</th>
                                <th>Name</th>
                                <th>Date Joined</th>
                                <th>Coach</th>
                                <th>Program</th>
                                {{-- <th width="100px">Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($newest_clients) && count($newest_clients) > 0)
                                @foreach($newest_clients as $client)
                                    <tr>
                                        <td>
                                        @if(Auth::user()->hasAccess('clients.update'))
                                            {!! Html::decode(link_to_route('clients.edit', $client->id, array(Crypt::encryptString($client->id),'_url'=> Route::currentRouteName()), ['class' => 'btn', 'title' => 'Edit', 'target' =>"_blank"])) !!}
                                        @else
                                            {{ $client->id }}
                                        @endif
                                        </td>
                                        <td>
                                            {!! Html::decode(link_to_route('messages.admindata', $client->user->name, array('role' => 'client', 'id' => Crypt::encryptString($client->user_id)), ['class' => 'btn', 'title' => 'Contact' ,'target' => "_blank"])) !!}
                                        </td>
                                        <td> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $client->created_at)->format('m/d/Y')  }} </td>
                                        <td>
                                            {{ ($client->coach) ? $client->coach->user->name : ''}}
                                        </td>
                                        <td>{{$client->program->program_name or '' }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">
                                        No client has been registered in this month.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        {{--
        <div class="panel-heading bg-white">
            <h5 class="panel-title no-padding">Newest Coaches
            </h5>
        </div>
        --}}
        <div class="panel-body">
            <div class="">
                <div class="col-md-4 no-padding">
                    <h5><strong>Coaches</strong></h5>
                </div>
                <div class="col-md-6">
                    {{ Form::open(array('route' => 'coaches.index','method'=>'GET','class'=>'form-filter','role'=>"form")) }}
                    <div class="row">
                        <div class="col-md-12">
                        <div class="form-group">
                            <div class="inner-addon right-addon ">
                                <i class="fa fa-search"></i>
                                {!! Form::text('fullname', Request::get('name',null), ['class' => 'form-control ','placeholder'=> trans("comman.name") ]) !!}
                            </div>
                        </div>
                        </div>
                    </div>
                    {{Form::close()}}
                </div>
                <div class="col-md-2 no-padding">
                    {!! link_to_route('coaches.create', 'Add New Coach', [], ['class' => 'btn btn-xs btn-primary pull-right', 'style' => 'color: #FFF;']) !!}
                </div>
            </div>
            <div class="col-md-12 no-padding">
                <div class="table-responsive">
                    <table class="table table-bordered table-lg">
                        <thead>
                            <tr>
                                <th>Coach ID</th>
                                <th>Name</th>
                                {{-- <th>Date Joined</th> --}}
                                <th>Active Clients</th>
                                <th>Last Login</th>
                                <th width="100px">Login As</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($newest_coaches) && count($newest_coaches) > 0)
                                @foreach($newest_coaches as $coach)
                                    <tr>
                                        <td>
                                            @if(Auth::user()->hasAccess('coaches.update'))
                                                {!! Html::decode(link_to_route('coaches.edit', $coach->id, array(Crypt::encryptString($coach->id),'_url'=> Route::currentRouteName()), ['class' => 'btn', 'title' => 'Edit'])) !!}
                                            @else
                                                {{ $coach->id}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(Auth::user()->hasAccess('messages.create'))
                                                {!! Html::decode(link_to_route('messages.admindata', $coach->user->name , array('role' => 'coach', 'id' => Crypt::encryptString($coach->user_id)), ['class' => 'btn', 'title' => 'Contact'])) !!}
                                            @else
                                                {{ $coach->user->name }}
                                            @endif
                                        </td>
                                        <td> {{ $coach->clients->count() }} </td>
                                        <td>
                                        @if(isset($coach->user->last_login) && $coach->user->last_login != "0000-00-00 00:00:00")
                                            {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $coach->user->last_login)->format('m/d/Y')  }}
                                        @endif
                                        </td>
                                        <td></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5">
                                        No coach has been registered in this month.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading bg-white">
            <h5 class="panel-title"> Programs </h5>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-lg">
                    <thead>
                        <tr>
                            <th>Program Name</th>
                            <th width="175px">No of Active Clients</th>
                            <th width="175px">% Of Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total_clients = $programs->sum(function($program){
                                    return $program->active_clients;
                            });
                            $total_client_per= 00.00;
                        @endphp
                        @if(isset($programs) && count($programs) > 0)
                            @foreach($programs as $program)
                                <tr>
                                    <td> {{ $program->program_name }} </td>
                                    <td> {{ $program_client = ($program->active_clients) }} </td>
                                    <td>
                                        @if($program_client > 0)
                                        @php $total_client_per= $total_client_per+number_format(round($program_client/$total_clients * 100, 2),'2','.','') @endphp
                                        {{ number_format(round($program_client/$total_clients * 100, 2),'2','.','') }}%
                                        @else
                                            00.00%
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">
                                    No Program found
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td colspan=""><strong>Total</strong></td>
                            <td>{{ $total_clients or ''}}</td>
                            <td>{{ $total_client_per }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading bg-white">
            <h5 class="panel-title"> Client Activity
            </h5>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-lg">
                    <thead>
                        <tr>
                            <th>Days</th>
                            <th width="150px">No of Active Clients</th>
                            <th width="150px">%</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td> Last 30 Days </td>
                            @php $total_client_login_30_day_per=0; $total_client_login_60_day_per=0;@endphp
                            <td>{{$client_count['client_login_in_30_day']}}</td>
                            <td>

                                @if($client_count['client_login_in_30_day'] > 0)
                                 @php  $total_client_login_30_day_per=$total_client_login_30_day_per+number_format(round($client_count['client_login_in_30_day']/$total_clients * 100, 2),'2','.','')
                                @endphp
                                    {{ number_format(round($client_count['client_login_in_30_day']/$total_clients * 100, 2),'2','.','') }}%
                                @else
                                    00.00%
                                @endif
                            </td>
                         </tr>
                         <tr>
                            <td> Last 60 Days </td>
                            <td> {{$client_count['client_login_in_60_day']}} </td>
                            <td>
                                @if($client_count['client_login_in_60_day'] > 0)
                                @php  $total_client_login_30_day_per=$total_client_login_30_day_per+number_format(round($client_count['client_login_in_60_day']/$total_clients * 100, 2),'2','.','')
                                @endphp
                                    {{ number_format(round($client_count['client_login_in_60_day']/$total_clients * 100, 2),'2','.','') }}%
                                @else
                                    00.00%
                                @endif
                            </td>
                         </tr>
                         <tr>
                             <td>Total</td>
                             <td>{{$client_count['client_login_in_30_day']+$client_count['client_login_in_60_day']}}</td>
                             <td>{{$total_client_login_30_day_per}}%</td>
                         </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="panel">
                <div class="panel-heading bg-white" >
                    <h5 class="text-semibold panel-title">
                        <i class="icon-folder6 position-left"></i>
                        Upcoming Sessions
                    </h5>
                    {{-- <hr style="margin-bottom: 0"> --}}
                </div>
                @if(isset($upcoming_sessions) && count($upcoming_sessions) > 0)
                    <div class="col-md-12" style="padding-top: 0">
                     <table class="table table-bordered table-lg session-table">
                     <thead>
                        <tr>
                            <th>Session</th>
                            <th>Coach</th>
                            <th>Client</th>
                        </tr>
                    </thead>
                     <tbody>

                        @foreach($upcoming_sessions as $all_session)
                        @if(isset($all_session->user) && !empty($all_session))
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
                          <tr>
                          <td>
                            <li class="list-group-item" style="color: #333333;">
                                <i class="icon-calendar52"></i>
                                {{ $start_time }}
                            </li>
                            </td>
                            <td>
                                {{ $all_session->coach_schedule->user->name }}
                            </td>
                            <td>{{ $all_session->user->name }}
                            </td></tr>
                        @endif
                        @endforeach
                        </tbody>
                    </table>
                    </div>


                    @if(Auth::user()->hasAccess('all_session.view'))

                        <div class="">
                        <a href="allsession" class="btn btn-primary btn-xs" style="margin: 10px; margin-left: 10px;"><i class=" icon-arrow-right22"></i>  View all upcoming sessions</a>
                        </div>

                    @endif

                @else
                    <div class="list-group">
                        <a href="#" class="list-group-item">
                            <i class=""></i> No upcoming session
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    </div>
    <!-- Widget Data End -->
    </div>
</div>
<!-- /dashboard content -->
<!-- Bottom right menu -->
<ul class="fab-menu fab-menu-fixed fab-menu-bottom-right" data-fab-toggle="click">
    <li>
        <a class="fab-menu-btn btn  btn-primary btn-float btn-rounded btn-icon">
            <i class="fab-icon-open icon-plus3"></i>
            <i class="fab-icon-close icon-cross2"></i>
        </a>
        <ul class="fab-menu-inner">
            <li>
                <div data-fab-label="Add Client">
                    {!! Html::decode(link_to_route('clients.create', '<i class="icon-user"></i>', array('_url'=> Route::currentRouteName()), ['class' => 'btn bg-violet btn-rounded btn-icon btn-float'])) !!}
                </div>
            </li>
            <li>
                <div data-fab-label="Add Coach">
                    {!! Html::decode(link_to_route('coaches.create', '<i class="icon-eye"></i>', array('_url'=> Route::currentRouteName()), ['class' => 'btn bg-orange btn-rounded btn-icon btn-float'])) !!}
                </div>
            </li>
        </ul>
    </li>
</ul>
<!-- /bottom right menu -->
</div>
@push('scripts')
<script type="text/javascript">
 $(window).scroll(function() {
        if($(window).scrollTop() + $(window).height() > $(document).height() - 40) {
            $('.fab-menu-bottom-left, .fab-menu-bottom-right').addClass('reached-bottom');
        }
        else {
            $('.fab-menu-bottom-left, .fab-menu-bottom-right').removeClass('reached-bottom');
        }
    });
 </script>
 @endpush
<!-- /main content -->
@endsection
