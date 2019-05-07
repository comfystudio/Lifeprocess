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
                            @if(isset($data) && !empty($data))
                                @foreach($data as $key => $program)
                                    <div class="tab-pane fade fade active in">
                                        <div class="panel panel-white no-border">
                                            <div class="panel-heading no-border">
                                                <h5 class="panel-title">Program - {{$key}}</h5>
                                            </div>
                                            <div class="message-listing">
                                                <div class="row no-margin left">
                                                    <div class="msg" id="chat" style="">
                                                    @foreach($program as $key2 => $module)
                                                        <div class="panel-heading no-border">
                                                            <h6 class="panel-title">Module - {{$module['module_title']}}</h6>
                                                            {!! Html::decode(link_to_route('download.feedback', '<i class="icon-file-pdf"></i> Download Feedback', ['module_id' => Crypt::encryptString($key2),'excercise_id' =>Crypt::encryptString($module['exercise_id']), 'user_id' => $user->id], ['class' => 'btn btn-warning','target'=>'_blank'])) !!}
                                                            {{--{!! link_to_route('coach.module.feedback', 'View Feedback', ['module_id' => Crypt::encryptString($key2), 'client_id' => Crypt::encryptString($user->id),'excercise_id'=>Crypt::encryptString($module['exercise_id'])],['class' => 'btn btn-success1 pull-right1','target'=>'_blank']) !!}--}}

                                                        </div>

                                                        @foreach($module as $question)
                                                            @if(is_array($question))
                                                                {{--<li class="media date-step content-divider"><span></span></li>--}}

                                                                <div class="row no-margin left">
                                                                    <div class="col-md-offset-2 col-md-8 col-sm-10 col-xs-8 text">
                                                                        <h5 class="panel-title">Question - {{$question['question_title']}}</h5>
                                                                    </div>
                                                                    <div class="col-md-offset-2 col-md-8 col-sm-10 col-xs-8 no-padding">
                                                                        <p class="date-time">
                                                                            <strong>Answer - </strong>
                                                                            {{$question['answer']}}
                                                                        </p>
                                                                        <p class="date-time">
                                                                            <strong>Coach Response - </strong>
                                                                            {{$question['coach_respond']}}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="tab-pane fade fade active in">
                                    <div class="panel panel-white no-border">
                                        <div class="panel-heading no-border">
                                            <h5 class="panel-title">No Program Data</h5>
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
@endsection