@extends($theme)

@section('title', $title)

@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-body">
            {{ Form::open(array('route' => 'states.index','method'=>'GET','class'=>'form-filter','role'=>"form")) }}
                <div class="col-md-2">
                    <div class="form-group">                    
                        {!! Html::decode(Form::label('country_id', trans("comman.country"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::text('country_id', Request::get('country_id',null), ['class' => 'form-control ','placeholder'=> trans("comman.country") ]) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">                    
                        {!! Html::decode(Form::label('state', trans("comman.state"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::text('state', Request::get('state',null), ['class' => 'form-control ','placeholder'=> trans("comman.state") ]) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label><br>
                        {!! Form::submit(trans('comman.search'), ['name' => 'search','class' => 'btn btn-primary btn-xs']) !!}
                        {!! link_to_route('states.index', trans('comman.cancel'), [], array('class' => 'btn btn-default btn-xs cancel')) !!}
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5 class="panel-title">states</h5>
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
            <table class="table table-bordered table-hover no-footer">
                <thead>                
                    <tr>
                        <th style="width: 50px;">No.</th>
                        <th>Country</th>                        
                        <th>Name</th>                        
                        <th style="width: 80px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($states) && count($states) > 0)
                        @foreach($states as $state)
                            <tr>
                                <td> {{ ++$counter }}. </td>
                                <td> {{ $state->country->country }} </td>                          
                                <td> {{ $state->state }} </td>                          
                                <td>
                                    @if(Auth::user()->hasAnyAccess(['states.update','states.delete']))
                                    <ul class="icons-list">
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                <i class="icon-menu9"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                @if(Auth::user()->hasAccess('states.update'))
                                                <li>
                                                    {!! Html::decode(link_to_route('states.edit', '<i class="icon-pencil7"></i>Edit', array($state->id))) !!}
                                                </li>
                                                @endif
                                                @if(Auth::user()->hasAccess('states.delete'))
                                                <li>
                                                    {!! Html::decode(link_to_route('states.destroy', '<i class="icon-trash"></i>Delete', array($state->id), ['data-method' => 'delete', 'data-modal-text' => ' Country?', 'class' => 'action_confirm text-danger-600', 'title' => 'Delete'])) !!}
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
                            <td colspan="4">No data found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>        
    </div>
</div>
@endsection