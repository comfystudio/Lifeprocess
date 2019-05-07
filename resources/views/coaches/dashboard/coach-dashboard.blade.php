@extends($theme)
@section('title', $title)
@section('content')
<style type="text/css">
    .table-lg>tbody>tr>td {
        padding: 10px;
    }
    .table > thead > tr > td {
        vertical-align: middle;
    }
    .table > thead > tr {
        /* background: #BBDDF7; */
        background: #E3F3FB;
        font-weight: bold;
    }
    .bg-success {
        background-color: #dff0d8;
        color: #000;
    }
    .bg-primary {
        background-color: #2196F3;
        color: #FFFFFF;
    }
    .bg-danger {
        background-color: #f2dede;
        color: #000;
    }
    .bg-warning {
        background-color: #fcf8e3;
        color: #000;
    }
    .table tr[class*=bg-] > td, .table tr[class*=bg-] > th {
        border-color: #ddd;
    }
    .list-group {
        border: none;
    }
    .font-class-label
    {
        font-family:open_sanssemibold;
        font-size: 13px;
        font-weight: bold;
    }
    .font-class
    {
        font-family:open_sanssemibold;
        font-size: 15px;
        font-weight: bold;

    }
</style>
<!-- Main content -->
<div class="content-wrapper">
    <!-- Dashboard content -->
    <div class="panel panel-white">
        <div class="panel-heading">
            <div class="row">
                <h3 class="panel-title">{{ $module_title }}</h3>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                {{--      <div class="row">
                <div class="col-sm-6 col-md-3">
                    <div class="panel panel-body bg-orange-400 has-bg-image">
                        <div class="media no-margin">
                            <div class="media-body">
                                <h3 class="no-margin">{{ $slots_remaining }}</h3>
                                <span class="text-uppercase text-size-mini">Slots Remaining</span>
                            </div>
                            <div class="media-right media-middle">
                                <i class="icon-calendar icon-3x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-md-3">
                    <div class="panel panel-body bg-orange-400 has-bg-image">
                        <div class="media no-margin">
                            <div class="media-body">
                                <h3 class="no-margin">{{ $slots_added }}</h3>
                                <span class="text-uppercase text-size-mini">Slots used</span>
                            </div>
                            <div class="media-right media-middle">
                                <i class="icon-calendar2 icon-3x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}
            <div class="panel-default">
                <h5 class="font-class">
                    Upcoming Sessions
                </h5>
                <div class="table-responsive">
                 <table class="table table-bordered table-lg">
                    <thead>
                        <tr>
                            <td><strong>Client</strong></td>
                            <td>
                                <strong>Session</strong>
                            </td>
                            <td>
                                <strong>Date</strong>
                            </td>
                            <td>
                                <strong>Time</strong>
                            </td>
                            <td>
                                 <strong> Contact</strong>
                            </td>

                            <td>
                                <strong>Cancel</strong>
                            </td>
                        </tr>
                    </thead>

            @if(isset($allsession) && count($allsession) > 0)
                        @php
                            $counter = 1;
                        @endphp
                        @foreach($allsession as $all_session)

                        @php
                                    if($all_session->booked_for=='f'){
                                        $for='20min';
                                    }
                                    if($all_session->booked_for=='g'){
                                        $for='Gratuate';
                                    }
                                    if($all_session->booked_for=='s'){
                                        $for='60min';
                                    }
                                    if($all_session->booked_slot>0){
                                        if($all_session->booked_slot==1){
                                            $start_time = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$all_session->coach_schedule->start_datetime)->format('H:i a');
                                            $start = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$all_session->coach_schedule->start_datetime)->format('H:i');
                                            $end_time = Carbon\Carbon::parse($start)->addMinutes(20)->format('H:i a');
                                            $total_time = $start_time."-".$end_time;
                                        }
                                        elseif($all_session->booked_slot==2){
                                            $start_time = Carbon\Carbon::parse($all_session->coach_schedule->start_datetime)->addMinutes(20)->format('H:i a');
                                            $start = Carbon\Carbon::parse($all_session->coach_schedule->start_datetime)->addMinutes(20)->format('H:i');
                                            $end_time = Carbon\Carbon::parse($start)->addMinutes(20)->format('H:i a');
                                            $total_time = $start_time."-".$end_time;
                                        }
                                        elseif ($all_session->booked_slot==3) {
                                            $start_time = Carbon\Carbon::parse($all_session->coach_schedule->start_datetime)->addMinutes(40)->format('H:i a');
                                            $end_time = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$all_session->coach_schedule->end_datetime)->format('H:i a');
                                            $total_time = $start_time."-".$end_time;
                                        }

                                    }
                                     else{
                                            $start_time = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $all_session->coach_schedule->start_datetime)->format('H:i a');
                                            $end_time = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $all_session->coach_schedule->end_datetime)->format('H:i a');
                                            $total_time = $start_time."-".$end_time;
                                        }


                                @endphp
                            <tr>
                                {{--
                                <td>{{ ($allsession->currentPage()-1) * $allsession->perPage()+ $counter++ }}</td>
                                --}}
                                <td>
                                    {{$all_session->user->name or ''}}
                                </td>
                                 <td>{{ $for }}</td>{{-- cnacel reson --}}
                                <td>
                                    {{ $all_session->coach_schedule ? Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$all_session->coach_schedule->start_datetime)->format('d-m-Y H:i:s')  : ''}}
                                </td>
                                <td>
                                    {{ $all_session->coach_schedule ? $start_time."-".$end_time : ''}}
                                </td>


                                <td>
                                {{ $all_session->meeting_type }}
                                </td>
                                {{-- session completed remark --}}

                                <td class="text-center" id="booked_schedule_{{ $all_session->id }}" >
                                     <button type="button" class="btn btn-danger cancle" data-toggle="modal" data-target="#myModal" data_clientid="{{ $all_session->booked_user_id }}" data_id="{{ $all_session->coach_schedules_id }}">Cancel Session
                    </button>
                                </td>
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
        </table>
    </div>
