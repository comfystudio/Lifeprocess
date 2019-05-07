@extends($theme)
@section('title', $title)
@section('content')
<div class="row">
    {!! Form::open(array('route' => ['scheduled-session-problem.store', 'scheduled_session_id' => Crypt::encryptString($scheduled_session_id)],'class'=>'form-horizontal','role'=>"form",'id'=>'submit_session_problem_form')) !!}
    <div class="col-md-12">
       <div class="panel panel-white">
            <div class="panel-heading">
                <div class="col-sm-9"><h5 class="panel-title">{{ $title }}</h5></div>
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                <div class="row">
                    {{-- {{ dump($scheduled_session) }} --}}
                    <div class="col-lg-6">
                        <div class="form-group {{ $errors->has('first_name') ? 'has-error' : ''}}">
                            {!! Html::decode(Form::label('', 'Coach name:', ['class' => 'col-sm-4 control-label '])) !!}
                            <div class="col-sm-8">
                                {!! Form::text('coach_name', $scheduled_session->coach_schedule->user->name, ['class' => 'form-control','placeholder'=> 'Coach name', 'id' => 'coach_name', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group {{ $errors->has('last_name') ? 'has-error' : ''}}">
                            {!! Html::decode(Form::label('', 'Session Time:', ['class' => 'col-sm-4 control-label '])) !!}
                            <div class="col-sm-8">
                                @php
                                    $timezone = 'UTC';
                                    if(isset($scheduled_session->client->user->timezone)) {
                                        $timezone = $scheduled_session->client->user->timezone;
                                    }
                                    $session_time = Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $scheduled_session->coach_schedule->start_datetime, $timezone)->format('m/d/Y H:i');
                                @endphp
                                {!! Form::text('session_time', $session_time, ['class' => 'form-control','placeholder'=>'Last name', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                    </div>            
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group {{ $errors->has('problem') ? 'has-error' : ''}}">
                            {!! Html::decode(Form::label('problem', 'Problem:<span class="has-stik">*</span>', ['class' => 'col-sm-4 control-label '])) !!}
                            <div class="col-sm-8">
                                {!! Form::select('problem', $problems, null, ['class' => 'form-control single-select','placeholder'=> 'Select problem', 'id' => 'problem','onchange' => 'other_problem_enable()']) !!}
                                {!! ($errors->has('problem') ? $errors->first('problem', '<p class="text-danger">:message</p>') : '') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6" id="other_problem" style="display: none;">
                        <div class="form-group {{ $errors->has('other') ? 'has-error' : ''}}">
                            {!! Html::decode(Form::label('other', 'Other:<span class="has-stik">*</span>', ['class' => 'col-sm-4 control-label '])) !!}
                            <div class="col-sm-8">
                                {!! Form::textarea('other', null, ['class' => 'form-control','placeholder'=> 'Other', 'id' => 'other', 'rows' => '5']) !!}
                                {!! ($errors->has('other') ? $errors->first('other', '<p class="text-danger">:message</p>') : '') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-6 text-right">
                    {!! Form::submit(trans('comman.submit'), ['name' => 'save','class' => 'btn btn-primary']) !!}
                    {!! link_to(URL::full(), trans('comman.cancel'),array('class' => 'btn btn-warning cancel')) !!}
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>
@push('scripts')
<script>
function other_problem_enable() {
    var problem = document.getElementById("problem").value;
    document.getElementById("other_problem").style.display = 'none';
    if(problem == 'Other')
    {
        document.getElementById("other_problem").style.display = 'block';
    }
}
</script>
@endpush
@endsection


