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
    <div class="tab-pane fade in active" id="coach">
        <div class="tab-title">
            <h1 class="no-margin">Your conversation with Admin</h1>
        </div>
    </div>
    <div class="client-msg">
        <div class="row no-margin left">
            <div class="msg" id="chat">
                @if(!empty($default_message->program->default_message))
                    <div class="row no-margin left">
                        <div class="col-md-10 col-sm-10 col-xs-10 text">
                                <p>
                                    {{$default_message->program->default_message}}
                                </p>
                        </div>
                    </div>
                @endif
                @foreach($messages as $message)
                    @if($message->create_user_id != Auth::id())
                        <div class="row no-margin left">
                            <div class="col-md-10 col-sm-10 col-xs-10 text">
                                    <p>
                                        {{$message ->messages}}
                                    </p>
                            </div>
                            <div class="col-md-10 col-sm-10 col-xs-10">
     @if(isset($message->attachment)  && !empty($message->attachment->attachment))

                                                            <div class="col-md-6 col-sm-10 col-xs-10 " style="float:right; margin-top: 1px;">
                             <p class="">
                                                            <strong>Attachment :</strong>
                                                            <a href="{{url('uploads/messages/attachment/'.$message->attachment->attachment)}}" download>
                                                                {{$message->attachment->attachment}}
                                                            </a>
                                                              </p>
                                                        </div>
                                                            @endif
                            </div>
                            <div class="col-md-10 col-sm-10 col-xs-12 no-padding">
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
                            <div class="col-md-2 col-sm-2 col-xs-2 logo">
                                @if(isset(Auth::user()->image) && !empty(Auth::user()->image))
                                        {{Html::image(AppHelper::path('uploads/user/')->size('36x36')->getImageUrl(Auth::user()->image),'User Photo',array("class"=>"img-circle"))}}
                                @else
                                    <img src="{{ asset('themes/limitless/images/client-view/client-logo.png')}}" class="img-responsive img-circle pull-right">
                                @endif
                            </div>
                            <div class="col-md-10 col-sm-10 col-xs-10 text">
                                <p>
                                    {{$message ->messages}}
                                </p>

                            </div>


                                                           @if(isset($message->attachment)  && !empty($message->attachment->attachment))

                                                            <div class="col-md-6 col-sm-10 col-xs-10 " style="float:right; margin-top: 1px;">
                             <p class="">
                                                            <strong>Attachment :</strong>
                                                            <a href="{{url('uploads/messages/attachment/'.$message->attachment->attachment)}}" download>
                                                                {{$message->attachment->attachment}}
                                                            </a>
                                                              </p>
                                                        </div>
                                                            @endif

                            <div class="col-md-offset-2 col-sm-offset-2  col-xs-offset-2 col-md-10 col-sm-10 col-xs-10 no-padding">
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
        <div class="row new-msg no-margin" >
            <div class="col-md-offset-1 col-md-10 col-sm-12 col-xs-12">
                {!! Form::open(array('route' =>  ['messages.admin-store'],'class'=>'send-msg','role'=>"form",'files' => true,'enctype'=>'multipart/form-data')) !!}
                    <h4>Send a reply</h4>
                   <div class="emoji-picker-container {{ $errors->has('messages') ? 'has-error' : ''}}">
                        {!! Form::textarea('messages', null, ['placeholder' => trans("comman.type_message"),'rows' => '5','data-emojiable' => 'true']) !!}
                        {!! $errors->first('messages', '<p class="help-block">:message</p>') !!}
                    </div>
                    <img src="{{ asset('themes/limitless/images/client-view/attach.png')}}" width="24px" class="file-icon" />
                      {!! Form::file('attachment', ['class' => 'form-control inputfile hide','type' => "file",'id'=>"attachment",'onchange' => 'readURL(this)']) !!}
                                                    {!! $errors->first('attachment', '<p class="help-block">:message</p>') !!}
                  {{--   <input type="file" id="upload-coach" name="upload-coach" class="inputfile hide" onchange="readURL(this)" /> --}}
                    <img src="" id="upload-coach_section" class="uploaded_img">
                    {!! Form::submit("Send", ['name' => 'save','class' => 'send pull-right']) !!}
                {!! Form::close() !!}
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
        // Finds all elements with `emojiable_selector` and converts them to rich emoji input fields
        // You may want to delay this step if you have dynamically created input fields that appear later in the loading process
        // It can be called as many times as necessary; previously converted input fields will not be converted again
        window.emojiPicker.discover();
      });
</script>
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