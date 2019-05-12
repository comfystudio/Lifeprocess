    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Signup Report</h3>
        </div>
        <div class="panel-body">
            {{ Form::open(array('route' => 'signupreport','method'=>'GET','class'=>'form-filter','role'=>"form")) }}
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Html::decode(Form::label('from_date', trans("comman.from_date"). ':', ['class' => 'control-label'])) !!}
                            {!! Form::text('from_date', Request::get('from_date',null), ['class' => 'form-control datepicker','placeholder'=> trans("comman.from_date"), 'autocomplete' => 'off' ]) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Html::decode(Form::label('to_date', trans("comman.to_date"). ':', ['class' => 'control-label'])) !!}
                            {!! Form::text('to_date', Request::get('to_date',null), ['class' => 'form-control datepicker','placeholder'=> trans("comman.to_date") , 'autocomplete' => 'off']) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Html::decode(Form::label('group_by', trans("comman.groupby"). ':', ['class' => 'control-label'])) !!}
                            {{-- {!! Form::select('group_by',array('all'=>'all','client'=>'Client','coach'=>'Coach','user' => 'User','agent' => 'Agent'),Request::get('group_by',null),array('class' => 'form-control single-select')) !!} --}}
                            {!! Form::select('group_by',array('role'=>'Role','user'=>'User'),Request::get('group_by',null),array('class' => 'form-control single-select')) !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Html::decode(Form::label('status', trans("comman.status"). ':', ['class' => 'control-label'])) !!}
                            {!! Form::select('status',array('active'=>'Active','inactive'=>'Inactive'),Request::get('status',null),array('class' => 'form-control single-select'))!!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">&nbsp;</label><br>
                            {!! Form::submit(trans('comman.search'), ['name' => 'search','class' => 'btn btn-primary btn-xs']) !!}
                            {!! link_to_route('signupreport', trans('comman.cancel'), [], array('class' => 'btn btn-default btn-xs cancel')) !!}
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
        </div>
    </div>