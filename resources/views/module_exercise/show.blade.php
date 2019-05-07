@extends($theme)

@section('title', $title)

@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <h5 class="panel-title">
                        {{ trans('comman.exercise') . ' ' . $exercise['module_no'] . '.' . $exercise['module_exercises'][0]['exercise_no'] }} - {{ $exercise['module_exercises'][0]['title'] }}
                        {{-- {!! Html::decode(link_to_route('programs.edit', '<span class="label label-info"><i class="fa fa-pencil"></i> Edit </span>', [$program->id, "_edit_url" => request()->getRequestUri()], ['class' => 'pull-right'])) !!}  --}}
                    </h5>
                    <hr>
                    <p> {{ $exercise['module_exercises'][0]['sort_description'] }} </p>
                </div>
            </div>
        </div>
        <div class="panel-body collapsible-group">
            @if(count($exercise['module_exercises'][0]['module_exercise_questions']) > 0)
                <div class="panel-group ">
                    <div class="panel panel-default">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="list-group panel-collapse collapse in">
                                    @foreach ($exercise['module_exercises'][0]['module_exercise_questions'] as $question)
                                        <div class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-9"> <i class="fa fa-question-circle"></i> <strong> &nbsp; {{ $question['question_no'] }} &nbsp; {{ $question['question_title'] }} </strong></div>
                                                <div class="col-md-3 text-right">
                                                    {!! Html::decode(link_to_route('exercise_questions.edit', '<i class="fa fa-pencil"></i> ' , array('program_id' => Crypt::encryptString($program_id), 'module_id' => Crypt::encryptString($module_id), 'exercise_id' => Crypt::encryptString($exercise['module_exercises'][0]['id']), Crypt::encryptString($question['id']), '_url'=> request()->getRequestUri()), ['class' => 'btn btn-xs btn-primary','title' => 'Edit'])) !!}

                                                    {!! Html::decode(link_to_route('exercise_questions.destroy', '<i class="fa fa-trash"></i> ' , array('program_id' => Crypt::encryptString($program_id), 'module_id' => Crypt::encryptString($module_id), 'exercise_id' => Crypt::encryptString($exercise['module_exercises'][0]['id']), Crypt::encryptString($question['id'])), ['data-method' => 'delete', 'data-modal-text' => ' ' . trans('comman.exercise_question') . ' ?','title' => 'Delete', 'class' => 'btn btn-xs btn-danger'])) !!}
                                                </div>
                                            </div>
                                        </div>
                                        @if(isset($question['sub_questions']) && count($question['sub_questions']) > 0)
                                            @include('module_exercise.nestedQuestionsList',['sub_questions' => $question['sub_questions'], 'parent_question_no' => $question['question_no'], 'level' => '2'])
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>        
    </div>
</div>
@endsection