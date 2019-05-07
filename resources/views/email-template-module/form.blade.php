<div class="form-group">
    {!! Html::decode(Form::label('template_name', trans("comman.name"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-6">
        {!! Form::text('template_name', null, ['class' => 'form-control number','placeholder' => trans("comman.name")]) !!}
    </div>
</div>
<div class="form-group">
    {!! Html::decode(Form::label('slug', trans("comman.slug"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-6">
        {!! Form::text('slug', null, ['class' => 'form-control','readonly']) !!}
    </div>
</div>
<div class="form-group">
    {!! Html::decode(Form::label('slug', trans("comman.trigger"), ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-6">
        {!! Form::text('trigger', null, ['class' => 'form-control','readonly']) !!}
    </div>
</div>
<div class ="form-group">
    {!! Html::decode(Form::label('tags', trans("comman.tags"), ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-6">
        {!! Form::text('tags', null, ['class' => 'form-control','readonly']) !!}
    </div>
</div>
<div class="form-group">
    {!! Html::decode(Form::label('to', trans("comman.to"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-6">
        {!! Form::text('to', null, ['class' => 'form-control number','placeholder' => trans("comman.to")]) !!}
    </div>
</div>
<div class="form-group">
    {!! Html::decode(Form::label('subject', trans("comman.subject"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-6">
        {!! Form::text('subject', null, ['class' => 'form-control number','placeholder' => trans("comman.subject")]) !!}
    </div>
</div>
<div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
{!! Html::decode(Form::label('content', trans("comman.content"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
<div class="col-sm-6">
     {!! Form::textarea('content', null, ['class' => 'form-control summernote','rows' => '5','id' =>'summernote']) !!}
     {!! ($errors->has('content') ? $errors->first('content', '<p class="text-danger">:message</p>') : '') !!}
</div>
</div>
