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
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade fade active in" id="all-coach">
                    <div class="tabbable nav-tabs-vertical nav-tabs-left">
                        <div class="tab-content no-padding">
                            <div class="tab-pane fade fade active in">
                                <div class="panel panel-white no-border">
                                    <div class="panel-heading no-border">
                                        <h5 class="panel-title">Conversation</h5>
                                    </div>
                                    <div class="message-listing">
                                        <div class="row no-margin left">
                                            <div class="msg" id="chat" style="">
                                            <?php //dd($messages);?>
                                            @foreach($messages as $message)
                                                @if($message->receive_user_id == $user['id'] || $message->create_user_id == $user['id'])
                                                    {{-- <li class="media date-step content-divider"><span></span></li> --}}
                                                    @if($message->create_user_id != Auth::id())
                                                        <div class="row no-margin left">
                                                            {{--@if(isset($user_image) && !empty($user_image))--}}
                                                            <div class="col-md-1 col-sm-12 col-xs-12">
                                                                @if(isset($message->userCreator->image) && !empty($message->userCreator->image))
                                                                    {{Html::image(AppHelper::path('uploads/user/')->size('64x64')->getImageUrl($message->userCreator->image),'User Photo',array("class"=>"pull-left logo"))}}
                                                                @else
                                                                    {{Html::image(AppHelper::size('64x64')->getDefaultImage(),'User Photo',array("class"=>"pull-left logo staff","height"=>'40px','width'=>'40px'))}}
                                                                @endif
                                                                <br/>
                                                                <br/>
                                                                <br/>
                                                                {{$message->userCreator->name}}

                                                            </div>

                                                            <div class="col-md-10 col-sm-12 col-xs-12 text">
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

                                                            <div class="col-md-10 col-sm-12 col-xs-12 no-padding">
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
                                </div>
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