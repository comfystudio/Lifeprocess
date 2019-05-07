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

                    <li class="active">
                        <a href="#all-client" data-toggle="tab" aria-expanded="true">Clients</a>
                    </li>
                    @if(Auth::user()->user_type != "read-only-coach")
                        <li class="">{!! link_to_route('messages.index','Admin') !!}</li>
                        <li class="">
                            {!! link_to_route('messages.coach.system_alerts','System Alerts') !!}
                        </li>
                    @endif
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade fade active in" id="all-coach">
                        <div class="tabbable nav-tabs-vertical nav-tabs-left">
                            <ul class="nav nav-tabs nav-tabs-highlight">
                                <li>
                                    {{Form::open(['route' => ['messages.getrole','role' =>'coach-client'],'method' => "GET",'style'=>"margin-bottom:0px;"])}}
                                    <div class="inner-addon right-addon @if(count($users)==0)  message-search-width @endif">
                                        <i class="fa fa-search fa-rotate-90"></i>
                                        {{Form::text('search',request()->get('search',null),['id'=>"search_coach",'class'=>"form-control",'placeholder'=>"Search for a client",'style'=>"width:200px"])}}
                                    </div>
                                    {{Form::close()}}
                                </li>
                                @php  $count=0;  @endphp
                                @if(isset($users) && count($users) > 0)

                                @foreach($users as $user)
                                @if(App::make("App\Http\Controllers\MessageController")->isOnline($user['id']))
                                @php $online='<i class="fa fa-circle"></i>';@endphp
                            @else
                                    @php $online='';@endphp
                            @endif
                                    {{-- @if($user['is_login']=='1')
                                        @php $online='<i class="fa fa-circle"></i>';@endphp
                                    @else
                                        @php $online='';@endphp
                                    @endif --}}
                                    @if(Auth::user()->user_type != "read-only-coach")
                                        @if($count++ == 0)
                                            <li class="active">
                                                {!! Html::decode(link_to_route('messages.admindata', $online." ".$user['name'], ['role'=>'coach-client','id' =>Crypt::encryptString($user['id'])])) !!}
                                            </li>
                                        @else
                                            <li class="">
                                                {!! Html::decode(link_to_route('messages.admindata', $online." ".$user['name'], ['role'=>'coach-client','id' =>Crypt::encryptString($user['id'])])) !!}
                                            </li>
                                        @endif
                                    @else
                                        @if($count++ == 0)
                                            <li class="active">
                                                {!! Html::decode(link_to_route('messages.admindata', $online." ".$user['name'], ['role'=>'read-only-coach','id' =>Crypt::encryptString($user['id'])])) !!}
                                            </li>
                                        @else
                                            <li class="">
                                                {!! Html::decode(link_to_route('messages.admindata', $online." ".$user['name'], ['role'=>'read-only-coach','id' =>Crypt::encryptString($user['id'])])) !!}
                                            </li>
                                        @endif
                                    @endif

                                   {{--  @endif --}}
                                @endforeach
                                @endif
                            </ul>
                            <div class="tab-content no-padding">
                                @if(isset($users[0]['id']) && count($users) > 0)

                                <div class="tab-pane fade fade active in" id="{{ $users[0]['id'] }}">
                                    <div class="panel panel-white no-border">
                                        <div class="panel-heading no-border">
                                            <h5 class="panel-title">Your Conversation with {{ $users[0]['name'] }}</h5>
                                        </div>
                                        <div class="message-listing">
                                            <div class="row no-margin left">
                                                <div class="msg" id="chat" style="">
                                                @foreach($messages as $message)
                                                @if($message->receive_user_id == $users[0]['id'] || $message->create_user_id == $users[0]['id'])
                                                {{-- <li class="media date-step content-divider"><span></span></li> --}}
                                                @if($message->create_user_id != Auth::id())
                                                <div class="row no-margin left">
                                                    @if(isset($user_image) && !empty($user_image))
                                                    {{Html::image(AppHelper::path('uploads/user/')->size('36x36')->getImageUrl($user_image),'User Photo',array("class"=>"pull-left logo"))}}
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
                                                           @if(isset($message->attachment)  && !empty($message->attachment->attachment))
                                                            <strong>Attachment :</strong>
                                                            <a href="{{url('uploads/messages/attachment/'.$message->attachment->attachment)}}" download>
                                                                {{$message->attachment->attachment}}
                                                            </a>
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
                                                            @if(isset($message->attachment)  && !empty($message->attachment->attachment))
                                                            <strong>Attachment :</strong>
                                                            <a href="{{url('uploads/messages/attachment/'.$message->attachment->attachment)}}" download>
                                                                {{$message->attachment->attachment}}
                                                            </a>
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

                                        @if(Auth::user()->user_type != "read-only-coach")
                                            <div class="row new-msg no-margin">
                                                <div class="col-md-offset-1 col-md-10 col-sm-12 col-xs-12">
                                                {!! Form::open(array('route' =>  ['messages.save', 'id' => Crypt::encryptString($users[0]['id'])],'class'=>'form-horizontal send-msg','role'=>"form",'files' => true,'enctype'=>'multipart/form-data')) !!}
                                                <div class="form-group no-margin {{ $errors->has('messages') ? 'has-error' : ''}}">
                                                    <h4>Send a reply</h4>
                                                    <div class="emoji-picker-container">
                                                        {!! Form::textarea('messages', null, ['class' => 'form-control','placeholder' => trans("comman.message"),'rows' => '5','data-emojiable' => 'true','placeholder'=>"Type your message here..."]) !!}
                                                        {!! $errors->first('messages', '<p class="help-block">:message</p>') !!}
                                                    </div>
                                                </div>
                                                <div class="form-group {{ $errors->has('attachment') ? 'has-error' : ''}}">
                                                    <div class="col-sm-6">
                                                        {{-- <label for="attachment" id='attachment-label'><i class="icon-attachment"></i></label> --}}
                                                        <img src="{{ asset('themes/limitless/images/client-view/attach.png')}}" width="24px" class="file-icon" />
                                                        {!! Form::file('attachment', ['class' => 'form-control inputfile hide','type' => "file",'id'=>"attachment",'onchange' => 'readURL(this)']) !!}
                                                        {!! $errors->first('attachment', '<p class="help-block">:message</p>') !!}

                                                        {{-- <img src="{{ asset('themes/limitless/images/client-view/attach.png')}}" width="24px" class="file-icon" />
                                                        {!! Form::file('attachment', ['class' => 'inputfile hide','id' => 'upload-coach','type' => "file",'onchange' => 'readURL(this)']) !!} --}}
                                                        {{-- <img src="" id="attachment_section" class="uploaded_img"> --}}
                                                    </div>
                                                    <div class="col-sm-6 text-right">
                                                        {!! Form::submit("Send", ['name' => 'save','class' => 'btn btn-primary pull-right send']) !!}
                                                    </div>
                                                </div>
                                                {!! Form::close() !!}
                                            </div>
                                        @endif
                                    </div>
                                    @endif
                                        </div>

                                          </div>

                                   </div>
                        </div>
                </div>
    </div>
</div>
</div>
@push('scripts')
<script>
$(function() {
      var objDiv = document.getElementById("chat");
        objDiv.scrollTop = objDiv.scrollHeight;
        // Initializes and creates emoji set from sprite sheet
        window.emojiPicker = new EmojiPicker({
          emojiable_selector: '[data-emojiable=true]',
          assetsPath: '{{asset('themes/limitless/images/emoji/')}}',
          popupButtonClasses: 'fa fa-smile-o'
        });
        window.emojiPicker.discover();
      });
</script>
@endpush
@endsection