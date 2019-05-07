@extends($theme)

@section('title', $title)

@section('content')
<div class="content-wrapper">
{{-- {{ dump($notifications->toArray()) }} --}}
    <div class="panel panel-default">
         <div class="panel-heading">
            <h3 class="panel-title">Alerts</h3>            
        </div>       
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
@endsection