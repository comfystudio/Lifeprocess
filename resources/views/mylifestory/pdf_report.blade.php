 @extends($theme)
 @section('content')

<style>
thead {display: table-row-group;}
hr {
  border: 0;
  border-top: 1px solid #8c8c8c;
  text-align:center;
}
hr:after {

  content: '\221E';
  display: inline-block;
  position: relative;
  top: -13px;
  padding: 0 3px;
  background: #fff;
  color: #8c8c8c;
  font-size: 18px;
}

</style>
        <div class="col-md-12" style="text-align: center;">
                <img src="{{ asset("themes/limitless/images/client-view/logo.png") }}" alt="Life Process Program" >
        </div>

        <div class="col-md-12" style="background-color: #0060AA; text-align: center; color: #fff;">
            <h1>My Life Story</h1>
        </div>

                @if(isset($mylifestory) && count($mylifestory) > 0)
                    @foreach($mylifestory as $mylifestorys)

                           <div class="panel-body col-md-12" style="overflow: auto; text-align: justify;  ">
                               <div class="diary-entry">
                               @php
                                echo $mylifestorys->message;
                               @endphp
                                {{--   {{$mylifestorys->message}}
                                 --}}
                                </div>
                                <div class="text-right diary">
                                    Diary Entry:
                                    {{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $mylifestorys->created_at)->format('m/d/Y')}}
                                    @if($timezone != null)
                                     <span >{{$mylifestorys->created_at->timezone($timezone)->format('H:i')}}</span>
                                    @else
                                    <span >{{$mylifestorys->created_at->format('H:i')}}</span>
                                    @endif
                                </div>

                        </div>
                        <div class="col-md-12">
                        <hr class="style17">

            @endforeach
            @endif

</div>

@endsection


