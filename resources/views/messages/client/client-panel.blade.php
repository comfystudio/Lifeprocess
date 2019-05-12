@extends($theme)
@section('title', $title)
<style>
    .send
    {
        background-color: #82cd49;
        border: 0 none;
        border-radius: 0;
        color: #fff;
        padding: 10px 18px;
    }
    .secDiv{
        /*margin-top: 10px;
  */      padding:0px;
    }
    .sample_head{
        padding: 20px;
    }
    .biography{
        margin: 30px;
        padding: 15px;
    }
    #message.secDiv {
      padding: 0px;
    }
</style>
@section('content')

{{-- <div id ="message">
    <ul class="nav nav-tabs">

        <li  class="active">
            <a href="#coach" data-toggle="tab">Coach</a>
        </li>


        <li class="">
            <a href="{{ route('messages.client-admin') }}">Admin</a>

        </li>

    </ul>
</div> --}}

<div class="tab-content">
    <div class="tab-pane fade in active" id="coach">
        <div class="tab-title">
            <h1 class="no-margin">Your conversation with {{$client->coach->user->first_name}}</h1>
        </div>
      {{--   <div class="tab-title">
            <h1 class="no-margin">My Coach</h1>
        </div> --}}
        {{-- <div id ="message" class="firstDiv">

            <div class="client-msg">
             <div class="row no-margin left">
                <div class="msg center-block text-center">
                    @if(isset($client->coach->user->image) && !empty($client->coach->user->image))
                    {{Html::image(AppHelper::path('uploads/user/')->size('100x100')->getImageUrl($client->coach->user->image),'User Photo',array("class"=>"img-circle center-block"))}}
                    @else
                    {{Html::image(AppHelper::size('36x36')->getDefaultImage(),'User Photo',array("class"=>"img-circle staff","height"=>'100px','width'=>'100px'))}}
                    @endif

                    <p class="center-block">{{$client->coach->user->name}}</p>
                 {{--    <p class="center-block">Coach No. {{$client->coach->user->id}}</p>
                    <p class="center-block">{{$client->coach->user->email}}</p>

                    <p class="center-block biography">{{$client->coach->biography}}</p> --}}
               {{--  </div>
            </div>
        </div>
        </div> --}}

        {{-- <div>
            <h5 class="no-margin"><b>Contact My Coach</b></h5>
        </div> --}}
        </div>
        <div id ="message" class="">
        <div class="client-msg">
        <div class="">
            <div class="msg" id="chat" style="">
                <div class="row no-margin left">
                {{-- welcome message from coach --}}
                @if(!empty($client->coach->user->welcome_message))
                    @if(isset($client->coach->user->image) && !empty($client->coach->user->image))
                        {{Html::image(AppHelper::path('uploads/user/')->size('36x36')->getImageUrl($client->coach->user->image),'User Photo',array("class"=>"pull-left logo"))}}
                    @else
                        {{Html::image(AppHelper::size('36x36')->getDefaultImage(),'User Photo',array("class"=>"pull-left logo staff","height"=>'40px','width'=>'40px'))}}
                    @endif
                    <div class="col-md-8 col-sm-10 col-xs-8 text">
                            <p>
                                @php
                                    Emojione\Emojione::$imagePathPNG = asset("/images/emoji")."/";
                                    // defaults to jsdelivr's free CDN
                                    $textEmoji = Emojione\Emojione::unicodeToImage($client->coach->user->welcome_message);
                                @endphp
                                @if($textEmoji)
                                    {!! $textEmoji !!}
                                @else
                                    {{$client->coach->user->welcome_message}}
                                @endif
                            </p>
                        </div>
                </div>
                @endif

                @foreach($messages as $message)
                @if($message->receive_user_id == $client->coach->user->id || $message->create_user_id == $client->coach->user->id)
                @if($message->create_user_id != Auth::id())
                <div class="row no-margin left">
                @if(isset($client->coach->user->image) && !empty($client->coach->user->image))
                    {{Html::image(AppHelper::path('uploads/user/')->size('36x36')->getImageUrl($client->coach->user->image),'User Photo',array("class"=>"pull-left logo"))}}
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
                    {{-- <div class="col-md-2 col-sm-2 col-xs-2 logo">
                        @if(isset($client->coach->user->image) && !empty($client->coach->user->image))
                        {{Html::image(AppHelper::path('uploads/user/')->size('36x36')->getImageUrl($client->coach->user->image),'User Photo',array("class"=>""))}}
                        @else
                        {{Html::image(AppHelper::size('36x36')->getDefaultImage(),'User Photo',array("class"=>" staff","height"=>'50px','width'=>'50px'))}}
                        @endif
                    </div> --}}
                    <div class="col-md-8 col-sm-10 col-xs-8 no-padding timing">
                        <p class="date-time">
                            @if(isset($message->attachment))
                            <a href="{{url('uploads/messages/attachment/'.$message->attachment->attachment)}}" download>{{$message->attachment->attachment}}</a>
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
                    {{-- <div class="col-md-2 col-sm-2 col-xs-2 logo"> --}}
                        @if(isset(Auth::user()->image) && !empty(Auth::user()->image))
                        {{Html::image(AppHelper::path('uploads/user/')->size('36x36')->getImageUrl(Auth::user()->image),'User Photo',array("class"=>"pull-right logo"))}}
                        @else
                        {{Html::image(AppHelper::size('36x36')->getDefaultImage(),'User Photo',array("class"=>"pull-right logo staff","height"=>'40px','width'=>'40px'))}}
                        @endif
                    {{-- </div> --}}
                    <div class="col-md-9 col-sm-9 col-xs-9 text pull-right">
                        <p>
                            @php
                                Emojione\Emojione::$imagePathPNG = asset("/images/emoji")."/";
                                // defaults to jsdelivr's free CDN
                                $textEmoji = Emojione\Emojione::unicodeToImage($message ->messages);
                            @endphp
                            @if($textEmoji)
                                {!! $textEmoji !!}
                            @else
                                {{$message->messages}}
                            @endif
                        </p>
                    </div>
                    <div class="col-md-9 col-sm-9 col-xs-9  pull-right">
                        <p class="">

                            @if(isset($message->attachment))
                            Attachment:
                            <a href="{{url('uploads/messages/attachment/'.$message->attachment->attachment)}}" download>{{$message->attachment->attachment}}</a>
                            @endif
                        </p>
                    </div>
<div class="col-md-9 col-sm-9 col-xs-9 pull-right">
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
                {!! Form::open(array('route' =>  ['messages.save', 'id' =>Crypt::encryptString($client->coach->user->id)],'class'=>'send-msg','enctype'=>'multipart/form-data','role'=>"form",'files' => true)) !!}

                <div class="message-compose emoji-picker-container {{ $errors->has('messages') ? 'has-error' : ''}}">
                    {!! Form::textarea('messages', null, ['placeholder' => trans("comman.type_message"),'rows' => '5','data-emojiable' => 'true']) !!}
                    {!! $errors->first('messages', '<p class="help-block">:message</p>') !!}
                </div>
                <div class="{{ $errors->has('attachment') ? 'has-error' : ''}}">
                    <div class="">
                        {{-- <img src="{{ asset('themes/limitless/images/client-view/attach.png')}}" width="24px" class="file-icon" /> --}}
                        <img src="{{ asset('themes/limitless/images/client-view/attach.png')}}" width="24px" class="file-icon" />

                        {!! Form::file('attachment', ['class' => 'inputfile hide','id' => 'upload-coach','type' => "file",'onchange' => 'readURL(this)']) !!}
                        {{-- <input type="file" id="upload-coach" name="upload-coach" class="inputfile hide" onchange="readURL(this)" /> --}}
                        {{-- <img src="" id="attachment_section" class="uploaded_img"> --}}
                        {!! $errors->first('attachment', '<p class="help-block">:message</p>') !!}
                    </div>
                </div>
                {!! Form::submit("Send", ['name' => 'save','class' => 'send pull-right']) !!}
                {!! Form::close() !!}
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