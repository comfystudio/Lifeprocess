@extends($theme)
@section('title', $title)
@section('style')
@show
<style>
    .panel-footer .panel-flat.border-top-success
    {
        padding-left: 0px;
        padding-right: 0px;
    }
    .panel-footer .panel-heading{
        border:1px solid #DDD;
    }
    .lifestory-save
    {
        background-color: #82cd49;
        border: 0 none;
        border-radius: 0;
        color: #fff;
        padding: 10px 18px;
    }
    .lifestory-cancel
    {
        color: #333;
        background-color: #fcfcfc;
        border-color: #ddd;
        border: 1px solid #ddd;
        padding: 10px 18px;
    }
    .hr {
        background: url('http://i.stack.imgur.com/37Aip.png') no-repeat top center;
        background-size: contain;
        border: 0;
        border-top: 1px solid #8c8c8c;
        text-align:center;
    }
    .hr:after {
        content: '\221E';
        display: inline-block;
        position: relative;
        top: -13px;
        padding: 0 3px;
        background: #fff;
        color: #8c8c8c;
        font-size: 18px;
    }
</style>
@section('content')
<div class="tab-content">
    <div class="tab-pane fade in active" id="coach">
        <div class="tab-title">
          <h1 class="no-margin">
            My Life Story
            @if(Request::get("download",false))
            <div class="pull-right">
                <button type="button" class="close" ng-click="cancel($event)" aria-label="Close">
                    <span aria-hidden="true" ><i class="icon-cross2"></i></span>
                </button>
            </div>
            @endif
            @if(isset($mylifestory) && count($mylifestory) > 0)
            <div class="pull-right">
                <a  href=" {{URL::to('getpdf')}}" type="button" class="btn bg-info btn-labeled heading-btn" target="_blank"><b><i class="fa fa-file-pdf-o"></i></b>{{ trans('comman.saveaspdf')}}</a>
            </div>
            @endif
            </h1>
        </div>

        <div id="lifestory">
            <div class="panel-body col-md-12 col-sm-12">
                @if(isset($page))
                    @if(Auth::user()->user_type == 'user' && Auth::user()->hasAccess('pages.update'))
                        <div class="pull-right">{!! Html::decode(link_to_route('pages.edit', '<i class="icon-pencil7"></i>', array($page->id,'_url'=> Request::path()))) !!}</div>
                    @endif
                    {!!html_entity_decode($page->content)!!}
                @else
                    Static page about-lifestory not found
                @endif
            </div>
            <div class="row no-margin left">
                <div class="col-md-12 col-sm-12 col-xs-12 text">

                    <div class="col-md-12">
                        <div class="border-top-success col-md-12">
                            <div class="col-md-12 col-sm-12">
                                {!! Form::open(array('route' => 'mylifestory.store','class'=>'form-horizontal','role'=>"form")) !!}
                                @include('mylifestory.form')
                            </div>

                            <div class="form-group">
                                <div class="panel-heading">
                                    <div class="col-sm-6">
                                        {!! Form::submit("Save", ['name' => 'save','class' => 'lifestory-save']) !!}
                                        {!! link_to(URL::full(), "Cancel",array('class' => 'lifestory-cancel')) !!}
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                            <div class="row">
                                <hr>
                            </div>
                            @if(isset($mylifestory) && count($mylifestory) > 0)
                                @foreach($mylifestory as $mylifestorys)
                                    <div class="panel panel-flat border-top-success col-md-12">
                                        @if(Auth::user()->hasAnyAccess(['my_lifestory.update','my_lifestory.delete']))
                                            <div class="panel-heading">
                                                <a class="heading-elements-toggle"><i class="icon-more"></i></a>
                                                    <div class="pull-right">
                                                        <ul class="icons-list">
                                                            <li class="dropdown">
                                                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                                                    <i class="icon-menu9"></i>
                                                                </a>
                                                                <ul class="dropdown-menu dropdown-menu-right">
                                                                    @if(Auth::user()->hasAccess('my_lifestory.update'))
                                                                    <li>
                                                                        <a class="lifstorey_edit_popup" onclick="open_popup({{$mylifestorys->id}})"><i class="icon-pencil7"></i>Edit</a>
                                                                    </li>
                                                                   @endif
                                                                    @if(Auth::user()->hasAccess('my_lifestory.delete'))
                                                                    <li>
                                                                        {!! Html::decode(link_to_route('mylifestory.destroy', '<i class="icon-trash"></i>Delete', array('id'=>Crypt::encryptString($mylifestorys->id)), ['data-method' => 'delete', 'data-modal-text' => ' this part of your lifestory?', 'class' => 'action_confirm text-danger-600', 'title' => 'Delete'])) !!}
                                                                    </li>
                                                                    @endif
                                                                </ul>
                                                             </li>
                                                        </ul>
                                                   </div>
                                            </div>
                                        @endif
                                        <div class="panel-body col-md-12" style="overflow: auto; text-align: justify;  ">
                                            <div class="diary-entry">
                                                 @php
                                                 echo $mylifestorys->message;
                                                 @endphp
                                                    {{--   {{$mylifestorys->message}}
                                                    --}}
                                            </div>
                                            <div class="text-right diary">
                                                Diary Entry:
                                                {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $mylifestorys->created_at)->format('m/d/Y')}}
                                                @if($timezone != null)
                                                <span >{{$mylifestorys->created_at->timezone($timezone)->format('H:i')}}</span>
                                                @else
                                                <span >{{$mylifestorys->created_at->format('H:i')}}</span>
                                                @endif
                                            </div>

                                        </div>
                                        <div class="col-md-12">
                                            <hr class="hr">
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-center">

                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('mylifestory.edit-popup')
@endsection