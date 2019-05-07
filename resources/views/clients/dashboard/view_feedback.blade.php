@extends($theme)
@section('title', $title)
@section('content')
<style type="text/css">
    form {
        margin-bottom: 0;
    }
</style>
<div class="">

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-sm-9">
                    <h6 class="panel-title"> <strong> {{ $module_title }} </strong>
                    </h6>
                </div>
                @if(!request()->get('user_id', false))
                    <div class="col-sm-3">
                        {!! Html::decode(link_to_route('download.feedback', '<i class="icon-file-pdf"></i> Download Feedback', ['module_id' => Crypt::encryptString($module_id),'excercise_id' => Crypt::encryptString($excercise_id)], ['class' => 'btn btn-warning pull-right'])) !!}

                    </div>
                @endif
            </div>
            <div class="row" style="padding-top: 5px;">
                <div class="col-sm-12">
                    {{ $module_description }}
                </div>
            </div>
        </div>
        {{-- {{ dump($userAnswers) }} --}}{{--
        {!! Form::model($coachResponse, array('route' => ['coach.respond.store', 'module_id' => $module_id, 'client_id' => $client_id],'class'=>'form-horizontal','role' => "form",'id' => 'exercise_question_answer_response')) !!} --}}
        <div class="panel-body">
            @php
                $total_question = 0;
            @endphp
            @foreach ($client_module_exercises->items() AS $exercise_questions)

                @if(isset($exercise_questions->user_module_exercise_questions) && count($exercise_questions->user_module_exercise_questions) > 0)
                    @foreach ($exercise_questions->user_module_exercise_questions AS $question)
                        {{-- {{ dump($question) }} --}}
                        @if($question->question_answer)
                            <div class="form-group col-md-12">
                                <b>
                                    {{ $question->question_no }}. &nbsp;{{ $question->question_title }}
                                </b>
                                @if($question->answer_format!='statement')
                                <div class="panel panel-flat border-left-info" style="margin: 10px; ">
                                    <div class="panel-body" style="padding: 10px;">
                                        <b>Your Answer:</b> <br>
                                        {{ $question->question_answer->answer }}
                                    </div>
                                </div>
                                @endif

                                <div class="panel panel-flat border-left-primary" style="margin: 10px;">
                                    <div class="panel-body" style="padding: 10px; color: #0096fd;">
                                        <b>Coach Response: </b> <br>
                                        {{ $question->question_answer->coach_respond }}
                                    </div>
                                </div>
                                @if(isset($question->sub_questions) && count($question->sub_questions) > 0)
                                    @include('clients.dashboard.view_feedback_subQuestions',['sub_questions' => $question->sub_questions, 'parent_question_no' => $question->question_no, 'level' => '2'])
                                @endif
                            </div>
                        @endif
                        <div class="clearfix"></div>
                    @endforeach
                @endif
            @endforeach
        </div>
        <div class="col-md-12 panel-footer" style="padding: 10px;">
            <div class="col-md-4">
                @php
                    if($client_module_exercises->previousPageUrl()) {
                        if(request()->get('user_id', false)) {
                            echo link_to($client_module_exercises->appends(Request::except('page'))->previousPageUrl(), 'Previous Exercise', ['class' => 'btn btn-primary']);
                        } else {
                            echo link_to($client_module_exercises->previousPageUrl(), 'Previous Exercise', ['class' => 'btn btn-primary']);
                        }
                    }
                @endphp
            </div>
            <div class="col-md-4 text-center">
                @if(request()->get('user_id', false))
                    {!! link_to_route('coach.unlock-modules.index', 'Return to home', ['coach_id' => request()->get('coach_id')], ['class' => 'btn btn-info']) !!}
                @else
                    {!! link_to_route('client.dashboard', 'Return to home', [], ['class' => 'btn btn-info']) !!}
                @endif
            </div>
            <div class="col-md-4 text-right">
                @php
                    if ($total_exercise <= $client_module_exercises->currentPage()) {
                    } else if($client_module_exercises->nextPageUrl()) {
                        if(request()->get('user_id', false)) {
                            echo link_to($client_module_exercises->appends(Request::except('page'))->nextPageUrl(), 'Next Exercise', ['class' => 'btn btn-primary']);
                        } else {
                            echo link_to($client_module_exercises->nextPageUrl(), 'Next Exercise', ['class' => 'btn btn-primary']);
                        }
                    }
                @endphp
            </div>
            </div>
        </div>
        {{-- {!! Form::hidden('redirect_to', '', ['id' => 'redirect_to']) !!}
        {!! Form::close() !!} --}}
    </div>
</div>
@endsection