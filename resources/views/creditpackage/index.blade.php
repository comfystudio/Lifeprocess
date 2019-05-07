@extends($theme)

@section('title', $title)

@section('content')

<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{trans("comman.creadit_package")}}</h3>
        </div>
        <div class="panel-body">
            {{ Form::open(array('route' => 'creditpackage.index','method'=>'GET','class'=>'form-filter','role'=>"form")) }}
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('credit', trans("comman.no_of_credit"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::text('credit', Request::get('credit',null), ['class' => 'form-control ','placeholder'=> trans("comman.no_of_credit") ]) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('price', trans("comman.credit_price"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::text('price', Request::get('price',null), ['class' => 'form-control ','placeholder'=> trans("comman.credit_price") ]) !!}
                    </div>
                </div>
                <div class="col-md-2">
                        <div class="form-group">
                            {!! Html::decode(Form::label('status', trans("comman.status"). ':', ['class' => 'control-label'])) !!}
                            {!! Form::select('status', ['draft' => trans('comman.draft'), 'public' => trans('comman.public')], Request::get('status',null), ['class' => 'form-control single-select','placeholder'=> trans("comman.status") ]) !!}
                        </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label><br>
                        {!! Form::submit(trans('comman.search'), ['name' => 'search','class' => 'btn btn-primary btn-xs']) !!}
                        {!! link_to_route('creditpackage.index', trans('comman.cancel'), [], array('class' => 'btn btn-default btn-xs cancel')) !!}
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading bg-white">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="panel-title">{{ trans('comman.creadit_package') }}</h5>
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
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Credit</th>
                            <th>Price</th>
                            <th class="text-center">Status</th>
                             @if(Auth::user()->hasAnyAccess(['credit_package.update','credit_package.delete']))
                                <th style="width: 80px;" class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>

                        @if(isset($packages) && count($packages) > 0 )
                            @php
                                $counter = 1;
                            @endphp
                            @foreach($packages as $package)
                                <tr>
                                    <td>
                                        {{ ($packages->currentPage()-1) * $packages->perPage()+ $counter++ }}
                                    </td>
                                    <td> {{ $package->credit }} </td>
                                    <td> {{'$'." ".$package->price}} </td>
                                    <td class="text-center">
                                        @php
                                            $class = 'label-info';
                                            if($package->status == 'public') {
                                                $class = 'label-success';
                                            }
                                        @endphp
                                        <label class="label {{ $class}}">
                                            {{$package->status}}
                                        </label>
                                        </td>
                                        @if(Auth::user()->hasAnyAccess(['credit_package.update','credit_package.delete']))
                                        <td class="text-center">
                                            <ul class="icons-list">
                                                <li class="dropdown">
                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                        <i class="icon-menu9"></i>
                                                    </a>
                                                    <ul class="dropdown-menu dropdown-menu-right">
                                                        @if(Auth::user()->hasAccess('credit_package.update'))
                                                            <li>
                                                                {!! Html::decode(link_to_route('creditpackage.edit', '<i class="icon-pencil7"></i>Edit', array(Crypt::encryptString($package->id), '_url'=> request()->getRequestUri()))) !!}
                                                            </li>
                                                        @endif
                                                        @if(Auth::user()->hasAccess('credit_package.delete'))
                                                            <li>
                                                                {!! Html::decode(link_to_route('creditpackage.destroy', '<i class="icon-trash"></i>Delete', array($package->id, '_url'=> request()->getRequestUri()), ['data-method' => 'delete', 'data-modal-text' => ' Package?', 'class' => 'action_confirm text-danger-600', 'title' => 'Delete'])) !!}
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
                {{ $packages->appends(request()->except('page'))->links('vendor.pagination.bootstrap-custom')}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection