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
                    <a href="#all-coach" data-toggle="tab" aria-expanded="true">Coachs</a>
                </li>
                <li class="">
                    {!! link_to_route('messages.getrole','Client', ['role'=>'client']) !!}
                </li>
                <li class="">
                    {!! link_to_route('messages.getrole','Client Managers', ['role'=>'agent']) !!}
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade fade active in" id="all-coach">
                    <div class="tabbable nav-tabs-vertical nav-tabs-left">
                        <ul class="nav nav-tabs nav-tabs-highlight">
                            <li>
                                {{Form::open(['route' => ['messages.index'],'method' => "GET",'style'=>"margin-bottom:0px;"])}}
                                <div class="inner-addon right-addon @if(count($all_coach)==0)  message-search-width @endif ">
                                    <i class="fa fa-search fa-rotate-90"></i>
                                    {{Form::text('search',request()->get('search',null),['id'=>"search_coach",'class'=>"form-control",'placeholder'=>"Search Coach",'style'=>"width:200px"])}}
                                </div>
                                {{Form::close()}}
                            </li>
                            @php $coach_name = ""; @endphp
                            @foreach($all_coach as $coach)
                            @if(App::make("App\Http\Controllers\MessageController")->isOnline($coach['id']))
                                @php $online='<i class="fa fa-circle"></i>';@endphp
                            @else
                                    @php $online='';@endphp
                            @endif
                            {{-- @if($coach['is_login']=='1')
                                @php $online='<i class="fa fa-circle"></i>';@endphp
                            @else
                                @php $online='';@endphp
                            @endif --}}
                                @if(Crypt::decryptString($id) == $coach->id)
                                    @php $coach_name = $coach->name; @endphp
                                    <li class="active">{!! Html::decode(link_to_route('messages.admindata', $online." ".$coach->name, ['role'=>'coach','id' =>Crypt::encryptString($coach->id),])) !!}
                                    </li>
                                @else
                                    <li class="">{!! Html::decode(link_to_route('messages.admindata', $online." ".$coach->name, ['role'=>'coach','id' =>Crypt::encryptString($coach->id),])) !!}
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        <div class="tab-content no-padding">
                            <div class="panel panel-default no-border">
                                <div class="panel-heading no-border">
                                    <h5 class="panel-title">
                                        Your conversation with {{$coach_name}}
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