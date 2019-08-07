@extends($theme)
@section('title', 'Client Details')
@section('content')
<style type="text/css">
    .table-lg>tbody>tr>td {
        padding: 10px 20px;
    }
    .table > tbody > tr > td {
        vertical-align: middle;
    }
    .table > thead > tr {
    /* background: #BBDDF7; */
    background: #E3F3FB;
    font-weight: bold;
   }
    .text-ellipsis {
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
</style>
<!-- Main content -->
<div class="content-wrapper">
    <!-- Dashboard content -->
    <div class="panel panel-default">
        <div class="panel-heading" style="padding: 5px 20px;">
            <div class="row">
                <div class="col-md-10" >
                    <h5 >Clients</h5>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
              <div class="tr">
                <div class="col-md-4">
                 Name:  <br/>
                 <span style="color: #999;">{{ $client->user->name }}</span>
                </div>

                <div class="col-md-4">Last Login: <br>
                 <span style="color: #999;">{{ $client->user->last_active }}</span>
                </div>

                <div class="col-md-4">Program: <br>
                 <span style="color: #999;">{{ isset($client->program) ? $client->program->program_name : '' }} </span>
                </div>
                <div class="col-md-12"><br></div>
                <div class="col-md-4">Time Zone: <br>
                 <span style="color: #999;">{{ $client->user->timezone }}</span>
                </div>

                <div class="col-md-4">Module Progress: <br>
                 <span style="color: #999;">@if(count($client->user->latest_module) > 0)
                                        @php
                                            $latest_module = $client->user->latest_module->first();
                                        @endphp
                                        {{ $latest_module->module_no }}.{{ $latest_module->module_title }}
                                    @else
                                       -
                                    @endif</span>
                </div>

                <div class="col-md-4">Life Story: <br>
                  {!! Html::decode(link_to_route('mylifestory.show', 'View Life Story', array(Crypt::encryptString($client->user_id)) , ['class' => ''])) !!}
                </div>
                </div>

                <div class="col-md-12">
                 <br>
                 {!! Html::decode(link_to_route('messages.admindata',' Message Client
                   <i class="fa fa-comments"></i>',['role' => 'coach-client', 'id' => Route::Input('client_id')],['class' => 'btn btn-primary'])) !!}
                </div>
                <div class="col-md-12">
                <br>
                       <table class="table table-bordered table-lg">
                        <thead>
                        <tr>

                            <td><h5>Feedback – Complete Modules</h5></td>
                        </tr>
                        </thead>

                         @if(!empty($client_activity) && count($client_activity->user->module_progress) > 0)

                                    @php $module_id=array(); @endphp

                                    @if(count($client_activity->user->module_progress) > 0)
                                        @foreach($client_activity->user->module_progress as $feedback)
                                          @if($feedback->pivot->module_exercise_id!=0 && $feedback->pivot->status=='reviewed')
                                          @if(in_array($feedback->pivot->module_id, $module_id))

                                         @else
                                        <tr>

                                        <td>
                                            <div class="col-md-9"> Module
                                                @if(isset($latest_module))
                                                {{ $feedback->module_no }} {{ $latest_module->module_title }}
                                                @endif
                                            </div>

                                            <div class="col-md-2">
                                            {!! link_to_route('coach.module.feedback', 'View Feedback', ['module_id' => Crypt::encryptString($feedback->pivot->module_id), 'client_id' => Crypt::encryptString($feedback->pivot->user_id),'excercise_id'=>Crypt::encryptString($feedback->pivot->module_exercise_id)],['class' => 'btn btn-primary']) !!}
                                            @php $module_id[]=$feedback->pivot->module_id; @endphp
                                           </div>
                                            </td>
                            </tr>
                                        @endif
                                        @endif
                                        @endforeach

                                    @endif


                        @else
                            <tr>
                               <td colspan="4">
                                   No activity found.
                               </td>
                            </tr>
                        @endif
                </table>
                <table class="table table-bordered table-lg">
                        <thead>
                        <tr>
                          <!--  <td > <strong>Name</strong> </td>
                            <td ><strong>Last Activity</strong></td>
                            <td ><strong>Module progress</strong></td> -->
                            <td><h5>Feedback – Individual Exercises</h5></td>
                        </tr></thead>

                        @if(!empty($client_activity) && count($client_activity->user->module_progress) > 0)

                                    @if(count($client_activity->user->module_progress) > 0)
                                          <tr>
                                          @foreach($complete as $excercise)
                                          @if($excercise->module_exercise_id!=0 && $excercise->status=='reviewed')
                                            <td>
                                            <p><div class="col-md-9 text-ellipsis"><i class="icon-file-pdf"></i></i> &nbsp;Module
                                            {{ $excercise->modules->module_no . '-' . $excercise->modules->module_title }} : Exercise
                                            {{ $excercise->module_excercise->exercise_no . ' ' . $excercise->module_excercise->title}}
                                            </div>

                                            {{-- <div class="col-md-9 text-ellipsis"><i class="icon-file-pdf"></i> &nbsp;Module {{ $feedback->module_no . ' ' . $feedback->module_title }} </div> --}}
                                               <div class="col-md-2">
                                               {!! link_to_route('coach.download.feedback', 'View
                                               Feedback', ['module_id' => Crypt::encryptString($excercise->module_id), 'client_id' => Crypt::encryptString($excercise->user_id),'exercise_id' => Crypt::encryptString($excercise->module_exercise_id)],['class' => 'btn btn-primary']) !!}
                                               </div>
                                               </p>
                                            @endif

                                            </td>
                                            </tr>
                                        @endforeach
                                    @endif
                        @else
                            <tr>
                               <td colspan="4">
                                   No activity found.
                               </td>
                            </tr>
                        @endif
                </table>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <div class="col-md-10" >
                    <h5>Notes
                    :</h5>
                    These notes are shared between you and Admin:
            </div>
            <div class="col-md-12">
             {!! Form::model($coach_notes, ['method' =>
            'POST','route' =>
            ['clientdetails.updatenote',$client->user->id],'class' =>
            'form-horizontal','files'=>
            'true']) !!}
                        @if(isset($coach_notes) && count($coach_notes) > 0)
                            @foreach($coach_notes as $note)
                               <div class="col-md-12 row">
                               <br>
                               <input type="hidden" name="route" value="{{ url()->current() }}">
                               <textarea class="form-control" rows="7" name="coachnote[{{ $note->id }}]"> {{ $note->note }} </textarea>
                                </div>
                                <div class="col-md-9"></div>
                                <div class="col-md-3">
                                <br>
                                   {!! link_to_route('coach-notes.edit', 'Edit', ['client_id' => Crypt::encryptString($client->user_id), 'id' => Crypt::encryptString($note->id), '_url'=> request()->getRequestUri()], ['class' => 'btn btn-primary']) !!}
                                   {!! Form::submit(trans('comman.save'),['name' =>
                                    'save','class' =>
                                    'btn btn-primary']) !!}

                                </div>
                            @endforeach
                        @else
                            <tr>
                               <td colspan="4">
                                   No note found.
                               </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
                  {!! Form::close() !!}
            </div>
        </div>
        <div class="panel-body">
          <div class="col-md-12" >
        <h5>Coaching</h5>
         @php $credits=0; @endphp
         @if(isset($coaching_sessions) && count($coaching_sessions) > 0)
                            @foreach($coaching_sessions as $session)
                            @if(isset($session->client) && !empty($session->client))
                            @php $credits=$session->client->credits; @endphp
                            @endif
                            @endforeach
         @endif
         <b>Coaching Credits:{{ $credits }}</b>
          <br><br><b>Upcoming Sessions:</b>
            </label>
             <div class="table table-responsive">
              @if(isset($upcoming_coaching_sessions) && count($upcoming_coaching_sessions) > 0)
                <table class="table table-bordered table-lg">
                    <thead>
                        <tr>
                                    <td>
                                        Session
                                    </td>
                                    <td>
                                        Date
                                    </td>
                                    <td>
                                        Time
                                    </td>
                                    <td>
                                        Contact
                                    </td>
                                    <td>
                                        Coach
                                    </td>
                                    <td>
                                        Cancel
                                    </td>
                        </tr>
                        </thead>


                            @foreach($upcoming_coaching_sessions as $session)
                                 @if(!empty($session->coach_schedule) && isset($session->coach_schedule))
                                   @php

                                    if($session->booked_for=='f'){
                                        $for='20';
                                    }
                                    if($session->booked_for=='g'){
                                        $for='20';
                                    }
                                    if($session->booked_for=='s'){
                                        $for='60';
                                    }
                                    if($session->booked_slot>0){
                                        if($session->booked_slot==1){
                                            $start_time = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$session->coach_schedule->start_datetime)->format('H:i a');
                                            $start = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$session->coach_schedule->start_datetime)->format('H:i');
                                            $end_time = Carbon\Carbon::parse($start)->addMinutes(20)->format('H:i a');
                                            $total_time = $start_time."-".$end_time;
                                        }
                                        elseif($session->booked_slot==2){
                                            $start_time = Carbon\Carbon::parse($session->coach_schedule->start_datetime)->addMinutes(20)->format('H:i a');
                                            $start = Carbon\Carbon::parse($session->coach_schedule->start_datetime)->addMinutes(20)->format('H:i');
                                            $end_time = Carbon\Carbon::parse($start)->addMinutes(20)->format('H:i a');
                                            $total_time = $start_time."-".$end_time;
                                        }
                                        elseif($session->booked_slot==3) {
                                            $start_time = Carbon\Carbon::parse($session->coach_schedule->start_datetime)->addMinutes(40)->format('H:i a');
                                            $end_time = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$session->coach_schedule->end_datetime)->format('H:i a');
                                            $total_time = $start_time."-".$end_time;
                                        }

                                    }
                                     else{
                                            $start_time = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $session->coach_schedule->start_datetime)->format('H:i a');
                                            $end_time = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $session->coach_schedule->end_datetime)->format('H:i a');
                                            $total_time = $start_time."-".$end_time;
                                        }


                                @endphp
                                <tr>
                                <td>{{ $for }}min</td>
                                <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $session->coach_schedule->start_datetime)->format('m-d-Y') }}
                                    </td>
                                    <td>{{ $start_time }}-{{ $end_time }}
                                    </td>

                                    <td>
                                         {{ $session->meeting_type }}
                                    </td>
                                    <td>
                                         {{ $session->coach_schedule->user->name }}
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger cancle" data-toggle="modal" data-target="#myModal" data_id="{{ $session->coach_schedules_id }}">
                                                Cancel Session
                                        </button>
                                    </td>
                                </tr>
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

                              {{--  @if(isset($coach_cancle_session) && !empty($coach_cancle_session))
                                    @foreach($coach_cancle_session as $cancle_session)
                                    @if(isset($cancle_session->
                                    coach_schedule->
                                    start_datetime) && !empty($cancle_session->
                                    coach_schedule->
                                    start_datetime))
                                    @php
                                    $datetime1 = strtotime($cancle_session->
                                    coach_schedule->
                                    start_datetime);
                                    $datetime2 = strtotime($cancle_session->
                                    coach_schedule->
                                    end_datetime);
                                    $interval  = abs($datetime2 - $datetime1);
                                    $minutes   = round($interval / 60);
                                    $minutes   = 60;
                                    @endphp
                                    @endif
                                    <tr>
                                        <td>
                                            {{ $minutes or '' }}min
                                        </td>
                                        @if(isset($cancle_session->
                                        coach_schedule->
                                        start_datetime))
                                        <td>
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $cancle_session->coach_schedule->start_datetime)->format('d-m-Y') }}
                                        </td>
                                        @endif
                                        @if(isset($cancle_session->
                                        coach_schedule->
                                        end_datetime))
                                        <td>
                                            {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $cancle_session->coach_schedule->end_datetime)->format('H:i') }}
                                        </td>
                                        @endif
                                        <td>
                                            {{ $cancle_session->meeting_type }}
                                        </td>
                                        <td>
                                            {{ $cancle_session->coach_schedule->user->name }}
                                        </td>
                                        <td>
                                            Session Cancelled
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @endif --}}
                    </tbody>
                </table>
                @else
                 -
                 @endif
            </div>
        </div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
              <div class="col-md-10" >
                    <h5>Coaching Historys: </h5>
                </div>
                <div class="col-md-12">
                 @if(isset($coaching_sessions) && count($coaching_sessions) > 0)
                <table class="table table-bordered table-lg">
                    <thead>
                         <tr>
                                    <td>
                                        Session
                                    </td>
                                    <td>
                                        Date
                                    </td>
                                    <td>
                                        Time
                                    </td>
                                    <td>
                                        Contact
                                    </td>
                                    <td>
                                        Coach
                                    </td>
                        </tr>
                        </thead>

                            @foreach($coaching_sessions as $session)
                            @if($session->completed_session)
                            <tr>
                                    <td>
                                        session
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $session->completed_session->completed_at)->format('d-m-Y')  }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $session->completed_session->completed_at)->format('H:i')  }}
                                    </td>
                                    <td>
                                        {{ $session->completed_session->contact_methods }}
                                    </td>
                                    <td>
                                        {{ $session->coach_schedule->user->name }}
                                    </td>
                            </tr>
                            @endif
                            @endforeach

                    </tbody>
                </table>
                @else
                -
                @endif
                </div>
            </div>

                    <div class="table-responsive">
              <div class="col-md-10" >
                    <h5>Credit Historys:</h5>
                </div>
                <div class="col-md-12">
                     @php $cr=0; @endphp


                @if(isset($client_details->user->credit_history) && !empty($client_details->user->credit_history) && count($client_details->user->credit_history)>0)
                <table class="table table-bordered">
                <thead>
                <tr>
                    <td><b>Date</b></td>
                    <td><b>Description</b></td>
                    <td><b>Credits Purchased</b></td>
                    <td><b>Credits Used</b></td>
                    <td><b>Balance</b></td>

                    {{-- <td><b>Format</b></td>
                    <td><b>Credit Purchased</b></td>
                    <td><b>Total Paid</b></td>
                    <td><b>Result</b></td> --}}
                </tr>
                </thead>
                <tbody>

                     @php $total_credit = 0;$total_debit = 0; $balance=0; @endphp
            @php

            @endphp
                @foreach($client_details->user->credit_history as $credit_history)

                @php
                    if($credit_history->transaction_type == 'plus')
                        {
                            $credit = $credit_history->credit_score;
                            $debit = '-';
                            $balance += $credit_history->credit_score;
                        }
                        else{
                            $debit = $credit_history->credit_score;
                            $credit = '-';
                            $balance -= $credit_history->credit_score;
                        }


                if($credit_history->object_type=='coach_schedules_booked')
                {
                    if($credit_history->transaction_type=='plus')
                    {
                        $type='cancel Session for '.\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $credit_history->created_at)->format('d-m-Y').' at '.\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $credit_history->created_at)->format('H:i:s');
                    }
                    else
                    {

                        $type='Booked 1-1 Session for '.\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $credit_history->coach_booked_schedule->coach_schedule->start_datetime)->format('d-m-Y').' at '.\Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $credit_history->coach_booked_schedule->coach_schedule->start_datetime)->format('H:i:s');
                    }
                }
                else if($credit_history->object_type=='clients')
                {
                    $type='Purchase Credits';
                }
                else
                {
                    $type=$credit_history->object_type;
                }
                @endphp


                @if(isset($credit_history->creditpackage) && !empty($credit_history->creditpackage))
                <tr>
                    <td>
                        {{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $credit_history->created_at)->format('d-m-Y')  }}
                    </td>
                    <td>{{ $type }}</td>
                    {{-- <td>{{ number_format($credit) }}</td>
                    <td>{{ number_format($debit) }}</td>
                    <td>{{ number_format($balance) }}</td> --}}
                    <td>{{$credit}}</td>
                    <td>{{ $debit}}</td>
                    <td>{{ $balance}}</td>
                    {{--  <td>{{ $credit_history->payment_type }}</td>
                    <td>{{ $credit_history->credit_score }}</td>
                    <td>${{ number_format($credit_history->creditpackage->price ,2) * $credit_history->credit_score }}</td>
                    <td> Success </td> --}}
                </tr>
                @php $cr+=$credit_history->creditpackage->price * $credit_history->credit_score; @endphp
                @endif
                @endforeach

                </tbody>
            </table>
             @else
              -
             @endif
                </div>
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
    });
    });
    jQuery(document).ready(function() {
    jQuery('#save').click(function(){
     var a=jQuery(this).attr('data_id');
     var bookedid= jQuery('#bookedid').val();
     var reason= jQuery('#reason').val();
     var reason= jQuery('#reason').val();
     if(!reason.trim())
     {
        jQuery('#error').html('<div class="text-danger">* Add reason for cancel</div>'); exit;
     }
     console.log(bookedid);
     console.log(reason);
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