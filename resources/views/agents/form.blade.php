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
                {!! Form::text('email', null, ['class' => 'form-control','placeholder'=>'Email', 'id' => 'email']) !!}
                {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6 hide">
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
    <div class="col-lg-6 gender-row hide">
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
                <!-- <div class="clearfix"></div> -->
                {!! ($errors->has('gender') ? $errors->first('gender', '<p class="text-danger">:message</p>') : '') !!}

                <input name="gender" type="hidden" value="Male" id="gender">
            </div>
        </div>
    </div>
    <div class="col-md-6 hide">
        <div class="form-group {{ $errors->has('program_id') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('program_id', trans('comman.program') . ':<span class="has-stik">*</span>', ['class' => 'col-md-4 control-label '])) !!}
            <div class="col-md-8">
                {{-- {{ dump($coach['coach_program_id']) }} --}}
                @if(isset($programs) && count($programs) > 0)
                    @foreach($programs as $id => $program)
                        <label class="checkbox-inline">
                            {!! Form::checkbox('program_id[]', $id, true, ['class' => 'styled']) !!}
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
<h5> {{ trans('comman.client_manager') }} </h5>
<div class="row">
    <div class="col-lg-6 gender-row">
        <div class="form-group {{ $errors->has('need_card') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('need_card', trans('comman.need_card') . ':<span class="has-stik">*</span>', ['class' => 'col-md-4 control-label '])) !!}
            <div class="col-md-8">
                <div class="col-md-4">
                    <div class="radio">
                        <label>
                             {!! Form::radio('need_card', '1', 'default') !!} Yes
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="radio">
                        <label>
                            {!! Form::radio('need_card', '0') !!} No
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <i style="font-size: 18px;" class="fa fa-question" data-toggle="tooltip" title="This will usually be set to ‘yes’ for a ‘real’ client. Set to ‘no’ if we are just setting up a test or if payment will take place outside of the system"></i>
                </div>
                <!-- <div class="clearfix"></div> -->
                {!! ($errors->has('need_card') ? $errors->first('need_card', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6 gender-row">
            <div class="form-group {{ $errors->has('module_restriction') ? 'has-error' : ''}}">
                {!! Html::decode(Form::label('module_restriction', trans('comman.module_restriction') . ':<span class="has-stik">*</span>', ['class' => 'col-md-4 control-label '])) !!}
                <div class="col-md-8">
                    <div class="col-md-4">
                        <div class="radio">
                            <label>
                                 {!! Form::radio('module_restriction', '1', 'default') !!} Yes
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="radio">
                            <label>
                                {!! Form::radio('module_restriction', '0') !!} No
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <i style="font-size: 18px;" class="fa fa-question" data-toggle="tooltip" title="The max no of modules per billing cycle is set in general ‘settings’. Click ‘yes’ if we want this restriction to persist. Select ‘no’ if these clients can complete at their own pace"></i>
                    </div>
                    <!-- <div class="clearfix"></div> -->
                    {!! ($errors->has('module_restriction') ? $errors->first('module_restriction', '<p class="text-danger">:message</p>') : '') !!}
                </div>
            </div>
        </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('pp_llpcoach_fast') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('pp_llpcoach_fast', 'Price Per LLPcoach Fast:', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-6">
                {!! Form::text('pp_llpcoach_fast', null,['class' => 'form-control','placeholder'=> 'Price Per LLPcoach Fast', 'id' => 'pp_llpcoach_fast']) !!}
                {!! ($errors->has('pp_llpcoach_fast') ? $errors->first('pp_llpcoach_fast', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            <div class="col-md-2">
                <i style="font-size: 18px;" class="fa fa-question" data-toggle="tooltip" title="Client Manager using our LPP coaches – cost per month for Fast-track access"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('pp_coach_fast') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('pp_coach_fast', 'Price Per Coach Fast:', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-6">
                {!! Form::text('pp_coach_fast', null, ['class' => 'form-control','placeholder'=>'Price Per Coach Fast']) !!}
                {!! ($errors->has('pp_coach_fast') ? $errors->first('pp_coach_fast', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            <div class="col-md-2">
                <i style="font-size: 18px;" class="fa fa-question" data-toggle="tooltip" title="Client Manager using their own LPP coaches – cost per month for Fast-track access"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('pp_llpcoach_normal') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('pp_llpcoach_normal', 'Price Per LLPcoach Normal:', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-6">
                {!! Form::text('pp_llpcoach_normal', null, ['class' => 'form-control','placeholder'=> 'Price Per LLPcoach Normal', 'id' => 'pp_llpcoach_normal']) !!}
                {!! ($errors->has('pp_llpcoach_normal') ? $errors->first('pp_llpcoach_normal', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            <div class="col-md-2">
                <i style="font-size: 18px;" class="fa fa-question" data-toggle="tooltip" title="Client Manager using our LPP coaches – cost per month for restricted (2 mods per month) access"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('pp_coach_normal') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('pp_coach_normal', 'Price Per Coach Normal:', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-6">
                {!! Form::text('pp_coach_normal', null, ['class' => 'form-control','placeholder'=>'Price Per Coach Normal']) !!}
                {!! ($errors->has('pp_coach_normal') ? $errors->first('pp_coach_normal', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            <div class="col-md-2">
                <i style="font-size: 18px;" class="fa fa-question" data-toggle="tooltip" title="Client Manager using their own LPP coaches – cost per month for (2 mods per month) access"></i>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 gender-row">
        <div class="form-group {{ $errors->has('credits_accumulate') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('credits_accumulate', trans('comman.credits_accumulate') . ':', ['class' => 'col-md-4 control-label '])) !!}
            <div class="col-md-8">
                <div class="col-md-4">
                    <div class="radio">
                        <label>
                             {!! Form::radio('credits_accumulate', '1', 'default') !!} Yes
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="radio">
                        <label>
                            {!! Form::radio('credits_accumulate', '0') !!} No
                        </label>
                    </div>
                </div>
                <div class="col-md-4">
                    <i style="font-size: 18px;" class="fa fa-question" data-toggle="tooltip" title="Client Managers clients acquire credits every month, allow them to accumulate?"></i>
                </div>
                <!-- <div class="clearfix"></div> -->
                {!! ($errors->has('credits_accumulate') ? $errors->first('credits_accumulate', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('credits_per_month') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('credits_per_month', 'Coach Credits Per Month:', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-6">
                {!! Form::text('credits_per_month', null, ['class' => 'form-control','placeholder'=>'Coach Credits Per Month']) !!}
                {!! ($errors->has('credits_per_month') ? $errors->first('credits_per_month', '<p class="text-danger">:message</p>') : '') !!}
            </div>
            <div class="col-md-2">
                <i style="font-size: 18px;" class="fa fa-question" data-toggle="tooltip" title="The number of credits given to Client Managers per month"></i>
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
        {!! Form::hidden('country_id', 0) !!}
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
{{--<hr>--}}
<div class="row hide">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('promotional_call') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('promotional_call', trans('comman.promotional_call'). ': ', ['class' => 'control-label col-md-4'])) !!}
            <div class="col-md-8">
                {!! Form::text('promotional_call', null, ['class' => 'form-control','placeholder'=> trans('comman.promotional_call'), 'id' => 'promotional_call']) !!}
                {!! ($errors->has('promotional_call') ? $errors->first('promotional_call', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('one_hour_session') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('one_hour_session', trans('comman.one_hour_session'). ': ', ['class' => 'control-label col-md-4'])) !!}
            <div class="col-md-8">
                {!! Form::text('one_hour_session', null, ['class' => 'form-control','placeholder'=> trans('comman.one_hour_session'), 'id' => 'one_hour_session']) !!}
                {!! ($errors->has('one_hour_session') ? $errors->first('one_hour_session', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
<div class="row hide">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('free_20_min_session') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('free_20_min_session', trans('comman.free_20_min_session'). ': ', ['class' => 'control-label col-md-4'])) !!}
            <div class="col-md-8">
                {!! Form::text('free_20_min_session', null, ['class' => 'form-control','placeholder'=> trans('comman.free_20_min_session'), 'id' => 'free_20_min_session']) !!}
                {!! ($errors->has('free_20_min_session') ? $errors->first('free_20_min_session', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>

{{--<hr>--}}
{{--<div class="row">--}}
    {{--<div class="col-lg-6">--}}
        {{--<div class="form-group {{ $errors->has('image') ? 'has-error' : ''}}">--}}
            {{--{!! Html::decode(Form::label('image', trans('comman.avatar_photo'). ': <br> <span style="color: #999;">' . trans('comman.displayed_to_client') .'</span>', ['class' => 'control-label col-md-12'])) !!}--}}
            {{--<div class="col-md-6">--}}
                {{-- {!! Form::text('image', null, ['class' => 'form-control','placeholder'=>'Photo', 'id' => 'image']) !!} --}}
                {{--{!! Form::file('image', ['onchange' => 'readUserURL(this)', 'accept' => 'image/*']) !!}--}}
                {{-- <input type="file" name="image" onchange="readUserURL(this)" accept="image/*"> --}}
                {{--{!! ($errors->has('image') ? $errors->first('image', '<p class="text-danger">:message</p>') : '') !!}--}}
            {{--</div>--}}
            {{--<div class="col-md-6">--}}
                {{--@if(isset($agent['image']) && !empty($agent['image']))--}}
                    {{--{{Html::image(AppHelper::path('uploads/user/')->size('100x100')->getImageUrl($agent['image']),'User Photo',array("class"=>"img-circle",'id'=>'staff','height'=>'100','width'=>'100'))}}--}}
                {{--@else--}}
                    {{--{{Html::image(AppHelper::size('100x100')->getDefaultImage(),'User Photo',array("class"=>"img-circle staff",'id'=>'staff','height'=>'100','width'=>'100'))}}--}}
                {{--@endif--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-lg-6">--}}
        {{--<div class="form-group {{ $errors->has('biography') ? 'has-error' : ''}}">--}}
            {{--{!! Html::decode(Form::label('biography', trans('comman.biography'). ': <br> <span style="color: #999;">' . trans('comman.displayed_to_client') .'</span>', ['class' => 'control-label col-md-12'])) !!}--}}
            {{--<div class="col-md-12">--}}
                {{--{!! Form::textarea('biography', null, ['class' => 'form-control','placeholder'=> trans('comman.biography'), 'id' => 'biography', 'rows' => '5']) !!}--}}
                {{--{!! ($errors->has('biography') ? $errors->first('biography', '<p class="text-danger">:message</p>') : '') !!}--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}
{{--<div class="row">--}}
    {{--<div class="col-lg-6">--}}
        {{--<div class="form-group {{ $errors->has('qualifications') ? 'has-error' : ''}}">--}}
            {{--{!! Html::decode(Form::label('qualifications', trans('comman.qualifications'). ': ', ['class' => 'control-label col-md-12'])) !!}--}}
            {{--<div class="col-md-12">--}}
                {{--{!! Form::textarea('qualifications', null, ['class' => 'form-control','placeholder'=> trans('comman.qualifications'), 'id' => 'qualifications', 'rows' => '6']) !!}--}}
                {{--{!! ($errors->has('qualifications') ? $errors->first('qualifications', '<p class="text-danger">:message</p>') : '') !!}--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div class="col-lg-6">--}}
        {{--<div class="form-group {{ $errors->has('experience') ? 'has-error' : ''}}">--}}
            {{--{!! Html::decode(Form::label('experience', trans('comman.experience'). ': ', ['class' => 'control-label col-md-12'])) !!}--}}
            {{--<div class="col-md-12">--}}
                {{--{!! Form::textarea('experience', null, ['class' => 'form-control','placeholder'=> trans('comman.experience'), 'id' => 'experience', 'rows' => '6']) !!}--}}
                {{--{!! ($errors->has('experience') ? $errors->first('experience', '<p class="text-danger">:message</p>') : '') !!}--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}
<div class="row">
    <div class="col-lg-6 hide">
        <div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('status', trans('comman.status') . ':', ['class' => 'col-sm-4 control-label '])) !!}
            <div class="col-sm-8">
                <label class="checkbox-inline">
                    {!! Form::hidden('status', 1) !!}
                    {{--{!! Form::checkbox('status', 'active', (old('status' , (isset($agent) ? $agent['status'] : '')) == 'active') ? 'checked="checked"' : '', ['class' => 'styled', 'id' => 'status']) !!}--}}
                    {{ trans('comman.active') }}
                </label>
                {!! ($errors->has('status') ? $errors->first('status', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6 hide">
        @if(isset($roles) && count($roles) > 0)
            <div class="form-group {{ $errors->has('role_id') ? 'has-error' : ''}}">
                {!! Html::decode(Form::label('image', trans('comman.roles'). ':<span class="has-stik">*</span>', ['class' => 'control-label col-sm-12'])) !!}
                <div class="col-sm-6">
                    @foreach($roles as $role)
                        @if($role['id'] == 5)
                            @php
                                $rolestatus = true;
                                if(Route::currentRouteName() == 'agents.create') {
                                    if(strtolower($role['role_name']) == 'agent') {
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

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
{!! ajax_fill_dropdown('lang','country_id',route('ajax.country')) !!}
@endpush
