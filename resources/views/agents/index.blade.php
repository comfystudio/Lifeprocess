@extends($theme)

@section('title', $title)

@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ trans('comman.client_managers') }}</h3>
        </div>
        <div class="panel-body">
            {{ Form::open(array('route' => 'agents.index','method'=>'GET','class'=>'form-filter','role'=>"form")) }}
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('fullname', trans("comman.name"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::text('fullname', Request::get('fullname',null), ['class' => 'form-control ','placeholder'=> trans("comman.name") ]) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('date_joined', trans("comman.date_joined"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::text('date_joined', Request::get('date_joined',null), ['class' => 'form-control datepicker','placeholder'=> trans("comman.date_joined") ]) !!}
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('status', trans("comman.status"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::select('status', ['active' => trans('comman.active'), 'in_active' => trans('comman.in_active')] ,Request::get('status',null), ['class' => 'form-control single-select','placeholder'=> trans("comman.status") ]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label><br>
                        {!! Form::submit(trans('comman.search'), ['name' => 'search','class' => 'btn btn-primary btn-xs']) !!}
                        {!! link_to_route('agents.index', trans('comman.cancel'), [], array('class' => 'btn btn-default btn-xs cancel')) !!}
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading bg-white">
            {{-- <h5 class="panel-title">{{ trans('comman.client_managers') }}</h5>
            <div class="heading-elements">
                @if(!empty($module_action))
                    <div class="pull-right">
                        @foreach($module_action as $key=>$action)
                        {!! Html::decode(Html::link($action['url'],$action['title'],$action['attributes'])) !!}
                        @endforeach
                    </div>
                @endif
            </div> --}}
             <div class="row">
                <div class="col-md-6">
                    <h5 class="panel-title">{{ trans('comman.client_managers') }}</h5>
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
                        <th> {{ trans('comman.name') }} </th>
                        <th> {{ trans('comman.date_joined') }} </th>
                        <th> {{ trans('comman.no_of_clients') }} </th>
                        <th> {{ trans('comman.no_of_coaches') }} </th>
                        <th class="text-center"> {{ trans('comman.status') }} </th>
                        @if(Auth::user()->hasAnyAccess(['agents.update','agents.delete']))
                            <th style="width: 80px;" class="text-center">{{ trans('comman.action') }}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if(isset($agents) && count($agents) > 0)
                        @php
                            $counter = 0;
                        @endphp

                        @foreach($agents as $agent)
                            <tr>
                                <td>
                                {{ ($agents->currentPage()-1) * $agents->PerPage() + $counter + 1 }}
                                @php
                                $counter++;
                                @endphp
                                </td>
                                <td> {{ $agent->user->name }} </td>
                                <td> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $agent->created_at)->format('m/d/Y')  }} </td>
                                <td> {{ count($agent->clients) }} </td>
                                <td> {{ count($agent->coaches) }} </td>
                                <td class="text-center">
                                    @php
                                        $class = 'label-danger';
                                        if($agent->user->status == 'active') {
                                            $class = 'label-success';
                                        }
                                    @endphp
                                    <label class="label {{ $class}}">
                                        {{ trans('comman.' . $agent->user->status) }}
                                    </label>
                                </td>
                                @if(Auth::user()->hasAnyAccess(['agents.update','agents.delete']) || (Auth::user()->hasAccess('auto_login.can_login') && $agent->user->status == 'active'))
                                <td class="text-center">
                                    <ul class="icons-list">
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                @if(Auth::user()->hasAccess('agents.update'))
                                                <li>
                                                    {!! Html::decode(link_to_route('agents.edit', '<i class="icon-pencil7"></i>Edit', array(Crypt::encryptString($agent->id)))) !!}
                                                </li>
                                                @endif

                                                @if(count($agent->clients) <= 0 && count($agent->coaches) <= 0)
                                                    @if(Auth::user()->hasAccess('agents.delete'))
                                                    <li>
                                                        {!! Html::decode(link_to_route('agents.destroy', '<i class="icon-trash"></i>Delete', array(Crypt::encryptString($agent->id)), ['data-method' => 'delete', 'data-modal-text' => ' ' . trans('comman.client_manager') . '?', 'class' => 'action_confirm text-danger-600', 'title' => 'Delete'])) !!}
                                                    </li>
                                                    @endif
                                                @endif

                                                @if(Auth::user()->hasAccess('auto_login.can_login') && $agent->user->status == 'active')
                                                    <li>
                                                        {!! Html::decode(link_to_route('users.auto_login', '<i class="icon-unlocked2"></i>Auto Login', array('user_id' => Crypt::encryptString($agent->user_id)))) !!}
                                                    </li>
                                                @endif
                                            </ul>
                                        </li>
                                    </ul>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7"> {{ trans('comman.no_data_found') }} </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="pagination-wraper">
            {{ $agents->appends(request()->except('page'))->links('vendor.pagination.bootstrap-custom')}}
            </div>
        </div>
    </div>
</div>
@endsection