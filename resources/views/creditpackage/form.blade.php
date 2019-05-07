<div class="form-group {{ $errors->has('credit') ? 'has-error' : ''}}">
{!! Html::decode(Form::label('credit', trans("comman.no_of_credit"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
     <div class="col-sm-4">
	{!! Form::text('credit', null, ['class' => 'form-control number','placeholder' => trans("comman.no_of_credit")]) !!}
     {!! ($errors->has('credit') ? $errors->first('credit', '<p class="text-danger">:message</p>') : '') !!}
     </div>
</div>
<div class="form-group {{ $errors->has('price') ? 'has-error' : ''}}">
{!! Html::decode(Form::label('price', trans("comman.credit_price"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
     <div class="col-sm-4">
     {!! Form::text('price', null, ['class' => 'form-control ','placeholder' => trans("comman.credit_price")]) !!}
     {!! ($errors->has('price') ? $errors->first('price', '<p class="text-danger">:message</p>') : '') !!}
     </div>
</div>
<div class="form-group">
{!! Html::decode(Form::label('status', trans("comman.status"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
     <div class="col-md-9">
 <div class="radio">
 <label>
{{ Form::radio('status', 'public') }}Public
</label>
</br>
<label>
{{ Form::radio('status', 'draft','true') }}Draft
</label>
</div>
</div>
@push('scripts')
<script type="text/javascript">
      $('.number').keypress(function(event){
            console.log(event.which);
        if(event.which != 8 && isNaN(String.fromCharCode(event.which))){
            event.preventDefault();
        }});
</script>
@endpush




