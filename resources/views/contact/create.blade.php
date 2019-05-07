@extends($theme)
@section('title', $title)
@section('content')
    <div class="panel panel-white">
        <div class="panel-body">
            @if(isset($page))
                @if(Auth::user()->user_type == 'user' && Auth::user()->hasAccess('pages.update'))
                    <div class="pull-right">{!! Html::decode(link_to_route('pages.edit', '<i class="icon-pencil7"></i>', array($page->id,'_url'=> Request::path()))) !!}</div>
                @endif
                {!!html_entity_decode($page->content)!!}
             @else
                Static page contact-us not found
        @endif
        </div>
    </div>
    <div class="row">
        {!! Form::open(array('route' => 'contact.store','class'=>'form-horizontal','role'=>"form")) !!}
            <div class="col-md-12">
                 <div class="panel panel-white">
                        <div class="panel-heading">
                            <div class="col-sm-12"><h5 class="panel-title">Contact us</h5></div>
                            <div class="clearfix"></div>
                        </div>
                         <div class="panel-body">
                                <div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
                                        {!! Html::decode(Form::label('namee', trans("comman.name"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                                        <div class="col-sm-4">
                                            {!! Form::text('name', null, ['class' => 'form-control','placeholder' => trans("comman.name")]) !!}
                                            {!! ($errors->has('name') ? $errors->first('name', '<p class="text-danger">:message</p>') : '') !!}
                                        </div>
                                </div>
                                <div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
                                        {!! Html::decode(Form::label('namee', trans("comman.email"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                                        <div class="col-sm-4">
                                            {!! Form::text('email', null, ['class' => 'form-control','placeholder' => trans("comman.email")]) !!}
                                            {!! ($errors->has('email') ? $errors->first('email', '<p class="text-danger">:message</p>') : '') !!}
                                        </div>
                                </div>
                                <div class="form-group {{ $errors->has('subject') ? 'has-error' : ''}}">
                                        {!! Html::decode(Form::label('namee', trans("comman.subject"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                                        <div class="col-sm-4">
                                            {!! Form::text('subject', null, ['class' => 'form-control','placeholder' => trans("comman.subject")]) !!}
                                            {!! ($errors->has('subject') ? $errors->first('subject', '<p class="text-danger">:message</p>') : '') !!}
                                        </div>
                                </div>
                                <div class="form-group {{ $errors->has('message') ? 'has-error' : ''}}">
                                        {!! Html::decode(Form::label('namee', trans("comman.your_message"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
                                        <div class="col-sm-4">
                                            {!! Form::textarea('message', null, ['class' => 'form-control','placeholder'=> trans('comman.your_message'), 'rows' => '5']) !!}
                                            {!! ($errors->has('message') ? $errors->first('message', '<p class="text-danger">:message</p>') : '') !!}
                                        </div>
                                </div>
                         </div>
                         <div class="form-group">
                            <div class="col-sm-6 text-right">
                                {!! Form::submit("Submit", ['name' => 'save','class' => 'btn btn-primary']) !!}
                                {!! link_to(URL::full(), "Cancel",array('class' => 'btn btn-warning cancel')) !!}
                            </div>
                         </div>
                </div>
            </div>
        {!! Form::close() !!}
    </div>
@endsection