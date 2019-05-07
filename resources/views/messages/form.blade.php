<?php
//echo $id;exit;
use App\Models\Message;
$updated = Message::where('receive_user_id', '=', Auth::id())->where('create_user_id', '=', Crypt::decryptString($id))->update(['is_read' => 1]);
?>

<div class="tab-pane fade fade active in" id="{{$id}}">

    <div class="message-listing">
        <div class="row no-margin left">
            <div class="msg" id="chat" style="">
            @foreach($messages as $message)
                @if($message->receive_user_id == Crypt::decryptString($id) || $message->create_user_id == Crypt::decryptString($id))
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
                            {{-- <div class="media-right" style="width:20%">
                                {{$user_name}}
                            </div> --}}
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
    <div class="row new-msg no-margin">
        <div class="col-md-offset-1 col-md-10 col-sm-12 col-xs-12">
        {!! Form::open(array('route' =>  ['messages.save', 'id' => $id],'class'=>'form-horizontal send-msg','role'=>"form",'files' => true)) !!}
            <div class="form-group no-margin  {{ $errors->has('messages') ? 'has-error' : ''}}">
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
                    {{-- <img src="" id="attachment_section" class="uploaded_img"> --}}
                </div>
                <div class="col-sm-6 text-right">
                    {!! Form::submit("Send", ['name' => 'save','class' => 'btn btn-primary pull-right send']) !!}
                </div>
            </div>
        {!! Form::close() !!}
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