@extends($theme)
@section('title', $title)
@section('content')
	<div class="row">
		 {!! Form::open(array('route' =>  ['clients.note', 'id' => $id],'class'=>'form-horizontal','role'=>"form",'id'=>'client_admin_note',)) !!}
		 <div class="col-md-12">
       			<div class="panel panel-white">
       				<div class="panel-heading">
					<div class="col-sm-9"><h5 class="panel-title">{{ trans('comman.admin_note') }}</h5></div>
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
               				<div class="form-group {{ $errors->has('admin_note') ? 'has-error' : ''}}">
                                                        {!! Html::decode(Form::label('admin_note','Add Note', ['class' => 'col-sm-3 control-label'])) !!}
                                                        <div class="col-sm-7">
                                                            {!! Form::textarea('admin_note', $get_note->admin_note, ['class' => 'form-control','placeholder' => trans("comman.note"),'rows' => '5']) !!}
                                                            {!! $errors->first('admin_note', '<p class="help-block">:message</p>') !!}
                                                        </div>
                                      </div>
               			</div>
                   			<div class="form-group">
                		                <div class="col-sm-6 text-right">
                		                    {!! Form::submit("Save", ['name' => 'save','class' => 'btn btn-primary']) !!}
                		                    {!! link_to('clients', "Cancel",array('class' => 'btn btn-warning cancel')) !!}
                		                </div>
                        			</div>
            			</div>
                              @if(isset($get_note->admin_note))
                                <div class="panel panel-flat border-top-success col-md-10 col-md-offset-1">
                                    <div class="panel-body" style="overflow: auto;">

                                        {{$get_note->admin_note}}
                                        <div class="text-right">
                                          {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $get_note->updated_at)->format('m/d/Y')}}
                                          @if($timezone != null)
                                           <span >{{$get_note->updated_at->timezone($timezone)->format('H:i')}}</span>
                                          @else
                                          <span >{{$get_note->updated_at->format('H:i')}}</span>
                                          @endif
                                      </div>
                                    </div>
                                </div>
                              @else
                                  <div class="panel panel-flat border-top-success col-md-10 col-md-offset-1">
                                      <div class="panel-body" style="overflow: auto;">
                                          No Privious Admin Note Found 
                                      </div>
                                  </div>
                              @endif
       		</div>
                       {!! Form::close() !!}
	</div>

@include('clients.admin-note-popup')
@endsection
