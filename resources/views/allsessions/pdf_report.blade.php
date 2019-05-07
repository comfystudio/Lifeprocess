 @extends($theme)
 @section('content')
<div class="content-wrapper">
<div class="panel panel-default">
    <div class="panel-heading">
                <h5 class="panel-title">{{ trans('comman.allsession') }}</h5>
    </div>
    <div class="panel-body">
          <table class="table table-bordered table-hover no-footer">
                <thead>
                    <tr>
                        <th style="width: 50px;">{{ trans('comman.no') }}</th>
                        <th> {{ trans('comman.date') }} </th>
                        <th> {{ trans('comman.time') }} </th>
                        <th> {{ trans('comman.client') }} </th>
                        @if($user_type != 'coach')
                            <th> {{ trans('comman.coach') }} </th>
                        @endif
                        <th>Session Problem</th>
                        <th>Cancel Reason</th>
                        <th>Remark</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($allsession) && count($allsession) > 0)
                        @php
                            $counter = 0;
                        @endphp
                        @foreach($allsession as $all_session)
                            @php
                                $counter++;
                            @endphp
                            <tr>
                            <td> {{ $counter }}. </td>
                                <td>
                                    {{ $all_session->coach_schedule ? Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$all_session->coach_schedule->start_datetime)->format('m/d/Y')  : ''}}
                                 </td>
                                <td>
                                    {{ $all_session->coach_schedule ? Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $all_session->coach_schedule->start_datetime)->format('H:i') : ''}} </td>
                                <td>
                                    {{$all_session->user->name or ''}}
                                </td>
                                @if($user_type != "coach")
                                    <td> {{$all_session->coach_schedule->user->name  or ''}}</td>
                                @endif
                                {{-- session problem  --}}
                                @if(isset($all_session->problem_with_session))
                                        <td> 
                                            @if($all_session->problem_with_session->problem == "Other")
                                                {{ $all_session->problem_with_session->other}}
                                            @else
                                                {{ $all_session->problem_with_session->problem }}
                                            @endif
                                        </td>
                                    @else
                                        <td></td>
                                @endif
                                {{-- cancel reason --}}
                                @if($all_session->session_status == 'cancelled')
                                <td>
                                    {{$all_session->cancel_reson}}
                                </td>
                                @else
                                    <td></td>
                                @endif
                                {{-- session completed remark --}}
                                @if(isset($all_session->completed_session))
                                    <td>{{$all_session->completed_session->remarks}}</td>
                                @else
                                        <td> </td>
                                @endif        
                                </td>
                                <td class="text-center" id="booked_schedule_{{ $all_session->id }}" >
                                    @if($all_session->session_status == 'cancelled')
                                        @if($all_session->coach_schedule->user->id == $all_session->cancelled_by_user_id)
                                            @if($user_type == 'coach')
                                                <label class="label bg-danger">{{ ucwords($all_session->session_status) }} by you</label>
                                            @else
                                                <label class="label bg-danger">{{ ucwords($all_session->session_status) }} by coach</label>
                                            @endif
                                        @else
                                            <label class="label bg-danger">{{ ucwords($all_session->session_status) }} by client</label>
                                        @endif
                                    @elseif($all_session->session_status == 'completed')
                                        <label class="label bg-success">{{ ucwords($all_session->session_status) }}</label>
                                    @else
                                            {{-- {{ dump($all_session->updated_at) }} --}}
                                        @if($user_type == 'coach')
                                            {{-- {!! link_to_route('ajax.cancelBookedSchedule', 'Cancel', ['booked_schedule_id' => $all_session->coach_schedule->id, 'booked_user_id' => $all_session->booked_user_id, 'schedule' => $all_session->coach_schedule->start_datetime, 'created_at' => $all_session->created_at->format('Y-m-d H:i:s')], ['class' => 'btn btn-xs btn-default border-danger'] ) !!} --}}
                                            <a class="btn btn-xs btn-default border-danger cancel-reson" onclick="open_popup({{$all_session->id}})">Cancel</a>
                                        @else
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
                </tbody>
            </table>
    </div>
</div>
</div>
@endsection


