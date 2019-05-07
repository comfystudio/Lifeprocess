@extends($theme)
@section('title', 'Download title')
@section('content')
<style type="text/css">
    form {
        margin-bottom: 0;
    }
    body{
        background-color: #FFF;
    }
</style>
<div class="row">
    @php
        $total_question = 0;
    @endphp

     @foreach($client_module AS $exercise_questions)
        <div class="panel panel-default border-top-warning">
            <div class="panel-heading">
                <h6 class="panel-title"> <strong> {{ $exercise_questions->module->module_no . '.' . $exercise_questions->module_exercise->exercise_no . ' - ' . $exercise_questions->module_exercise->title }} </strong></h6>
            </div>
            <div class="panel-body">
           {{--  {{dd($exercise_questions->user_module_exercise_questions)}} --}}
                @if(isset($exercise_questions->user_module_exercise_questions) && count($exercise_questions->user_module_exercise_questions) > 0)
                    @foreach ($exercise_questions->user_module_exercise_questions AS $question)
 <div class="form-group col-md-12">
                            <strong>
                                {{ $question->question_no }}. &nbsp;{{ $question->question_title }}
                            </strong>

                            <div class="" style="margin: 10px;">
                                <div class="panel-body" style="padding: 10px;">
                                    <strong>Client's Answer:</strong> <br>
                                      @if(!empty($question->question_answer))
                                    {{ $question->question_answer->answer }}
                                    @endif
                                </div>
                            </div>
                            <div class="" style="margin: 10px; color: #037AD5;">
                                <div class="panel-body" style="padding: 10px;">
                                    <strong>Your Response: </strong> <br>
                                      @if(!empty($question->question_answer))
                                    {{ $question->question_answer->coach_respond }}
                                     @endif
                                </div>
                            </div>
                            @if(isset($question->sub_questions) && count($question->sub_questions) > 0)
                                @include('coaches.dashboard.client_detail.download_feedback_subQuestions',['sub_questions' => $question->sub_questions, 'parent_question_no' => $question->question_no, 'level' => '2'])
                            @endif
                        </div>

                        <div class="clearfix"></div>
                    @endforeach
                @endif
            </div>
        </div>
    @endforeach
</div>
@endsection