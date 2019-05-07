@section('style')
<style type="text/css">
    .checkbox-inline + .checkbox-inline {
        margin-left: 0;
    }
    .multiselect-container
    {
        padding: 10px;
    }
    td.col-md-5.left-padding-20 { padding-left: 20px; }
</style>
@endsection
@if(request()->get('download'))
   {!! Form::hidden("country_id",request()->get('country_id')) !!}
@endif
<div class="panel panel-white">
    <div class="panel-heading">
            <h5 class="panel-title">{{ trans('comman.coach') }} Info</h5>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group {{ $errors->has('id') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('id', 'Coach ID:', ['class' => 'col-sm-12 control-label '])) !!}
                    <div class="col-sm-12">
                        {!! Form::text('id', null, ['class' => 'form-control','placeholder'=> 'Coach ID', 'id' => 'id', 'readonly' => 'true']) !!}
                        {!! ($errors->has('id') ? $errors->first('id', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('email', 'Email:<span class="has-stik">*</span>', ['class' => 'col-sm-12 control-label '])) !!}
                    <div class="col-sm-12">
                        {!! Form::text('email', null, ['class' => 'form-control','placeholder'=>'Email', 'id' => 'email']) !!}
                        {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group {{ $errors->has('first_name') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('first_name', 'First name:<span class="has-stik">*</span>', ['class' => 'col-sm-12 control-label '])) !!}
                    <div class="col-sm-12">
                        {!! Form::text('first_name', null, ['class' => 'form-control','placeholder'=> 'First name', 'id' => 'first_name']) !!}
                        {!! ($errors->has('first_name') ? $errors->first('first_name', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('password', 'Password:', ['class' => 'col-sm-12 control-label '])) !!}
                    <div class="col-sm-12">
                        {!! Form::password('password', ['class' => 'form-control','placeholder'=>'Password', 'id' => 'password']) !!}
                        {!! ($errors->has('password') ? $errors->first('password', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group {{ $errors->has('last_name') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('last_name', 'Last name:<span class="has-stik">*</span>', ['class' => 'col-sm-12 control-label '])) !!}
                    <div class="col-sm-12">
                        {!! Form::text('last_name', null, ['class' => 'form-control','placeholder'=>'Last name']) !!}
                        {!! ($errors->has('last_name') ? $errors->first('last_name', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group {{ $errors->has('timezone') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('timezone', trans('comman.location_timezone') . ':<span class="has-stik">*</span>', ['class' => 'col-sm-12 control-label '])) !!}
                    <div class="col-sm-12">
                        {!! Form::select('timezone', ['' => trans('comman.location_timezone')] + $timezones, null, ['class' => 'form-control single-select', 'id' => 'timezone']) !!}
                        {!! ($errors->has('timezone') ? $errors->first('timezone', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group {{ $errors->has('gender') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('gender', 'Sex' . ':<span class="has-stik">*</span>', ['class' => 'col-sm-12 control-label '])) !!}
                    <div class="col-sm-12">
                        {!! Form::select('gender', ['' => 'gender'] + ['Male' => 'Male'] + ['Female' => 'Female'] , null, ['class' => 'form-control single-select', 'id' => 'gender']) !!}
                        {!! ($errors->has('gender') ? $errors->first('gender', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group {{ $errors->has('paypal_id') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('paypal_id', trans('comman.paypal_id') . ':<span class="has-stik">*</span>', ['class' => 'col-sm-12 control-label '])) !!}
                    <div class="col-sm-12">
                        {!! Form::text('paypal_id', null, ['class' => 'form-control','placeholder'=> trans('comman.paypal_id'), 'id' => 'paypal_id']) !!}
                        {!! ($errors->has('paypal_id') ? $errors->first('paypal_id', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('api_key') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('zoom_api', 'Zoom api:<span class="has-stik">*</span>', ['class' => 'col-sm-12 control-label '])) !!}
            <div class="col-sm-12">
                {!! Form::text('api_key', null, ['class' => 'form-control','placeholder'=> 'Zoom api key', 'id' => '']) !!}
                {!! ($errors->has('zoom_api') ? $errors->first('zoom_api', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('zoom_secret') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('zoom_secret', 'Zoom Secret:<span class="has-stik">*</span>', ['class' => 'col-sm-12 control-label '])) !!}
            <div class="col-sm-12">
                {!! Form::text('api_secret', null, ['class' => 'form-control','placeholder'=>'zoom secret']) !!}
                {!! ($errors->has('zoom_secret') ? $errors->first('zoom_secret', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
 <div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('zoom_email') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('zoom_email', 'Zoom Email:<span class="has-stik">*</span>', ['class' => 'col-sm-12 control-label '])) !!}
            <div class="col-sm-12">
                {!! Form::text('zoom_email', null, ['class' => 'form-control','placeholder'=> 'Zoom email', 'id' => '']) !!}
                {!! ($errors->has('zoom_email') ? $errors->first('zoom_email', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>

</div>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('status', trans('comman.status') . ':', ['class' => 'col-md-4 control-label '])) !!}
                    <div class="col-md-12">
                        <div class="col-md-4">
                            <div class="radio">
                                <label>
                                     {!! Form::radio('status', 'active') !!} active
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="radio">
                                <label>
                                    {!! Form::radio('status', 'in_active') !!} Inactive
                                </label>
                            </div>
                        </div>

                        {!! ($errors->has('status') ? $errors->first('status', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel panel-white">
    <div class="panel-heading">
        <h5 class="panel-title">{{trans('comman.program')}} Availability</h5>
    </div>
    <div class="panel-body">
        <div class="form-group">
            {!! Html::decode(Form::label('available', 'New clients can be allocated to this coach?', ['class' => 'col-md-4 control-label '])) !!}
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

            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group {{ $errors->has('program_id') ? 'has-error' : ''}}">
                    <div class="col-md-12">
                        {{-- {{ dump($coach['coach_program_id']) }} --}}
                        @if(isset($programs_name) && count($programs_name) > 0)
                            @foreach($programs_name as $id => $program)
                            <div class="col-sm-4">
                                <label class="checkbox-inline">
                                    {!! Form::checkbox('program_id[]', $id, null, ['class' => 'styled']) !!}
                                    {{ $program }}
                                </label>
                                @if(isset($coach['coach_program_id'][$id]))
                                    {!! Form::hidden('coach_program_id['.$id.']', $coach['coach_program_id'][$id]) !!}
                                @endif
                                </div>
                            @endforeach
                        @endif
                        {!! ($errors->has('program_id') ? $errors->first('program_id', '<p class="text-danger">:message</p>') : '') !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 @if(isset(Auth::user()->user_type) && Auth::user()->user_type == 'agent') hide @endif">
            <div class="form-group {{ $errors->has('proxy_coach_id') ? 'has-error' : ''}}">
                <div class="col-md-12 no-padding">
                {!! Html::decode(Form::label('other_coach_feedback', trans('comman.other_coach_feedback') . ':', ['class' => 'control-label '])) !!}
                </div>
                <div class="col-md-12">
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
</div>
<div class="panel panel-white @if(isset(Auth::user()->user_type) && Auth::user()->user_type == 'agent') hide @endif">
    <div class="panel-heading">
        <h5 class="panel-title">Payment Details</h5>
    </div>
    <div class="panel-body">
    <div class="table-responsive">
    <table class="table table-bordered table-lg">
        <tbody>
            @if(isset($coach_programs) && count($coach_programs) > 0)
                @php
                    $program_counter = 1;
                @endphp

                @foreach($coach_programs as $coach_prg)
                @if(!empty($coach_prg->coach_program_detail))
                <tr class="border-double">

                        <th colspan="2"> Program - {{ $program_counter++ }}: {{ $coach_prg->coach_program_detail->program_name }} </th>
                </tr>
                @php
                $programs = $coach_prg->coach_program_detail->program_name;
                @endphp
                @if(count($coach_prg->coach_program_detail->modules) > 0)
                    <tr>
                            <th>Module</td>
                            <th>Rate</td>
                        </tr>

                 @foreach($coach_prg->coach_program_detail->modules as $module)
                        <tr>
                                <td class="col-md-5 left-padding-20">
                                    Module - {{ $module->module_no }}: {{ $module->module_title }}
                                </td>
                                <td class="col-sm-3 {{ $errors->first('module.'.$coach_prg->coach_program_detail->id.'.'.$module->id, 'has-error') }}" >
                                    {!! Form::text('module['. $coach_prg->coach_program_detail->id .']['. $module->id .']', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('module.'.$coach_prg->coach_program_detail->id.'.'.$module->id, '<span class="help-block">:message</span>') !!}
                                    @if (isset($module_rates['module_id'][$coach_prg->coach_program_detail->id][$module->id]))
                                        {!! Form::hidden('module['. $coach_prg->coach_program_detail->id .']['. $module->id .']', $module_rates['module_id'][$coach_prg->coach_program_detail->id][$module->id]) !!}
                                    @endif
                                </td>
                            </tr>

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

<div class="panel panel-white">
    <div class="panel-heading">
        <h5 class="panel-title">Coaching</h5>
    </div>
    <div class="panel-body">
        <div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('free_20_min_session') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('free_20_min_session', trans('comman.initial_consultation'). ':<span class="has-stik">*</span> ', ['class' => 'control-label col-md-12'])) !!}
            <div class="col-md-12">
                {!! Form::text('free_20_min_session', null, ['class' => 'form-control','placeholder'=> trans('comman.initial_consultation'), 'id' => 'free_20_min_session']) !!}
                {!! ($errors->has('free_20_min_session') ? $errors->first('free_20_min_session', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('one_hour_session') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('one_hour_session', trans('comman.std_one_hour_session'). ':<span class="has-stik">*</span> ', ['class' => 'control-label col-md-12'])) !!}
            <div class="col-md-12">
                {!! Form::text('one_hour_session', null, ['class' => 'form-control','placeholder'=> trans('comman.std_one_hour_session'), 'id' => 'one_hour_session']) !!}
                {!! ($errors->has('one_hour_session') ? $errors->first('one_hour_session', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('graduate_session') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('graduate_session', trans('comman.graduate_session'). ':<span class="has-stik">*</span> ', ['class' => 'control-label col-md-12'])) !!}
            <div class="col-md-12">
                {!! Form::text('graduate_session', null, ['class' => 'form-control','placeholder'=> trans('comman.graduate_session'), 'id' => 'graduate_session']) !!}
                {!! ($errors->has('graduate_session') ? $errors->first('graduate_session', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('min_slots_availability_per_week') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('min_slots_availability_per_week', trans('comman.min_slots_availability_per_week'). ':<span class="has-stik">*</span> ', ['class' => 'control-label col-md-12'])) !!}
            <div class="col-md-12">
                {!! Form::text('min_slots_availability_per_week', null, ['class' => 'form-control','placeholder'=> trans('comman.min_slots_availability_per_week'), 'id' => 'min_slots_availability_per_week']) !!}
                {!! ($errors->has('min_slots_availability_per_week') ? $errors->first('min_slots_availability_per_week', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
    </div>
</div>
<div class="panel panel-white">
    <div class="panel-heading">
        <h5 class="panel-title">Coach Profile</h5>
    </div>
    <div class="panel-body">
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
<h5> {{ trans('comman.address_details') }} </h5>
    <div class="row">
    <div class="col-lg-6">
        <div class="form-group">
            {!! Html::decode(Form::label('address_line_one', trans('comman.address_line1') . ':', ['class' => 'col-md-4 control-label '])) !!}
            <div class="col-sm-12">
                {!! Form::text('address_line_one', null, ['class' => 'form-control','placeholder'=> trans('comman.address_line1') , 'id' => 'address_line_one']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Html::decode(Form::label('address_line_two', trans('comman.address_line2') . ':', ['class' => 'col-md-4 control-label '])) !!}
            <div class="col-sm-12">
                {!! Form::text('address_line_two', null, ['class' => 'form-control','placeholder'=> trans('comman.address_line2') , 'id' => 'address_line_two']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Html::decode(Form::label('address_line_three', trans('comman.address_line3') . ':', ['class' => 'col-md-4 control-label '])) !!}
            <div class="col-sm-12">
                {!! Form::text('address_line_three', null, ['class' => 'form-control','placeholder'=> trans('comman.address_line3') , 'id' => 'address_line_three']) !!}
            </div>
        </div>
        </div>
        <div class="col-lg-6">
        {!! Form::hidden('country_id', 0) !!}
        <div class="form-group">
            {!! Html::decode(Form::label('city', trans('comman.city') . ':', ['class' => 'col-md-4 control-label '])) !!}
            <div class="col-sm-12">
                {!! Form::text('city', null, ['class' => 'form-control','placeholder'=> trans('comman.city') , 'id' => 'city']) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Html::decode(Form::label('zip_code', trans('comman.zip_code') . ':', ['class' => 'col-md-4 control-label '])) !!}
            <div class="col-sm-12">
                {!! Form::text('zip_code', null, ['class' => 'form-control','placeholder'=> trans('comman.zip_code') , 'id' => 'zip_code']) !!}
            </div>
        </div>
    </div>
</div>
<div class="row">

    <div class="col-lg-6">
        <div class="form-group {{ $errors->has('skype_id') ? 'has-error' : ''}}">
            {!! Html::decode(Form::label('skype_id', trans('comman.skype_id') . ':', ['class' => 'col-sm-12 control-label '])) !!}
            <div class="col-sm-12">
                {!! Form::text('skype_id',null ,['class' => 'form-control','placeholder' => trans('comman.skype_id') , 'id' => 'skype_id']) !!}
                {!! ($errors->has('skype_id') ? $errors->first('skype_id', '<p class="text-danger">:message</p>') : '') !!}
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6"><br/><br/></div>
    <div class="form-group col-md-6 no-padding">
        <div class="col-sm-12 no-padding text-right">
            {!! Form::submit(trans('comman.save'), ['name' => 'save','class' => 'btn btn-primary']) !!}
            @if(request()->get('_url'))
                {!! Html::decode(link_to(request()->get('_url'), trans('comman.save_exit'),array('class' => 'btn btn-primary'))) !!}
            @else
                {!! Form::submit(trans('comman.save_exit'), ['name' => 'save_exit','class' => 'btn btn-primary']) !!}
            @endif
            {!! Html::decode(link_to(URL::full(), trans('comman.cancel'),array('class' => 'btn btn-warning'))) !!}
        </div>
    </div>
</div>
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
