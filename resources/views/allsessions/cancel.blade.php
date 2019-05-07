@extends($theme)
@section('title', $title)
@section('content')
  <div class="row">
     {!! Form::open(array('route' => ['cancel.schedule', 'id' => $id],'class'=>'form-horizontal','role'=>"form",'id'=>'cancel_schedule',)) !!}
     <div class="col-md-12">
            <div class="panel panel-white">
              <div class="panel-heading">
                  <div class="col-sm-9"><h5 class="panel-title">{{ trans('comman.scehdule_cancel_reson') }}</h5></div>
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
                <div class="form-group {{ $errors->has('cancel_reson') ? 'has-error' : ''}}">
                    {!! Html::decode(Form::label('cancel_reson','Reason', ['class' => 'col-sm-3 control-label'])) !!}
                    <div class="col-sm-7">
                        {!! Form::textarea('cancel_reson', null, ['class' => 'form-control','placeholder' =>'Reason','rows' => '5']) !!}
                        {!! $errors->first('cancel_reson', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
              </div>
              <div class="form-group">
                  <div class="col-sm-6 text-right">
                      {!! Form::submit("Save", ['name' => 'save','class' => 'btn btn-primary']) !!}
                      {!! link_to('allsession', "Cancel",array('class' => 'btn btn-warning cancel')) !!}
                  </div>
              </div>
            </div>        
          </div>
           {!! Form::close() !!}
  </div>
@endsection
