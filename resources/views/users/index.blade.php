@extends($theme)

@section('title', $title)

@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Users</h3>
        </div>
        <div class="panel-body">
            {{ Form::open(array('route' => 'users.index','method'=>'GET','class'=>'form-filter','role'=>"form")) }}
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('name', trans("comman.name"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::text('name', Request::get('name',null), ['class' => 'form-control ','placeholder'=> trans("comman.name") ]) !!}
                        {{ Form::hidden('filter[under_group][type]','1') }}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('email', trans("comman.email"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::text('email', Request::get('email',null), ['class' => 'form-control ','placeholder'=> trans("comman.email") ]) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('mobile_no', trans("comman.mobile_no"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::text('mobile_no', Request::get('mobile_no',null), ['class' => 'form-control ','placeholder'=> trans("comman.mobile_no") ]) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('status', trans("comman.status"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::select('status', ['active' => trans('comman.active'), 'in_active' => trans('comman.in_active')], Request::get('status',null), ['class' => 'form-control single-select','placeholder'=> trans("comman.status") ]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label><br>
                        {!! Form::submit(trans('comman.search'), ['name' => 'search','class' => 'btn btn-primary btn-xs']) !!}
                        {!! link_to_route('users.index', trans('comman.cancel'), [], array('class' => 'btn btn-default btn-xs cancel')) !!}
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading bg-white">
         <div class="row">
                <div class="col-md-6">
                    <h5 class="panel-title">{{ trans('comman.users') }}</h5>
                </div>
                <div class="heading-elements col-md-4">
                    @if(isset($clients) && count($clients) > 0)
                        {{-- <div class="col-md-4"> --}}
                        <div class="text-right">
                            <a href="javascript:void(0);" class="btn bg-info btn-labeled  heading-btn broadcast_mail_popup pull-right" title="Broadcast Mail"><b><i class="icon-paperplane"></i></b> Send Mail</a>
                        </div>
                        {{-- </div> --}}
                    @endif
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
                        <th>No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th class="text-center"> {{ trans('comman.status') }} </th>
                        @if(Auth::user()->hasAnyAccess(['users.update','users.delete']) || (Auth::user()->hasAccess('auto_login.can_login')))
                            <th width="80px" style="width: 80px;" class="text-center">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if(isset($users) && count($users) > 0)
                        @foreach($users as $user)
                            <tr>
                                <td>
                                    {{ ($users->currentPage()-1) * $users->PerPage() + $counter + 1 }}
                                     @php
                                     $counter++;
                                     @endphp
                                </td>
                                <td> {{ $user->name }} </td>
                                <td> {{ $user->email }} </td>
                                <td> {{ $user->mobile_no }} </td>
                                <td class="text-center">
                                    @php
                                        $class = 'label-danger';
                                        if($user->status == 'active') {
                                            $class = 'label-success';
                                        }
                                    @endphp
                                    <label class="label {{ $class}}">
                                        {{ trans('comman.' . $user->status) }}
                                    </label>
                                </td>
                                @if(Auth::user()->hasAnyAccess(['users.update','users.delete']) || (Auth::user()->hasAccess('auto_login.can_login') && $user->status == 'active'))
                                <td class="text-center">
                                    <ul class="icons-list">
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                @if(Auth::user()->hasAccess('users.update'))
                                                <li>
                                                    {!! Html::decode(link_to_route('users.edit', '<i class="icon-pencil7"></i>Edit', array(Crypt::encryptString($user->id)))) !!}
                                                </li>
                                                @endif
                                                @if(Auth::user()->hasAccess('users.delete'))
                                                <li>
                                                    {!! Html::decode(link_to_route('users.destroy', '<i class="icon-trash"></i>Delete', array(Crypt::encryptString($user->id)), ['data-method' => 'delete', 'data-modal-text' => ' User?', 'class' => 'action_confirm text-danger-600', 'title' => 'Delete'])) !!}
                                                </li>
                                                @endif
                                                @if(Auth::user()->hasAccess('auto_login.can_login') && $user->status == 'active')
                                                <li>
                                                    {!! Html::decode(link_to_route('users.auto_login', '<i class="icon-unlocked2"></i>Auto Login', array('user_id' => Crypt::encryptString($user->id)))) !!}
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
                            <td colspan="5">No data found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="pagination-wraper">
                {{ $users->appends(request()->except('page'))->links('vendor.pagination.bootstrap-custom')}}
            </div>
        </div>
    </div>
</div>

@endsection