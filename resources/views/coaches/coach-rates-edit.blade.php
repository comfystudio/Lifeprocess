@extends($theme)
@section('title', $title)
@section('content')
<div class="row">
    <div class="tab-content-bordered content-group">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="">
                {{-- <a href="#create_coach" data-toggle="tab" aria-expanded="false">Coach</a> --}}
                {!! link_to_route('coaches.edit', 'Edit Coach ', ['id' => Crypt::encryptString($coach_id)]) !!}
            </li>
            <li class="active">
                <a href="#" data-toggle="tab" aria-expanded="false">Edit Coach Rate </a>
            </li>` 
        </ul>
        <div class="tab-content">
            <div class="tab-pane has-padding active">
                <div class="row">
                {{-- {!! Form::open(array('route' => ['coach-rates.update', 'coach_id' => $coach_id],'class'=>'form-horizontal','role'=>"form", 'files' => 'true')) !!} --}}
                {{ Form::model($module_rates, array('method' => 'PATCH', 'route' => array('coach-rates.update', 'coach_id' => Crypt::encryptString($coach_id)),'class'=>'form-horizontal','role'=>"form")) }}
                    {{Form::hidden('_edit_url',request()->getRequestUri())}}
                    {{Form::hidden('_url',request()->get('_url'))}}
                <div class="col-md-12">
                   <div class="panel panel-white">
                        <div class="panel-heading">
                            <div class="col-sm-12">
                                <h3 class="panel-title">Edit Coach Rate</h3>
                                <div class="heading-elements">
                                    @if(!empty($module_action))
                                        <div class="text-right">
                                            @foreach($module_action as $key=>$action)
                                            {!! Html::decode(Html::link($action['url'],$action['title'],$action['attributes'])) !!}
                                            @endforeach
                                        </div>            
                                    @endif
                                </div>
                            </div>
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
                            @include('coaches.coach-rates-form')
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
            </div>
        </div>
    </div>
</div>
@endsection


