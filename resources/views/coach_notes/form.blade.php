<div class="form-group {{ $errors->has('note') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('note', trans("comman.notes"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-2 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::textarea('note', null, ['class' => 'form-control','placeholder' => trans("comman.notes"),'rows' => '5']) !!}
        {!! $errors->first('note', '<p class="help-block">:message</p>') !!}
    </div>
</div>
