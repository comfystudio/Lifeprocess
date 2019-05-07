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

                    <li class="">{!! link_to_route('messages.getrole','Clients', ['role'=>'coach-client']) !!}
                    </li>
                    <li class="">{!! link_to_route('messages.index','Admin') !!}</li>
                   <li class="active">
                        <a href="#alerts" data-toggle="tab" aria-expanded="true">System Alerts</a>
                    </li>
                </ul>
                <div class="tab-content">
                        <div class="tab-pane fade fade active in" id="alerts">
                            <div class="panel-body collapsible-group">
            <div class="panel-group ">
                <div class="panel panel-default">
                    <div class="clickable panel-heading bg-white">
                        <div class="row">
                            <div class="col-md-3" >
                                <h6 class="panel-title">
                                  <strong>Alert Generated </strong>
                                </h6>
                            </div>
                            <div class="col-md-9" >
                                <h6 class="panel-title">
                                  <strong>Alert</strong>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="panel-collapse collapse in list-group">
                        @if(isset($notifications) && count($notifications) > 0)
                            @foreach($notifications AS $notification)
                                <div class="list-group-item">
                                    <div class="row">
                                        <div class="col-md-3">
                                            {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at)->format('D dS F Y \a\t h:i a') }}
                                        </div>
                                        <div class="col-md-9">
                                            {!! $notification->notification->notification_text !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="list-group-item">
                                <div class="row">
                                    <div class="col-md-10 col-md-offset-1 text-center">
                                        {{ trans('comman.no_data_found') }}
                                    </div>
                                </div>
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