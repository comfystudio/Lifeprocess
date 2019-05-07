@extends($theme)
@section('title', $title)
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            {!! Form::model($client, ['method' => 'PATCH','route' => ['clients.update', Crypt::encryptString($client['id'])],'class' => 'form-horizontal', 'id' => 'clients_create_form']) !!}
             <div class="panel panel-white">
                <div class="panel-heading">
                    <h5 class="panel-title">{{trans('comman.edit')}} {{ trans('comman.client') }}</h5>
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
                    @include('clients.form')
                </div>
                <div class="form-group">
                    <div class="col-sm-6 text-right">
                        {!! Form::submit(trans('comman.save'), ['name' => 'save','class' => 'btn btn-primary']) !!}
                        @if(request()->get('_url'))
                            {!! Html::decode(link_to(request()->get('_url'), trans('comman.save_exit'),array('class' => 'btn btn-primary'))) !!}
                        @else
                            {!! Form::submit(trans('comman.save_exit'), ['name' => 'save_exit','class' => 'btn btn-primary']) !!}
                        @endif
                            {!! Html::decode(link_to(URL::full(), trans('comman.cancel'),array('class' => 'btn btn-warning'))) !!}
                    </div>
                </div>
             </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
{{-- Popup File --}}
@include('clients.popup')
@endsection
