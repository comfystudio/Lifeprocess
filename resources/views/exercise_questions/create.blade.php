@extends($theme)
@section('title', $title)
@section('content')
<div class="row">
    {!! Form::open(array('route' => ['exercise_questions.store', 'program_id' => Crypt::encryptString($program_id), 'module_id' => Crypt::encryptString($module_id), 'exercise_id' => Crypt::encryptString($exercise_id)],'class'=>'form-horizontal','role'=>"form",'id'=>'exercise_questions_create_form', 'files' => true)) !!}

        {{Form::hidden('_url',request()->get('_url', request()->getRequestUri()))}}

    <div class="col-md-12">
       <div class="panel panel-white">
            <div class="panel-heading">
                <div class="col-sm-9"><h5 class="panel-title">{{trans('comman.addexercise_question')}}</h5>
                </div>
                  @if(Request::get("download",false))
                    <div class="pull-right">
                        <button type="button" class="close" ng-click="cancel($event)" aria-label="Close">
                        <span aria-hidden="true" ><i class="icon-cross2"></i></span>
                        </button>
                    </div>
                @else
                    <div class="heading-elements">
                        @if(!empty($module_action))
                        <div class="text-right">
                            @foreach($module_action as $key=>$action)
                            {!! Html::decode(Html::link($action['url'],$action['title'],$action['attributes'])) !!}
                            @endforeach
                        </div>
                        @endif
                    </div>
                @endif

                @if(Request::get("download",false))
                    <div class="pull-right">
                        <button type="button" class="close" ng-click="cancel($event)" aria-label="Close">
                        <span aria-hidden="true" ><i class="icon-cross2"></i></span>
                        </button>
                    </div>
                @endif
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                @include('exercise_questions.form')
            </div>
            <div class="form-group">
                <div class="col-sm-6 text-right">
                    {!! Form::submit(trans('comman.save'), ['name' => 'save','class' => 'btn btn-primary']) !!}
                    @if(!Request::get("download",false))
                        {!! Form::submit(trans('comman.save_exit'), ['name' => 'save_exit','class' => 'btn btn-primary']) !!}
                    @endif
                    {!! link_to(URL::full(), trans('comman.cancel'),array('class' => 'btn btn-warning cancel')) !!}
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>

{{-- Popup File --}}
{{-- @include('exercise_questions.popup') --}}

@endsection


