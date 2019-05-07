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
                <li class="">
                    {!! link_to_route('messages.index','Coachs') !!}
                </li>
                <li class="">
                    {!! link_to_route('messages.getrole','Client', ['role'=>'client']) !!}
                </li>
                <li class="active">
                    <a href="#all-agent" data-toggle="tab" aria-expanded="true">Client Manager</a>
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
                            @php $agent_name = ""; @endphp
                            @foreach($all_agent as $agent)
                            @if(App::make("App\Http\Controllers\MessageController")->isOnline($agent['id']))
                                @php $online='<i class="fa fa-circle"></i>';@endphp
                            @else
                                    @php $online='';@endphp
                            @endif
                           {{--  @if($agent['is_login']=='1')
                                @php $online='<i class="fa fa-circle"></i>';@endphp
                            @else
                                @php $online='';@endphp
                            @endif --}}
                                @if(Crypt::decryptString($id) == $agent->id)
                                @php $agent_name = $agent->name; @endphp
                                <li class="active">
                                    {!! Html::decode(link_to_route('messages.admindata', $online." ".$agent->name, ['role'=>'agent','id' =>Crypt::encryptString($agent->id),])) !!}
                                </li>
                                @else
                                <li class="">
                                    {!! Html::decode(link_to_route('messages.admindata', $online." ".$agent->name, ['role'=>'agent','id' =>Crypt::encryptString($agent->id),])) !!}
                                </li>
                                @endif
                            @endforeach
                        </ul>
                        <div class="tab-content no-padding">
                            <div class="panel panel-default no-border">
                                <div class="panel-heading no-border">
                                    <h5 class="panel-title">
                                        Your conversation with {{$agent_name}}
                                    </h5>
                                </div>
                                <div class="panel-body no-padding">
                                    @include('messages.form')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
    @if($errors->has('messages'))
        $(document).ready(function() {
            $('html, body').animate({
                scrollTop: $(".message-compose").offset().top
            }, 500);
        });
    @endif
</script>
@endpush