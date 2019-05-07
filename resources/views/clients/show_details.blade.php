@extends($theme)
@section('title', 'Client Details')
@section('content')
<style type="text/css">
    .btn-labeled.btn-xs > b {
        padding: 7px;
    }
    .clearfix {
        background-color:#ffffff;
    }
    .panel-title-remove{ padding-left: 10px;  }
</style>
<div class="content-wrapper">
    @if(isset($client_details->user_id)
        && !empty($client_details->user_id))
        {!! Form::model($client_details, ['method' =>'POST','route' =>['clientdetails.update', $client_details->user_id],'class' =>'form-horizontal','files'=>'true']) !!}
            <div class="panel panel-default ">
                <div class="panel-heading">
                    <h5 class="panel-title"> Client Info </h5>
                </div>
                <div class="panel-body row">
                    <div class="col-md-6">
                        <div class="form-group">
                        <label for="id" class="col-md-8">Client ID:</label>
                        <div class="col-md-12">
                            {!! Form::text('id',$client_details->user->id,['class' =>'form-control','id'=>'userid','readonly']) !!}
                        </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email" class="col-md-6"> Email:</label>
                            <div class="col-md-12">
                            {{ Form::email('email', $client_details->user->email,['class' =>'form-control']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        <label for="card_type" class="col-md-12">First Name:</label>
                        <div class="col-md-12">
                        {{ Form::text('first_name', $client_details->user->first_name,['class' =>'form-control']) }}
                        </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        <label for="card_type" class="col-md-12">Password:</label>
                        <div class="col-md-12">
                        {{ Form::password('password',['class' =>'form-control']) }}
                        </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" class="col-md-6">Last Name:</label>
                            <div class="col-md-12">
                            {{ Form::text('last_name', $client_details->user->last_name,['class' =>'form-control']) }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        <label for="" class="col-md-12">Time Zone:</label>
                        <div class="col-md-12">
                            @php
                             $set_timezone = null;
                             if(isset($client_details->user->timezone)) {
                             $set_timezone = $client_details->user->timezone;
                              }
                            @endphp
                            {!! Form::select('timezone',  $timezones,$set_timezone , ['class' =>'form-control single-select', 'id' =>'timezone']) !!}
                            {!! ($errors->has('timezone') ? $errors->first('timezone',
                            '<p class="text-danger">:message</p>') : '') !!}
                        </div>
                        </div>
                    </div>
                    <div class="col-md-12 @if(isset(Auth::user()->user_type) && Auth::user()->user_type == 'agent') hide @endif">
                        <div class="panel-heading no-padding">
                            <h5 class=""><b>Payment Details</b></h5>
                        </div>
                    </div>
                    @if(isset($client_details->user->credit_card_detail)
                                && !empty($client_details->user->credit_card_detail))
                        <div class="col-md-12">
                            <div class="col-md-5">
                                <div class="form-group">
                                <label for="">Card Type:</label>
                                <br>
                                {{$client_details->user->credit_card_detail->card_type }}
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="">Card Number:</label><br>
                                    @php
                                    $number=$client_details->user->credit_card_detail->card_number;
                                    echo 'xxxx-xxxx-xxxx-'.substr($number, -4);
                                    @endphp
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for=""> Card Holder Name:</label>
                                    <br>{{$client_details->user->credit_card_detail->card_holder}}
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for=""> Expiry Date:</label>
                                    <br>{{$client_details->user->credit_card_detail->expiry_date}}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-12 @if(isset(Auth::user()->user_type) && Auth::user()->user_type == 'agent') hide @endif">
                            <div class="col-md-6">
                                <label for="">Card Type:</label>
                                <br>
                                -
                            </div>
                            <div class="col-md-6">
                                <label for="">Card Number:</label>
                                 <br>
                                -
                            </div>
                            <div class="col-md-6">
                                <label for="">Card Holder Name:</label>
                                 <br>
                                -
                            </div>
                            <div class="col-md-6">
                                <label for="">Expiry Date:</label>
                                 <br>
                                -
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading bg-white">
                    <h5 class="panel-title"> Client Activity: </h5>
                </div>
                <div class="panel-body row">
                        <div class="col-md-6">
                            <div class="form-group">
                            {!! Html::decode(Form::label('program_id', 'Program:', ['class' =>'col-md-6'])) !!}
                            <div class="col-md-12">
                             {!! Form::select('program_id', array(""=>trans("comman.select_coach"))+$programs,Request::get('program_id',null),array('class' =>'form-control single-select','id'=>'programid')) !!}
                              {!! ($errors->has('program_id') ? $errors->first('program_id', '<p class="text-danger">:message</p>') : '') !!}
                            </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            {!! Html::decode(Form::label('last_login', 'Last Login:', ['class' =>'col-md-4'])) !!}
                            <div class="col-md-12">
                            @if(isset($client_details->user->last_active) && !empty($client_details->user->last_active))
                                {{ Form::text('last_login',\Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$client_details->user->last_active)->format('d-m-Y'),['class' =>'form-control']) }}
                            @else
                                {{ Form::text('last_login','',['class' =>'form-control']) }}
                            @endif
                            </div>
                            </div>
                        </div>
                        <input type="hidden" id="userid" value={{ $client_details->user->id or '' }}>
                        <div class="clearfix"></div>
                        <div class="col-md-6">
                        @if(!empty($client_details->coach->id))
                        <input type="hidden" name="coach_id" value="{{ $client_details->coach->id }}">
                        @endif
                          {{--   @if($module_remain > 0)
                            <div class="form-group">
                            {!!  Html::decode(Form::label('coach_id:', trans("comman.coach"). ':', ['class' =>'col-md-4'])) !!}
                            <div class="col-md-12">
                            {{ Form::label($client_details->coach->user->name,'',['class' =>'form-control']) }}
                            </div>
                            </div>
                            @else --}}
                            <div class="form-group">
                            {!!  Html::decode(Form::label('coach_id:', trans("comman.coach"). ':', ['class' =>'col-md-4'])) !!}
                            <div class="col-md-12">
                            {!! Form::select('coach_id',array(""=>trans("comman.select_coach"))+$coaches,Request::get('coach_id',null),array('class' =>'form-control single-select')) !!}
                            {!! $errors->first('coach_id', '<p class="help-block">:message</p>') !!}
                            </div>
                            </div>
                           {{--  @endif --}}
                        </div>
                        <div class="col-md-6">
                            {!! Html::decode(Form::label('Module Progress:'),['class' =>
                            'col-md-4 control-label ']) !!}
                            <br/>
                            @if(count($client_details->user->latest_module)>0)
                                @php
                                    $latest_module = $client_details->user->latest_module->first();
                                    $latest_module=$latest_module->module_title;
                                @endphp
                            @else
                            @php
                            $latest_module='No Module Selected';
                            @endphp
                            @endif
                            {{ Form::label($latest_module,'',['class' =>'form-control']) }}
                            {!! $errors->
                            first('module_id', '
                            <p class="help-block">
                                :message
                            </p>
                            ') !!}
                        </div>
                        <div class="clearfix">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            {!!  Html::decode(Form::label('module_id:', 'Date Joined:', ['class' =>'col-md-4 control-label'])) !!}
                            <div class="col-md-12">
                            {{ Form::text('datejoined',  \Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$client_details->user->created_at)->format('d-m-Y') ,['class' =>'form-control']) }}
                            </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                <div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('status', trans('comman.status') . ':', ['class' => 'col-md-4 control-label '])) !!}
                    <?php if($client_details->user->status=='active'){
                            $active='true';
                            $in_active='';
                        }
                            else{
                                $active='';
                                $in_active='true';
                            }
                    ?>
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="radio">
                                <label>
                                     {!! Form::radio('status', 'active',$active) !!} active
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio">
                                <label>
                                    {!! Form::radio('status', 'in_active',$in_active) !!} Inactive
                                </label>
                            </div>
                        </div>

                        {!! ($errors->has('status') ? $errors->first('status', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                     <?php if($client_details->module_restriction=='Yes'){
                            $active='';
                            $in_active='Yes';
                        }
                            else{
                                $active='No';
                                $in_active='';
                            }
                    ?>
                    {!! Html::decode(Form::label('Override Max No of Modules Restriction', 'Override Max No of Modules Restriction :', ['class' => 'col-md-4 control-label '])) !!}
                     <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="radio">
                                <label>
                                     {!! Form::radio('module_restriction', 'No',$active) !!} No
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio">
                                <label>
                                    {!! Form::radio('module_restriction', 'Yes',$in_active) !!} Yes
                                </label>
                            </div>
                        </div>
                        {!! ($errors->has('module_restriction') ? $errors->first('status', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                </div>
            </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="col-md-6 pull-right text-right">
                                    @if(Auth::user()->user_type != 'read-only-coach')
                                        {!! Form::submit(trans('comman.save'),['name' =>
                                        'save','class' =>
                                        'btn btn-primary']) !!}
                                        @if(request()->
                                        get('_url'))
                                        {!! Html::decode(link_to(request()->
                                        get('_url'), trans('comman.save_exit'),array('class' =>
                                        'btn btn-primary'))) !!}
                                        @else
                                        @if(!Request::get("download",false))
                                        {!! Form::submit(trans('comman.save_exit'), ['name' =>
                                        'save_exit','class' =>
                                        'btn btn-primary']) !!}
                                        @endif
                                        @endif
                                        {!! link_to(URL::full(), trans('comman.cancel'),array('class' =>
                                        'btn btn-warning cancel')) !!}
                                    @endif
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        {!! Form::close() !!}

        {!! Form::model($client_details, ['method' =>'POST','route' =>['clientdetails.updatenote', $client_details->user_id],'class' =>'form-horizontal','files'=>'true']) !!}
            <div class="panel panel-default">
                <div class="panel-heading bg-white">
                    <h5 class="panel-title">Notes:</h5>
                </div>
                <div class="panel-body row">
                    <div class="col-md-12">
                       <input type="hidden" name="route" value="{{ url()->current() }}">
                                <label for="">Notes Shared With Coach:</label>
                                @if(count($coach_note)>0 && !empty($coach_note))

                                <div id="coachnote">
                                    @foreach($coach_note as $note)
                                    <label> {{ $note->updated_at }} </label>
                                    <textarea class="form-control" name="coachnote[{{ $note->id }}]" id="note">{{ $note->note}}</textarea>
                                    @endforeach
                                </div>
                                @else
                                 <div id="coachnote">
                                 <textarea class="form-control"  name="addcoachnote"></textarea>
                                 </div>
                                @endif


                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">Notes for Admin:</label>
                                <textarea class="form-control" name="adminnote">{{ $client_details->admin_note or '' }}</textarea>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <div class="col-md-12 text-right">
                                @if(Auth::user()->user_type != 'read-only-coach')
                                    {!! Form::submit(trans('comman.save'),['name' =>'save','class' =>'btn btn-primary']) !!}
                                    @if(request()->get('_url')){!! Html::decode(link_to(request()->get('_url'), trans('comman.save_exit'),array('class' =>'btn btn-primary'))) !!}
                                    @else
                                        @if(!Request::get("download",false))
                                        {!! Form::submit(trans('comman.save_exit'), ['name' =>
                                        'save_exit','class' =>
                                        'btn btn-primary']) !!}
                                        @endif
                                    @endif
                                    {!! link_to(URL::full(), trans('comman.cancel'),array('class' =>
                                    'btn btn-warning cancel')) !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        {!! Form::close() !!}
    @endif

    <div class="panel panel-default">
        <div class="panel-heading bg-white">
            <h5 class="panel-title">Coaching</h5>
            <br>
            <b>Coaching credits: {{ $client_details->credits or '' }}</b>
            <br><br>
            Upcoming Sessions:
            <br>
        </div>
        <div class="panel-body row">
            <div class="col-md-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td><b>Session</b></td>
                            <td class="panel-title"><b>Date</b></td>
                            <td class="panel-title"><b>Time</b></td>
                            <td class="panel-title"><b>Contact</b></td>
                            <td class="panel-title"><b>Coach</b></td>
                            <td class="panel-title"><b>Cancel</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($upcoming_coach_sessions) && !empty($upcoming_coach_sessions))

                        @foreach($upcoming_coach_sessions as $c)
                        @if(isset($c->coachschedulebooked) && !empty($c->coachschedulebooked))
                            @php

                                    if($c->coachschedulebooked->booked_for=='f'){
                                        $for='20';
                                    }
                                    if($c->coachschedulebooked->booked_for=='g'){
                                        $for='20';
                                    }
                                    if($c->coachschedulebooked->booked_for=='s'){
                                        $for='60';
                                    }
                                    if($c->coachschedulebooked->booked_slot>0){
                                        if($c->coachschedulebooked->booked_slot==1){
                                            $start_time = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$c->start_datetime)->format('H:i a');
                                            $start = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$c->start_datetime)->format('H:i');
                                            $end_time = Carbon\Carbon::parse($start)->addMinutes(20)->format('H:i a');
                                            $total_time = $start_time."-".$end_time;
                                        }
                                        elseif($c->coachschedulebooked->booked_slot==2){
                                            $start_time = Carbon\Carbon::parse($c->start_datetime)->addMinutes(20)->format('H:i a');
                                            $start = Carbon\Carbon::parse($c->start_datetime)->addMinutes(20)->format('H:i');
                                            $end_time = Carbon\Carbon::parse($start)->addMinutes(20)->format('H:i a');
                                            $total_time = $start_time."-".$end_time;
                                        }
                                        elseif ($c->coachschedulebooked->booked_slot==3) {
                                            $start_time = Carbon\Carbon::parse($c->start_datetime)->addMinutes(40)->format('H:i a');
                                            $end_time = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$c->end_datetime)->format('H:i a');
                                            $total_time = $start_time."-".$end_time;
                                        }

                                    }
                                     else{
                                            $start_time = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $c->start_datetime)->format('H:i a');
                                            $end_time = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $c->end_datetime)->format('H:i a');
                                            $total_time = $start_time."-".$end_time;
                                        }


                                @endphp
                            <tr>
                                <td>{{ $for }}min</td>
                                <td>
                                    {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $c->start_datetime)->format('d-m-Y')  }}
                                </td>
                                <td>
                                    {{ $start_time }}-{{ $end_time }}
                                </td>
                                <td>{{ $c->coachschedulebooked->meeting_type }}</td>
                                <td>{{ $c->user->name }}</td>
                                <td>
                                    <button type="button" class="btn btn-danger cancle" data-toggle="modal" data-target="#myModal" data_id="{{ $c->id }}" data_clientid="{{$c->coachschedulebooked->booked_user_id}}">Cancel Session</button>

                                </td>
                                @endif
                            </tr>
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
                                        <input type="hidden" name="clientid" id="clientid"/>
                                        <textarea id="reason" class="form-control" required></textarea>
                                        <div id="error"></div>
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
                        @endforeach
                        @endif

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading bg-white">
            <h5 class="panel-title">Coaching History</h5>
        </div>
        <div class="panel-body row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <td><b>Session</b></td>
                        <td><b>Date</b></td>
                        <td><b>Time</b></td>
                        <td><b>Contact</b></td>
                        <td><b>Coach</b></td>
                       <td><b>Status</b></td>
                    </tr>
                </thead>
                <tbody>
                @if(isset($coach_booked_session) && !empty($coach_booked_session))
                    @foreach($coach_booked_session as $c)
                    @if($c->completed_session==NULL)
                    @else
                    <tr>
                        <td>session</td>
                        <td>
                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $c->completed_session->completed_at)->format('d-m-Y')  }}
                        </td>
                        <td>
                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $c->completed_session->completed_at)->format('H:i')  }}
                        </td>
                        <td>{{ $c->completed_session->contact_methods }}</td>
                        <td>{{ $c->coach_schedule->user->name }}</td>
                        <td>{{ $c->session_status }}</td>
                    </tr>
                    @endif
                    @endforeach
                @endif

                @if(isset($coach_cancle_session) && !empty($coach_cancle_session))
                        @foreach($coach_cancle_session as $cancle_session)
                            @if(isset($cancle_session->coach_schedule->start_datetime)
                                && !empty($cancle_session->coach_schedule->start_datetime))
                            @php
                            $datetime1 = strtotime($cancle_session->coach_schedule->start_datetime);
                            $datetime2 = strtotime($cancle_session->coach_schedule->end_datetime);
                            $interval  = abs($datetime2 - $datetime1);
                            $minutes   = round($interval / 60);
                            $minutes   = 60;
                            @endphp
                            @endif
                            <tr>
                                @if(isset($cancle_session->coach_schedule->start_datetime))
                                  <td>{{ $minutes or '' }}min</td>
                                <td>
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $cancle_session->coach_schedule->start_datetime)->format('d-m-Y') }}
                                </td>
                                @endif
                                @if(isset($cancle_session->coach_schedule->end_datetime))
                                <td>
                                {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $cancle_session->coach_schedule->end_datetime)->format('H:i') }}
                                </td>

                                <td>{{ $cancle_session->meeting_type }}</td>
                                <td>{{ $cancle_session->coach_schedule->user->name}}</td>
                                <td>1 Hour session (Cancelled)</td>
                                @endif
                            </tr>
                        @endforeach
                        @endif
                </tbody>
            </table>
        </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading bg-white">
            <h5 class="panel-title">Coaching Purchase History:</h5>
        </div>
        <div class="panel-body row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <td><b>Date</b></td>
                    <td><b>Format</b></td>
                    <td><b>Credit Purchased</b></td>
                    <td><b>Total Paid</b></td>
                    <td><b>Result</b></td>
                </tr>
                </thead>
                <tbody>
                @php $cr=0; @endphp
                @if(isset($client_details->user->credit_history)
                    && !empty($client_details->user->credit_history))
                @foreach($client_details->user->credit_history as $credit_history)
                @if(isset($credit_history->creditpackage) && !empty($credit_history->creditpackage))
                @if($credit_history->transaction_type=='plus')
                <tr>
                    <td>
                       {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $credit_history->created_at)->format('d-m-Y')  }}
                    </td>
                    <td>{{ $credit_history->payment_type }}</td>
                    <td>{{ $credit_history->credit_score }}</td>
                    <td>${{ number_format($credit_history->creditpackage->price ,2) * $credit_history->credit_score }}</td>
                    <td> Success </td>
                </tr>
                @php $cr+=$credit_history->creditpackage->price * $credit_history->credit_score; @endphp
                @endif
                @endif
                @endforeach
                @endif
                </tbody>
            </table>
            <p class="spacer"></p>
            <label for=""><b>Coaching Revenue To Date: ${{ $cr }}</b></label>
        </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading bg-white">
            <h5 class="panel-title">Membership Payment History:</h5>
        </div>
        <div class="panel-body row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                    <tr class="table-info">
                        <td><b>Date</b></td>
                        <td><b>Format</b></td>
                        <td><b>Type</b></td>
                        <td><b>Result</b></td>
                    </tr>
                </thead>
                <tbody>
                    @php $total=0; @endphp
                    @if(isset($client_details->user->transactionHistories)
                    && !empty($client_details->user->transactionHistories))
                    @foreach($client_details->user->transactionHistories as $key=>$history)
                    <tr>
                    <td>
                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $history->created_at)->format('d-m-Y')  }}
                    </td>
                    <td> {{$history->format}} </td>
                    <td>{{ $history->transaction_type }}</td>
                    <td>{{ $history->transaction_status }}</td>
                    </tr>
                @if($history->transaction_status!='Failure')
                    @php $total+=$history->transaction_amount; @endphp
                @endif
                @endforeach
                @endif
                </tbody>
            </table>
            <p class="spacer"></p>
            <label for=""><b>Revenue To Date: ${{ $total }}</b></label>
        </div>
        </div>
    </div>
