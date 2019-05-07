@extends($theme)
@section('title', $title)
@section('content')
<div class="row">
    {!! Form::open(array('route' => 'update_session_status.store','class'=>'form-horizontal','role'=>"form",'id'=>'complete_session_form')) !!}
    <div class="col-md-12">
       <div class="panel panel-white">
            <div class="panel-heading">
                <div class="col-sm-9"><h5 class="panel-title">{{trans('comman.complete_session')}}</h5></div>
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
                <div class="form-group {{ $errors->has('sessionTime') ? 'has-error' : ''}}">
                    {!!  Html::decode(Form::label('sessionTime', trans("comman.session_startTime"). ':', ['class' => 'col-sm-4 control-label'])) !!}
                    <div class="col-sm-6">
                        {!! Form::text('sessionTime', $sessionTime, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                        {!! $errors->first('sessionTime', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('contact_methods') ? 'has-error' : ''}}">
                    {!!  Html::decode(Form::label('contact_methods', trans("comman.preferred_contact_methods"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-4 control-label'])) !!}
                    <div class="col-sm-6">
                        {!! Form::select('contact_methods',array( "" => trans("comman.select")) + $contact_methods ,Request::get('contact_methods',null), ['class' => 'form-control', 'onchange' => 'autoFill_contact_byMethod(this.value, "'. $booked_session->booked_user_id .'");']) !!}
                        {!! $errors->first('contact_methods', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('contact_detail') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('contact_detail', trans("comman.contact_detail"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-4 control-label'])) !!}
                    <div class="col-sm-6">
                        {!! Form::text('contact_detail', null, ['class' => 'form-control','placeholder'=> trans("comman.contact_detail") ]) !!}
                        {!! $errors->first('contact_detail', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                <div class="form-group {{ $errors->has('remarks') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('remarks', trans("comman.remarks"). ':', ['class' => 'col-sm-4 control-label'])) !!}
                    <div class="col-sm-6">
                        {!! Form::textarea('remarks', null, ['class' => 'form-control','placeholder'=> trans("comman.remarks"), 'rows' => '5' ]) !!}
                        {!! $errors->first('remarks', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                {!! Form::hidden('session_status', 'completed') !!}
                {!! Form::hidden('booked_schedule_id', $booked_session->id) !!}
                {!! Form::hidden('coach_id', $booked_session->coach_schedule->created_user_id) !!}
            </div>
            <div class="form-group">
                <div class="col-sm-7 text-right">
                    {!! Form::submit(trans('comman.save'), ['name' => 'save','class' => 'btn btn-primary']) !!}
                    @if(!Request::get("download",false))
                        {!! Form::submit(trans('comman.save_exit'), ['name' => 'save_exit','class' => 'btn btn-primary']) !!}
                    @endif
                    {!! link_to(URL::full(), trans('comman.cancel'),array('class' => 'btn btn-warning cancel')) !!}
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@endsection