
<div class="form-group {{ $errors->has('program_name') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('program_name', trans("comman.program_name"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::text('program_name', null, ['class' => 'form-control','placeholder' => trans("comman.program_name") ]) !!}
        {!! $errors->first('program_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('status', trans("comman.status"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::select('status', $program_status, null, ['class' => 'form-control single-select' ]) !!}
        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('program_fee') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('program_fee', trans("comman.program_fee"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::text('program_fee', null, ['class' => 'form-control' ,'placeholder' => trans("comman.program_fee")]) !!}
        {!! $errors->first('program_fee', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('program_icon') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('program_icon', trans("comman.program_icon"). ':', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::file('program_icon', ['class' => 'form-control', 'onchange' => 'readUserURL(this)', 'accept' => 'image/*']) !!}
        {!! $errors->first('program_icon', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-sm-5">
        @if(isset($program['program_icon']) && !empty($program['program_icon']))
            {{Html::image(AppHelper::path('uploads/program/icons/')->size('50x50')->getImageUrl($program['program_icon']),'User Photo',array("class"=>"img-circle",'id'=>'staff','height'=>'50','width'=>'50'))}}
        @else
            {{Html::image(AppHelper::size('50x50')->getDefaultImage(),'User Photo',array("class"=>"img-circle staff",'id'=>'staff','height'=>'50','width'=>'50'))}}
        @endif
    </div>
</div>
<div class="form-group {{ $errors->has('sort_description') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('sort_description', trans("comman.sort_description"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-8">
        {!! Form::textarea('sort_description', null, ['class' => 'form-control','placeholder' => trans("comman.sort_description"), 'rows' => '5' ,'class' =>'summernote']) !!}
        {!! $errors->first('sort_description', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('introduction_long_description') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('introduction_long_description', trans("comman.introduction_long_description"). ':', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-8">
        {!! Form::textarea('introduction_long_description', null, ['class' => 'form-control','placeholder' => trans("comman.introduction_long_description"), 'rows' => '9','class' =>'summernote']) !!}
        {!! $errors->first('introduction_long_description', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('default_message') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('default_message', trans("comman.default_message"). ':', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-5">
        {!! Form::textarea('default_message', null, ['class' => 'form-control','placeholder' => trans("comman.default_message"), 'rows' => '9' ]) !!}
        {!! $errors->first('default_message', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('introduction_video') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('introduction_video','introduction_video'. ':', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-5">
        {!! Form::textarea('introduction_video', null, ['class' => 'form-control','placeholder' => 'Add introduction video link', 'rows' => '4' ]) !!}
        {!! $errors->first('introduction_video', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('gratuate_video') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('gratuate video','gratuate video'. ':', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-5">
        {!! Form::textarea('gratuate_video', null, ['class' => 'form-control','placeholder' => 'Add gratuate video link', 'rows' => '4' ]) !!}
        {!! $errors->first('gratuate_video', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('stripe_program_name') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('Stripe Program Name','stripe program name'. ':', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-5">
        {!! Form::text('stripe_program_name', null, ['class' => 'form-control','placeholder' => 'Add Stripe Program Name', 'rows' => '4' ]) !!}
        {!! $errors->first('stripe_program_name', '<p class="help-block">:message</p>') !!}
    </div>
</div>

{!! Form::hidden('_edit_url', request()->get('_edit_url', route('programs.index'))) !!}