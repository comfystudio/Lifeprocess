@if(request()->get('download'))
   {!! Form::hidden("module_id",request()->get('module_id')) !!}
@else
    {!! Form::hidden("module_id", $module_id) !!}
@endif
{!! Form::hidden("program_id", $program_id) !!}

<div class="form-group {{ $errors->has('module_id') ? 'has-error' : ''}}">
    {!!  Html::decode(Form::label('module_id', trans("comman.module"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::select('module_id',array(""=>trans("comman.select_module"))+$programs,Request::get('module_id', $module_id),['class' => 'form-control single-select', 'disabled' => 'disabled']) !!}
        {!! $errors->first('module_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('exercise_no') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('exercise_no', trans("comman.exercise_no"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::text('exercise_no', null, ['class' => 'form-control','placeholder'=> trans("comman.exercise_no") ]) !!}
        {!! $errors->first('exercise_no', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('title') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('title', trans("comman.exercise_title"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::text('title', null, ['class' => 'form-control','placeholder'=> trans("comman.exercise_title") ]) !!}
        {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('sort_description') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('sort_description', trans("comman.sort_description"). ':', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-8">
        {!! Form::textarea('sort_description', null, ['class' => 'form-control','placeholder'=> trans("comman.sort_description"), 'rows' => '5','id'=>'summernote']) !!}
        {!! $errors->first('sort_description', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('reading_material') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('reading_material', 'reading material'. ':', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::file('reading_material', ['class' => 'form-control']) !!}
        @if(!empty($reading_material))
        {{$reading_material}}
        @endif
        {!! $errors->first('reading_material', '<p class="help-block">:message</p>') !!}
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
{{-- {!! ajax_fill_dropdown('lang','program_id',route('ajax.program')) !!} --}}
@endpush
