@extends($theme)
@section('title', $title)
@section('content')
<style type="text/css">
    #broadcast_mail_form .panel-body {
        padding: 15px;
    }
    #broadcast_mail_form .panel {
        margin-bottom: 0;
    }
    .note-editor .note-toolbar {
        padding: 0 5px 5px;
    }
    .note-toolbar > .note-btn-group{
        margin-top: 5px;
        margin-right: 5px;
    }
</style>
<div class="row">
    {!! Form::open(array('route' => 'clients.send-mail.send','class'=>'form-horizontal','role'=>"form",'id'=>'broadcast_mail_form')) !!}
    <div class="col-md-12">
       <div class="panel panel-white" style="margin-bottom: 0;">
            <div class="panel-heading">
                <div class="col-sm-9"><h5 class="panel-title">Broadcast Mail</h5></div>
                @if(Request::get("download",false))
                    <div class="pull-right">
                        <button type="button" class="close" ng-click="cancel($event)" aria-label="Close">
                        <span aria-hidden="true" ><i class="icon-cross2"></i></span>
                        </button>
                    </div>
                @endif
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <div class="form-group {{ $errors->has('subject') ? 'has-error' : ''}}" style="margin-bottom: 0;">
                    {!! Html::decode(Form::label('subject', trans("comman.subject"). ':<span class="has-stik">*</span>', ['class' => 'control-label col-sm-12', 'style' => 'padding-bottom:0;'])) !!}
                    <div class="col-sm-8">
                        {!! Form::text('subject', null, ['class' => 'form-control','placeholder'=> trans("comman.subject") ]) !!}
                        {!! $errors->first('subject', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('subject') ? 'has-error' : ''}}" style="margin-bottom: 0;">
                    {!! Html::decode(Form::label('message', trans("comman.message"). ':<span class="has-stik">*</span>', ['class' => 'control-label col-sm-12', 'style' => 'padding-bottom:0;'])) !!}
                    <div class="col-sm-12">
                        {!! Form::textarea('message', null, ['class' => 'form-control summernote','placeholder'=> trans("comman.message") ]) !!}
                        {!! $errors->first('message', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
            </div>
            <div class="panel-footer">
                    {!! link_to(URL::full(), trans('comman.cancel'),array('class' => 'btn btn-warning cancel pull-right')) !!}
                    {!! Form::submit(trans('comman.send'), ['name' => 'save','class' => 'btn btn-primary pull-right', 'style' => 'margin-right:10px;']) !!}
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection