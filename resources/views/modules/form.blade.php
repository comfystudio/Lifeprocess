@if(request()->get('download'))
   {!! Form::hidden("program_id",request()->get('program_id')) !!}
@else
    {!! Form::hidden("program_id", $program_id) !!}
@endif

<div class="form-group {{ $errors->has('program_id') ? 'has-error' : ''}}">
    {!!  Html::decode(Form::label('program_id', trans("comman.program"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::select('program_id',array(""=>trans("comman.select_program"))+$programs,Request::get('program_id', $program_id),['class' => 'form-control single-select', 'disabled' => 'disabled']) !!}
        {!! $errors->first('program_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('module_title') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('module_title', trans("comman.module_title"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::text('module_title', null, ['class' => 'form-control','placeholder'=> trans("comman.module_title") ]) !!}
        {!! $errors->first('module_title', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('module_no') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('module_no', trans("comman.module_no"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::text('module_no', null, ['class' => 'form-control','placeholder'=> trans("comman.module_no") ]) !!}
        {!! $errors->first('module_no', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('status', trans("comman.status"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::select('status', $module_status, null, ['class' => 'form-control single-select']) !!}
        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('introduction_video') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('introduction_video', trans("comman.introduction_video"). ':', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::textarea('introduction_video', null, ['class' => 'form-control','id'=>'introduction_video','placeholder'=> trans("comman.introduction_video"), 'rows' => '5' ]) !!}
        {!! $errors->first('introduction_video', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('delay_btw_chapter_exercise') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('delay_btw_chapter_exercise', trans("comman.delay_btw_chapter_exercise"). ':', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::text('delay_btw_chapter_exercise', isset($default_delay) ? $default_delay : null , ['class' => 'form-control','placeholder'=> trans("comman.delay_btw_chapter_exercise")]) !!}
        {!! $errors->first('delay_btw_chapter_exercise', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('reading_material') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('reading_material', trans("comman.reading_material"). ':', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::file('reading_material', ['class' => 'form-control']) !!}
        @if(!empty($reading_material))
        {{$reading_material}}
        @endif
        {!! $errors->first('reading_material', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<input type="hidden" name="introduction_video_fixed" id="introduction_video_fixed" value="">
@push('scripts')
<script type="text/javascript">
    jQuery('.app > .app-content > .box').append('<div class="overlay ajax-overlay"><i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i></div>');
    var $loading = jQuery('.ajax-overlay').hide();
    jQuery(document).ajaxStart(function () {
        $loading.show();
    }).ajaxStop(function () {
        $loading.hide();
    });
    jQuery('#introduction_video').click(function(event) {
            var str=jQuery('#introduction_video').val();
            //alert(str);
            var container = jQuery("<div>" + str + "</div>");
            container.find("iframe").attr("width", "100%");
            str = container.html();
            jQuery('#introduction_video_fixed').val(str);
            });

    jQuery('#introduction_video').mouseleave(function(event) {
            var str=jQuery('#introduction_video').val();
            //alert(str);
            var container = jQuery("<div>" + str + "</div>");
            container.find("iframe").attr("width", "100%");
            str = container.html();
            jQuery('#introduction_video_fixed').val(str);
    });
</script>
{!! ajax_fill_dropdown('lang','program_id',route('ajax.program')) !!}
@endpush
