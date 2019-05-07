@extends($theme)
@section('title', $title)
@section('content')
<div class="row">
{{-- {{ dump(Config::get('srtpl.settings')) }} --}}
{{-- {{ dump(head(json_decode(json_encode(DB::select("SELECT @@@system_time_zone")), true))['@@@system_time_zone']) }} --}}
    <style>
        .panel.programs-list
        {
            border: 0px;
        }
        .programs-list .list-group
        {
            padding: 0px 5px;
        }
        .fa-blue { color: #3D92F3; }
    </style>

    {!! Form::model($settings, ['route' => ['users.settingsStore'],'class' => 'form-horizontal']) !!}
    <div class="col-md-12">
       <div class="panel panel-white">
            <div class="panel-heading">
                <div class="col-sm-9">
                    <h3 class="panel-title">Admin Settings</h5>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
            <div class="form-group {{ $errors->has('admin_email') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('admin_email', 'Admin Email'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-4">
                        {!! Form::text('admin_email', null, ['class' => 'form-control','placeholder' => 'Admin Email']) !!}
                        {!! $errors->first('admin_email', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('max_modules_per_bill_cycle') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('max_modules_per_bill_cycle', 'Max No of Modules Per billing cycle'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-4">
                        {!! Form::text('max_modules_per_bill_cycle', null, ['class' => 'form-control','placeholder' => 'Max No of Modules Per billing cycle' ]) !!}
                        {!! $errors->first('max_modules_per_bill_cycle', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('per_page') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('per_page', 'Paging -No of records per screen'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-4">
                        {!! Form::text('per_page', null, ['class' => 'form-control','placeholder' => 'Contact us Email']) !!}
                        {!! $errors->first('per_page', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-heading">
                        <h5 class="panel-title">Subscription Costs</h5>
                    </div>
                    <div class="panel-body">
                        <div class="form-group {{ $errors->has('standard_subscription_cost') ? 'has-error' : ''}}">
                            {!! Html::decode(Form::label('standard_subscription_cost', 'Standard'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                            <span class="pull-left dollar-sign">$</span>
                            <div class="col-sm-4">
                                {!! Form::text('standard_subscription_cost', null, ['class' => 'form-control','placeholder' => 'Standard']) !!}
                                {!! $errors->first('standard_subscription_cost', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('graduate_subscription_cost') ? 'has-error' : ''}}">
                            {!! Html::decode(Form::label('graduate_subscription_cost', 'Graduate'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                            <span class="pull-left dollar-sign">$</span>
                            <div class="col-sm-4">

                                {!! Form::text('graduate_subscription_cost', null, ['class' => 'form-control','placeholder' => 'Graduate']) !!}
                                {!! $errors->first('graduate_subscription_cost', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>
                </div>
                <h3><strong>Default Coach Payments - Programs - Modules</strong></h3>
                @if($programs->count())
                    <div class="panel panel-white programs-list">
                        @foreach($programs as $key=>$program)
                            <div class="clickable panel-heading bg-white" data-toggle="collapse" href="#collapse{{ $key }}" data-id={{$key}}>
                                <h6 class="panel-title">
                                  <i class="icon-circle-down2 fa-blue"></i>&nbsp;
                                  <strong>Program : {{$program->program_name}}</strong>
                                </h6>
                            </div>
                            {{-- Module Title here over--}}
                        <div id="collapse{{ $key }}" class="panel-collapse collapse panel-body list-group">
                            <div class="col-md-9">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="50%">Modules</th>
                                        <th>Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($program->modules))
                                        @foreach($program->modules as $MKey=>$module)
                                            <tr>
                                                <td>{{$module->module_title or ''}}</td>
                                                <td>{{Form::text('default_rate['.$program->id.']['.$module->id.']',$module->default_rate,['class'=>"form-control"])}}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="2" class="text-center">
                                                No Modules Found
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
                <h3><strong>Default Coach Payments - Coaching</strong></h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('cancel_booking_within') ? 'has-error' : ''}}">
                        {!! Html::decode(Form::label('cancel_booking_within', 'Initial Consultation (20 mins)'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-7 control-label'])) !!}
                        <div class="col-sm-6">
                        {!! Form::text('initial_min_consulatation', null, ['class' => 'form-control','placeholder' => 'Initial Consultation' ]) !!}
                        {!! $errors->first('cancel_booking_within', '<p class="help-block">:message</p>') !!}
                        </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('cancel_booking_within') ? 'has-error' : ''}}">
                        {!! Html::decode(Form::label('Standard 1 hr Session', 'Standard 1 hr Session'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-7 control-label'])) !!}
                        <div class="col-sm-6">
                        {!! Form::text('standard_1hr_session', null, ['class' => 'form-control','placeholder' => 'Standard 1 hr Session' ]) !!}
                        {!! $errors->first('cancel_booking_within', '<p class="help-block">:message</p>') !!}
                        </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('cancel_booking_within') ? 'has-error' : ''}}">
                        {!! Html::decode(Form::label('Graduate Session (20 min)', 'Graduate Session (20 min)'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-7 control-label'])) !!}
                        <div class="col-sm-6">
                        {!! Form::text('graduate_session_20min', null, ['class' => 'form-control','placeholder' => 'Graduate Session (20 min)' ]) !!}
                        {!! $errors->first('cancel_booking_within', '<p class="help-block">:message</p>') !!}
                        </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group {{ $errors->has('cancel_booking_within') ? 'has-error' : ''}}">
                        {!! Html::decode(Form::label('Min Slots Availibility per week', 'Min Slots Availibility per week '. ':<span class="has-stik">*</span>', ['class' => 'col-sm-7 control-label'])) !!}
                        <div class="col-sm-6">
                        {!! Form::text('min_slots_availibility_per_week', null, ['class' => 'form-control','placeholder' => 'Min Slots Availibility per week' ]) !!}
                        {!! $errors->first('cancel_booking_within', '<p class="help-block">:message</p>') !!}
                        </div>
                        </div>
                    </div>
                </div>
                <div class="form-group {{ $errors->has('maintenance_mode') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('maintenance_mode', 'Maintenance Mode'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-4">
                        {{-- {!! Form::text('maintenance_mode', null, ['class' => 'form-control','placeholder' => 'Maintenance Mode' ]) !!} --}}
                        <div class="checkbox checkbox-switch">
                            <label>
                                {!! Form::checkbox('maintenance_mode', 'On', ($settings['maintenance_mode'] == 'On') ,['class' => 'switch', "data-on-text" => "On", "data-off-text" => "Off", "data-on-color" => "warning", "data-off-color" => "success"]) !!}
                            </label>
                        </div>
                        {!! $errors->first('maintenance_mode', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <h3><strong>Other Settings</strong></h3>
                <div class="form-group {{ $errors->has('allow_booking_hour') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('Don’t allow bookings within the next (Hrs)', 'Don’t allow bookings within the next (Hrs)'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-4">
                        {!! Form::text('allow_booking_hour', null, ['class' => 'form-control','placeholder' => 'Don’t allow bookings (Hour)' ]) !!}
                        {!! $errors->first('allow_booking_hour', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('cancel_booking_within') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('cancel_booking_within', 'Cancel Booking Within (Hour)'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-4">
                        {!! Form::text('cancel_booking_within', null, ['class' => 'form-control','placeholder' => 'Cancel Booking Within (Hour)' ]) !!}
                        {!! $errors->first('cancel_booking_within', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('coach_credit_threshold') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('coach_credit_threshold', 'Coach Credit Threshold'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-4">
                        {!! Form::text('coach_credit_threshold', null, ['class' => 'form-control','placeholder' => 'Coach Credit Threshold' ]) !!}
                        {!! $errors->first('coach_credit_threshold', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('review_per_billing_cycle') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('review_per_billing_cycle', 'Review per Billing Cycle'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-4">
                        {!! Form::text('review_per_billing_cycle', null, ['class' => 'form-control','placeholder' => 'Review per Billing Cycle' ]) !!}
                        {!! $errors->first('review_per_billing_cycle', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('hasnot_viewed_feedback_after') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('hasnot_viewed_feedback_after', 'Has not Viewed/Downloaded Feedback after X days'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-4">
                        {!! Form::text('hasnot_viewed_feedback_after', null, ['class' => 'form-control','placeholder' => 'Has not Viewed/Downloaded Feedback after X days' ]) !!}
                        {!! $errors->first('hasnot_viewed_feedback_after', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('reviewed_within_last_days') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('reviewed_within_last_days', 'Recently Reviewed modules within X Days'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-4">
                        {!! Form::text('reviewed_within_last_days', null, ['class' => 'form-control','placeholder' => 'Recently Reviewed modules within X Days.' ]) !!}
                        {!! $errors->first('reviewed_within_last_days', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group {{ $errors->has('maintenance_mode_message') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('maintenance_mode_message', 'Maintenance mode message'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-4">
                        {!! Form::text('maintenance_mode_message', null, ['class' => 'form-control','placeholder' => 'Maintenance mode message' ]) !!}
                        {!! $errors->first('maintenance_mode_message', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('max_exercise_can_complete_per_day') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('max_exercise_can_complete_per_day', 'Max Exercise Client Can Complete Per Day'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-4">
                        {!! Form::text('max_exercise_can_complete_per_day', null, ['class' => 'form-control','placeholder' => 'Max Exercise Client Can Complete Per Day' ]) !!}
                        {!! $errors->first('max_exercise_can_complete_per_day', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('default_delay_between_modules') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('default_delay_between_modules', 'Default Delay between Modules (Days)'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-4">
                        {!! Form::text('default_delay_between_modules', null, ['class' => 'form-control','placeholder' => 'Default Delay between Modules (Days)' ]) !!}
                        {!! $errors->first('default_delay_between_modules', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('contact_us_email') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('contact_us_email', 'Contact us Email'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-4">
                        {!! Form::text('contact_us_email', null, ['class' => 'form-control','placeholder' => 'Contact us Email']) !!}
                        {!! $errors->first('contact_us_email', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <h3><strong>Users Actively Logged On</strong></h3>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <td>User ID</td>
                            <td>User</td>
                            <td>Login Time (GMT)</td>
                            <td>Time Since Last Update(Mins)</td>
                        </tr>
                    </thead>
                    <tbody>
                        @if($users->count())
                            @foreach($users as $key => $user)
                            @if($user->last_active)
                            <tr>
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $last_active = "";
                                    if($user->last_active){
                                        $last_active = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $user->last_active);
                                        $diff = $last_active->diffForHumans($now);
                                    }
                                @endphp
                                <td>{{$user->id}}</td>
                                <td>{{$user->name}}</td>
                                <td>
                                {{ empty($last_active) ? '' :$last_active->format('H:i d/m/Y')}}
                                </td>
                                <td>{{$diff or ''}}</td>
                            </tr>
                            @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="panel-heading">
                    <div class="form-group">
                        <div class="col-sm-5 text-right">
                            {!! Form::submit("Update", ['name' => 'save','class' => 'btn btn-primary']) !!}
                            {!! link_to(URL::full(), "Cancel",array('class' => 'btn btn-warning cancel')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection
@push('scripts')
<script>
    $(".clickable.panel-heading").on('click', function () {
        var icon = $(this).find('.fa-blue');
        if(icon.hasClass('icon-circle-down2')) {
            icon.removeClass('icon-circle-down2');
            icon.addClass('icon-circle-up2');
        } else {
            icon.addClass('icon-circle-down2');
            icon.removeClass('icon-circle-up2');
        }

    });

</script>
@endpush