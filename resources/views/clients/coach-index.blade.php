@extends($theme)
@section('title', $title)
@section('content')
<style>
.table-bordered > thead > tr {
    /* background: #BBDDF7; */
    background: #E3F3FB;
}
</style>
<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">{{ trans('comman.clients') }}</h3>
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
        <div class="panel panel-default">
        <div class="panel-body">
            {{ Form::open(array('route' => 'clients.index','method'=>'GET','class'=>'form-filter','role'=>"form")) }}
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Html::decode(Form::label('name_or_email', trans("comman.name_or_email"). ':', ['class' => 'control-label'])) !!}
                            {!! Form::text('name_or_email', Request::get('name_or_email',null), ['class' => 'form-control ','placeholder'=> trans("comman.name_or_email") ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Html::decode(Form::label('program_id', trans("comman.program"). ':', ['class' => 'control-label'])) !!}
                            {!! Form::select('program_id', ["" => trans("comman.select_program")] + $programs, Request::get('program_id',null), ['class' => 'form-control single-select' ]) !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Html::decode(Form::label('module_completed', trans("comman.module_completed"). ':', ['class' => 'control-label'])) !!}
                            {!! Form::select('module_completed', $modules, Request::get('module_completed',null), ['class' => 'form-control single-select','placeholder'=> trans("comman.module_completed") ]) !!}
                        </div>
                    </div>
                      <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">&nbsp;</label><br>
                            {!! Form::submit(trans('comman.search'), ['name' => 'search','class' => 'btn btn-primary btn-xs']) !!}
                              {!! link_to_route('clients.index', trans('comman.cancel'), [], array('class' => 'btn btn-default btn-xs cancel')) !!}
                        </div>
                    </div>
                </div>

            </div>
            {!! Form::close() !!}
        </div>
        <table class="table-bordered table-hover no-footer">
                <thead>
                    <tr>
                        <th style="width: 40px; height:50px;">{{ trans('comman.no') }}</th>
                        <th> Client ID  </th>
                        <th> {{ trans('comman.name') }} </th>
                        <th> {{ trans('comman.program') }}   </th>
                        <th> Progress </th>
                        <th> {{ trans('comman.credits') }}  </th>
                        <th> Life Story</th>
                        <th> Contact </th>

                    </tr>
                </thead>
                <tbody >
                    @if(isset($clients) && count($clients) > 0)
                        @php
                            $counter = 0;
                        @endphp
                        @foreach($clients as $client)
                            @php
                                $counter++;
                            @endphp
                            <tr>
                                <td>{{ $counter }}.</td>
                                <td>{{ link_to_route('client.detail', $client->user->id, ['client_id' => Crypt::encryptString($client->user_id)])  }}</td>
                                <td>{{  $client->user->name  }}</td>
                                <td>{{ isset($client->program) ? $client->program->program_name : '' }}</td>
                                <td>
                                    @if(count($client->user->latest_module) > 0)
                                        @php
                                            $latest_module = $client->user->latest_module->first();
                                        @endphp
                                        {{ $latest_module->module_no }}.{{ $latest_module->module_title }}
                                    @else
                                       -
                                    @endif
                                </td>
                                <td class="text-center">
                                {!! Html::decode(link_to_route('coach.credithistory', ($client->credits ? : '0'), ['id' => Crypt::encryptString($client->user_id)], ['class' => 'label bg-primary '])) !!}
                                {{--    {!! Html::decode(link_to_route('client.myCredits', ($client->credits ? : '0'), ['cid' => Crypt::encryptString($client->user_id)], ['class' => 'label bg-primary '])) !!} --}}
                                </td>
                                <td class="text-center">
                                    {!! Html::decode(link_to_route('mylifestory.show', 'Life Story', array(Crypt::encryptString($client->user_id)) , ['class' => ''])) !!}
                                </td>
                                <td>
                                    {!! Html::decode(link_to_route('messages.admindata', 'Contact <i class="fa fa-comments" aria-hidden="true"></i>
                                    ', array('role' => 'coach', 'id' => Crypt::encryptString($client->user_id)), ['class' => 'btn btn-primary '])) !!}
                                </td>

                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8"> {{ trans('comman.no_data_found') }} </td>
                        </tr>
                    @endif
                </tbody>
            </table>
    <br>
              {{ $clients->appends(request()->except('page'))->links() }}

        </div>

    </div>
</div>
@endsection