@foreach ($sub_questions as $sub_question)
    <div class="list-group-item">
        <div class="row">
            <div class="col-md-9"> <i class="fa fa-question-circle"></i> <strong>  &nbsp;&nbsp;&nbsp; {{ str_repeat('- ', $level) }} {{ $parent_question_no }}.{{ $sub_question['question_no'] }} &nbsp; {{ $sub_question['question_title'] }} </strong></div>
            <div class="col-md-3 text-right">
                {!! Html::decode(link_to_route('exercise_questions.edit', '<i class="fa fa-pencil"></i> ' , array('program_id' => Crypt::encryptString($program_id), 'module_id' => Crypt::encryptString($module_id), 'exercise_id' => Crypt::encryptString($exercise['module_exercises'][0]['id']), Crypt::encryptString($sub_question['id']), '_url'=> request()->getRequestUri()), ['class' => 'btn btn-xs btn-primary','title' => 'Edit'])) !!}

                {!! Html::decode(link_to_route('exercise_questions.destroy', '<i class="fa fa-trash"></i> ' , array('program_id' => Crypt::encryptString($program_id), 'module_id' => Crypt::encryptString($module_id), 'exercise_id' => Crypt::encryptString($exercise['module_exercises'][0]['id']), Crypt::encryptString($sub_question['id'])), ['data-method' => 'delete', 'data-modal-text' => ' ' . trans('comman.exercise_question') . ' ?','title' => 'Delete', 'class' => 'btn btn-xs btn-danger'])) !!}
            </div>
        </div>
    </div>
    @if(isset($sub_question['sub_questions']) && count($sub_question['sub_questions']))
        @include('module_exercise.nestedQuestionsList',['sub_questions' => $sub_question['sub_questions'], 'parent_question_no' => $parent_question_no . '.' . $sub_question['question_no'], 'level' => $level+1 ])
    @endif
@endforeach