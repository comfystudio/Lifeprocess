@extends($theme)

@section('title', $title)

@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Resource Library</h3>
        </div>
        <div class="panel-body">
            {{ Form::open(array('route' => 'resource_library.index','method'=>'GET','class'=>'form-filter','role'=>"form")) }}
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('name', 'Name'. ':', ['class' => 'control-label'])) !!}
                        {!! Form::text('name', Request::get('name',null), ['class' => 'form-control ','placeholder'=> 'Resource Library Name' ]) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('status', trans("comman.status"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::select('status', $program_status, Request::get('status',null), ['class' => 'form-control single-select' ]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label><br>
                        {!! Form::submit(trans('comman.search'), ['name' => 'search', 'class' => 'btn btn-primary btn-xs']) !!}
                        {!! link_to_route('resource_library.index', trans('comman.cancel'), [], array('class' => 'btn btn-default btn-xs cancel')) !!}
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading bg-white">
           {{--  <h5 class="panel-title">{{ trans('comman.programs') }}</h5>
            <div class="heading-elements">
                @if(!empty($module_action))
                    <div class="text-right">
                        @foreach($module_action as $key=>$action)
                        {!! Html::decode(Html::link($action['url'],$action['title'],$action['attributes'])) !!}
                        @endforeach
                    </div>
                @endif
            </div> --}}
             <div class="row">
                <div class="col-md-6">
                    <h5 class="panel-title">Resource Library</h5>
                </div>
                <div class="heading-elements col-md-4">
                    @if(!empty($module_action))
                        <div class="pull-right">
                            @foreach($module_action as $key=>$action)
                            {!! Html::decode(Html::link($action['url'],$action['title'],$action['attributes'])) !!}
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-hover no-footer">
                <thead>
                    <tr>
                        <th style="width: 50px;">{{ trans('comman.no') }}</th>
                        <th>{{ trans('comman.name') }}</th>
                        <th>{{ trans('comman.status') }}</th>
                        <th>{{ trans('comman.date') }}</th>
                        <th style="width: 180px;" class="text-center">{{ trans('comman.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($programs) && count($programs) > 0)
                        @foreach($programs as $program)
                            <tr>
                                <td>
                                {{ ($programs->currentPage()-1) * $programs->PerPage() + $counter + 1 }}
                                @php
                                $counter++;
                                @endphp
                                </td>
                                <td> {{ $program->name }} </td>
                                <td> {{ trans('comman.'.$program->status) }} </td>
                                <td> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$program->created_at)->format('m/d/Y')}} </td>
                                <td class="text-center">

                                    @if(Auth::user()->hasAnyAccess(['programs.update','programs.delete']))
                                        @if(Auth::user()->hasAccess('programs.update'))
                                            {!! Html::decode(link_to_route('resource_library.edit', '<i class="fa fa-pencil"></i>', array(Crypt::encryptString($program->id), '_url'=> request()->getRequestUri()), ['class' => 'btn btn-xs btn-primary', 'title' => trans('comman.edit') ])) !!}
                                        @endif
                                        @if(Auth::user()->hasAccess('programs.delete'))
                                            {!! Html::decode(link_to_route('resource_library.destroy', '<i class="fa fa-trash"></i>', array(Crypt::encryptString($program->id)), ['data-method' => 'delete', 'data-modal-text' => ' program?', 'title' => trans('comman.delete'), 'class' => 'btn btn-xs btn-danger'])) !!}
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="text-center">{{ trans('comman.no_data_found') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="pagination-wraper">
                @if(isset($programs) && count($programs) > 0)
                    {{ $programs->appends(request()->except('page'))->links('vendor.pagination.bootstrap-custom')}}
                @endif
            </div>
        </div>
    </div>
</div>
@endsection