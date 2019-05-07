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
            <ul class="nav nav-tabs nav-tabs-highlight no-margin">
                <li>
                    {!! link_to_route('messages.index','Coaches') !!}</li>
                <li class="">
                    {!! link_to_route('messages.getrole','Clients', ['role'=>'client']) !!}
                </li>
                <li class="active">
                    <a href="#all-agent" data-toggle="tab" aria-expanded="true">Client Managers</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade fade active in" id="all-agent">
                    <div class="tabbable nav-tabs-vertical nav-tabs-left">
                        <ul class="nav nav-tabs nav-tabs-highlight">
                            <li>
                                {{Form::open(['route' => ['messages.getrole','agent'],'method' => "GET",'style'=>"margin-bottom:0px;"])}}
                                <div class="inner-addon right-addon @if(count($all_agent)==0) message-search-width @endif ">
                                    <i class="fa fa-search fa-rotate-90"></i>
                                    {{Form::text('search',request()->get('search',null),['id'=>"search_coach",'class'=>"form-control",'placeholder'=>"Search Client Managers"])}}
                                </div>
                                {{Form::close()}}
                            </li>
                            @php
                                $count=0;
                                $agent_name = '';
                            @endphp
                            @foreach($all_agent as $agent)
                            @if(App::make("App\Http\Controllers\MessageController")->isOnline($agent['id']))
                                @php $online='<i class="fa fa-circle"></i>';@endphp
                            @else
                                    @php $online='';@endphp
                            @endif
                            {{-- @if($agent['is_login']=='1')
                                @php $online='<i class="fa fa-circle"></i>';@endphp
                            @else
                                @php $online='';@endphp
                            @endif --}}
                                @if($count++ == 0)
                                    <li class="active">
                                    {!! Html::decode(link_to_route('messages.admindata', $online." ".$agent['name'], ['role'=>'agent','id' =>Crypt::encryptString($agent['id'])])) !!}
                                    @php $agent_name = $agent['name']; @endphp
                                    </li>
                                @else
                                    <li class="">
                                    {!! Html::decode(link_to_route('messages.admindata', $online." ".$agent['name'], ['role'=>'agent','id' =>Crypt::encryptString($agent['id'])])) !!}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        @if(isset($all_agent) && count($all_agent) > 0 )
                            <div class="tab-content no-padding">
                                <div class="panel panel-default no-border">
                                    <div class="panel-heading no-border">
                                        <h5 class="panel-title">
                                            Your conversation with {{$all_agent[0]['name']}}
                                        </h5>
                                    </div>
                                    <div class="panel-body no-padding">
                                    <div class="tab-pane fade fade active in" id="{{$all_agent[0]['id']}}">
                                        <div class="message-listing">
                                        <div class="row no-margin left">
                                            <div class="msg">
                                            @foreach($messages as $message)
                                                @if($message->receive_user_id == $all_agent[0]['id'] || $message->create_user_id == $all_agent[0]['id'])
                                                    {{-- <li class="media date-step content-divider"><span></span></li> --}}
                                                    @if($message->create_user_id != Auth::id())
                                                        <div class="row no-margin left">
                                                            @if(isset($all_agent[0]['image']) && !empty($all_agent[0]['image']))
                                                                    {{Html::image(AppHelper::path('uploads/user/')->size('36x36')->getImageUrl($all_agent[0]['image']),'User Photo',array("class"=>"pull-left logo"))}}
                                                            @else
                                                                {{Html::image(AppHelper::size('36x36')->getDefaultImage(),'User Photo',array("class"=>"pull-left logo staff","height"=>'40px','width'=>'40px'))}}
                                                            @endif
                                                            <div class="col-md-8 col-sm-10 col-xs-8 text">
                                                                <p>
                                                                @php
                                                                Emojione\Emojione::$imagePathPNG = asset("/images/emoji")."/";
                                                                // defaults to jsdelivr's free CDN
                                                                $textEmoji = Emojione\Emojione::unicodeToImage($message ->messages);
                                                                @endphp
                                                                @if($textEmoji)
                                                                    {!! $textEmoji !!}
                                                                @else
                                                                    {{$message ->messages}}
                                                                @endif
                                                                </p>
                                                            </div>
                                                            <div class="col-md-8 col-sm-10 col-xs-8 timing no-padding">
                                                                <p class="date-time">
                                                                    @if(isset($message->attachment) && !empty($message->attachment->attachment))
                                                                        <strong>Attachment :<strong> <a href="{{url('uploads/messages/attachment/'.$message->attachment->attachment)}}" download>{{$message->attachment->attachment}}</a>
                                                                    @endif
                                                                </p>
                                                                <p class="date-time">
                                                                    {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $message->created_at)->format('l, d F Y, ')}}
                                                                    @if($timezone != null)
                                                                        {{$message->created_at->timezone($timezone)->format('H:i:s')}}
                                                                    @else
                                                                        {{$message->created_at->format('H:i:s')}}
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="row no-margin right">
                                                            @if(isset(Auth::user()->image) && !empty(Auth::user()->image))
                                                                {{Html::image(AppHelper::path('uploads/user/')->size('36x36')->getImageUrl(Auth::user()->image),'User Photo',array("class"=>"pull-right logo"))}}
                                                            @else
                                                                {{Html::image(AppHelper::size('36x36')->getDefaultImage(),'User Photo',array("class"=>"pull-right logo staff","height"=>'40px','width'=>'40px'))}}
                                                            @endif
                                                            <div class="col-md-8 col-sm-10 col-xs-8 text pull-right">
                                                                <p>
                                                                @php
                                                                Emojione\Emojione::$imagePathPNG = asset("/images/emoji")."/";
                                                                // defaults to jsdelivr's free CDN
                                                                $textEmoji = Emojione\Emojione::unicodeToImage($message ->messages);
                                                                @endphp
                                                                @if($textEmoji)
                                                                    {!! $textEmoji !!}
                                                                @else
                                                                    {{$message ->messages}}
                                                                @endif
                                                                </p>
                                                            </div>
                                                            <div class="col-md-8 col-sm-10 col-xs-8 no-padding pull-right timing">
                                                                <p class="date-time">
                                                                    @if(isset($message->attachment) && !empty($message->attachment->attachment))
                                                                        <strong>Attachment :<strong> <a href="{{url('uploads/messages/attachment/'.$message->attachment->attachment)}}" download>{{$message->attachment->attachment}}</a>
                                                                    @endif
                                                                </p>
                                                                <p class="date-time">
                                                                    {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $message->created_at)->format('l, d F Y, ')}}
                                                                    @if($timezone != null)
                                                                        {{$message->created_at->timezone($timezone)->format('H:i:s')}}
                                                                    @else
                                                                        {{$message->created_at->format('H:i:s')}}
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </div>
                                        </div>
                                        <div class="row new-msg no-margin">
                                            <div class="col-md-offset-1 col-md-10 col-sm-12 col-xs-12">
                                        {!! Form::open(array('route' =>  ['messages.save', 'id' => Crypt::encryptString($all_agent[0]['id'])],'class'=>'form-horizontal send-msg','role'=>"form",'files' => true)) !!}
                                            <div class="form-group no-margin {{ $errors->has('messages') ? 'has-error' : ''}}">
                                                <h4>Send a reply</h4>
                                                <div class="emoji-picker-container">
                                                    {!! Form::textarea('messages', null, ['class' => 'form-control','placeholder' => trans("comman.message"),'rows' => '5','data-emojiable' => 'true']) !!}
                                                    {!! $errors->first('messages', '<p class="help-block">:message</p>') !!}
                                                </div>
                                            </div>
                                            <div class="form-group {{ $errors->has('attachment') ? 'has-error' : ''}}">
                                                <div class="col-sm-6">
                                                    <img src="{{ asset('themes/limitless/images/client-view/attach.png')}}" width="24px" class="file-icon" />
                                                    {!! Form::file('attachment', ['class' => 'form-control inputfile hide','type' => "file",'id'=>"attachment",'onchange' => 'readURL(this)']) !!}
                                                    {!! $errors->first('attachment', '<p class="help-block">:message</p>') !!}
                                                    {{-- <img src="" id="attachment_section" class="uploaded_img"> --}}
                                                </div>
                                                <div class="col-sm-12 text-right">
                                                    {!! Form::submit("Send", ['name' => 'save','class' => 'btn btn-primary pull-right send']) !!}
                                                </div>
                                            </div>

                                        {!! Form::close() !!}
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="row" style="min-height: 350px;">
                                <div class="col-md-12 text-center">
                                    <h1>No Client Managers Found</h1>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
$(function() {
        // Initializes and creates emoji set from sprite sheet
        window.emojiPicker = new EmojiPicker({
          emojiable_selector: '[data-emojiable=true]',
          assetsPath: '{{asset('themes/limitless/images/emoji/')}}',
          popupButtonClasses: 'fa fa-smile-o'
        });
        window.emojiPicker.discover();
      });
    @if($errors->has('messages'))
        $(document).ready(function() {
            $('html, body').animate({
                scrollTop: $(".message-compose").offset().top
            }, 500);
        });
    @endif
</script>
@endpush
@endsection