</div>

<div class="panel-default">
    <br>
      <label class="font-class-label">You have

       @php $count=0; @endphp
           @if(isset($new_module_submitted) && count($new_module_submitted) > 0)

               @foreach($new_module_submitted AS $client)

                @if($client->status!='reviewed')
                    @if(!empty($client->submittedBy->client->coach))
                    @php
                    $count+=count($client->id);
                    @endphp
                    @endif
                @endif

               @endforeach

                @php echo $count; @endphp
           @else
                @php echo $count; @endphp
           @endif
       exercises waiting for feedback</label>
      <table class="table table-bordered table-lg">
         <thead>
            <tr>
                <td> <strong>Name</strong> </td>
                <td><strong>Date Submitted</strong></td>
                <td><strong>Module</strong></td>
                <td><strong>Excercise</strong></td>
                <td style="width:90px;"><strong>My Client?</strong></td>
                <td style="width:90px;"><strong>Respond</strong></td>
            </tr>
        </thead>
        @foreach($new_module_submitted as $clients)
        @if($clients->status!='reviewed')
            @php
                $row_color = '';

                if (!($clients->status)) {

                    $three_weeks_ago = Carbon\Carbon::now()->subWeeks(3);
                    $two_weeks_ago = Carbon\Carbon::now()->subWeeks(2);
                    $now = Carbon\Carbon::now();

                    $row_color = '';

                    if ($clients->completed_at < $three_weeks_ago) {
                                                                                    //$row_color = 'alpha-danger';
                        $btn_class='btn-danger';
                    } else if ($clients->completed_at < $two_weeks_ago) {
                                                                                    //$row_color = 'alpha-orange';
                        $btn_class='btn-warning';
                    } else if ($clients->completed_at <= $now) {
                                                                                    //$row_color = 'alpha-green';
                        $btn_class='btn-success';
                    }
                }
            @endphp
            @if(!empty($clients->submittedBy->client->coach))
            <tr>
                <td>{{ link_to_route('client.detail',  $clients->submittedBy->name, ['client_id' => Crypt::encryptString($clients->submittedBy->id)])  }}</td>
                <td><strong>{{$clients->completed_at}}</strong></td>
                <td><strong>{{$clients->modules->module_no }}</strong></td>
                <td><strong>{{$clients->modules->module_no }}.{{$clients->module_excercise->exercise_no}}</strong></td>
                <td style="width:90px;">Yes</td>
                <td>
                    @if($clients->status)
                        @if($clients->status == 'unlock')
                        <label>{!! Html::decode(link_to_route('coach.respond', '<i class="fa fa-pencil-square-o"></i> Edit', ['module_id' => Crypt::encryptString($clients->id), 'client_id' => Crypt::encryptString($clients->user_id),'excercise_id'=>Crypt::encryptString($clients->module_exercise_id)], ['class' => 'label bg-teal'])) !!}
                        </label>
                        @endif
                        {{-- @else
                        <label class="label label-success">Reviewed</label>
                        @endif --}}
                    @else

                            @php

                            $attr = [];
                            $param = [];
                            if(isset($total_reviews_in_month[$clients->id]) && $total_reviews_in_month[$clients->id] >= $review_perBilling_cycle) {

                                $param['receive_p'] = 'false';
                                $attr['onclick'] = "confirm_to_review('" . route('coach.respond', ['module_id' => Crypt::encryptString($clients->module->id), 'client_id' => Crypt::encryptString($clients->user_id),'excercise_id'=>Crypt::encryptString($clients->module_exercise_id)] + $param) . "')";
                                echo Form::button('<i class="fa fa-pencil-square-o"></i>Respond', ['class' => 'btn ' . $btn_class] + $attr);
                            } else {
                                echo link_to_route('coach.respond', 'Respond', ['module_id' => Crypt::encryptString($clients->modules->id), 'client_id' => Crypt::encryptString($clients->user_id),'module_exercise_id'=>Crypt::encryptString($clients->module_exercise_id)], ['class' => 'btn ' . $btn_class]);
                            }
                            @endphp

                    @endif
                </td>
            </tr>
            @endif
        @endif
        @endforeach
    </table>

