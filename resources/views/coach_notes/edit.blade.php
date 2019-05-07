@extends($theme)
@section('title', $title)
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            {!! Form::model($coach_note, ['method' => 'PATCH','route' => ['coach-notes.update', Crypt::encryptString($coach_note->id), 'client_id' => Crypt::encryptString($client_id)],'class' => 'form-horizontal']) !!}
            {{Form::hidden('_edit_url',request()->getRequestUri())}}
            {{Form::hidden('_url',request()->get('_url'))}}
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h5 class="panel-title">{{trans('comman.edit')}} {{ trans('comman.message') }}</h5>
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
                    @include('coach_notes.form')
                </div>
                <div class="form-group">
                    <div class="col-sm-5 text-right">
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
@endsection