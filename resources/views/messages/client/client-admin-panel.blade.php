
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
</style>
@section('content')

<div id ="message">
    <ul class="nav nav-tabs">

        <li  class="">
         <a href="{{ route('messages.index') }}">Coach</a>

        </li>

        <li class="active">
                    <a href="#admin" data-toggle="tab">Admin</a>

                      </li>
    </ul>
    <div class="tab-content">


    <div class="tab-pane fade in active" id="admin">
                        <div id="message">
                            <div class="tab-title">
                                <h1 class="no-margin">Your conversation with All Admin</h1>
                            </div>
                           <div class="client-msg">
        <div class="row no-margin left">
            <div class="msg">

                @foreach($messages as $message)

                    @if($message->create_user_id != Auth::id())

                        <div class="row no-margin left">
                            @if(isset($message->image) && !empty($message->image))
                                {{Html::image(AppHelper::path('uploads/user/')->size('36x36')->getImageUrl($message->image),'User Photo',array("class"=>"pull-left logo"))}}
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
                           {{--  <div class="col-md-2 col-sm-2 col-xs-2 logo">

                                @if(isset($message->image) && !empty($message->image))
                                        {{Html::image(AppHelper::path('uploads/user/')->size('36x36')->getImageUrl($message->image),'User Photo',array("class"=>""))}}
                                @else
                                    {{Html::image(AppHelper::size('36x36')->getDefaultImage(),'User Photo',array("class"=>" staff","height"=>'50px','width'=>'50px'))}}
                                @endif
                            </div> --}}
                            <div class="col-md-8 col-sm-10 col-xs-8 timing no-padding">
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
                @endforeach
            </div>
        </div>
        <div class="row new-msg no-margin">
            <div class="col-md-offset-1 col-md-10 col-sm-12 col-xs-12">
                {!! Form::open(array('route' =>  ['messages.adminclient-store'],'class'=>'send-msg','role'=>"form",'enctype'=>'multipart/form-data')) !!}
                    <h4>Send a reply</h4>
                   <div class="emoji-picker-container {{ $errors->has('messages') ? 'has-error' : ''}}">
                        {!! Form::textarea('messages', null, ['placeholder' => trans("comman.type_message"),'rows' => '5','data-emojiable' => 'true']) !!}
                        {!! $errors->first('messages', '<p class="help-block">:message</p>') !!}
                    </div>
                    <img src="{{ asset('themes/limitless/images/client-view/attach.png')}}" width="24px" class="file-icon" />

                    <input type="file" id="upload-coach" name="upload-coach" class="inputfile hide" onchange="readURL(this)" />
                    {{-- <img src="" id="upload-coach_section" class="uploaded_img"> --}}
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