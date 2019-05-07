@extends($theme)
@section('title', $title)
@section('content')
<div class="content-wrapper">
    <div class="tab-content-bordered content-group">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="active">
                <a href="#create_coach" data-toggle="tab" aria-expanded="false">Add Coach</a>
            </li>
            <li class="disabled">
                <a aria-expanded="false">Coach Rate</a>
                {{-- {!! link_to_route('coach-rates.edit', 'Edit Coach Rate', ['coach_id' => Crypt::encryptString($coach['id']), '_url' => request()->get('_url', request()->getRequestUri())]) !!} --}}
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane has-padding active" id="create_coach">
                <div class="row">
                    {!! Form::open(array('route' => 'coaches.store','class'=>'form-horizontal','role'=>"form",'id'=>'coach_create_form', 'files' => 'true')) !!}
                    <div class="col-md-12">
                       <div class="panel panel-white">
                            <div class="panel-heading">
                                <div class="col-sm-9">
                                    <h3 class="panel-title">{{trans('comman.addcoach')}}</h3>
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
                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-body">
                                @include('coaches.form')
                            </div>
                            <div class="form-group">
                                <div class="col-sm-6 text-right">
                                    {!! Form::submit(trans('comman.save'), ['name' => 'save','class' => 'btn btn-primary']) !!}
                                    {{-- @if(!Request::get("download",false))
                                        {!! Form::submit(trans('comman.save_exit'), ['name' => 'save_exit','class' => 'btn btn-primary']) !!}
                                    @endif --}}
                                    {!! link_to(URL::full(), trans('comman.cancel'),array('class' => 'btn btn-warning cancel')) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Popup File --}}
@include('coaches.popup')
@endsection


