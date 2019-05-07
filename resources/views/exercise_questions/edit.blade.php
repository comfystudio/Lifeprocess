@extends($theme)
@section('title', $title)
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            {!! Form::model($question, ['method' => 'PATCH','route' => ['exercise_questions.update', 'program_id' => Crypt::encryptString($program_id) ,'module_id' => Crypt::encryptString($module_id), 'exercise_id' => Crypt::encryptString($exercise_id),  Crypt::encryptString($question->id)],'class' => 'form-horizontal', 'files' => true]) !!}
            {{Form::hidden('_edit_url',request()->getRequestUri())}}
            {{Form::hidden('_url',request()->get('_url'))}}
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h5 class="panel-title">{{trans('comman.edit')}} {{ trans('comman.exercise_question') }}</h5>
                </div>
                <div class="panel-body">
                    @include('exercise_questions.form')
                </div>
                <div class="form-group">
                    <div class="col-sm-6 text-right">
                        {!! Form::submit(trans('comman.save'), ['name' => 'save','class' => 'btn btn-primary']) !!}
                        {!! Form::submit(trans('comman.save_exit'), ['name' => 'save_exit','class' => 'btn btn-primary']) !!}
                        {!! Html::decode(link_to(URL::full(), trans('comman.cancel'),array('class' => 'btn btn-warning'))) !!}
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
{{-- Popup File --}}
{{-- @include('exercise_questions.popup') --}}
@endsection
