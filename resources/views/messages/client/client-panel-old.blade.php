@extends($theme)
@section('title', $title)
<style>
.media {
     margin-top: 0px !important;
}
.nav-tabs-vertical .nav-tabs {
    width: 200px !important;
}
.chat-list .media-left ,.chat-list .media-right {
        font-weight: bold !important;
        padding-top:10px;
}
</style>
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="tabbable">
                <ul class="nav nav-tabs nav-tabs-highlight">
                    <li class="">{!! link_to_route('messages.index','Admin') !!}</li>
                    <li class="active">
                        <a href="#coach" data-toggle="tab" aria-expanded="true">Coach</a>
                    </li>
                </ul>
                <div class="tab-content">
                        <div class="tab-pane fade fade active in" id="coach">
                                    <div class="tabbable nav-tabs-vertical nav-tabs-left">
                                         <ul class="nav nav-tabs nav-tabs-highlight">
                                                        <li class="active">
                                                                <a href="">{{$client->coach->user->name}}</a>
                                                        </li>
                                         </ul>
                                        <div class="tab-content">
                                        <div class="tab-pane fade fade active in" id="{{$client->coach->user->id}}">
                                            {!! Form::open(array('route' =>  ['messages.save', 'id' =>Crypt::encryptString($client->coach->user->id)],'class'=>'form-horizontal','role'=>"form")) !!}
                                               <div class="form-group {{ $errors->has('messages') ? 'has-error' : ''}}">
                                                    {!! Html::decode(Form::label('message','Send a Reply', ['class' => 'col-sm-12 control-label'])) !!}
                                                    <div class="col-sm-12">
                                                        {!! Form::textarea('messages', null, ['class' => 'form-control','placeholder' => trans("comman.message"),'rows' => '5']) !!}
                                                        {!! $errors->first('messages', '<p class="help-block">:message</p>') !!}
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                        <div class="col-sm-12 text-right">
                                                            {!! Form::submit("Send", ['name' => 'save','class' => 'btn btn-primary']) !!}
                                                        </div>
                                                </div>
                                                {!! Form::close() !!}
                                        </div>
                                        </div>
                                   </div>
                        </div>
                </div>
    </div>
</div>
</div>
@endsection