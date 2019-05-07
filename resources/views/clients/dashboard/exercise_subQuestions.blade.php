<style>
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
<br>
{{-- {{ dump($sub_questions) }} --}}
<div class="panel panel-default border-top-info">
    <div class="panel-body">
        @foreach($sub_questions AS $sub_question)
            <div class="row">
                <div class="col-md-12" style="padding-left: 15px;">
                    <strong>
                       {{ $parent_question_no }}.{{ $sub_question->question_no }}. {{ $sub_question->question_title }}
                    </strong>
                    @if($sub_question->answer_format == 'plain_text')
                        <div class="form-group {{ $errors->has('answer.'.$sub_question->id) ? 'has-error' : ''}}" style="margin-top:6px;">
                            <div class="col-sm-7" style="padding-left: 20px;">
                                {!! Form::textarea('answer['.$sub_question->id.']', null, ['class' => 'form-control answer', 'rows' => 5]) !!}
                                {!! $errors->first('answer.'.$sub_question->id, '<p class="help-block">:message</p>') !!}
                                {!! Form::hidden('min_value['. $sub_question->id .']', $sub_question->min_value) !!}
                                {!! Form::hidden('max_value['. $sub_question->id .']', $sub_question->max_value) !!}
                            </div>
                        </div>
                    @elseif($sub_question->answer_format == 'boolean_yes_no')
                        <div class="form-group {{ $errors->has('answer.'.$sub_question->id) ? 'has-error' : ''}}" style="padding-left: 20px;">
                            <div class="radio">
                                <label>
                                    {!! Form::radio('answer['.$sub_question->id.']','yes',true,['class'=>'answer answer['.$question->id.']','id'=>'answer['.$question->id.']']) !!} Yes
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    {!! Form::radio('answer['.$sub_question->id.']','no',null,['class'=>'answer answer['.$question->id.']','id'=>'answer['.$sub_question->id.']']) !!} No
                                </label>
                            </div>
                            {{--   <input type=hidden name="answer[{{$sub_question->id}}]" value="" id="answer[{{$sub_question->id}}]"> --}}
                          {{--   <div class="radio">
                            <label>
                                {!! Form::radio('answer['.$sub_question->id.']','not answered',true,['class'=>'answer']) !!} Not Answered
                            </label>
                            </div> --}}
                            {!! $errors->first('answer.'.$sub_question->id, '<p class="help-block">:message</p>') !!}
                        </div>
                    @elseif($sub_question->answer_format == 'statement')

                    <div class="form-group {{ $errors->has('answer.'.$question->id) ? 'has-error' : ''}}" style="padding-left: 20px;">

                        {!! $errors->first('answer.'.$question->id, '<p class="help-block">:message</p>') !!}
                    </div>

                    @elseif($sub_question->answer_format=='slider')

                        <div class="form-group {{ $errors->has('answer.'.$sub_question->id) ? 'has-error' : ''}}" style="">


                        <input type="range" min="{{$sub_question->min_range_value}}" max="{{$sub_question->max_range_value}}" value="{{$sub_question->min_range_value}}" class="slider form-group" id="myRange" name="answer[{{$sub_question->id}}]">

                        </div>

                    @else
                      <div class="form-group {{ $errors->has('answer.'.$sub_question->id) ? 'has-error' : ''}}" style="margin-left: 10px;">

                        @foreach($sub_question->module_exercise_question_options AS $option)
                            <div class="">
                                <label>
                                    {!! Form::radio('answer['.$sub_question->id.']', $option->option_value,null,['class'=>'answer answer['.$sub_question->id.']','id'=>'answer['.$sub_question->id.']']) !!} {{ $option->option_value }}
                                </label>
                            </div>
                               {{--    <input type=hidden name="answer[{{$sub_question->id}}]" value="" id="answer[{{$sub_question->id}}]"> --}}
                        @endforeach
                            <div class="">
                            <label>
                               {!! Form::radio('answer['.$sub_question->id.']','not answered',true,['class'=>'answer']) !!} Not Answered
                            </label>
                            </div>
                            {!! $errors->first('answer.'.$sub_question->id, '<p class="help-block">:message</p>') !!}
                          </div>
                    @endif

                </div>
            </div>
            {{-- {!! Form::hidden('question_id[]', $sub_question->id) !!} --}}
            {!! Form::hidden('question_answer_format['.$sub_question->id.']', $sub_question->answer_format) !!}

            @if(isset($sub_question->sub_questions) && count($sub_question->sub_questions))
                @include('clients.dashboard.exercise_subQuestions',['sub_questions' => $sub_question->sub_questions, 'parent_question_no' => $parent_question_no . '.' . $sub_question->question_no, 'level' => $level+1])
            @endif
        @endforeach
    </div>
</div>