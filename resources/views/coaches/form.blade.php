<style type="text/css">
    .checkbox-inline + .checkbox-inline {
        margin-left: 0;
    }
    .multiselect-container
    {
        padding: 10px;
    }
</style>

@if(request()->get('download'))
   {!! Form::hidden("country_id",request()->get('country_id')) !!}
@endif

@if (Auth::user()->user_type == 'agent')
    <input type="hidden" name="agent_id" value="{{Auth::user()->id}}">
@else
    <input type="hidden" name="agent_id">
@endif

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
        <div class="form-group {{ $errors->has('paypal_id') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('paypal_id', trans('comman.paypal_id') . ':', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::text('paypal_id', null, ['class' => 'form-control','placeholder'=> trans('comman.paypal_id'), 'id' => 'paypal_id']) !!}
                {!! ($errors->has('paypal_id') ? $errors->first('paypal_id', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('password', 'Password:<span class="has-stik">*</span>', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::password('password', ['class' => 'form-control','placeholder'=>'Password', 'id' => 'password']) !!}
                {!! ($errors->has('password') ? $errors->first('password', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('password_confirmation') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('password_confirmation', 'Confirm Password:', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::password('password_confirmation', ['class' => 'form-control','placeholder'=>'Confirm Password', 'id' => 'password_confirmation']) !!}
                {!! ($errors->has('password_confirmation') ? $errors->first('password_confirmation', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('api_key') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('api_key', 'Zoom api:', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::text('api_key', null, ['class' => 'form-control','placeholder'=> 'Zoom api key', 'id' => '']) !!}
                {!! ($errors->has('zoom_api') ? $errors->first('zoom_api', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('api_secret') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('api_secret', 'Zoom Secret:', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::text('api_secret', null, ['class' => 'form-control','placeholder'=>'zoom secret']) !!}
                {!! ($errors->has('zoom_secret') ? $errors->first('zoom_secret', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('zoom_email') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('zoom_email', 'Zoom Email:', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::text('zoom_email', null, ['class' => 'form-control','placeholder'=> 'Zoom email', 'id' => '']) !!}
                {!! ($errors->has('zoom_email') ? $errors->first('zoom_email', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('gender') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('gender', trans('comman.gender') . ':<span class="has-stik">*</span>', ['class' => 'col-md-4 control-label '])) !!}
            <div class="col-md-8">
                <div class="col-md-4">
                    <div class="radio">
                        <label>
                             {!! Form::radio('gender', 'Male') !!} Male
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="radio">
                        <label>
                            {!! Form::radio('gender', 'Female') !!} Female
                        </label>
                    </div>
                </div>
                <div class="clearfix" style="background: none;"></div>
                {!! ($errors->has('gender') ? $errors->first('gender', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group {{ $errors->has('program_id') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('program_id', trans('comman.program') . ':<span class="has-stik">*</span>', ['class' => 'col-md-4 control-label '])) !!}
            <div class="col-md-8">
                {{-- {{ dump($coach['coach_program_id']) }} --}}
                @if(isset($programs) && count($programs) > 0)
                    @foreach($programs as $id => $program)
                        <label class="checkbox-inline">
                            {!! Form::checkbox('program_id[]', $id, null, ['class' => 'styled']) !!}
                            {{ $program }}
                        </label>
                        @if(isset($coach['coach_program_id'][$id]))
                            {!! Form::hidden('coach_program_id['.$id.']', $coach['coach_program_id'][$id]) !!}
                        @endif
                    @endforeach
                @endif
                {!! ($errors->has('program_id') ? $errors->first('program_id', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            {!! Html::decode(Form::label('available', trans('comman.available_for_new_client'), ['class' => 'col-md-4 control-label '])) !!}
            <div class="col-md-8">
                <div class="col-md-4">
                    <div class="radio">
                        <label>
                             {!! Form::radio('available', 'yes','true') !!} Yes
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="radio">
                        <label>
                            {!! Form::radio('available', 'no') !!} No
                        </label>
                    </div>
                </div>
                <!-- <div class="clearfix"></div> -->
            </div>
        </div>
    </div>
    <div class="col-lg-6 @if (Auth::user()->user_type == 'agent') hide @endif">
        <div class="form-group">
            {!! Html::decode(Form::label('available_for_review', trans('comman.available_for_review'), ['class' => 'col-md-4 control-label '])) !!}
            <div class="col-md-8">
                <div class="col-md-4">
                    <div class="radio">
                        <label>
                             {!! Form::radio('available_for_review', 'yes','true') !!} Yes
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="radio">
                        <label>
                            {!! Form::radio('available_for_review', 'no') !!} No
                        </label>
                    </div>
                </div>
                <!-- <div class="clearfix"></div> -->
            </div>
        </div>
    </div>
</div>

@if (Auth::user()->user_type != 'agent')
    <hr>
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group {{ $errors->has('proxy_coach_id') ? 'has-error' : ''}}">
                {!! Html::decode(Form::label('other_coach_feedback', trans('comman.other_coach_feedback') . ':', ['class' => 'col-md-2 control-label '])) !!}
                <div class="col-md-10">
                    @if(isset($othercoach) && count($othercoach) > 0)
                        @foreach($othercoach as $id => $proxy_coach)
                            <label class="checkbox-inline col-md-2">
                                {!! Form::checkbox('proxy_coach_id[]', $id, null, ['class' => 'styled']) !!}
                                {{ $proxy_coach }}
                            </label>
                        @endforeach
                    @endif
                </div>
            </div>
            {!! ($errors->has('proxy_coach_id') ? $errors->first('proxy_coach_id', '<p class="text-danger">:message</p>') : '') !!}
        </div>
    </div>
@endif

<hr>
<h5> {{ trans('comman.address_details') }} </h5>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            {!! Html::decode(Form::label('address_line_one', trans('comman.address_line1') . ':', ['class' => 'col-md-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::text('address_line_one', null, ['class' => 'form-control','placeholder'=> trans('comman.address_line1') , 'id' => 'address_line_one']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Html::decode(Form::label('address_line_two', trans('comman.address_line2') . ':', ['class' => 'col-md-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::text('address_line_two', null, ['class' => 'form-control','placeholder'=> trans('comman.address_line2') , 'id' => 'address_line_two']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Html::decode(Form::label('address_line_three', trans('comman.address_line3') . ':', ['class' => 'col-md-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::text('address_line_three', null, ['class' => 'form-control','placeholder'=> trans('comman.address_line3') , 'id' => 'address_line_three']) !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        @if (Auth::user()->user_type != 'agent')
            <div class="form-group {{ $errors->has('country_id') ? 'has-error' : ''}}">
                {!!  Html::decode(Form::label('country_id', trans("comman.country"). ':<span class="has-stik">*</span>', ['class' => 'col-md-4 control-label'])) !!}
                <div class="col-md-8">
                    <?php $classed = array('class' => 'form-control single-select');
                        if (request()->get('download')) {
                            $classed['disabled'] = 'true';
                        }
                        if (request()->get('agency')) {
                            unset($classed['disabled']);
                        }
                    ?>
                    @if(request()->get('download'))
                        {!! Form::select('country_id',array(""=>trans("comman.select_country"))+$countries,Request::get('country_id',null),$classed) !!}
                    @else
                        <div class="input-group">
                            {!! Form::select('country_id',array(""=>trans("comman.select_country"))+$countries,Request::get('country_id',null),$classed) !!}
                            <span class="input-group-addon">
                                <a class="new_country_popup"><i class="icon-plus3"></i></a>
                            </span>
                        </div>
                    @endif
                    {!! $errors->first('country_id', '<p class="help-block">:message</p>') !!}
                </div>
            </div>
        @else
            <input type="hidden" name="country_id" value="3">
        @endif
        <div class="form-group">
            {!! Html::decode(Form::label('city', trans('comman.city') . ':', ['class' => 'col-md-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::text('city', null, ['class' => 'form-control','placeholder'=> trans('comman.city') , 'id' => 'city']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Html::decode(Form::label('zip_code', trans('comman.zip_code') . ':', ['class' => 'col-md-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::text('zip_code', null, ['class' => 'form-control','placeholder'=> trans('comman.zip_code') , 'id' => 'zip_code']) !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('timezone') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('timezone', trans('comman.location_timezone') . ':<span class="has-stik">*</span>', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::select('timezone', ['' => trans('comman.location_timezone')] + $timezones, null, ['class' => 'form-control single-select', 'id' => 'timezone']) !!}
                {!! ($errors->has('timezone') ? $errors->first('timezone', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('skype_id') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('skype_id', trans('comman.skype_id') . ':', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::text('skype_id',null ,['class' => 'form-control','placeholder' => trans('comman.skype_id') , 'id' => 'skype_id']) !!}
                {!! ($errors->has('skype_id') ? $errors->first('skype_id', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>

<hr>
<h5> {{ trans('comman.coaching_details') }} </h5>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('free_20_min_session') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('free_20_min_session', trans('comman.free_20_min_session'). ': <span class="has-stik">*</span>', ['class' => 'control-label col-md-4'])) !!}
            <div class="col-md-8">
                {!! Form::text('free_20_min_session', null, ['class' => 'form-control','placeholder'=> trans('comman.free_20_min_session'), 'id' => 'free_20_min_session']) !!}
                {!! ($errors->has('free_20_min_session') ? $errors->first('free_20_min_session', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('one_hour_session') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('one_hour_session', trans('comman.one_hour_session'). ': <span class="has-stik">*</span>', ['class' => 'control-label col-md-4'])) !!}
            <div class="col-md-8">
                {!! Form::text('one_hour_session', null, ['class' => 'form-control','placeholder'=> trans('comman.one_hour_session'), 'id' => 'one_hour_session']) !!}
                {!! ($errors->has('one_hour_session') ? $errors->first('one_hour_session', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('graduate_session') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('graduate_session', trans('comman.graduate_session'). ': <span class="has-stik">*</span>', ['class' => 'control-label col-md-4'])) !!}
            <div class="col-md-8">
                {!! Form::text('graduate_session', null, ['class' => 'form-control','placeholder'=> trans('comman.graduate_session'), 'id' => 'graduate_session']) !!}
                {!! ($errors->has('graduate_session') ? $errors->first('graduate_session', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('min_slots_availability_per_week') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('min_slots_availability_per_week', trans('comman.min_slots_availability_per_week'). ': <span class="has-stik">*</span>', ['class' => 'control-label col-md-4'])) !!}
            <div class="col-md-8">
                {!! Form::text('min_slots_availability_per_week', null, ['class' => 'form-control','placeholder'=> trans('comman.min_slots_availability_per_week'), 'id' => 'min_slots_availability_per_week']) !!}
                {!! ($errors->has('min_slots_availability_per_week') ? $errors->first('min_slots_availability_per_week', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('image', trans('comman.avatar_photo'). ': <br> <span style="color: #999;">' . trans('comman.displayed_to_client') .'</span>', ['class' => 'control-label col-md-12'])) !!}
            <div class="col-md-6">
                {{-- {!! Form::text('image', null, ['class' => 'form-control','placeholder'=>'Photo', 'id' => 'image']) !!} --}}
                {!! Form::file('image', ['onchange' => 'readUserURL(this)', 'accept' => 'image/*']) !!}
                {{-- <input type="file" name="image" onchange="readUserURL(this)" accept="image/*"> --}}
                {!! ($errors->has('image') ? $errors->first('image', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            <div class="col-md-6">
                @if(isset($coach['image']) && !empty($coach['image']))
                    {{Html::image(AppHelper::path('uploads/user/')->size('100x100')->getImageUrl($coach['image']),'User Photo',array("class"=>"img-circle",'id'=>'staff','height'=>'100','width'=>'100'))}}
                @else
                    {{Html::image(AppHelper::size('100x100')->getDefaultImage(),'User Photo',array("class"=>"img-circle staff",'id'=>'staff','height'=>'100','width'=>'100'))}}
                @endif
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('biography') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('biography', trans('comman.biography'). ': <br> <span style="color: #999;">' . trans('comman.displayed_to_client') .'</span>', ['class' => 'control-label col-md-12'])) !!}
            <div class="col-md-12">
                {!! Form::textarea('biography', null, ['class' => 'form-control','placeholder'=> trans('comman.biography'), 'id' => 'biography', 'rows' => '5']) !!}
                {!! ($errors->has('biography') ? $errors->first('biography', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('qualifications') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('qualifications', trans('comman.qualifications'). ': ', ['class' => 'control-label col-md-12'])) !!}
            <div class="col-md-12">
                {!! Form::textarea('qualifications', null, ['class' => 'form-control','placeholder'=> trans('comman.qualifications'), 'id' => 'qualifications', 'rows' => '6']) !!}
                {!! ($errors->has('qualifications') ? $errors->first('qualifications', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('experience') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('experience', trans('comman.experience'). ': ', ['class' => 'control-label col-md-12'])) !!}
            <div class="col-md-12">
                {!! Form::textarea('experience', null, ['class' => 'form-control','placeholder'=> trans('comman.experience'), 'id' => 'experience', 'rows' => '6']) !!}
                {!! ($errors->has('experience') ? $errors->first('experience', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    @if (isset(Auth::user()->user_type) && Auth::user()->user_type == 'agent')
        <input type="hidden" name="status" value="active">
    @else
        <div class="col-lg-6">
            <div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
                {!! Html::decode(Form::label('status', trans('comman.status') . ':', ['class' => 'col-sm-4 control-label '])) !!}
                <div class="col-sm-8">
                    <label class="checkbox-inline">
                        {!! Form::checkbox('status', 'active', (old('status' , (isset($coach) ? $coach['status'] : '')) == 'active') ? 'checked="checked"' : '', ['class' => 'styled', 'id' => 'status']) !!}
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
                {!! Html::decode(Form::label('image', trans('comman.roles'). ':<span class="has-stik">*</span>', ['class' => 'control-label col-sm-12'])) !!}
                <div class="col-sm-6">
                    @foreach($roles as $role)
                        @if($role['id'] == 3)
                            @php
                                $rolestatus = false;
                                if(Route::currentRouteName() == 'coaches.create') {
                                    if(strtolower($role['role_name']) == 'coach') {
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
</script>
{!! ajax_fill_dropdown('lang','country_id',route('ajax.country')) !!}
@endpush
