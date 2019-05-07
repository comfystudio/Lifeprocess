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
<div class="tab-content">
    <div class="tab-pane fade in active" id="coach">
      <div class="tab-title">

      @foreach ($client_module_exercises AS $exercise_questions)
       <h1 class="no-margin"> Module : {{$exercise_questions->module->module_no}}   {{$exercise_questions->module->module_title}}

        <strong>  => Exercise: {{ $exercise_questions->module->module_no . '.' . $exercise_questions->module_exercise->exercise_no . ' - ' . $exercise_questions->module_exercise->title }} </strong>
        @endforeach
      </h1>

      </div>
       <div class="panel col-md-12" style="margin-top: 0px;">
            <div class="panel-body col-md-12">
    @php
        $total_question = 0;
    @endphp
    @foreach ($client_module_exercises AS $exercise_questions)

            {{-- <div class="col-md-12" style="background-color: #0060AA;">
                <img src="{{ asset("themes/limitless/images/client-view/logo.png") }}" alt="Life Process Program" >
            </div> --}}
{{--
            <div class="panel-body">

            </div>
            <hr> --}}
            <div class="panel-body">
                @if(isset($exercise_questions->user_module_exercise_questions) && count($exercise_questions->user_module_exercise_questions) > 0)
                    @foreach ($exercise_questions->user_module_exercise_questions AS $question)
                        {{-- {{ dump($question) }} --}}
                        <div class="form-group col-md-12">
                            <strong>
                                {{ $question->question_no }}. &nbsp;{{ $question->question_title }}
                            </strong>
                            @if($question->answer_format!='statement')
                            <div class="" style="margin: 10px;">
                                <div class="panel-body" style="padding: 10px; color: #2a4fc7;">
                                    <strong>Your Answer:</strong> <br>
                                    @if(!empty($question->question_answer))
                                    {{ $question->question_answer->answer }}
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if(isset($question->sub_questions) && count($question->sub_questions) > 0)
                                @include('clients.dashboard.download_excercise_subQuestions',['sub_questions' => $question->sub_questions, 'parent_question_no' => $question->question_no, 'level' => '2'])
                            @endif
                        </div>
                        <div class="clearfix"></div>
                    @endforeach
                @endif
            </div>
        </div>
    @endforeach
</div></div></div>
</div>
@endsection