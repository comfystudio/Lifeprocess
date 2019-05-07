@extends($theme)
@section('title', $title)
@section('content')
<div class="row">
    {!! Form::open(array('url' => '/agent/edit-theme','class'=>'form-horizontal','role'=>"form",'id'=>'programs_create_form', 'files' => true)) !!}
        {{Form::hidden('_url',request()->get('_url', request()->getRequestUri()))}}
    <div class="col-md-12">
       <div class="panel panel-white">
            <div class="panel-heading">
                <div class="col-sm-9">
                    <h3 class="panel-title"> Edit Theme </h3>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">

                <div class="form-group {{ $errors->has('colour_1') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('colour_1', 'Header Colour'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-4">
                        {!! Form::color('colour_1', $agent->colour_1, ['class' => 'form-control','placeholder' => 'Main Colour' ]) !!}
                        {!! $errors->first('colour_1', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                
                <div class="form-group {{ $errors->has('colour_2') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('colour_2', 'Subheader Colour'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-4">
                        {!! Form::color('colour_2', $agent->colour_2, ['class' => 'form-control','placeholder' => 'Secondary Colour' ]) !!}
                        {!! $errors->first('colour_2', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group {{ $errors->has('colour_3') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('colour_3', 'Menu Colour'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-4">
                        {!! Form::color('colour_3', $agent->colour_3, ['class' => 'form-control','placeholder' => 'Tertiary Colour' ]) !!}
                        {!! $errors->first('colour_3', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                
                <div class="form-group {{ $errors->has('colour_4') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('colour_4', 'Background Colour'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-4">
                        {!! Form::color('colour_4', $agent->colour_4, ['class' => 'form-control','placeholder' => 'Tertiary Colour' ]) !!}
                        {!! $errors->first('colour_4', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>

                <div class="form-group {{ $errors->has('logo') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('logo', 'Agent Logo'. ':', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-4">
                        {!! Form::file('logo', ['class' => 'form-control', 'onchange' => 'readUserURL(this)', 'accept' => 'image/*']) !!}
                        {!! $errors->first('logo', '<p class="help-block">:message</p>') !!}
                    </div>
                    <div class="col-sm-5">
                        @if(isset($agent['logo']) && !empty($agent['logo']))
                            {{--{{Html::image(AppHelper::path($agent['logo']),'User Photo',array("class"=>"img-circle",'id'=>'staff','height'=>'50','width'=>'50'))}}--}}
                            <img class = "img-circle" src="/{{$agent->logo}}" alt="{{$agent->logo}}" style = "width:50px;">
                        @else
                            {{Html::image(AppHelper::size('50x50')->getDefaultImage(),'User Photo',array("class"=>"img-circle staff",'id'=>'staff','height'=>'50','width'=>'50'))}}
                        @endif
                    </div>
                </div>


            </div>
            <div class="form-group">
                <div class="col-sm-6 text-right">
                    {{--{!! Form::submit("Save", ['name' => 'save','class' => 'btn btn-primary']) !!}--}}

                    {!! Form::submit("Save & Exit", ['name' => 'save_exit','class' => 'btn btn-primary']) !!}

                    {!! link_to(URL::full(), "Cancel",array('class' => 'btn btn-warning cancel')) !!}
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection