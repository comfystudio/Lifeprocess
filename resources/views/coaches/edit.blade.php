@extends($theme)
@section('title', $title)
@section('content')
<div class="content-wrapper">
    <div class="tab-content-bordered content-group">
        <ul class="nav nav-tabs nav-tabs-highlight">
            <li class="active">
                <a href="#edit_coach" data-toggle="tab" aria-expanded="false">Edit Coach</a>
            </li>
            <li class="">
                {{-- <a href="#coach_rate" data-toggle="tab" aria-expanded="false">Coach Rate</a> --}}
                {!! link_to_route('coach-rates.edit', 'Edit Coach Rate', ['coach_id' => Crypt::encryptString($coach['id']), '_url' => request()->get('_url', request()->getRequestUri())]) !!}
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane has-padding active" id="edit_coach">
                <div class="row">
                    <div class="col-md-12">
                        {!! Form::model($coach, ['method' => 'PATCH','route' => ['coaches.update', Crypt::encryptString($coach['id'])],'class' => 'form-horizontal', 'files' => 'true']) !!}
                        <div class="panel panel-white">
                            <div class="panel-heading">
                                <h3 class="panel-title">{{trans('comman.edit')}} {{ trans('comman.coach') }}</h3>
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
                                @include('coaches.form')
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
        </div>
    </div>
</div>
{{-- Popup File --}}
@include('coaches.popup')
@endsection
