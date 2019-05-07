@extends($theme)
@section('title', $title)
@section('content')
<div class="row">
    {!! Form::open(array('route' => 'mylifestory.store','class'=>'form-horizontal','role'=>"form")) !!}
    <div class="col-md-12 col-sm-12">
       <div class="panel panel-white">
            <div class="panel-heading">
                <div class="col-sm-9"><h5 class="panel-title"> Add My Life Story</h5></div>
                @if(Request::get("download",false))
                    <div class="pull-right">
                        <button type="button" class="close" ng-click="cancel($event)" aria-label="Close">
                        <span aria-hidden="true" ><i class="icon-cross2"></i></span>
                        </button>
                    </div>
                @endif
                <div class="clearfix"></div>
            @if(isset($mylifestory) && count($mylifestory) > 0)
            <div class="heading-elements">
            <a  href=" {{URL::to('getpdf')}}" type="button" class="btn bg-danger btn-labeled heading-btn"><b><i class="fa fa-file-pdf-o"></i></b>{{ trans('comman.saveaspdf')}}</a>
            </div>
            @endif
            </div>
            <div class="panel-body">

            </div>
            <div class="panel-footer">
                @if(isset($mylifestory) && count($mylifestory) > 0)
            @foreach($mylifestory as $mylifestorys)
                <div class="panel panel-flat border-top-success col-md-10 col-md-offset-1">
                    <div class="panel-heading" style="border: none;">
                        <a class="heading-elements-toggle"><i class="icon-more"></i></a>
                                                    <div class="heading-elements">
                                                        <ul class="icons-list">
                                                        <li class="dropdown">
                                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                                <i class="icon-menu9"></i>
                                                            </a>
                                                            <ul class="dropdown-menu dropdown-menu-right">
                                                                <li>
                                                                     <a class="lifstorey_edit_popup" onclick="open_popup({{$mylifestorys->id}})"><i class="icon-pencil7"></i>Edit</a>
                                                                </li>
                                                                <li>
                                                                    {!! Html::decode(link_to_route('mylifestory.destroy', '<i class="icon-trash"></i>Delete', array($mylifestorys->id), ['data-method' => 'delete', 'data-modal-text' => ' this part of your lifestory?', 'class' => 'action_confirm text-danger-600', 'title' => 'Delete'])) !!}
                                                                </li>
                                                            </ul>
                                                    </div>
                    </div>
                    <div class="panel-body" style="overflow: auto;">
                            {{$mylifestorys->message}}
                        <div class="text-right">
                            {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $mylifestorys->created_at)->format('m/d/Y')}}
                            @if($timezone != null)
                             <span >{{$mylifestorys->created_at->timezone($timezone)->format('H:i')}}</span>
                            @else
                            <span >{{$mylifestorys->created_at->format('H:i')}}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            @else
                No Data Found
            @endif
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@include('mylifestory.edit-popup')
@endsection