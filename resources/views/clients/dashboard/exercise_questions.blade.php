@extends($theme)
@section('title', $title)
@section('content')
<style type="text/css">
    form {
        margin-bottom: 0;
    }
    #fbtn
    {
        margin-right:42px;
    }
    .btn-space
    {
        margin-left: 10px;
    }

    .slidecontainer {
        width: 100%;
    }

    .slider {
        -webkit-appearance: none;
        width: 100%;
        height: 25px;
        background: #d3d3d3;
        outline: none;
        opacity: 0.7;
        -webkit-transition: .2s;
        transition: opacity .2s;
    }

    .slider:hover {
        opacity: 1;
    }

    .slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 25px;
        height: 25px;
        background: #4CAF50;
        cursor: pointer;
    }

    .slider::-moz-range-thumb {
        width: 25px;
        height: 25px;
        background: #4CAF50;
        cursor: pointer;
    }
</style>
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="tab-title">
            <h1>
              Module {{ $module_info->module_no }} - {{$module_info->module_title}}
            </h1>
            <h6 class="col-md-10" style="float: left;"> <b>
                Excercise {{$module_info->module_no}}.{{$excercise_info->exercise_no}} - {{$excercise_info->title}} </b></h6>
            @if(!empty($excercise_info->reading_material))
            {!! Html::decode(Html::link(AppHelper::path('uploads/module_excercise/')->getImageUrl($excercise_info->reading_material),'Download Worksheet',array('id' => $excercise_info->reading_material,'download','class'=>"btn bg-primary btn-labeled heading-btn",'type'=>'button'))) !!}
            @endif
            <br>
            <p>
            {!! $exercise_description !!}
            </p>
        </div>

        <div class="panel-heading">
        <div class="row">
        <ol class="step_progress">
        <div class="stepwizard">
                <div class="stepwizard-row">
                <div class="stepwizard-step">
                @php
                    $exceed_current_page = false;
                @endphp
                @for($page = 1; $page <= $exercise_questions->total(); $page++)
                    @php
                        $class = 'is-complete';
                        if($exercise_questions->currentPage() == $page) {
                            $class = 'btn-primary';
                            $exceed_current_page = true;
                        } else if($exceed_current_page) {
                            $class = 'btn-default';
                        }
                    @endphp
                   {{--  <a href="{{ $exercise_questions->appends($page)}}" ><button type="button" class="btn {{$class}} btn-circle">{{$page}}</button></a>
                     <input type="hidden" id="page" value="{{$page}}"> --}}
                @endfor
                <div class="page_save">
                 {{$exercise_questions->appends($page)}}
                </div>

                </div>
                </div>
        </div>
        </ol>
        </div>
        </div>


        {!! Form::model($userAnswers, array('route' => ['client-exercises.store', 'module_id' => Crypt::encryptString($module_id), 'exercise_id' => Crypt::encryptString($exercise_id)],'class'=>'form-horizontal submitform','role'=>"form",'id'=>'add_exercise_question')) !!}

        <div class="panel-body">
            @php
                // global $total_question;
                static $total_question = 0;
                // $answer=array();
            @endphp
            @foreach ($exercise_questions->items() AS $question)

                <strong>
                    {{ $question->question_no }}. {{ $question->question_title }}
                </strong>

                @if($question->answer_format == 'plain_text')
                    <div class="form-group {{ $errors->has('answer.'.$question->id) ? 'has-error' : ''}}" style="margin-top:6px;">
                        <div class="col-sm-7" style="padding-left: 20px;">
                            {!! Form::textarea('answer['.$question->id.']', null, ['class' => 'form-control answer', 'rows' => 5]) !!}
                            {!! $errors->first('answer.'.$question->id, '<p class="help-block">:message</p>') !!}
                            {!! Form::hidden('min_value['. $question->id .']', $question->min_value) !!}
                            {!! Form::hidden('max_value['. $question->id .']', $question->max_value) !!}
                            {!! Form::hidden('is_gratuate_excersize', Auth::user()->is_gratuate) !!}
                        </div>
                    </div>
                @elseif($question->answer_format == 'boolean_yes_no')
                    <div class="form-group {{ $errors->has('answer.'.$question->id) ? 'has-error' : ''}}" style="padding-left: 20px;">
                        <div class="radio">
                            <label>
                                {!! Form::radio('answer['.$question->id.']', 'yes',null,['class'=>'answer answer['.$question->id.']','id'=>'answer['.$question->id.']'] ) !!} Yes
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                {!! Form::radio('answer['.$question->id.']', 'no',null,['class'=>'answer answer['.$question->id.']','id'=>'answer['.$question->id.']']) !!} No
                            </label>
                        </div>
                        {{--     <input type=hidden name="answer[{{$question->id}}]" value="" id="answer[{{$question->id}}]"> --}}

                        {{--   <div class="radio">
                            <label>
                                {!! Form::radio('answer['.$question->id.']', 'not answered',true,['class'=>'answer']) !!} Not Answered
                            </label>
                        </div> --}}
                        {!! $errors->first('answer.'.$question->id, '<p class="help-block">:message</p>') !!}
                    </div>
                @elseif($question->answer_format == 'statement')

                   <div class="form-group {{ $errors->has('answer.'.$question->id) ? 'has-error' : ''}}" style="padding-left: 20px;">

                   {!! $errors->first('answer.'.$question->id, '<p class="help-block">:message</p>') !!}
                   </div>

                @elseif($question_answer_format=='slider')

                    <div class="form-group {{ $errors->has('answer.'.$question->id) ? 'has-error' : ''}}" style=">

                        <input type="range" min="{{$question->min_range_value}}" max="{{$question->max_range_value}}" value="50" name="answer[{{$question->id}}]">
                        {!! $errors->first('answer.'.$question->id, '<p class="help-block">:message</p>') !!}
                    </div>
                @else
                    @foreach($question->module_exercise_question_options AS $option)
                    <div class="radio">
                        <label>
                            {!! Form::radio('answer['.$question->id.']', $option->option_value,null,['class'=>'answer answer['.$question->id.']','id'=>'answer['.$question->id.']']) !!} {{ $option->option_value }}
                        </label>
                      {{--   <input type=hidden name="answer[{{$question->id}}]" value="" id="answer[{{$question->id}}]"> --}}
                    </div>
                    @endforeach
                    <div class="radio">
                        <label>
                           {!! Form::radio('answer['.$question->id.']','not answered',true,['class'=>'answer']) !!} Not Answered
                        </label>
                    </div>
                    <input type=hidden name="answer[{{$question->id}}]" value="notanswered" id="answer[{{$question->id}}]">

                    {!! $errors->first('answer.'.$question->id, '<p class="help-block">:message</p>') !!}

                @endif
                {{-- {!! Form::hidden('question_id[]', $question->id) !!} --}}
                {!! Form::hidden('question_answer_format['.$question->id.']', $question->answer_format) !!}

                @if(isset($question->sub_questions) && count($question->sub_questions))
                    @include('clients.dashboard.exercise_subQuestions',['sub_questions' => $question->sub_questions, 'parent_question_no' => $question->question_no, 'level' => '2'])
                @endif
            @endforeach
        </div>
        <div class="panel-footer">
            <div class="text-center pull-right btn-space">
                @php
                    if($exercise_questions->nextPageUrl()) {
                        echo link_to($exercise_questions->nextPageUrl(), 'Next Question', ['class' => 'btn btn-primary submit_form']);
                    } else if($lastExerciseIdOfModule == $exercise_id) {
                        // You won't be able to update your answers once module is submitted. Are you sure you want to submit the module for review?
                        // echo Form::submit('Submit for review', ['name' => 'submit_for_review','class' => 'btn btn-success', 'onClick' => 'return confirm("You won\'t be able to update your answers once module is submitted. Are you sure you want to submit the module for review?")']);
                        echo Form::button('Submit for review', ['name' => 'btn_submit_for_review','class' => 'btn btn-success ', 'onClick' => 'confirm_before_submit()']);
                        echo Form::hidden('submit_for_review', 'Submit for review');
                    } else {
                        if($dont_show_dialog == '0')
                             // echo Form::button('Submit for review', ['name' => 'finish','class' => 'btn btn-success ', 'onClick' => 'confirm_before_submit()']);
                            echo Form::button('Submit for review', ['name' => 'finish','class' => 'btn btn-success btn-block', 'onclick'=>'confirm_before_finish()']);
                        else
                             // echo Form::button('Submit for review', ['name' => 'finish','class' => 'btn btn-success ', 'onClick' => 'finish_without_dialog()','id'=>'fbtn']);
                            echo Form::button('Submit for review', ['name' => 'finish','class' => 'btn btn-success btn-block', 'onclick'=>'finish_without_dialog()','id'=>'fbtn']);
                       // echo Form::submit('Finish', ['name' => 'finish','class' => 'btn btn-primary','onClick' => 'confirm_before_finish()']);
                    }
                @endphp
            </div>
            <div class="text-center pull-right btn-space">
                {!! Form::submit('Save & return to home', ['name' => 'save_exit','class' => 'btn btn-info']) !!}
            </div>
            <div class="text-center pull-right btn-space">
                @php
                    if($exercise_questions->previousPageUrl()) {
                        echo link_to($exercise_questions->previousPageUrl(), 'Previous Question', ['class' => 'btn btn-primary submit_form']);
                    }
                @endphp
            </div>
            <div style="clear: both;"></div>
        </div>
        {{--    {{dump($popup_option)}} --}}
        @if(isset($popup_option) && !empty($popup_option))

        <input type="hidden" name="popup_option" value="{{$popup_option}}" class="popupoption">
        @else
        <input type="hidden" name="popup_option" class="popupoption" value="yes">
        @endif
        {!! Form::hidden('redirect_to', '', ['id' => 'redirect_to']) !!}
        {!! Form::close() !!}

    </div>
