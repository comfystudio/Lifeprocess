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
                @if($user_type == 'coach')
                <li class="">
                    {!! link_to_route('messages.getrole','Clients', ['role'=>'coach-client']) !!}
                </li>
                @endif
                <li class="active">
                    <a aria-expanded="true" data-toggle="tab" href="#admin">
                        Admin
                    </a>
                </li>
                @if($user_type == 'coach')
                <li class="">
                    {!! link_to_route('messages.coach.system_alerts','System Alerts') !!}
                </li>

                @elseif($user_type == 'client')
                <li class="">
                    {!! link_to_route('messages.getrole','Coach', ['role'=>'client-coach']) !!}
                </li>
                @endif
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade fade active in" id="all-coach">
                    <div class="tabbable nav-tabs-vertical nav-tabs-left">
                        <ul class="nav nav-tabs nav-tabs-highlight">
                            <li>
                                {{Form::open(['route' => ['messages.index'],'method' => "GET",'style'=>"margin-bottom:0px;"])}}
                                <div class="inner-addon right-addon @if(count($admin)==0)  message-search-width @endif">
                                    <i class="fa fa-search fa-rotate-90"></i>
                                    {{Form::text('search',request()->get('search',null),['id'=>"search_coach",'class'=>"form-control",'placeholder'=>"Search Coach",'style'=>"width:200px"])}}
                                </div>
                                {{Form::close()}}
                            </li>
                            @foreach($admin as $admins)
                             @if($admins['is_login']=='1')
                                @php $online='<i class="fa fa-circle"></i>';@endphp
                            @else
                                @php $online='';@endphp
                            @endif
                            @if(Crypt::decryptString($id) == $admins['id'])
                                <li class="active">
                                    {!! Html::decode(link_to_route('messages.admindata', $online." ".$admins['name'], ['role'=>'admin','id' =>Crypt::encryptString($admins['id']),]))!!}
                                </li>
                            @else
                            <li class="">
                                {!! Html::decode(link_to_route('messages.admindata', $online." ".$admins['name'], ['role'=>'admin','id' =>Crypt::encryptString($admins['id']),]))!!}
                            </li>
                            @endif
                                                @endforeach
                        </ul>
                        <div class="panel panel-default no-border">
                                <div class="panel-heading">
                                    <h5 class="panel-title">
                                        Your conversation with {{ $user_name }}
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
@endsection
