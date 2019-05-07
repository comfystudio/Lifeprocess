@extends($theme)
@section('title', $title)
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            {!! Form::model($agent, ['method' => 'PATCH','route' => ['agents.update', Crypt::encryptString($agent['id'])],'class' => 'form-horizontal', 'files' => 'true']) !!}
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h3 class="panel-title">{{trans('comman.edit')}} {{ trans('comman.client_manager') }}</h3>
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
                    @include('agents.form')
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
@include('agents.popup')
@endsection
