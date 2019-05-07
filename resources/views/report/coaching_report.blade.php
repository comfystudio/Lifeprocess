@extends($theme)

@section('title', $title)
@section('style')
    <style type="text/css">
        tbody tr, thead th{
            text-align: center;
        }
    </style>
@endsection
@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Coaching Report</h3>
        </div>
        <div class="panel-body">
            {{ Form::open(array('route' => 'report.coaching','method'=>'GET','class'=>'form-filter','role'=>"form")) }}
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('From Date', trans("comman.from_date"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::text('from_date', Request::get('from_date',null), ['class' => 'form-control datepicker','placeholder'=> trans("comman.to_date") ]) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('To Date', trans("comman.to_date"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::text('to_date', Request::get('to_date',null), ['class' => 'form-control datepicker','placeholder'=> trans("comman.to_date") ]) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('Coach', trans("comman.coach"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::select('coach', [''=>'select coach']+$coaches, Request::get('coach',null), ['class' => 'form-control single-select' ]) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('Status', trans("comman.status"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::select('status', [''=>'select status','active'=>'Active','in_active'=>'Inactive'], Request::get('status',null), ['class' => 'form-control single-select' ]) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        {!! Html::decode(Form::label('Program', trans("comman.program"). ':', ['class' => 'control-label'])) !!}
                        {!! Form::select('program', [''=>'select program']+$programs, Request::get('program',null), ['class' => 'form-control single-select' ]) !!}
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="control-label">&nbsp;</label><br>
                        {!! Form::submit(trans('comman.search'), ['name' => 'search', 'class' => 'btn btn-primary btn-xs']) !!}
                        {!! link_to_route('report.coaching', trans('comman.cancel'), [], array('class' => 'btn btn-default btn-xs cancel')) !!}
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading bg-white">
            <h5 class="panel-title">{{ trans('comman.report') }}aa</h5>
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
                        <th rowspan="2" style="width: 30px;">{{ trans('comman.no').'.' }}</th>
                        <th rowspan="2" style="width: 150px;">{{ trans('comman.coach_name') }}</th>
                        <th rowspan="2">{{ trans('comman.no_of_clients') }}</th>
                        <th rowspan="2" style="width: 80px;">Exercises Submitted Yesterday</th>
                        <th rowspan="2">Exercises Returned Yesterday</th>

                        <th colspan="4" style="text-align: center;"> Total Number of exercises waiting for feedback</th>
                        <th rowspan="2">{{ trans('comman.total_session_bought') }}</th>
                        <th rowspan="2">{{ trans('comman.total_session_deliverd') }}</th>
                    </tr>
                    <tr>
                        <th>{{ trans('comman.last_14_days') }}</th>
                        <th>{{ trans('comman.last_14_21_days') }}</th>
                        <th>{{ trans('comman.last_21_days_ago') }}</th>
                        <th>{{ trans('comman.total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $counter = 0;?>
                    @if(isset($data) && count($data) > 0)
                        @foreach($data as $key=>$value)
                            <tr>
                                <?php
                                    $value['submit1'] = isset($value['submit1']) ? $value['submit1'] : '0';
                                    $value['submit2'] = isset($value['submit2']) ? $value['submit2'] : '0';
                                    $value['submit3'] = isset($value['submit3']) ? $value['submit3'] : '0';
                                ?>
                                <td> {{ ++$counter }}. </td>
                                <td> {{ $value['coach'] }} </td>
                                <td> {{ $value['total_clients'] }} </td>
                                <td> {{ isset($value['yesterday_modules'])?$value['yesterday_modules']:'0' }} </td>
                                <td> {{ isset($value['feedback_modules'])?$value['feedback_modules']:'0' }} </td>
                                <td> {{ $value['submit1'] }} </td>
                                <td> {{ $value['submit2'] }} </td>
                                <td> {{ $value['submit3'] }} </td>
                                <td> {{ $value['submit1']+$value['submit2']+$value['submit3'] }} </td>
                                <td> {{ isset($value['uncomplete'])?$value['uncomplete']:'0' }} </td>
                                <td> {{ isset($value['complete'])?$value['complete']:'0' }} </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="12" class="text-center">{{ trans('comman.no_data_found') }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection