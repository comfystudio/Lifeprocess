@extends($theme)
@section('title', $title)
@section('content')

<div class="row">
    {!! Form::open(array('route' => ['coach-notes.store', 'client_id' => Crypt::encryptString($client_id)],'class'=>'form-horizontal','role'=>"form")) !!}
    {{Form::hidden('_url',request()->get('_url', request()->getRequestUri()))}}
    <div class="col-md-12">
       <div class="panel panel-white">
            <div class="panel-heading">
                <div class="col-sm-9"><h5 class="panel-title"> {{ $module_title }} </h5></div>
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
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                @include('coach_notes.form')
            </div>
            <div class="form-group">
                <div class="col-sm-5 text-right">
                    {!! Form::submit("Save", ['name' => 'save','class' => 'btn btn-primary']) !!}
                    @if(!Request::get("download",false))
                        {!! Form::submit("Save & Exit", ['name' => 'save_exit','class' => 'btn btn-primary']) !!}
                    @endif
                    {!! link_to(URL::full(), "Cancel",array('class' => 'btn btn-warning cancel')) !!}
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection