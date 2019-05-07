@extends($theme)
@section('title', $title)
@section('content')
    <div class="row">
        {!! Form::model($mylifestory, ['method' => 'PATCH','route' => ['mylifestory.update', $mylifestory->id],'class' => 'form-horizontal','id' =>'lifestorey_edit_form','onSubmit' => "return lifeStorySubmitDynamic(event)"]) !!}
        <div class="col-md-12">
            <div class="panel panel-white">
                <div class="panel-heading">
                    <div class="col-sm-9"><h5 class="panel-title">{{trans('comman.edit')}} {{ trans('comman.mylifestory') }}</h5></div>
                    @if(Request::get("download",false))
                    <div class="pull-right">
                         <a href="mylifestory" class="close"><span aria-hidden="true" ><i class="icon-cross2"></i></span></a>
                    </div>
                @endif
                <div class="clearfix"></div>
                </div>
                <div class="panel-body">
                    @include('mylifestory.form')
                </div>
                <div class="form-group">
                    <div class="col-sm-6 text-right">
                        {!! Form::submit(trans('comman.save'), ['name' => 'save','class' => 'btn btn-primary']) !!}
                        @if(!Request::get("download",false))
                        {!! Form::submit("Save & Exit", ['name' => 'save_exit','class' => 'btn btn-primary']) !!}
                        @endif
                        <a href="#mylifestory" onclick="lifeStoryCancelDynamic()" class="btn btn-warning cancel">Cancel</a>
                    </div>
                </div>
            </div>
            </div>
            {!! Form::close() !!}
    </div>
@include('mylifestory.edit-popup')
@endsection