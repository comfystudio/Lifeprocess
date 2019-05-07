@extends($theme)

@section('title', $title)

@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h3 class="panel-title">{{trans('comman.edit')}} {{ trans('comman.role') }}</h3>
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
                <div class="panel-body">
                    {{ Form::model($role, array('method' => 'PATCH', 'route' => array('roles.update', Crypt::encryptString($role->id)),'class'=>'form-horizontal','role'=>"form")) }}
                    @include('roles.form')
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-4 text-center">
                                {!! Form::submit("Save", ['name'=>'save','class' => 'btn btn-primary']) !!}
                                {!! Form::submit("Save & Exit", ['name'=>'save_exit','class' => 'btn btn-primary']) !!}
                                {!! link_to(URL::full(), "Cancel",array('class' => 'btn btn-warning')) !!}
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop