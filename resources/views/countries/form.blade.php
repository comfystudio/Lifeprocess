<div class="form-group {{ $errors->has('country') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('country', trans("comman.country"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::text('country', null, ['class' => 'form-control','placeholder' => trans("comman.country") ]) !!}
        {!! $errors->first('country', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('country_code') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('country_code', trans("comman.country_code"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::text('country_code', null, ['class' => 'form-control','placeholder' => trans("comman.country_code") ]) !!}
        {!! $errors->first('country_code', '<p class="help-block">:message</p>') !!}
    </div>
</div>