</div>

@push('scripts')
    <script type="text/javascript">
        jQuery(".page_save").on('click', function(e){


             //return true;
        })
        jQuery(".submit_form").on('click', function(e){
            var fields = $(".submitform") .find("textarea,input[type='radio']").serializeArray();
            //alert(fields);
            var btntext=($(this).text());
            var link=($(this).attr('href'));
            //alert(link);
            var status =false;
            var status_false=true;
            var popup=jQuery('.popupoption').val();
            //alert(popup);

            $.each($("input[type='radio'].answer:checked"),function(index, el) {
                //alert($(this).val());
                if($(this).val() == 'not answered')
                {
                    status_false = false;
                }
                else
                {
                    status = true;
                }
            });
            //alert(status_false);
            $.each($("textarea.answer"),function(index, el) {
                if($(this).val() != ''){
                    status = true;
                }
                else
                {
                    status_false = false;
                }
            });
            if(popup=='yes')
            {
            if(status_false==false){
                e.preventDefault();
                swal({
                    title: '',
                     html:
                '<b>You have not completed this question. Are you sure you want to continue?<br><br>' +
                '<label><input type="checkbox" class="dont_show"> Do not show again</label>',
                    // text: "You have not completed this question. Are you sure you want to continue?+
                    // '<label><input type="checkbox" class="dont_show"> Do not show this again</label>'",
                    showCancelButton: true,
                    confirmButtonClass: 'btn btn-primary',
                    cancelButtonClass: 'btn btn-default',
                    confirmButtonText: "Proceed to "+btntext,
                    allowOutsideClick: false

                }).then(function () {
                     if($('.dont_show').prop('checked')){
                        jQuery('.popupoption').val('no');
                     }
                    var clicked_link_href=link;
                    //alert(clicked_link_href);
                    jQuery("#redirect_to").val(clicked_link_href);
                    jQuery("#add_exercise_question").submit();
                    return true;
                }, function(dismiss){
                    return false;
                });
            }
            else
            {
                    e.preventDefault();
                    var clicked_link_href = link;
                    jQuery("#redirect_to").val(clicked_link_href);
                    jQuery("#add_exercise_question").submit();
                    return true;
            }
            }
            else
            {
                 e.preventDefault();
                    var clicked_link_href = link;
                    jQuery("#redirect_to").val(clicked_link_href);
                    jQuery("#add_exercise_question").submit();
                    return true;
            }

        });

        function confirm_before_submit() {
            swal({
                title: '',
                text: "You won't be able to update your answers once module is submitted. Are you sure you want to submit the module for review?",
                showCancelButton: true,
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-default',
                confirmButtonText: "Submit",
                allowOutsideClick: false
            }).then(function () {

                jQuery('#add_exercise_question').submit();
                return true;
            }, function(dismiss){
                return false;
            });
        }

        function confirm_before_finish() {
            swal({
              html:
                '<b>You will not be able to edit this exercise after you submit it to your coach.<br><br> Are you sure you want to proceed? <br><br>' +
                '<label><input type="checkbox" class="dont_show"> Do not show this again</label>',
              showCloseButton: true,
              showCancelButton: true,
              cancelButtonClass:'dont_submit',
              confirmButtonText:
                'Submit Exercise',
              cancelButtonText:
                'Don’t Submit',
            }).then(function (input) {
                if($('.dont_show').prop('checked')){
                    jQuery('#add_exercise_question').append('<input type="hidden" name="dont_show_dialog" value="1" />');
                }
                jQuery('#add_exercise_question').append('<input type="hidden" name="finish" value="finish" />');
                jQuery('#add_exercise_question').submit();
                return true;
            }, function(dismiss){
                cancel_confirmation();
            });
        }

        function cancel_confirmation(){
            swal({
              html:
                '<b>You have not submitted Exercise <X.x></b> <br> You can still go back and edit your work, but please be aware that your coach will not be able to review your exercise until you submit it. <br/><br/>' +
                '<label><input type="checkbox" class="dont_show" style="font-weight:normal;"> Do not show this again</label>',
              showCloseButton: true,
              showCancelButton: false,
              confirmButtonText:
                'OK',
              cancelButtonText:
                '<i class="fa fa-thumbs-down"></i>',
            }).then(function (input) {
                if($('.dont_show').prop('checked')){
                    jQuery('#add_exercise_question').append('<input type="hidden" name="dont_show_dialog" value="1" /><input type="hidden" name="btn_name" value="cancel" />');
                }
                jQuery('#add_exercise_question').submit();
            }, function(dismiss){
                return false;
            });
        }

        function finish_without_dialog(){
            swal({
                title: '',
                html: "You will not be able to edit this exercise after you submit it to your coach.<br><br> Are you sure you want to proceed?",
                showCancelButton: true,
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-default',
                confirmButtonText: "Submit Exercise",
                cancelButtonText: "Don’t Submit",
                allowOutsideClick: false
            }).then(function () {
                jQuery('#add_exercise_question').append('<input type="hidden" name="finish" value="finish" />');
                jQuery('#add_exercise_question').submit();
                return true;
            }, function(dismiss){
                cancel_confirmation();
            });
        }
        $(document).ready(function() {
var i = 1;
$('.progress .circle').removeClass().addClass('circle');
$('.progress .bar').removeClass().addClass('bar');
setInterval(function() {
$('.progress .circle:nth-of-type(' + i + ')').addClass('active');

$('.progress .circle:nth-of-type(' + (i - 1) + ')').removeClass('active').addClass('done');

$('.progress .circle:nth-of-type(' + (i - 1) + ') .label').html('&#10003;');

$('.progress .bar:nth-of-type(' + (i - 1) + ')').addClass('active');

$('.progress .bar:nth-of-type(' + (i - 2) + ')').removeClass('active').addClass('done');

i++;

if (i == 0) {
$('.progress .bar').removeClass().addClass('bar');
$('.progress div.circle').removeClass().addClass('circle');
i = 1;
}
}, 1000);
});

    </script>
@endpush
@endsection