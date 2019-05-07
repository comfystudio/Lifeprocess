@extends($theme)
@section('title', $title)
@section('content')
<div class="row">
    {!! Form::open(array('route' => 'clients.store','class'=>'form-horizontal','role'=>"form",'id'=>'clients_create_form')) !!}
    <div class="col-md-12">
       <div class="panel panel-white">
            <div class="panel-heading">
                <div class="col-sm-9">
                    <h3 class="panel-title">{{trans('comman.addclient')}}</h3>
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
                @include('clients.form')
            </div>
            <div class="form-group">
                <div class="col-sm-6 text-right">
                    {!! Form::submit(trans('comman.save'), ['name' => 'save','class' => 'btn btn-primary']) !!}
                    @if(request()->get('_url'))
                        {!! Html::decode(link_to(request()->get('_url'), trans('comman.save_exit'),array('class' => 'btn btn-primary'))) !!}
                    @else
                        @if(!Request::get("download",false))
                        {!! Form::submit(trans('comman.save_exit'), ['name' => 'save_exit','class' => 'btn btn-primary']) !!}
                        @endif
                    @endif
                    {!! link_to(URL::full(), trans('comman.cancel'),array('class' => 'btn btn-warning cancel')) !!}
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>

{{-- Popup File --}}
@include('clients.popup')

@endsection


