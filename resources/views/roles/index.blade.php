@extends($theme)

@section('title', $title)

@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ trans('comman.roles') }}</h3>
        </div>
        <div class="panel-body">
            {{ Form::open(array('route' => 'roles.index','method'=>'GET','class'=>'form-filter','role'=>"form")) }}
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('role_name', trans('comman.role') . ' ' . trans('comman.name'). ':', ['class' => 'control-label'])) !!}
                        {!! Form::text('role_name', Request::get('role_name',null), ['class' => 'form-control ','placeholder'=> trans('comman.role') . ' ' . trans('comman.name') ]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label><br>
                        {!! Form::submit(trans('comman.search'), ['name' => 'search', 'class' => 'btn btn-primary btn-xs']) !!}
                        {!! link_to_route('roles.index', trans('comman.cancel'), [], array('class' => 'btn btn-default btn-xs cancel')) !!}
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading bg-white">
         <div class="row">
                <div class="col-md-6">
                    <h5 class="panel-title">{{ trans('comman.roles') }}</h5>
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
                        <th>{{ trans('comman.role') . ' ' . trans('comman.name') }}</th>
                        <th>{{ trans('comman.users') }}</th>
                        @if(Auth::user()->hasAnyAccess(['roles.update','roles.delete']))
                            <th style="width: 100px;" class="text-center">{{ trans('comman.action') }}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                   @if(isset($roles) && count($roles) > 0)
                   @php
                        $counter =0 ;
                   @endphp
                        @foreach($roles as $role)
                            <tr>
                                <td>
                                {{ ($roles->currentPage()-1) * $roles->PerPage() + $counter + 1 }}
                                @php
                                    $counter++;
                                @endphp
                                </td>
                                <td>{{ $role->role_name }}</td>
                                <td>{{ count($role->user) }}</td>
                                @if(Auth::user()->hasAnyAccess(['roles.update','roles.delete']))
                                <td class="text-center">
                                    <ul class="icons-list">
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                @if(Auth::user()->hasAccess('roles.update'))
                                                <li>
                                                    {!! Html::decode(link_to_route('roles.edit', '<i class="icon-pencil7"></i>Edit', array(Crypt::encryptString($role->id)))) !!}
                                                </li>
                                                @endif
                                                @if(Auth::user()->hasAccess('roles.delete'))
                                                <li>
                                                    {!! Html::decode(link_to_route('roles.destroy', '<i class="icon-trash"></i>Delete', array(Crypt::encryptString($role->id)), ['data-method' => 'delete', 'data-modal-text' => ' Role?', 'class' => 'action_confirm text-danger-600', 'title' => 'Delete'])) !!}
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
                            <td colspan="4" class="text-center">{{ trans('comman.no_data_found') }}</td>
                        </tr>
                   @endif
                </tbody>
            </table>
            <div class="pagination-wraper">
                {{ $roles->appends(request()->except('page'))->links('vendor.pagination.bootstrap-custom')}}
            </div>
        </div>
    </div>
</div>
@endsection