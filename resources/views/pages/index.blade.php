@extends($theme)

@section('title', $title)

@section('content')

<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{trans("comman.pages")}}</h3>
        </div>
        <div class="panel-body">
            {{ Form::open(array('route' => 'pages.index','method'=>'GET','class'=>'form-filter','role'=>"form")) }}
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('pages', trans("comman.page_title"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::text('title', Request::get('title',null), ['class' => 'form-control ','placeholder'=> trans("comman.page_title") ]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label><br>
                        {!! Form::submit(trans('comman.search'), ['name' => 'search','class' => 'btn btn-primary btn-xs']) !!}
                        {!! link_to_route('pages.index', trans('comman.cancel'), [], array('class' => 'btn btn-default btn-xs cancel')) !!}
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading bg-white">
            {{-- <h5 class="panel-title">{{trans("comman.pages")}}</h5>
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
                    <h5 class="panel-title">{{ trans('comman.pages') }}</h5>
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
                        <th>Title</th>
                        <th>Slug</th>
                        @if(Auth::user()->hasAnyAccess(['pages.update','pages.delete']))
                            <th style="width: 80px;" class="text-center">Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @if(isset($pages) && count($pages) > 0 )
                        @foreach($pages as $page)

                            <tr>
                                <td>
                                {{ ($pages->currentPage()-1) * $pages->PerPage() + $counter + 1 }}
                                @php
                                $counter++;
                                @endphp
                                </td>
                                <td> {{ $page->title }} </td>
                                <td> {{$page->slug}} </td>
                                @if(Auth::user()->hasAnyAccess(['pages.update','pages.delete']))
                                    <td class="text-center">
                                        <ul class="icons-list">
                                            <li class="dropdown">
                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                    <i class="icon-menu9"></i>
                                                </a>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                @if(Auth::user()->hasAccess('pages.update'))
                                                    <li>
                                                        {!! Html::decode(link_to_route('pages.edit', '<i class="icon-pencil7"></i>Edit', array($page->id))) !!}
                                                    </li>
                                                @endif
                                                @if(Auth::user()->hasAccess('pages.delete'))
                                                    <li>
                                                        {!! Html::decode(link_to_route('pages.destroy', '<i class="icon-trash"></i>Delete', array($page->id), ['data-method' => 'delete', 'data-modal-text' => ' Page?', 'class' => 'action_confirm text-danger-600', 'title' => 'Delete'])) !!}
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
                            <td colspan="4">No data found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
            <div class="pagination-wraper">
                {{ $pages->appends(request()->except('page'))->links('vendor.pagination.bootstrap-custom')}}
            </div>
        </div>
    </div>
</div>
@endsection