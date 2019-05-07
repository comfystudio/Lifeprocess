@extends($theme)
@section('title', $title)
@section('content')
<style type="text/css">
    form {
        margin-bottom: 0;
    }
</style>
<div class="row">
    <div class="panel panel-default border-top-warning">
        <div class="panel-heading">
            <h6 > {{ link_to_route('client.detail', $user_name, ['client_id' => Crypt::encryptString($client_id)])  }} </h6>
            <h6 class="panel-title"> <strong> {{ $module_title }} </strong></h6>
            <div class="row" style="padding-top: 5px;">
                <div class="col-sm-12">
                    {{ $module_description }}
                </div>
            </div>
        </div>
        {{-- {{ dump($userAnswers) }} --}}
        {!! Form::model($coachResponse, array('route' => ['coach.respond.store', 'module_id' => Crypt::encryptString($module_id), 'client_id' => Crypt::encryptString($client_id),'module_exercise_id'=>Crypt::encryptString($module_exercise_id)],'class'=>'form-horizontal','role' => "form",'id' => 'exercise_question_answer_response')) !!}
        <div class="panel-body">
            @php
                $total_question = 0;
            @endphp
            @foreach ($client_module_exercises->items() AS $exercise_questions)

                @if(isset($exercise_questions->user_module_exercise_questions) && count($exercise_questions->user_module_exercise_questions) > 0)
                    @foreach ($exercise_questions->user_module_exercise_questions AS $question)
                    @if(!empty($question->question_answer))
                        <div class="form-group col-md-12">
                            <strong>
                                {{ $question->question_no }}. &nbsp;{{ $question->question_title }}
                            </strong>
                            @if($question->answer_format!='statement')
                            <div class="panel panel-flat border-left-info" style="margin: 10px;">
                                <div class="panel-body" style="padding: 10px;">
                                    <strong>Client's Answer:</strong> <br>
                                    @if(!empty($question->question_answer))
                                    {{ $question->question_answer->answer }}
                                    @endif
                                </div>
                            </div>
                            <div class="panel panel-flat border-left-primary" style="margin: 10px;">
                                <div class="panel-body" style="padding: 10px;">
                                    <strong>Your Response: </strong> <br>
                                    <div class="form-group {{ $errors->has('response.'.$question->question_answer->id) ? 'has-error' : ''}}" style="margin:6px 0;">
                                        <div class="col-sm-7">
                                            {!! Form::textarea('response['.$question->question_answer->id.']', null ,['class' =>'form-control', 'rows' => 5]) !!}
                                            {!! $errors->first('response.'.$question->question_answer->id, '<p class="help-block">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if(isset($question->sub_questions) && count($question->sub_questions) > 0)
                                @include('coaches.dashboard.coach-respond-subQuestion',['sub_questions' => $question->sub_questions, 'parent_question_no' => $question->question_no, 'level' => '2'])
                            @endif
                        </div>
                    @endif
                        <div class="clearfix"></div>
                    @endforeach
                @endif
            @endforeach
        </div>
        <div class="panel-footer" style="padding: 10px;">
            <div class="col-md-4">
                @php
                    $client_module_exercises->appends(['receive_p' => request()->get('receive_p')])->render(); // to render the extran parameter passed to url except the page defaultly passed to pagination url...
                    if($client_module_exercises->previousPageUrl()) {
                        echo link_to($client_module_exercises->previousPageUrl(), 'Previous Exercise', ['class' => 'btn btn-primary']);
                    }
                @endphp
            </div>
            <div class="col-md-4 text-center">
                {!! Form::submit('Save & return to home', ['name' => 'save_exit','class' => 'btn btn-info']) !!}
            </div>
            <div class="col-md-4 text-right">
                @php
                    if ($total_exercise <= $client_module_exercises->currentPage()) {
                        // You won't be able to update your answers once module is submitted. Are you sure you want to submit the module for review?
                        echo Form::button('Submit review', ['name' => 'btn_submit_review','class' => 'btn btn-success', 'onClick' => 'confirm_before_submit()']);
                        echo Form::hidden('submit_review', 'Submit review');
                        if(request()->get('receive_p', false) && request()->get('receive_p') === 'false') {
                            echo Form::hidden('receive_p', request()->get('receive_p'));
                        }
                        // echo Form::submit('Submit review', ['name' => 'submit_review','class' => 'btn btn-success', 'onClick' => 'return confirm_before_submit()']);
                    } else if($client_module_exercises->nextPageUrl()) {
                        echo link_to($client_module_exercises->nextPageUrl(), 'Next Exercise', ['class' => 'btn btn-primary submit_form']);
                    }
                @endphp
            </div>
        </div>
        {!! Form::hidden('redirect_to', '', ['id' => 'redirect_to']) !!}
        {!! Form::close() !!}
    </div>
</div>
@push('scripts')
    <script type="text/javascript">
        function confirm_before_submit() {
            swal({
                title: '',
                text: "You won\'t be able to update your review once submitted. Are you sure you want to submit the review?",
                showCancelButton: true,
                confirmButtonClass: 'btn btn-primary',
                cancelButtonClass: 'btn btn-default',
                confirmButtonText: "Confirm",
                allowOutsideClick: false
            }).then(function () {
                jQuery('#exercise_question_answer_response').submit();
                return true;
            }, function(dismiss){
                return false;
            });
        }
        jQuery(".submit_form").on('click', function(e){
            // alert();
            e.preventDefault();
            var clicked_link_href = jQuery(this).attr('href')
            jQuery("#redirect_to").val(clicked_link_href);
            jQuery("#exercise_question_answer_response").submit();
        });
    </script>
@endpush
@endsection