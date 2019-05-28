@extends($theme)
@section('title', $title)
@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ trans('comman.coaches') }}</h3>
        </div>
        <div class="panel-body">
            {{ Form::open(array('route' => 'coaches.index','method'=>'GET','class'=>'form-filter','role'=>"form")) }}
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
                    {!! link_to_route('coaches.index', trans('comman.cancel'), [], array('class' => 'btn btn-default btn-xs cancel')) !!}
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading bg-white" >
          {{--   <div class="row">
                <div class="col-md-6">
                    <h5 class="panel-title">{{ trans('comman.coaches') }}</h5>
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
            </div> --}}
            <div class="row">
                <div class="col-md-6">
                    <h5 class="panel-title">{{ trans('comman.coaches') }}</h5>
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
                        <th width="40px">No</th>
                        <th style="width: 40px;">Coach ID</th>
                        <th>{!! dataSorter('user.name',request()->url(), [], trans('comman.name')) !!} </th>
                        <th style="width: 150px;">
                            {!! dataSorter('created_at',request()->url(), [], trans('comman.date_joined')) !!}
                        </th>
                        <th style="width: 150px;">
                            {!! dataSorter('user.updated_at',request()->url(), [], " Last Login ") !!}
                        </th>
                        <th style="width: 100px;">{{ trans('comman.no_of_clients') }}</th>
                        <th style="width: 120px;">
                        {!! dataSorter('min_slots_availability_per_week',request()->url(), []," Weekly coaching slots") !!}
                        </th>
                        <th style="width: 100px;" class="text-center">
                        {!! dataSorter('user.status',request()->url(), [], trans('comman.status')) !!}
                        </th>
                        <th style="width: 80px;" class="text-center">{{ trans('comman.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($coaches) && count($coaches) > 0)
                    @php
                    $counter = 0;
                    @endphp
                    @foreach($coaches as $coach)
                    <tr>
                        <td>
                        {{ ($coaches->currentPage()-1) * $coaches->PerPage() + $counter + 1 }}
                        @php
                        $counter++;
                        @endphp
                        </td>
                        <td>{!! Html::decode(link_to_route('coaches.show_details', $coach->id, array( Crypt::encryptString($coach->id)))) !!}</td>
                        <td> {{ $coach->user->name }} </td>
                        <td> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $coach->created_at)->format('m/d/Y')  }} </td>
                        <td>
                                    @php
                                        $lastActivity = $coach->user->last_active ? : $coach->user->updated_at  ;
                                    @endphp
                                    @if($lastActivity)
                                        {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $lastActivity)->format('m/d/Y') }}
                                    @endif
                                </td>
                        <td class="text-center">
                            {!! Html::decode(link_to_route('clients.index', count($coach->clients), ['coach' =>$coach->id], ['class' => 'label bg-primary'])) !!}
                        </td>
                        <td>
                           {{ ($coach->min_slots_availability_per_week) }}
                       </td>
                       <td class="text-center">
                        @php
                        $class = 'label-danger';
                        if($coach->user->status == 'active') {
                            $class = 'label-success';
                        }
                        @endphp
                        <label class="label {{ $class}}">
                            {{ trans('comman.' . $coach->user->status) }}
                        </label>
                    </td>
                    <td class="text-center">
                        @if(Auth::user()->hasAnyAccess(['coaches.update','coaches.delete', 'messages.create', 'auto_login.can_login']) || $coach->user->status == 'active')
                        <ul class="icons-list">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="icon-menu9"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    @if(Auth::user()->hasAccess('coaches.update'))
                                    <li>
                                        {!! Html::decode(link_to_route('coaches.show_details', '<i class="icon-pencil7"></i>Edit', array( Crypt::encryptString($coach->id)))) !!}
                                    </li>
                                    @endif
                                    @if(Auth::user()->hasAccess('coaches.delete'))
                                    <li>
                                        {!! Html::decode(link_to_route('coaches.destroy', '<i class="icon-trash"></i>Delete', array( Crypt::encryptString($coach->id)), ['data-method' => 'delete', 'data-modal-text' => ' ' . trans('comman.coach') . '?', 'class' => 'action_confirm text-danger-600', 'title' => 'Delete'])) !!}
                                    </li>
                                    @endif
                                    @if($coach->user->status == 'active')
                                    <li>
                                        {!! Html::decode(link_to_route('coach.unlock-modules.index', '<i class="icon-exit3"></i>Unlock Modules', array(Crypt::encryptString($coach->user_id)))) !!}
                                    </li>
                                    @endif
                                    @if(Auth::user()->hasAccess('messages.create') && Auth::user()->user_type == 'user' && $coach->user->status == 'active')
                                    <li>
                                        {!! Html::decode(link_to_route('messages.admindata', '<i class="icon-bubbles5"></i>Contact', array('role' => 'coach', 'id' => Crypt::encryptString($coach->user_id)))) !!}
                                    </li>
                                    @endif
                                    @if(Auth::user()->user_type == 'user')
                                    <li>
                                        <a class="admin_note_popup" onclick="open_popup({{$coach->id}})"><i class="icon-file-text2"></i> Admin note</a>
                                    </li>
                                    <li>
                                        {!! Html::decode(link_to_route('client.transaction', '<i class="fa fa-money"></i>Transaction Report', array(Crypt::encryptString($coach->user_id),'_url'=>'coach'))) !!}
                                    </li>
                                    @endif
                                    @if(Auth::user()->hasAccess('auto_login.can_login') && $coach->user->status == 'active')
                                    <li>
                                        {!! Html::decode(link_to_route('users.auto_login', '<i class="icon-unlocked2"></i>Auto Login', array('user_id' => Crypt::encryptString($coach->user_id)))) !!}
                                    </li>
                                    @endif
                                </ul>
                            </li>
                        </ul>
                        @endif
                    </td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td colspan="9"> {{ trans('comman.no_data_found') }} </td>
                </tr>
                @endif
            </tbody>
        </table>
        <div class="pagination-wraper">
            {{ $coaches->appends(request()->except('page'))->links('vendor.pagination.bootstrap-custom')}}
        </div>
    </div>

    <div class="panel panel-default">
            <div class="panel-heading bg-white" >
              {{--   <div class="row">
                    <div class="col-md-6">
                        <h5 class="panel-title">{{ trans('comman.coaches') }}</h5>
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
                </div> --}}
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="panel-title">{{ trans('comman.read_only_coach') }}</h5>
                    </div>
                    <div class="heading-elements col-md-4">
                        {{--@if(!empty($module_action))--}}
                            {{--<div class="pull-right">--}}
                                {{--@foreach($module_action as $key=>$action)--}}
                                {{--{!! Html::decode(Html::link($action['url'],$action['title'],$action['attributes'])) !!}--}}
                                {{--@endforeach--}}
                            {{--</div>--}}
                        {{--@endif--}}
                    </div>
                </div>
            </div>



            {{--!!!READ ONLY COACH!!!--}}
            <div class="panel-body">
                <table class="table table-bordered table-hover no-footer">
                    <thead>
                        <tr>
                            <th width="40px">No</th>
                            <th style="width: 40px;">Coach ID</th>
                            <th>{!! dataSorter('user.name',request()->url(), [], trans('comman.name')) !!} </th>
                            <th style="width: 150px;">
                                {!! dataSorter('created_at',request()->url(), [], trans('comman.date_joined')) !!}
                            </th>
                            <th style="width: 150px;">
                                {!! dataSorter('user.updated_at',request()->url(), [], " Last Login ") !!}
                            </th>
                            <th style="width: 100px;">{{ trans('comman.no_of_clients') }}</th>
                            <th style="width: 100px;" class="text-center">
                            {!! dataSorter('user.status',request()->url(), [], trans('comman.status')) !!}
                            </th>
                            <th style="width: 80px;" class="text-center">{{ trans('comman.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($read_only_coaches) && count($read_only_coaches) > 0)
                        @php
                        $counter = 1;
                        @endphp
                        @foreach($read_only_coaches as $coach)
                        <tr>
                            <td>
                            {{--{{ ($read_only_coaches->currentPage()-1) * $read_only_coaches->PerPage() + $counter + 1 }}--}}
                            @php
                             echo $counter++;
                            @endphp
                            </td>
                            <td> {{$coach->id}} </td>
                            <td> {{ $coach->name }} </td>
                            <td> {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $coach->created_at)->format('m/d/Y')  }} </td>
                            <td>
                                        @php
                                            $lastActivity = $coach->last_active ? : $coach->updated_at  ;
                                        @endphp
                                        @if($lastActivity)
                                            {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $lastActivity)->format('m/d/Y') }}
                                        @endif
                                    </td>
                            <td class="text-center">
                                {{--<a class="label bg-primary">{{ count($coach['Clients'])}}</a>--}}
                                  {!! Html::decode(link_to_route('clients.index', count($coach['Clients']), ['read_only_coach' =>$coach->email], ['class' => 'label bg-primary'])) !!}
                            </td>
                           <td class="text-center">
                            @php
                            $class = 'label-danger';
                            if($coach->status == 'active') {
                                $class = 'label-success';
                            }
                            @endphp
                            <label class="label {{ $class}}">
                                {{ trans('comman.' . $coach->status) }}
                            </label>
                        </td>
                        <td class="text-center">
                            @if(Auth::user()->hasAnyAccess(['coaches.update','coaches.delete', 'messages.create', 'auto_login.can_login']) || $coach->status == 'active')
                            <ul class="icons-list">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                        <i class="icon-menu9"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        {{--@if(Auth::user()->hasAccess('coaches.update'))--}}
                                        {{--<li>--}}
                                            {{--{!! Html::decode(link_to_route('coaches.show_details', '<i class="icon-pencil7"></i>Edit', array( Crypt::encryptString($coach->id)))) !!}--}}
                                        {{--</li>--}}
                                        {{--@endif--}}
                                        {{--@if(Auth::user()->hasAccess('coaches.delete'))--}}
                                        {{--<li>--}}
                                            {{--{!! Html::decode(link_to_route('coaches.destroy', '<i class="icon-trash"></i>Delete', array( Crypt::encryptString($coach->id)), ['data-method' => 'delete', 'data-modal-text' => ' ' . trans('comman.coach') . '?', 'class' => 'action_confirm text-danger-600', 'title' => 'Delete'])) !!}--}}
                                        {{--</li>--}}
                                        {{--@endif--}}
                                        {{--@if($coach->user->status == 'active')--}}
                                        {{--<li>--}}
                                            {{--{!! Html::decode(link_to_route('coach.unlock-modules.index', '<i class="icon-exit3"></i>Unlock Modules', array(Crypt::encryptString($coach->user_id)))) !!}--}}
                                        {{--</li>--}}
                                        {{--@endif--}}
                                        {{--@if(Auth::user()->hasAccess('messages.create') && Auth::user()->user_type == 'user' && $coach->user->status == 'active')--}}
                                        {{--<li>--}}
                                            {{--{!! Html::decode(link_to_route('messages.admindata', '<i class="icon-bubbles5"></i>Contact', array('role' => 'coach', 'id' => Crypt::encryptString($coach->user_id)))) !!}--}}
                                        {{--</li>--}}
                                        {{--@endif--}}
                                        {{--@if(Auth::user()->user_type == 'user')--}}
                                        {{--<li>--}}
                                            {{--<a class="admin_note_popup" onclick="open_popup({{$coach->id}})"><i class="icon-file-text2"></i> Admin note</a>--}}
                                        {{--</li>--}}
                                        {{--<li>--}}
                                            {{--{!! Html::decode(link_to_route('client.transaction', '<i class="fa fa-money"></i>Transaction Report', array(Crypt::encryptString($coach->user_id),'_url'=>'coach'))) !!}--}}
                                        {{--</li>--}}
                                        {{--@endif--}}
                                        @if(Auth::user()->hasAccess('auto_login.can_login') && $coach->status == 'active')
                                        <li>
                                            {!! Html::decode(link_to_route('users.auto_login', '<i class="icon-unlocked2"></i>Auto Login', array('user_id' => Crypt::encryptString($coach->id)))) !!}
                                        </li>
                                        @endif
                                    </ul>
                                </li>
                            </ul>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="9"> {{ trans('comman.no_data_found') }} </td>
                    </tr>
                    @endif
                </tbody>
            </table>
            {{--<div class="pagination-wraper">--}}
                {{--{{ $coaches->appends(request()->except('page'))->links('vendor.pagination.bootstrap-custom')}}--}}
            {{--</div>--}}
        </div>

</div>
</div>
@include('coaches.admin-note-popup')
@endsection
@section('style')

@endsection