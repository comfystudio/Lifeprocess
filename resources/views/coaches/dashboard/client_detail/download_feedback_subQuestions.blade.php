
    <div class="panel-body">

        @foreach($sub_questions AS $sub_question)

            <div class="row">
                <div class="panel-body" style="padding-left: 15px;">
                    <strong>
                        {{ $parent_question_no }}.{{ $sub_question->question_no }}. &nbsp; {{ $sub_question->question_title }}
                    </strong>
                    <div class="" style="margin: 10px;">
                        <div class="panel-body" style="padding: 10px;">
                            <strong>Client's Answer:</strong> <br>
                            @if(!empty($sub_question->question_answer->answer))
                              {{ $sub_question->question_answer->answer }}
                            @endif
                        </div>
                    </div>
                    <div class="" style="margin: 10px; color: #037AD5;">
                        <div class="panel-body" style="padding: 10px;">
                            <strong>Your Response: </strong> <br>
                              @if(!empty($sub_question->question_answer->coach_respond))
                            {{ $sub_question->question_answer->coach_respond }}
                              @endif
                        </div>
                    </div>
                </div>
            </div>
            @if(isset($sub_question->sub_questions) && count($sub_question->sub_questions) > 0)
                @include('coaches.dashboard.client_detail.download_feedback_subQuestions',['sub_questions' => $sub_question->sub_questions, 'parent_question_no' => $parent_question_no . '.' . $sub_question->question_no, 'level' => $level+1])
            @endif
        @endforeach

    </div>
