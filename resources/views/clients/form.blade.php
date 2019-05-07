@if (Auth::user()->user_type == 'agent')
    <input type="hidden" name="agent_id" value="{{Auth::user()->id}}">
@else
    <input type="hidden" name="agent_id">
@endif

<div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('coach_gender') ? 'has-error' : ''}}">
            {!!  Html::decode(Form::label('coach_gender', trans("comman.coach_gender"). ':<span class="has-stik">*</span>', ['class' => 'col-md-4 control-label'])) !!}
            <div class="col-md-8">
                {!! Form::select('coach_gender',array(""=>trans("comman.select_coach_gender"), 'Male' => 'Male', 'Female' => 'Female'),Request::get('coach_gender',null),array('class' => 'form-control single-select')) !!}
                {!! $errors->first('coach_gender', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('program_id') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('program_id', trans('comman.program') . ':<span class="has-stik">*</span>', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::select('program_id', ['' => trans('comman.select_program')] + $programs, null, ['class' => 'form-control single-select', 'id' => 'program_id', 'onchange' => 'load_Coaches()'] ) !!}
                {!! ($errors->has('program_id') ? $errors->first('program_id', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('coach_id') ? 'has-error' : ''}}">
            {!!  Html::decode(Form::label('coach_id', trans("comman.coach"). ':<span class="has-stik">*</span>', ['class' => 'col-md-4 control-label'])) !!}
            <div class="col-md-8">
                {!! Form::select('coach_id',array(""=>trans("comman.select_coach"))+$coaches,Request::get('coach_id',null),array('class' => 'form-control single-select')) !!}
                {!! $errors->first('coach_id', '<p class="help-block">:message</p>') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('first_name') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('first_name', 'First name:<span class="has-stik">*</span>', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::text('first_name', null, ['class' => 'form-control','placeholder'=> 'First name', 'id' => 'first_name']) !!}
                {!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('last_name') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('last_name', 'Last name:<span class="has-stik">*</span>', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::text('last_name', null, ['class' => 'form-control','placeholder'=>'Last name']) !!}
                {!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('email', 'Email:<span class="has-stik">*</span>', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::text('email', null, ['class' => 'form-control','placeholder'=>'Email', 'id' => 'email']) !!}
                {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('password', 'Password:<span class="has-stik">*</span>', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::password('password', ['class' => 'form-control','placeholder'=>'Password', 'id' => 'password']) !!}
                {!! ($errors->has('password') ? $errors->first('password', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('password_confirmation', 'Confirm Password:', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::password('password_confirmation', ['class' => 'form-control','placeholder'=>'Confirm Password', 'id' => 'password_confirmation']) !!}
                {!! ($errors->has('password_confirmation') ? $errors->first('password_confirmation', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('timezone') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('timezone', trans('comman.location_timezone') . ':<span class="has-stik">*</span>', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::select('timezone', ['' => trans('comman.location_timezone')] + $timezones, null, ['class' => 'form-control single-select', 'id' => 'timezone']) !!}
                {!! ($errors->has('timezone') ? $errors->first('timezone', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>

</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('mobile_no') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('mobile_no', trans('comman.phone') . ' ' .trans('comman.no') . ': ', ['class' => 'control-label col-md-4'])) !!}
            <div class="col-md-8">
                {!! Form::text('mobile_no', null, ['class' => 'form-control','placeholder'=> trans('comman.phone') . ' ' . trans('comman.no'), 'id' => 'mobile_no']) !!}
                {!! ($errors->has('mobile_no') ? $errors->first('mobile_no', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>

    <div class="col-lg-6 @if(isset(Auth::user()->user_type) && Auth::user()->user_type == 'agent') hide @endif">
        <div class="form-group {{ $errors->has('contact_methods') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('contact_methods', trans('comman.preferred_contact_methods'). ': ', ['class' => 'control-label col-md-4'])) !!}
            <div class="col-md-8">
                @if(isset($contact_methods_list) && count($contact_methods_list) > 0)
                    @foreach($contact_methods_list as $key => $value)
                        <label class="checkbox-inline">
                            {!! Form::checkbox('contact_methods[]', $key, in_array($key, old('contact_methods' , (isset($client) ? $client['contact_methods'] : []))) ? 'checked="checked"' : '', ['class' => 'styled']) !!}
                            {{ $key }}
                        </label>
                    @endforeach
                @endif
                {{-- <label class="checkbox-inline">
                    {!! Form::checkbox('contact_methods[]', 'skype', in_array('skype', old('contact_methods' , (isset($client) ? $client['contact_methods'] : []))) ? 'checked="checked"' : '', ['class' => 'styled']) !!}
                    {{ trans('comman.skype') }}
                </label>
                <label class="checkbox-inline">
                    {!! Form::checkbox('contact_methods[]', 'phone', in_array('phone', old('contact_methods' , (isset($client) ? $client['contact_methods'] : []))) ? 'checked="checked"' : '', ['class' => 'styled']) !!}
                    {{ trans('comman.phone') }}
                </label>
                <label class="checkbox-inline">
                    {!! Form::checkbox('contact_methods[]', 'chat', in_array('chat', old('contact_methods' , (isset($client) ? $client['contact_methods'] : []))) ? 'checked="checked"' : '', ['class' => 'styled']) !!}
                    {{ trans('comman.chat') }}
                </label>   --}}
                {!! ($errors->has('contact_methods') ? $errors->first('contact_methods', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>

<div class="row @if(isset(Auth::user()->user_type) && Auth::user()->user_type == 'agent') hide @endif">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('skype_id') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('skype_id', trans('comman.skype_id') . ':', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::text('skype_id',null ,['class' => 'form-control','placeholder' => trans('comman.skype_id') , 'id' => 'skype_id']) !!}
                {!! ($errors->has('skype_id') ? $errors->first('skype_id', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('LPAP_initial_fee') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('LPAP_initial_fee', trans('comman.LPAP_initial_fee'). ': ', ['class' => 'control-label col-md-4'])) !!}
            <div class="col-md-8">
                {!! Form::select('LPAP_initial_fee', ['paid' => trans('comman.paid'), 'not_paid' => trans('comman.not_paid')], old('LPAP_initial_fee', isset($client) ? $client['LPAP_initial_fee'] : 'not_paid'), ['class' => 'form-control single-select','id' => 'LPAP_initial_fee', 'rows' => '6']) !!}
                {!! ($errors->has('LPAP_initial_fee') ? $errors->first('LPAP_initial_fee', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
<div class="row @if(isset(Auth::user()->user_type) && Auth::user()->user_type == 'agent') hide @endif">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('LPAP_initial_fee') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('Next Payment Date','Next Payment Date'. ': ', ['class' => 'control-label col-md-4'])) !!}
            <div class="col-md-8">
                {!! Form::text('nextpaymentdate', '____/__/__', ['class' => 'form-control  date_of_birth ','placeholder'=>'Date Of Birth', 'id' => 'date_of_birth']) !!}
                <div class="form-control-feedback">
                    <i class="icon-calendar2 text-muted"></i>
                </div>
            </div>
        </div>
    </div>

    @if (isset(Auth::user()->user_type) && Auth::user()->user_type == 'agent')
        <input type="hidden" name="status" value="active">
    @else
        <div class="col-lg-6">
            <div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
                {!! Html::decode(Form::label('status', trans('comman.status') . ':', ['class' => 'col-sm-4 control-label '])) !!}
                <div class="col-sm-8">
                    <label class="checkbox-inline">
                        {!! Form::checkbox('status', 'active', (old('status' , (isset($client) ? $client['status'] : '')) == 'active') ? 'checked="checked"' : '', ['class' => 'styled', 'id' => 'status']) !!}
                        {{ trans('comman.active') }}
                    </label>
                    {!! ($errors->has('status') ? $errors->first('status', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
        </div>
    @endif


    <div class="col-lg-6 @if(isset(Auth::user()->user_type) && Auth::user()->user_type == 'agent') hide @endif">
        @if(isset($roles) && count($roles) > 0)
            <div class="form-group {{ $errors->has('role_id') ? 'has-error' : ''}}">
                {!! Html::decode(Form::label('image', trans('comman.roles'). ':<span class="has-stik">*</span>', ['class' => 'control-label col-sm-4'])) !!}
                <div class="col-sm-8">
                    @foreach($roles as $role)
                        @if($role['id'] == 4)
                            @php
                                $rolestatus = false;
                                if(Route::currentRouteName() == 'clients.create') {
                                    if(strtolower($role['role_name']) == 'client') {
                                        $rolestatus = true;
                                    }
                                }
                            @endphp
                            <div class="radio">
                                <label>
                                    {!! Form::radio('role_id', $role['id'], $rolestatus) !!}
                                    {{ ucfirst($role['role_name']) }}
                                </label>
                            </div>
                        @endif
                    @endforeach
                    {!! ($errors->has('role_id') ? $errors->first('role_id', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
        @endif
    </div>
</div>
@push('scripts')
<script type="text/javascript">
    jQuery('.app > .app-content > .box').append('<div class="overlay ajax-overlay"><i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i></div>');
    var $loading = jQuery('.ajax-overlay').hide();
    jQuery(document).ajaxStart(function () {
        $loading.show();
    }).ajaxStop(function () {
        $loading.hide();
    });

    jQuery(function() {
        jQuery( "#date_of_birth" ).datetimepicker({
          timepicker:false,
          format:'Y/m/d',
          minDate:'+1970/01/02',
          formatDate:'Y/m/d',
          changeMonth: false,
          changeYear: false,
        });
  });
    function load_Coaches() {
        // jQuery.ajax({
        //     url: '{!! route("ajax.coach") !!}',
        //     data: jQuery('#clients_create_form').serialize(),
        //     type: 'POST',
        //     success: function(data) {
        //         console.log((data.length));
        //         jQuery("select[name='coach_id']").find("option:not(:first)").remove();
        //         jQuery("select[name='coach_id']").val('').trigger('change');
        //         jQuery.each(data, function(key,value){
        //             jQuery("select[name='coach_id']").append("<option value='" + key + "'>" + value + "</option>");
        //         });
        //     }
        // });
    }
</script>
{!! ajax_fill_dropdown('coach_gender','coach_id',route('ajax.coach')) !!}
{!! ajax_fill_dropdown('program_id','coach_id',route('ajax.coach')) !!}
@endpush
