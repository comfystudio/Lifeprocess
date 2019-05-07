
<div class="form-group {{ $errors->has('use_your_name') ? 'has-error' : ''}}">
 {!! Html::decode(Form::label('Your Name', trans("comman.refererusename"), ['class' => ''])) !!}

 <div class="radio">
 <label class="radio-inline">
{{ Form::radio('use_your_name', 'Yes','true') }}Yes. Send this message using my name.
</label>

 <label class="radio-inline">
{{ Form::radio('use_your_name', 'No') }}No. Do <b>not</b> use my name.
</label>
</div>
 {!! $errors->first('use_your_name', '<p class="help-block">:message</p>') !!}

 </div>

<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">

<div class="col-sm-12">
{!! Html::decode(Form::label('Your Name', trans("comman.referername"), ['class' => ''])) !!}
</div>
<div class="col-sm-6">
	{!! Form::text('name', null, ['class' => 'form-control', 'placeholder' => trans("comman.referername")]) !!}
     {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
</div>
</div>



<div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
<div class="col-sm-12">
{!! Html::decode(Form::label('Your Email', trans("comman.refereremail"), ['class' => ''])) !!}
</div>
<div class="col-sm-6">
	 {!! Form::text('email', Auth::user()->email, ['class' => 'form-control', 'placeholder' => trans("comman.refereremail"),'readonly' => 'readonly']) !!}
     {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
     </div>

</div>


<div class="form-group {{ $errors->has('friends_email') ? 'has-error' : ''}}">
<div class="col-sm-12">
{!! Html::decode(Form::label('friend email', trans("comman.refererefriendmail"), ['class' => ''])) !!}
</div>
<div class="col-sm-6">
	{!! Form::text('friends_email', null, ['class' => 'form-control', 'placeholder' => trans("comman.refererefriendmail")]) !!}
     {!! ($errors->has('friends_email') ? $errors->first('friends_email', '<p class="text-danger">:message</p>') : '') !!}
</div>
</div>


 <div class="form-group {{ $errors->has('message') ? 'has-error' : ''}}">

  {!! Html::decode(Form::label('message', trans("comman.message"), ['class' => ''])) !!} <br>
<div style="font-size: 11px;font-family: Open Sans;font-style: italic;">  Suggested text - feel free to edit this message before sending </div>

  {!! Form::textarea('message',  $message_defualt_text, ['class' => 'form-control','placeholder' => trans("comman.message"),'rows' => '17']) !!}

  {!! $errors->first('message', '<p class="help-block">:message</p>') !!}

 </div>
