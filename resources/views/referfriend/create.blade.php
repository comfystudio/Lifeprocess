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
    margin-left:10px;
}

.col-sm-6,.col-sm-12{
    padding-left: 0px !important;
}


</style>
@section('content')


<div class="content-wrapper">
 <div class="panel">
        <div class="tab-title">
            <h1>
                Refer a Friend
            </h1>
        </div>

   <div class="panel-body">

  {{Html::image(AppHelper::path('images/')->getImageUrl('Refer.png'),'help a friend',array('id'=>'friend','height'=>'400','width'=>'100%'))}}

        <div class="panel-body">
            @if(isset($page))
            @if(Auth::user()->user_type == 'user' && Auth::user()->hasAccess('pages.update'))
                <div class="pull-right">{!! Html::decode(link_to_route('pages.edit', '<i class="icon-pencil7"></i>', array($page->id,'_url'=> Request::path()))) !!}</div>
            @endif
             <div class="right-margin">   {!!html_entity_decode($page->content)!!} </div>
            @else
                Static page help-friend not found
            @endif


 <div class="right-margin">

<h2><b>Referral Form</b></h2>

<p>
If you prefer, we will email your friend without using your name. However, if we send it with your name, it's more likely to be read.
</p>
</div>


{!! Form::open(array('route' => 'referfriend.store','class'=>'form-horizontal','role'=>"form")) !!}
       <div class="panel panel-white">
            <div class="panel-heading">
               <!--  <div class="col-sm-9"><h5 class="panel-title">{{$title}}</h5></div> -->
                @if(Request::get("download",false))
                    <div class="pull-right">
                        <button type="button" class="close" ng-click="cancel($event)" aria-label="Close">
                        <span aria-hidden="true" ><i class="icon-cross2"></i></span>
                        </button>
                    </div>
                @endif
                <div class="clearfix"></div>
            </div>
            <div class="panel-body" style="margin-left: -10px;">
                @include('referfriend.form')
            </div>
            <div class="form-group">
                <div class="col-sm-6 text-left">

                    {!! Form::submit("Send", ['name' => 'save','class' => 'send']) !!}

                </div>
            </div>
        </div>


    {!! Form::close() !!}


 </div>
    </div>

</div>
</div>
@endsection