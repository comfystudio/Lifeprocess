<div class="panel panel-default border-top-info" style="margin:0 10px 0 30px;">
    <div class="panel-body">

        @foreach($sub_questions AS $sub_question)
            <br>
            <div class="row">
                <div class="col-md-12" style="padding-left: 15px;">
                    <strong>
                        {{ $parent_question_no }}.{{ $sub_question->question_no }}. &nbsp; {{ $sub_question->question_title }}
                    </strong>
                        @if($sub_question->answer_format!='statement')

                    <div class="panel panel-flat border-left-info" style="margin: 10px;">
                        <div class="panel-body" style="padding: 10px;">
                            <strong>Client's Answer:</strong> <br>
                            {{ $sub_question->question_answer->answer }}
                        </div>
                    </div>
                    <div class="panel panel-flat border-left-primary" style="margin: 10px;">
                        <div class="panel-body" style="padding: 10px;">
                            <strong>Your Response: </strong> <br>
                            <div class="form-group {{ $errors->has('response.'.$sub_question->question_answer->id) ? 'has-error' : ''}}" style="margin:6px 0;">
                                <div class="col-sm-7">
                                    {!! Form::textarea('response['.$sub_question->question_answer->id.']', null ,['class' =>'form-control', 'rows' => 5]) !!}
                                    {!! $errors->first('response.'.$sub_question->question_answer->id, '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @if(isset($sub_question->sub_questions) && count($sub_question->sub_questions) > 0)
                @include('coaches.dashboard.coach-respond-subQuestion',['sub_questions' => $sub_question->sub_questions, 'parent_question_no' => $parent_question_no . '.' . $sub_question->question_no, 'level' => $level+1])
            @endif
        @endforeach
    </div>
</div>