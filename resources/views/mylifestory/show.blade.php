@extends($theme)
@section('title', $title)
@section('content')

<div class="row">
    <div class="col-md-12 col-sm-12">
       <div class="panel panel-white">
            <div class="panel-heading">
                <div class="col-sm-9"><h5 class="panel-title">

                {{$user->name}}'s

                Life Story
                </h5>
                </div>
                <div class="heading-elements">
                <a  href=" {{URL::to('getpdf').'?cl='.Crypt::encryptString($user->id)}}" type="button" class="btn bg-success btn-labeled heading-btn"><b><i class="fa fa-file-pdf-o"></i></b>{{ trans('comman.saveaspdf')}}</a>
                </div>
                <div class="clearfix"></div>

            @if(isset($mylifestory) && count($mylifestory) > 0)
            {{-- <div class="heading-elements">
            <a  href=" {{URL::to('getpdf')}}" type="button" class="btn bg-danger btn-labeled heading-btn"><b><i class="fa fa-file-pdf-o"></i></b>{{ trans('comman.saveaspdf')}}</a>
            </div> --}}
            @endif
            </div>
            <div class="panel-body" style="padding: 0px;">
            <br>
                @if(isset($mylifestory) && count($mylifestory) > 0)
            @foreach($mylifestory as $mylifestorys)
                <div class="panel panel-flat border-top-success col-md-10 col-md-offset-1">
                    <div class="panel-body" style="overflow: auto;">
                        <p>
                        @php
                        echo $mylifestorys->message;
                        @endphp
                        </p>
                        <div class="text-right">
                            {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $mylifestorys->created_at)->format('m/d/Y')}}
                            @if($timezone != null)
                             <span >{{$mylifestorys->created_at->timezone($timezone)->format('H:i')}}</span>
                            @else
                            <span >{{$mylifestorys->created_at->format('H:i')}}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
            @else
                No Data Found
            @endif
            </div>
        </div>
    </div>
</div>
@endsection