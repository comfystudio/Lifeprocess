@extends($theme)
@section('title', $title)

@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Coaching Sessions</h3>
        </div>
        <div class="panel-body">
            {{ Form::open(array('route' => 'allsession','method'=>'GET','class'=>'form-filter','role'=>"form")) }}
                <div class="row">
                    @if($user_type != "coach")
                        <div class="col-md-2">
                            <div class="form-group">
                                {!! Html::decode(Form::label('coach', trans("comman.coach"). ':', ['class' => 'control-label'])) !!}
                                {!! Form::select('coach', ['' => trans('comman.select_coach')] + $coaches, Request::get('coach',null), ['class' => 'form-control single-select']) !!}
                            </div>
                        </div>
                    @endif
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Html::decode(Form::label('client', trans("comman.client"). ':', ['class' => 'control-label'])) !!}
                            {!! Form::select('client', ['' => trans('comman.select_client')] +$clients, Request::get('client',null), ['class' => 'form-control single-select']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Html::decode(Form::label('from_date', trans("comman.from_date"). ':', ['class' => 'control-label'])) !!}
                            {!! Form::text('from_date', Request::get('from_date',null), ['class' => 'form-control datepicker','placeholder'=> trans("comman.from_date"), 'autocomplete' => 'off' ]) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Html::decode(Form::label('to_date', trans("comman.to_date"). ':', ['class' => 'control-label'])) !!}
                            {!! Form::text('to_date', Request::get('to_date',null), ['class' => 'form-control datepicker','placeholder'=> trans("comman.to_date") , 'autocomplete' => 'off']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Html::decode(Form::label('status', trans("comman.session"). ':', ['class' => 'control-label'])) !!}
                            {!! Form::select('session', ['upcoming' => trans('comman.upcoming'),'past' => trans('comman.past')] ,Request::get('session',null), ['class' => 'form-control single-select' ]) !!}
                        </div>
                    </div>
                    @if($user_type == "coach")
                        <div class="col-md-2">
                            <div class="form-group">
                                {!! Html::decode(Form::label('session_status', trans("comman.session") . ' ' . trans("comman.status"). ':', ['class' => 'control-label'])) !!}
                                {!! Form::select('session_status', ['' => 'Select status' , 'cancelled' => 'Cancelled','completed' => 'Completed'] ,Request::get('session_status',null), ['class' => 'form-control single-select' ]) !!}
                            </div>
                        </div>
                    @endif
                </div>
                <div class="row">
                 <div class="col-md-3">
                        <div class="form-group">
                                {!! Form::submit(trans('comman.search'), ['name' => 'search','class' => 'btn btn-primary btn-xs']) !!}
                                {!! link_to_route('allsession', trans('comman.cancel'), [], array('class' => 'btn btn-default btn-xs cancel')) !!}
                        </div>
                    </div>
               </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading bg-white">
            <h5 class="panel-title">Sessions</h5>
            @if(isset($allsession) && count($allsession) > 0)
            <div class="heading-elements">
            <a  href=" {!! route('allsessionpdf', request()->all()) !!}" type="button" class="btn bg-info btn-labeled heading-btn"><b><i class="fa fa-file-pdf-o"></i></b>{{ trans('comman.saveaspdf')}}</a>
            </div>
            @endif
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-hover no-footer">
                <thead>
                    <tr>
                        {{-- <th>No.</th> --}}
                        <th> {{ trans('comman.date') }} </th>
                        <th> {{ trans('comman.time') }} </th>
                        <th> {{ trans('comman.client') }} </th>
                        <th> {{ trans('comman.coach') }} </th>
                        <th>Session Type</th>
                        <th>Format</th>
                        <th>Remarks</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>

                    @if(isset($allsession) && count($allsession) > 0)
                        @php
                            $counter = 1;
                        @endphp
                        @foreach($allsession as $all_session)

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
                                    {{ $all_session->coach_schedule ? Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$all_session->coach_schedule->start_datetime)->format('m/d/Y')  : ''}}
                                </td>
                                <td>
                                    {{ $all_session->coach_schedule ? $start_time."-".$end_time : ''}}
                                </td>
                                <td>
                                    {{$all_session->user->name or ''}}
                                </td>
                                <td> {{$all_session->coach_schedule->user->name }}</td>

                                <td>{{ $for }}</td>{{-- cnacel reson --}}
                                <td>
                                {{ $all_session->meeting_type }}
                                </td>
                                {{-- session completed remark --}}
                                @if(isset($all_session->completed_session))
                                    <td>{{$all_session->completed_session->remarks}}</td>
                                @else
                                    <td>{{ $all_session->cancel_reson }}</td>
                                @endif
                                <td class="text-center" id="booked_schedule_{{ $all_session->id }}" >
                                    @if($all_session->session_status == 'cancelled')
                                        @if($all_session->coach_schedule->user->id == $all_session->cancelled_by_user_id)
                                            @if($user_type == 'coach')
                                                <button type="button" class="btn btn-danger cancle" data-toggle="modal" data-target="#myModal" data_id="" data_art="{{$all_session->cancel_reson}}">{{ ucwords($all_session->session_status) }} </label>
                                            @else
                                               <button type="button" class="btn btn-danger cancle" data-toggle="modal" data-target="#myModal" data_id="" data_art="{{$all_session->cancel_reson}}">{{ ucwords($all_session->session_status) }}</button>
                                            @endif
                                        @else
                                          <button type="button" class="btn btn-danger cancle" data-toggle="modal" data-target="" data_id="" data_art="{{$all_session->cancel_reson}}">{{ ucwords($all_session->session_status) }} </button>
                                        @endif
                                        @elseif($all_session->session_status == 'completed')
                                        <label class="label bg-success">{{ ucwords($all_session->session_status) }}</label>
                                        @else
                                            {{-- {{ dump($all_session->updated_at) }} --}}
                                        @if($user_type == 'coach' || $user_type == 'user')
                                         {{-- <!-- {!! link_to_route('ajax.cancelBookedSchedule', 'Cancel', ['booked_schedule_id' => $all_session->coach_schedule->id, 'booked_user_id' => $all_session->booked_user_id, 'schedule' => $all_session->coach_schedule->start_datetime, 'created_at' => $all_session->created_at->format('Y-m-d H:i:s')], ['class' => 'btn btn-xs btn-default border-danger'] ) !!}  -->
                                            <a class="btn btn-xs btn-default border-danger cancel-reson" onclick="open_popup({{$all_session->id}})">Cancel</a> --}}
                                            <button type="button" class="btn btn-xs btn-default border-danger cancle" data-toggle="modal" data-target="#myModal" data_clientid="{{ $all_session->booked_user_id }}" data_id="{{ $all_session->coach_schedules_id }}">Cancel
                                            </button>
                                        @endif
                                        @if($user_type == 'user')
                                            {!! link_to('#', 'Complete', ['class' => 'btn btn-default border-success btn-xs complete_session_popup', 'onclick' => 'open_complete_session_popup("' . $all_session->id . '")']) !!}
                                        @endif
                                    @endif
                                </td>
                            </tr>

            @endforeach
            @else
                <tr>
                    <td colspan="9"> {{ trans('comman.no_data_found') }} </td>
                </tr>
            @endif
         {{--    @if(isset($cancelsession) && !empty($cancelsession))
            @foreach($cancelsession as $session)

            @endforeach
            @endif --}}
            </tbody>
            </table>
            <div class="pagination-wraper">
                {{ $allsession->appends(request()->except('page'))->links('vendor.pagination.bootstrap-custom')}}
            </div>
        </div>
    </div>
</div>
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
@include('allsessions.cancel-popup')
@push('scripts')
@include('allsessions.cancel-popup')
<script type="text/javascript">
    //  jQuery(document).ready(function() {
    //  jQuery('.cancle').click(function(){
    //  var a=jQuery(this).attr('data_art');
    //  console.log(a);
    //  jQuery('.reason').html(a);
    // });
    // });

    jQuery('.app > .app-content > .box').append('<div class="overlay ajax-overlay"><i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i></div>');
    var $loading = jQuery('.ajax-overlay').hide();
    jQuery(document).ajaxStart(function () {
        $loading.show();
    }).ajaxStop(function () {
        $loading.hide();
    });
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

</script>
@endpush

{{-- Popup File --}}
@include('allsessions.popup')

@endsection