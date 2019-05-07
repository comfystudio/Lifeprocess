    <div class="panel-body">

        @foreach($sub_questions AS $sub_question)
            <div class="row">
                <div class="col-md-12" style="padding-left: 15px;">
                    <strong>
                        {{ $parent_question_no }}.{{ $sub_question->question_no }}. &nbsp; {{ $sub_question->question_title }}
                    </strong>
                    <div class="" style="margin: 10px;">
                        <div class="panel-body" style="padding: 10px;color: #2a4fc7;">
                            <strong>Your Answer:</strong> <br>
                            @if($sub_question->question_answer)
                            {{ $sub_question->question_answer->answer }}
                            @endif
                        </div>
                    </div>

                </div>
            </div>
            @if(isset($sub_question->sub_questions) && count($sub_question->sub_questions) > 0)
                @include('clients.dashboard.download_excercise_subQuestions',['sub_questions' => $sub_question->sub_questions, 'parent_question_no' => $parent_question_no . '.' . $sub_question->question_no, 'level' => $level+1])
            @endif
        @endforeach
    </div>