</div>
@include('clients.popup')
@endsection
@push('scripts')
<script type="text/javascript">
   jQuery(document).ready(function() {
   jQuery('.cancle').click(function(){
     var a=jQuery(this).attr('data_id');
     var b=jQuery(this).attr('data_clientid');
     jQuery('#bookedid').val(a);
     jQuery('#clientid').val(b);
     jQuery('#error').html('');
    });
    });
    jQuery(document).ready(function() {
    jQuery('#save').click(function(){
     var a=jQuery(this).attr('data_id');
     var bookedid= jQuery('#bookedid').val();
     var clientid= jQuery('#clientid').val();
     var reason= jQuery('#reason').val();
     var userid= jQuery('#userid').val();
     // var bookedid= jQuery('#bookedid').val();
     if(!reason.trim())
     {
        jQuery('#error').html('<div class="text-danger">* Add reason for cancel</div>'); exit;
     }
     console.log(bookedid);
     console.log(userid);
     console.log(reason);
      $.ajax({
        url: '{!! route("ajax.canclesession") !!}',
        type: 'POST',
        data: {
        bookedid:bookedid,
        clientid:clientid,
         _token:jQuery('input[name="_token"]').val(),
        reason:reason,
        userid:userid,
        },
        })
       .done(function(data) {
        console.log(data);
         window.location.reload();
    });
    });
});
   jQuery(document).ready(function() {
   jQuery('#coachid').change(function(){
   var coachid=jQuery('#coachid').val();
   var userid=jQuery('#userid').val();
   console.log(coachid);
   console.log(userid);
    $.ajax({
        url: '{!! route("ajax.notes") !!}',
        type: 'POST',
        data: {
        _token:jQuery('input[name="_token"]').val(),
        coach_id:coachid,
        client_id:userid,
        },
        })
        .done(function(data) {
        var j=data.length;
        jQuery('#coachnote').html('');
        for(var i=0;i<j;i++)
        {
            jQuery('#coachnote').append('<label>'+data[i].updated_at+'</label><textarea class=form-control name=coachnote['+data[i].id+']>'+data[i].note+'</textarea>');
        }
    });
   });
   });
</script>
{!! ajax_fill_dropdown('program_id','coach_id',route('ajax.coach')) !!}
{!! ajax_fill_dropdown('coach_id','module_id',route('ajax.ajaxcoachmodule')) !!}
@endpush
