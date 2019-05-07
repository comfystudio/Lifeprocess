<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
{!! Html::decode(Form::label('page', trans("comman.page_title"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
     <div class="col-sm-4">
	{!! Form::text('title', null, ['class' => 'form-control text-capitalize','id'=>'role_name','autofocus'=>'true', 'autocomplete' => 'off','placeholder' => trans("comman.page_title")]) !!}
     {!! ($errors->has('title') ? $errors->first('title', '<p class="text-danger">:message</p>') : '') !!}
     </div>
</div>
<div class="form-group {{ $errors->has('slug') ? 'has-error' : ''}}">
{!! Html::decode(Form::label('slug', trans("comman.page_slug"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
     <div class="col-sm-4">
    {!! Form::text('slug', null, ['class' => 'form-control','id'=>'slug', 'readonly']) !!}
     {!! ($errors->has('slug') ? $errors->first('slug', '<p class="text-danger">:message</p>') : '') !!}
     </div>
</div>
<div class="form-group {{ $errors->has('content') ? 'has-error' : ''}}">
{!! Html::decode(Form::label('content', trans("comman.page_content"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
<div class="col-sm-8">
     {!! Form::textarea('content', null, ['class' => 'form-control','rows' => '5','id' =>'summernote']) !!}
     {!! ($errors->has('content') ? $errors->first('content', '<p class="text-danger">:message</p>') : '') !!}
     </div>
</div>
@if(request()->get('_url'))
     {{ Form::hidden('_url',request()->get('_url'))}}
@endif



