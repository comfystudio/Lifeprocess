@extends($theme)

@section('title', $title)

@section('content')
<style type="text/css">
    .btn-labeled.btn-xs > b {
        padding: 7px;
    }
</style>

<div class="content-wrapper" >

    @if(Auth::user()->user_type != 'read-only-coach')
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">{{ trans('comman.clients') }}</h3>
            </div>

            <div class="panel-body">
                {{ Form::open(array('route' => 'clients.index','method'=>'GET','class'=>'form-filter','role'=>"form")) }}
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                {!! Html::decode(Form::label('name_or_email', trans("comman.name_or_email"). ':', ['class' => 'control-label'])) !!}
                                {!! Form::text('name_or_email', Request::get('name_or_email',null), ['class' => 'form-control ','placeholder'=> trans("comman.name_or_email") ]) !!}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                {!! Html::decode(Form::label('program_id', trans("comman.program"). ':', ['class' => 'control-label'])) !!}
                                {!! Form::select('program_id', ["" => trans("comman.select_program")] + $programs, Request::get('program_id',null), ['class' => 'form-control single-select' ]) !!}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                {!! Html::decode(Form::label('month_joined', trans("comman.month_joined"). ':', ['class' => 'control-label'])) !!}
                                {!! Form::select('month_joined', $months , Request::get('month_joined',null), ['class' => 'form-control single-select','placeholder'=> trans("comman.month_joined") ]) !!}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                {!! Html::decode(Form::label('coach', trans("comman.coach"). ':', ['class' => 'control-label'])) !!}
                                {!! Form::select('coach', ['' => trans('comman.select_coach')] + $coaches, Request::get('coach',null), ['class' => 'form-control single-select']) !!}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                {!! Html::decode(Form::label('status', trans("comman.status"). ':', ['class' => 'control-label'])) !!}
                                {!! Form::select('status', ['in_active' => trans('comman.in_active')], Request::get('status',null), ['class' => 'form-control single-select','placeholder'=> trans('comman.active')]) !!}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                {!! Html::decode(Form::label('not_logged_in', trans("comman.not_logged_in"). ':', ['class' => 'control-label'])) !!}
                                {!! Form::text('not_logged_in', Request::get('not_logged_in',null), ['class' => 'form-control','placeholder'=> trans("comman.not_logged_in") ]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Html::decode(Form::label('module_completed', trans("comman.module_completed"). ':', ['class' => 'control-label'])) !!}
                                {!! Form::select('module_completed', $modules, Request::get('module_completed',null), ['class' => 'form-control single-select','placeholder'=> trans("comman.module_completed") ]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {!! Html::decode(Form::label('module_progress', trans("comman.module_progress"). ':', ['class' => 'control-label'])) !!}
                                {!! Form::select('module_progress', $modules, Request::get('module_progress',null), ['class' => 'form-control single-select','placeholder'=> trans("comman.module_progress") ]) !!}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">&nbsp;</label><br>
                                {!! Form::submit(trans('comman.search'), ['name' => 'search','class' => 'btn btn-primary btn-xs']) !!}
                                {!! link_to_route('clients.index', trans('comman.cancel'), [], array('class' => 'btn btn-default btn-xs cancel')) !!}
                            </div>
                        </div>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    @endif

    <div class="panel panel-default">
        <div class="panel-heading bg-white " >
            <div class="row">
                <div class="col-md-6">
                    <h5 class="panel-title">{{ trans('comman.clients') }}</h5>
                </div>
                <div class="heading-elements col-md-4">
                    @if(!empty($module_action))
                        <div class="col-md-offset-4 col-md-2">
                            @foreach($module_action as $key=>$action)
                            {!! Html::decode(Html::link($action['url'],$action['title'],$action['attributes'])) !!}
                            @endforeach
                        </div>
                    @endif
                    @if(isset($clients) && count($clients) > 0 && Auth::user()->user_type != 'read-only-coach')
                        <div class="col-md-6">
                            <div class="text-right">
                                <a href="javascript:void(0);" class="btn bg-info btn-labeled  heading-btn broadcast_mail_popup pull-right" title="Broadcast Mail"><b><i class="icon-paperplane"></i></b> Send Mail</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="panel-body" style="overflow: scroll; min-height: 300px;">
            <table class="table table-bordered table-responsive table-hover no-footer">
                <thead>
                    <tr>
                        {{--<th style="width: 40px;">{{ trans('comman.no') }}</th>--}}
                        {{--<th>{!! dataSorter('users.id',request()->url(),[],"Client Id") !!}</th>--}}
                        {{--<th>{!! dataSorter('users.name',request()->url(), [], trans('comman.name')) !!} </th>--}}
                        {{--<th>{!! dataSorter('program.program_name',request()->url(), [] ,trans('comman.program')) !!}</th>--}}
                        {{--<th>--}}
                        {{--{!! dataSorter('user.latest_module',request()->url(), [] ,trans('comman.module_progress')) !!}--}}
                        {{--</th>--}}
                        {{--<th>{!! dataSorter('created_at',request()->url(), [], trans('comman.date_joined')) !!} </th>--}}
                        {{--<th>--}}
                            {{--{!! dataSorter('users.last_active',request()->url(), [], trans('comman.active_date')) !!}--}}
                        {{--</th>--}}
                        {{--<th>{!! dataSorter('coach.user.name',request()->url(), [],trans('comman.coach')) !!}</th>--}}
                        {{--<th>{!! dataSorter('credits',request()->url(), [], trans('comman.credits')) !!}</th>--}}
                        {{--<th>{!! dataSorter('users.status',request()->url(), [], trans('comman.status')) !!}</th>--}}
                        {{--<th style="width: 60px;">{{ trans('comman.action') }}</th>--}}
                    </tr>
                </thead>
                <tbody>

                    @if(isset($clients) && count($clients) > 0)
                        {{-- {{ dump($clients) }} --}}
                        @php
                            $counter = 0;
                        @endphp
                        @foreach($clients as $client)
                         <input type="hidden" name="to[]" value="{{$client->user->email}}">
                            {{-- <tr>
                                <td colspan="10"> {{ dump($client->user->latest_module) }} </td>
                            </tr> --}}
                            <tr>
                                <td>
                                    {{ ($clients->currentPage()-1) * $clients->PerPage() + $counter + 1 }}
                                     @php
                                     $counter++;
                                     @endphp
                                </td>
                                <td>
                                    @if(Auth::user()->user_type != 'read-only-coach')
                                        {!! Html::decode(link_to_route('client_details.show', $client->user->id, array(Crypt::encryptString($client->user->id)))) !!}
                                    @else
                                        {{ $client->user->id }}
                                    @endif
                                </td>
                                <td>{{ $client->user->name }}</td>
                                <td>{{ isset($client->program) ? $client->program->program_name : '' }} </td>
                                <td>
                                    @if(count($client->user->latest_module) > 0)
                                        @php
                                            $latest_module = $client->user->latest_module->first();
                                        @endphp
                                        {{ $latest_module->module_no }}.{{ $latest_module->module_title }}
                                    @else
                                       -
                                    @endif
                                </td>
                                <td>
                                    {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $client->created_at)->format('m/d/Y') }}
                                </td>
                                <td>
                                    @php
                                        $lastActivity = $client->user->last_active ? : $client->user->updated_at  ;
                                    @endphp
                                    @if($lastActivity)
                                        {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $client->user->updated_at)->format('m/d/Y') }}
                                    @endif
                                </td>
                                <td> {{ $client->coach ? $client->coach->user->name : '' }} </td>
                                <td class="text-center"><label class="label bg-primary"> {{-- {!! Html::decode(link_to_route('client.myCredits', ($client->credits ? : '0'), ['cid' => Crypt::encryptString($client->id)], ['class' => 'label bg-primary'])) !!} --}}{{ ($client->credits ? : '0') }} </label></td>
                                <td class="text-center">
                                    @php
                                        $class = 'label-danger';
                                        if($client->user->status == 'active') {
                                            $class = 'label-success';
                                        }
                                    @endphp
                                    <label class="label {{ $class}}">
                                        {{ trans('comman.' . $client->user->status) }}
                                    </label>
                                </td>
                                <td class="text-center">
                                        <ul class="icons-list">
                                            <li class="dropdown">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    @if(Auth::user()->hasAccess('clients.update'))
                                                        <li>
                                                           {{-- <a href="client_details/{{$client->user->id}}"><i class="icon-pencil7"></i>Edit</a> --}}
                                                           {!! Html::decode(link_to_route('client_details.show', '<i class="icon-pencil7"></i>Edit', array(Crypt::encryptString($client->user->id)))) !!}
                                                        </li>
                                                    @endif
                                                    @if(Auth::user()->hasAccess('clients.delete'))
                                                        <li>
                                                            {!! Html::decode(link_to_route('clients.destroy', '<i class="icon-trash"></i>Delete', array(Crypt::encryptString($client->id)), ['data-method' => 'delete', 'data-modal-text' => ' ' . trans('comman.client') . '?', 'class' => 'action_confirm text-danger-600', 'title' => 'Delete'])) !!}
                                                        </li>
                                                    @endif
                                                    @if(Auth::user()->hasAccess('my_lifestory.view'))

                                                        <li>
                                                            {!! Html::decode(link_to_route('mylifestory.show', '<i class="fa fa-heartbeat"></i>Life Story', array(Crypt::encryptString($client->user_id)))) !!}
                                                        </li>
                                                    @endif
                                                    @if(Auth::user()->hasAccess('messages.create') && Auth::user()->user_type == 'user' && $client->user->status == 'active')
                                                        <li>
                                                            {!! Html::decode(link_to_route('messages.admindata', '<i class="icon-bubbles5"></i>Contact', array('role' => 'client', 'id' => Crypt::encryptString($client->user_id)))) !!}
                                                        </li>
                                                    @endif
                                                    @if(Auth::user()->user_type == 'user')
                                                    <li>
                                                            <a class="client_admin_note_popup" onclick="open_popup({{$client->id}})"><i class="fa fa-file-text-o"></i>Admin note</a>
                                                    </li>
                                                    <li>
                                                        {!! Html::decode(link_to_route('client.transaction', '<i class="fa fa-money"></i>Transaction Report', array(Crypt::encryptString($client->user_id),'_url'=>'client'))) !!}
                                                    </li>
                                                    @endif
                                                    @if(Auth::user()->hasAccess('auto_login.can_login') && $client->user->status == 'active')
                                                        <li>
                                                            {!! Html::decode(link_to_route('users.auto_login', '<i class="icon-unlocked2"></i>Auto Login', array('user_id' => Crypt::encryptString($client->user_id)))) !!}
                                                        </li>
                                                    @endif

                                                    @if(Auth::user()->user_type == 'read-only-coach')
                                                        {{--<li>--}}
                                                           {{--{!! Html::decode(link_to_route('client_details.show', '<i class="icon-pencil7"></i>View', array(Crypt::encryptString($client->user->id)))) !!}--}}
                                                        {{--</li>--}}
                                                        <li>
                                                           <a href="{{url('messages-view/')}}/{{$client->user->id}}"><i class="icon-envelope"></i>View Messages</a>
                                                        </li>
                                                        <li>
                                                           <a href="{{url('program-view/')}}/{{$client->user->id}}"><i class="icon-reading"></i>View Program</a>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </li>
                                        </ul>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="11"> {{ trans('comman.no_data_found') }} </td>
                        </tr>
                    @endif
                </tbody>
            </table>



            <div class="pagination-wraper">
                {{ $clients->appends(request()->except('page'))->links('vendor.pagination.bootstrap-custom')}}
            </div>
        </div>
    </div>
</div>
@include('clients.popup')
@include('clients.admin-note-popup')
@endsection