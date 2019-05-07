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
                    <a href="#all-client" data-toggle="tab" aria-expanded="true">Clients</a>
                </li>
                <li class="">{!! link_to_route('messages.index','Admin') !!}</li>
                <li class="">
                    {!! link_to_route('messages.coach.system_alerts','System Alerts') !!}
                </li>
            </ul>
            <div class="tab-content no-padding">
                    <div class="tab-pane fade fade active in" id="all-client">
                                <div class="tabbable nav-tabs-vertical nav-tabs-left">

                                @if(isset($users) && count($users) > 0 )
                                     <ul class="nav nav-tabs nav-tabs-highlight">
                                          <li>
                                {{Form::open(['route' => ['messages.getrole','role' =>'coach-client'],'method' => "GET",'style'=>"margin-bottom:0px;"])}}
                                <div class="inner-addon right-addon @if(count($users)==0)  message-search-width @endif">
                                    <i class="fa fa-search fa-rotate-90"></i>
                                    {{Form::text('search',request()->get('search',null),['id'=>"search_coach",'class'=>"form-control",'placeholder'=>"Search Coach",'style'=>"width:200px"])}}
                                </div>
                                {{Form::close()}}
                            </li>
                                            @foreach($users as $user)
                                            @if(App::make("App\Http\Controllers\MessageController")->isOnline($user['id']))
                                @php $online='<i class="fa fa-circle"></i>';@endphp
                            @else
                                    @php $online='';@endphp
                            @endif
                                           {{-- @if($user['is_login']=='1')
                                                        @php $online='<i class="fa fa-circle"></i>';@endphp
                                                    @else
                                                        @php $online='';@endphp
                                                    @endif --}}
                                                @if(Crypt::decryptString($id) == $user['id'])
                                                  <li class="active">{!! Html::decode(link_to_route('messages.admindata', $online." ".$user['name'], ['role'=>'coach-client','id' =>Crypt::encryptString($user['id']),]))!!} </li>
                                     	          @else
                                                      <li class="">{!! Html::decode(link_to_route('messages.admindata', $online." ".$user['name'], ['role'=>'coach-client','id' =>Crypt::encryptString($user['id']),]))!!} </li>
                                                @endif
                                            @endforeach

                                     </ul>
                                @endif
                                    <div class="tab-content no-padding">
				                <div class="panel panel-default no-border">
                                <div class="panel-heading">
                                    <h5 class="panel-title">
                                        Your conversation with  {{ $user_name }}
                                    </h5>
                                </div>
                                <div class="panel-body">
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