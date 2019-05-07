@extends($theme)
@section('title', $title)
@section('content')
<div class="content-wrapper">
<div class="row">
    <div class="panel panel-white">
        <div class="panel-body">
            @if(isset($page))
            @if(Auth::user()->user_type == 'user' && Auth::user()->hasAccess('pages.update'))
                <div class="pull-right">{!! Html::decode(link_to_route('pages.edit', '<i class="icon-pencil7"></i>', array($page->id,'_url'=> Request::path()))) !!}</div>
            @endif
                {!!html_entity_decode($page->content)!!}
            @else
                Static page help-friend not found
            @endif
        </div>
    </div>
    {!! Form::open(array('route' => 'referfriend.store','class'=>'form-horizontal','role'=>"form")) !!}
       <div class="panel panel-white">
            <div class="panel-heading">
                <div class="col-sm-9"><h5 class="panel-title">{{$title}}</h5></div>
                @if(Request::get("download",false))
                    <div class="pull-right">
                        <button type="button" class="close" ng-click="cancel($event)" aria-label="Close">
                        <span aria-hidden="true" ><i class="icon-cross2"></i></span>
                        </button>
                    </div>
                @endif
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                @include('referfriend.form')
            </div>
            <div class="form-group">
                <div class="col-sm-6 text-right">
                    {!! Form::submit("Save", ['name' => 'save','class' => 'btn btn-primary']) !!}
                    {!! link_to(URL::full(), "Cancel",array('class' => 'btn btn-warning cancel')) !!}
                </div>
            </div>
        </div>
        <div class="panel panel-white">
            <div class="panel-heading">
                <h5 class="panel-title">Your Referals</h5>
            </div>
            <div class="panel-body">
                <table class="table table-bordered table-hover no-footer">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No.</th>
                            <th>Friend email</th>
                            <th>Message</th>
                        </tr>
                    </thead>
                    <tbody>
                     @if(isset($referals) && count($referals) > 0 )
                        @foreach($referals as $referal)
                                <tr>
                                    <td> {{ ++$counter }}. </td>
                                    <td>{{$referal->friends_email}}</td>
                                    <td>{{$referal->message}}</td>
                                </tr>
                        @endforeach
                    @else
                    <tr>
                            <td colspan="3">No data found.</td>
                    </tr>
                    @endif
                    </tbody>
                    </table>
            </div>
    </div>
    {!! Form::close() !!}
</div>
</div>
@endsection