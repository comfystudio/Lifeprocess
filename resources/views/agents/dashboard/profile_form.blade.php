<style type="text/css">
    .checkbox-inline + .checkbox-inline {
        margin-left: 0;
    }
</style>
@if(request()->get('download'))
   {!! Form::hidden("country_id",request()->get('country_id')) !!}
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
                {!! Form::text('email', null, ['class' => 'form-control','placeholder'=>'Email', 'id' => 'email', 'readonly' => 'readonly']) !!}
                {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('paypal_id') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('paypal_id', trans('comman.paypal_id') . ':<span class="has-stik">*</span>', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-8">
                {!! Form::text('paypal_id', null, ['class' => 'form-control','placeholder'=> trans('comman.paypal_id'), 'id' => 'paypal_id']) !!}
                {!! ($errors->has('paypal_id') ? $errors->first('paypal_id', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
{{-- <div class="row">
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
</div> --}}
<div class="row">
    <div class="col-lg-6 hide">
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
                <div class="clearfix"></div>
                {!! ($errors->has('gender') ? $errors->first('gender', '<p class="text-danger">:message</p>') : '') !!}
                <input name="gender" type="radio" value="Male" id="gender">
            </div>            
        </div>                
    </div>
    <div class="col-md-6 hide">
        <div class="form-group {{ $errors->has('program_id') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('program_id', trans('comman.program') . ':<span class="has-stik">*</span>', ['class' => 'col-md-4 control-label '])) !!}
            <div class="col-md-8">
                {{-- {{ dump($agent['agent_program_id']) }} --}}
                @if(isset($programs) && count($programs) > 0)
                    @foreach($programs as $id => $program)
                        <label class="checkbox-inline">
                            {!! Form::checkbox('program_id[]', $id, null, ['class' => 'styled']) !!}
                            {{ $program }}
                        </label>
                        @if(isset($agent['agent_program_id'][$id]))
                            {!! Form::hidden('agent_program_id['.$id.']', $agent['agent_program_id'][$id]) !!}
                        @endif
                    @endforeach
                @endif
                {!! ($errors->has('program_id') ? $errors->first('program_id', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
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
<div class="row hide">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('image', trans('comman.avatar_photo'). ': <br> <span style="color: #999;">' . trans('comman.displayed_to_client') .'</span>', ['class' => 'control-label col-md-12'])) !!}
            <div class="col-md-6">
                 {!! Form::text('image', null, ['class' => 'form-control','placeholder'=>'Photo', 'id' => 'image']) !!}
                {!! Form::file('image', ['onchange' => 'readUserURL(this)', 'accept' => 'image/*']) !!}
                 <input type="file" name="image" onchange="readUserURL(this)" accept="image/*">
                {!! ($errors->has('image') ? $errors->first('image', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            <div class="col-md-6">
                @if(isset($agent['image']) && !empty($agent['image']))
                    {{Html::image(AppHelper::path('uploads/user/')->size('100x100')->getImageUrl($agent['image']),'User Photo',array("class"=>"img-circle",'id'=>'staff','height'=>'100','width'=>'100'))}}
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
<div class="row hide">
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

<fieldset>
    <legend style="margin-bottom: 5px;">Change Password</legend>
    <div class="row">
        <div class="col-lg-4">
            <div class="form-group {{ $errors->has('current_password') ? 'has-error' : ''}}">
                {!! Html::decode(Form::label('current_password', 'Current Password:', ['class' => 'col-sm-12 control-label '])) !!}
                <div class="col-sm-12">
                    {!! Form::password('current_password', ['class' => 'form-control','placeholder'=>'Current Password', 'id' => 'current_password']) !!}
                    {!! ($errors->has('current_password') ? $errors->first('current_password', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group {{ $errors->has('new_password') ? 'has-error' : ''}}">
                {!! Html::decode(Form::label('new_password', 'New Password:', ['class' => 'col-sm-12 control-label '])) !!}
                <div class="col-sm-12">
                    {!! Form::password('new_password', ['class' => 'form-control','placeholder'=>'New Password', 'id' => 'new_password']) !!}
                    {!! ($errors->has('new_password') ? $errors->first('new_password', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="form-group {{ $errors->has('new_password_confirmation') ? 'has-error' : ''}}">
                {!! Html::decode(Form::label('new_password_confirmation', 'Confirm Password:', ['class' => 'col-sm-12 control-label '])) !!}
                <div class="col-sm-12">
                    {!! Form::password('new_password_confirmation', ['class' => 'form-control','placeholder'=>'Confirm Password', 'id' => 'new_password_confirmation']) !!}
                    {!! ($errors->has('new_password_confirmation') ? $errors->first('new_password_confirmation', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
        </div>
    </div>
</fieldset>

{{--Forcing inputs on agent updates--}}
<input name="need_card" type="hidden" value="{{$agent['need_card']}}" >
<input name="module_restriction" type="hidden" value="{{$agent['module_restriction']}}" >
<input name="pp_llpcoach_fast" type="hidden" value="{{$agent['pp_llpcoach_fast']}}" >
<input name="pp_coach_fast" type="hidden" value="{{$agent['pp_coach_fast']}}" >
<input name="pp_llpcoach_normal" type="hidden" value="{{$agent['pp_llpcoach_normal']}}" >
<input name="pp_coach_normal" type="hidden" value="{{$agent['pp_coach_normal']}}" >
<input name="credits_per_month" type="hidden" value="{{$agent['credits_per_month']}}" >
<input name="credits_accumulate" type="hidden" value="{{$agent['credits_accumulate']}}" >

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
