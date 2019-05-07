@extends($theme)

@section('title', $title)

@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Countries</h3>
        </div>
        <div class="panel-body">
            {{ Form::open(array('route' => 'countries.index','method'=>'GET','class'=>'form-filter','role'=>"form")) }}
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('country', trans("comman.country"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::text('country', Request::get('country',null), ['class' => 'form-control ','placeholder'=> trans("comman.country") ]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label><br>
                        {!! Form::submit(trans('comman.search'), ['name' => 'search','class' => 'btn btn-primary btn-xs']) !!}
                        {!! link_to_route('countries.index', trans('comman.cancel'), [], array('class' => 'btn btn-default btn-xs cancel')) !!}
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading bg-white">
            {{-- <h5 class="panel-title">Countries</h5>
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
                    <h5 class="panel-title">{{ trans('comman.country') }}</h5>
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
                        <th style="width: 50px;">No</th>
                        <th>Name{!! dataSorter('country',request()->url()) !!}</th>
                        <th>Code</th>
                        @if(Auth::user()->hasAnyAccess(['countries.update','countries.delete']))
                            <th style="width: 80px;" class="text-center">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if(isset($countries) && count($countries) > 0)
                        @foreach($countries as $country)
                            <tr>
                                <td>
                                {{ ($countries->currentPage()-1) * $countries->PerPage() + $counter + 1 }}
                                @php
                                $counter++;
                                @endphp
                                </td>
                                <td> {{ $country->country }} </td>
                                <td> {{ $country->country_code }} </td>
                                @if(Auth::user()->hasAnyAccess(['countries.update','countries.delete']))
                                <td class="text-center">
                                    <ul class="icons-list">
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                @if(Auth::user()->hasAccess('countries.update'))
                                                <li>
                                                    {!! Html::decode(link_to_route('countries.edit', '<i class="icon-pencil7"></i>Edit', array($country->id))) !!}
                                                </li>
                                                @endif
                                                @if(Auth::user()->hasAccess('countries.delete'))
                                                <li>
                                                    {!! Html::decode(link_to_route('countries.destroy', '<i class="icon-trash"></i>Delete', array($country->id), ['data-method' => 'delete', 'data-modal-text' => ' Country?', 'class' => 'action_confirm text-danger-600', 'title' => 'Delete'])) !!}
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
                            <td colspan="3">No data found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="pagination-wraper">
                {{ $countries->appends(request()->except('page'))->links('vendor.pagination.bootstrap-custom')}}
            </div>
        </div>
    </div>
</div>
@endsection