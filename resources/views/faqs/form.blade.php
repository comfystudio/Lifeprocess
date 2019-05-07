<div class="form-group {{ $errors->has('question') ? 'has-error' : ''}}">
{!! Html::decode(Form::label('question', trans("comman.faq_question"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
     <div class="col-sm-8">
	{!! Form::text('question', null, ['class' => 'form-control','placeholder' => trans("comman.faq_question")]) !!}
     	{!! ($errors->has('question') ? $errors->first('question', '<p class="text-danger">:message</p>') : '') !!}
     </div>
</div>
<div class="form-group {{ $errors->has('answer') ? 'has-error' : ''}}">
{!! Html::decode(Form::label('answer', trans("comman.faq_answer"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-8">
         {!! Form::textarea('answer', null, ['class' => 'form-control','rows' => '5','id' =>'summernote']) !!}
         {!! ($errors->has('answer') ? $errors->first('answer', '<p class="text-danger">:message</p>') : '') !!}
    </div>
</div>


