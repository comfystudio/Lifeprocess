
<div class="panel panel-default border-top-info" style="margin:0 10px 0 30px;">
    <div class="panel-body">
        @foreach($sub_questions AS $sub_question)
            @if ($sub_question->question_answer)
                <div class="row">
                    <div class="col-md-12" style="padding-left: 15px;">
                        <b>
                            {{ $parent_question_no }}.{{ $sub_question->question_no }}. &nbsp; {{ $sub_question->question_title }}
                        </b>
                        <div class="panel panel-flat border-left-info" style="margin: 10px;">
                            <div class="panel-body" style="padding: 10px;">
                                <b>Your Answer:</b> <br>
                                {{ $sub_question->question_answer->answer }}
                            </div>
                        </div>
                        <div class="panel panel-flat border-left-primary" style="margin: 10px;">
                            <div class="panel-body" style="padding: 10px; color: #0096fd;">
                                <b>Coach Response: </b> <br>
                                {{ $sub_question->question_answer->coach_respond }}
                            </div>
                        </div>
                    </div>
                </div>
                @if(isset($sub_question->sub_questions) && count($sub_question->sub_questions) > 0)
                    @include('clients.dashboard.view_feedback_subQuestions',['sub_questions' => $sub_question->sub_questions, 'parent_question_no' => $parent_question_no . '.' . $sub_question->question_no, 'level' => $level+1])
                @endif
            @endif
        @endforeach
    </div>
</div>