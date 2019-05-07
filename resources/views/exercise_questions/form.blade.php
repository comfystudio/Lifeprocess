{!! Form::hidden("program_id", $program_id) !!}
{!! Form::hidden("module_id", $module_id) !!}
{!! Form::hidden("module_exercise_id", $exercise_id) !!}

<div class="form-group {{ $errors->has('module_id') ? 'has-error' : ''}}">
    {!!  Html::decode(Form::label('module_id', trans("comman.module"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::select('module_id',array(""=>trans("comman.module"))+$programs,Request::get('module_id', $module_id),['class' => 'form-control single-select', 'disabled' => 'disabled']) !!}
        {!! $errors->first('module_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('module_exercise_id') ? 'has-error' : ''}}">
    {!!  Html::decode(Form::label('module_exercise_id', trans("comman.exercise"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::select('module_exercise_id',array(""=>trans("comman.exercise"))+$exercises,Request::get('module_exercise_id', $exercise_id),['class' => 'form-control single-select', 'disabled' => 'disabled']) !!}
        {!! $errors->first('module_exercise_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('parent_question_id') ? 'has-error' : ''}}">
    {!!  Html::decode(Form::label('parent_question_id', trans("comman.parent"). ':', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::select('parent_question_id',array(""=>trans("comman.no_parent")) + $parent_questions,Request::get('parent_question_id', null),['class' => 'form-control single-select']) !!}
        {!! $errors->first('parent_question_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('question_title') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('question_title', trans("comman.question"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::text('question_title', null, ['class' => 'form-control','placeholder'=> trans("comman.what_is_it") ]) !!}
        {!! $errors->first('question_title', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('question_no') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('question_no', trans("comman.question_no"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::text('question_no', null, ['class' => 'form-control','placeholder'=> trans("comman.question_no") ]) !!}
        {!! $errors->first('question_no', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('answer_format') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('answer_format', trans("comman.answer_format"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::select('answer_format', $answer_format, null, ['class' => 'form-control single-select', 'onchange' => 'hide_show_options(this.value)']) !!}
        {!! $errors->first('answer_format', '<p class="help-block">:message</p>') !!}
    </div>
</div>
@php
    $style = 'display: block;';
    if (isset($question) && $question['answer_format'] == 'plain_text') {
        $style = 'display: block;';
    }
    if (old('answer_format') == 'plain_text') {
        $style = 'display: block;';
    }
@endphp
<div id="min_max_value" style="{{ $style }}">
    <div class="form-group {{ $errors->has('min_value') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('min_value', trans("comman.min_value"). ':', ['class' => 'col-sm-3 control-label'])) !!}
        <div class="col-sm-4">
            {!! Form::text('min_value', '0', ['class' => 'form-control']) !!}
            {!! $errors->first('min_value', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('max_value') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('max_value', trans("comman.max_value"). ':', ['class' => 'col-sm-3 control-label'])) !!}
        <div class="col-sm-4">
            {!! Form::text('max_value', '10000', ['class' => 'form-control']) !!}
            {!! $errors->first('max_value', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>
<!-- Add more button -->
@php
    $style = 'display: none;';
    if (isset($question) && $question['answer_format'] == 'rank') {
        $style = 'display: block;';
    }
    if (old('answer_format') == 'rank') {
        $style = 'display: block;';
    }
@endphp
<div id="rank_options" style="{{ $style }}">
    <div class="col-md-1"></div>
    <div class="col-md-7">
        <table class="table table-bordered table-hover tablesorter">
            <thead>
                <tr>
                    <th style="width: 50px;"> {{ trans('comman.no') }}.</th>
                    <th> {{ trans('comman.options') }} </th>
                    <th class="text-center"><a class="btn btn-sm btn-success fieldsaddmore-addbtn2"><i class="fa fa-plus"></i></a></th>
                </tr>
            </thead>

            <!-- Main element container -->
            <tbody class="admore-fields2">
                <?php if(old('items',false)): ?>
                    <?php foreach(old('items') as $key => $row): $key + 1;?>
                        <tr class="fieldsaddmore-row2 rowId-{{$key}}">
                            @if(!empty(old("items.$key.id")))
                                {!! Form::hidden("items[$key][id]",old("items.$key.id")) !!}
                            @endif
                            <td> {{ $key }}. </td>
                            <td class="text-center {{ $errors->first('items.'.$key.'.option_value', 'has-error') }}">
                                {{ Form::text("items[$key][option_value]",null,array('class'=>'form-control text-capitalize'))}}
                                {!! $errors->first('items.'.$key.'.option_value', '<span class="help-block">:message</span>') !!}
                            </td>
                            <td class="text-center">
                                <a href="#" data-rowid="{{$key}}" class="btn btn-sm btn-warning fieldsaddmore-removebtn2"><i class="fa fa-minus"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php elseif(!empty($question['items'])): ?>
                    <?php foreach($question['items'] as $key => $row): $key+=1?>
                        <tr class="fieldsaddmore-row2 rowId-{{$key}}">
                            {!! Form::hidden("items[$key][id]",$row['id']) !!}
                            <td> {{ $key }}. </td>
                            <td class="text-center">
                                {{ Form::text("items[$key][option_value]",$row['option_value'],array('class'=>'form-control text-capitalize'))}}
                            </td>
                            <td class="text-center">
                                <a href="#" data-rowid="{{$key}}" class="btn btn-sm btn-warning fieldsaddmore-removebtn2"><i class="fa fa-minus"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<!-- Add slider option -->
@php
    $style = 'display: none;';
    if (isset($question) && $question['answer_format'] == 'slider') {
        $style = 'display: block;';
    }
    if (old('answer_format') == 'slider') {
        $style = 'display: block;';
    }

    if(!empty($question->min_range_value))
    {
        $max_range_value=$question->min_range_value;
        $min_range_value=$question->max_range_value;
    }
    else
    {
        $max_range_value=0;
        $min_range_value=0;
    }
@endphp
<div id="slider_options" style="{{ $style }}">
    <div class="form-group {{ $errors->has('min_range_value') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('min_value_range','Minimum value for Slider', ['class' => 'col-sm-3 control-label'])) !!}
        <div class="col-sm-4">
            {!! Form::text('min_range_value',$min_range_value, ['class' => 'form-control']) !!}
            {!! $errors->first('min_value_range', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
    <div class="form-group {{ $errors->has('max_range_value') ? 'has-error' : ''}}">
        {!! Html::decode(Form::label('max_value_range', 'Maximum value for Slider', ['class' => 'col-sm-3 control-label'])) !!}
        <div class="col-sm-4">
            {!! Form::text('max_range_value',$max_range_value, ['class' => 'form-control']) !!}
            {!! $errors->first('max_value_range', '<p class="help-block">:message</p>') !!}
        </div>
    </div>
</div>

<!--end slider option -->
<!-- Addmore template -->
<script id="fieldsaddmore-template2" type="text/template">
    <tr class="fieldsaddmore-row2 rowId">
        <td>key.</td>
        <td class="text-center">{{ Form::text('items[key][option_value]',null,array('class'=>'form-control text-capitalize'))}}</td>
        <td class="text-center"><a href="#" data-rowid="key" class="btn btn-sm btn-warning fieldsaddmore-removebtn2"><i class="fa fa-minus"></i></a></td>
    </tr>
</script>

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
<script type="text/javascript">
    var temp = [];
    (function($) {
        $('.admore-fields2').fieldsaddmore({

            templateEle: "#fieldsaddmore-template2",
            rowEle: ".fieldsaddmore-row2",
            addbtn: ".fieldsaddmore-addbtn2",
            removebtn: ".fieldsaddmore-removebtn2",
            min:($('.fieldsaddmore-row2').length>0)?0:1,
            callbackBeforeRemoveClick: function (ele, options) {
                options['min'] = 1;
            }
        });
    })(jQuery);

    function hide_show_options(val) {
        if(val == 'rank') {
            jQuery("#rank_options").show();
        } else {
            jQuery("#rank_options").hide();
        }

        if(val == 'plain_text') {
            jQuery("#min_max_value").show();
        } else {
            jQuery("#min_max_value").hide();
        }

       if(val == 'slider') {
            jQuery("#slider_options").show();
        } else {
            jQuery("#slider_options").hide();
        }
    }
</script>
{{-- {!! ajax_fill_dropdown('lang','program_id',route('ajax.program')) !!} --}}
@endpush
