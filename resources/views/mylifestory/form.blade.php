<div class="form-group {{ $errors->has('message') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('mylifeevent', trans("comman.addanupdate"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-12  control-label'])) !!}
    <div class="col-sm-12">
        {!! Form::textarea('message', null, ['class' => 'form-control','placeholder' => 'Add an Entry','rows' => '8' ,'id' =>'summernote']) !!}

        {!! $errors->first('message', '<p class="help-block">:message</p>') !!}
    </div>
</div>