</div>
<div class="panel-default">
    <h5 class="font-class">
        Recently Reviewed Exercises
    </h5>
    <div class="table-responsive">
        <table class="table table-bordered table-lg">
            <thead>
                <tr>
                    <td> <strong>Name</strong> </td>
                    {{--  <td style="width:120px;"><strong>Date Responded</strong></td> --}}
                    <td><strong>Reviewed Date</strong></td>
                    <td><strong>Module</strong></td>
                    <td><strong>Excercise</strong></td>
                    <td><strong>My Client?</strong></td>
                    <td><strong>My Feedback</strong></td>
                    <td><strong>Respond</strong></td>
                </tr>
            </thead>
            @if(isset($module_review_within_date))

            @foreach($module_review_within_date as $client)

            @if(isset($client->reviewedBy) && !empty($client->reviewedBy))
            @if(isset($client->submittedBy) && !empty($client->submittedBy))
            @if(isset($client->modules) && !empty($client->modules))
            @if(!empty($client->module_excercise) && isset($client->module_excercise))
            <tr>
                <td>
                 {{ link_to_route('client.detail', $client->submittedBy->name, ['client_id' => Crypt::encryptString($client->submittedBy->id)])  }}
               </td>
                                                       {{--  <td>
                                                            {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $client->completed_at)->format('m/d/Y') }}
                                                        </td> --}}
                                                        <td>
                                                            {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $client->reviewed_at)->format('m/d/Y') }}
                                                        </td>
                                                        <td>
                                                            {{ $client->modules->module_no }}
                                                        </td>
                                                        <td>
                                                            {{$client->module_excercise->exercise_no}}.{{ $client->module_excercise->title }}
                                                        </td>
                                                        <td>
                                                            @if(Auth::id()==$client->submittedBy->client->coach->user_id) Yes @else No @endif
                                                        </td>
                                                        <td>
                                                            @if(Auth::id()==$client->submittedBy->client->coach->user_id) Yes @else No @endif
                                                        </td>
                                                        <td>
                                                            {!! link_to_route('coach.download.feedback', 'View', ['module_id' => Crypt::encryptString($client->modules->id), 'client_id' => Crypt::encryptString($client->user_id),'excercise_id'=>Crypt::encryptString($client->module_excercise->id)],['class' => 'btn bg-primary']) !!}
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @endif
                                                    @endif
                                                    @endif
                                                    @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="panel-default">
                                        <h5 class="font-class">
                                         Module Submitted By Other Coach Client
                                     </h5>
                                     <div class="table-responsive">
                                        <table class="table table-bordered table-lg">
                                         <thead>
                                            <tr>
                                                <td> <strong>Name</strong> </td>
                                                <td><strong>Date Submitted</strong></td>
                                                <td><strong>Module Submitted</strong></td>
                                                <td><strong>Action</strong></td>
                                            </tr>
                                        </thead>

                                        @if(isset($other_coach_modules))
                                        @foreach($other_coach_modules as $modules)
                                        @if(!is_null($modules->coach))
                                        @if(isset($modules->clients) && count($modules->clients) > 0)
                                        @foreach($modules->clients AS $client)
                                        @if(isset($client->user->module_progress) && count($client->user->module_progress) > 0)
                                        @foreach($client->user->module_progress AS $progress)
                                        <tr>
                                            <td>
                                                {{$client->user->name}}

                                            </td>
                                            <td>
                                                @if($progress->pivot->completed_at)
                                                {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $progress->pivot->completed_at)->format('m/d/Y') }}
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td> {{ $progress->module_no . '.' . '   ' . $progress->module_title }} </td>
                                            <td>
                                                @if($progress->pivot->status)
                                                @if($progress->pivot->status == 'unlock')
                                                <label>{!! Html::decode(link_to_route('coach.respond', '<i class="fa fa-pencil-square-o"></i> Edit', ['module_id' => Crypt::encryptString($progress->id), 'client_id' => Crypt::encryptString($client->user_id),'excercise_id'=>Crypt::encryptString($progress->pivot->module_exercise_id)], ['class' => 'label bg-teal'])) !!}
                                                </label>
                                                @else
                                                <label class="label label-success">Reviewed</label>
                                                @endif
                                                @else
                                                @php
                                                $btn_class = 'btn-default';
                                                $attr = [];
                                                $param = [];
                                                if(isset($total_reviews_in_month[$client->id]) && $total_reviews_in_month[$client->id] >= $review_perBilling_cycle) {
                                                    $btn_class = 'bg-slate-400';
                                                    $param['receive_p'] = 'false';
                                                    $attr['onclick'] = "confirm_to_review('" . route('coach.respond', ['module_id' => Crypt::encryptString($progress->id), 'client_id' => Crypt::encryptString($client->user_id),'excercise_id'=>Crypt::encryptString($progress->pivot->module_exercise_id)] + $param) . "')";
                                                    echo Form::button('Respond', ['class' => 'btn ' . $btn_class] + $attr);
                                                } else {
                                                    echo link_to_route('coach.respond', 'Respond', ['module_id' => Crypt::encryptString($progress->id), 'client_id' => Crypt::encryptString($client->user_id),'excercise_id'=>Crypt::encryptString($progress->pivot->module_exercise_id)], ['class' => 'btn ' . $btn_class]);
                                                }
                                                @endphp
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                        @endif
                                        @endforeach
                                        @endif
                                        @endif
                                        @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Widget Data End -->
            </div>
        </div>
        <!-- /dashboard content -->
    </div>
    <!-- /main content -->
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
           if(!reason.trim())
           {
            jQuery('#error').html('<div class="text-danger">* Add reason for cancel</div>'); exit;
        }
        $.ajax({
            url: '{!! route("ajax.canclesession") !!}',
            type: 'POST',
            data: {
                bookedid:bookedid,
                clientid:clientid,
                _token:jQuery('input[name="_token"]').val(),
                reason:reason,
            },
        })
        .done(function(data) {
         window.location.reload();
     });
    });
    });
     function confirm_to_review(route_link) {
        swal({
            title: '',
            text: "This user has already received the maximum number of reviews for their current billing period. You may not receive payment for completing feedback at this time. Are you sure you wish to continue?",
            showCancelButton: true,
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-default',
            confirmButtonText: "Yes, I want",
            allowOutsideClick: false
        }).then(function () {
            window.location = route_link;
            return true;
        }, function(dismiss){
            return false;
        });
    }
</script>
@endpush
@endsection
