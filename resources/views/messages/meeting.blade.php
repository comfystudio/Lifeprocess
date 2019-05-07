@extends($theme)
@section('title', $title)
@section('content')
<div class="col-md-12 col-sm-12">
    <div class="panel">
        <div class="tab-title">
            <h1>
                Meeting
            </h1>
        </div>

          {{--   @if(Auth::user()->user_type=='client')
            @else
            {{Form::open(['route' => ['messages.createmeeting'],'method' => "POST",'style'=>"margin-bottom:0px;"])}}

            <input type="hidden" name="email" value="darshika.akhiyaniya@sphererays.net">
            <input type="hidden" name="host_id" value="916-132-8787">
            <input type="hidden" name="type" value="2">
            <input type="hidden" name="topic" value="Topic for meeting">
            <input type="submit" name="submit" value="Start Meeting with your Client">
            {{Form::close()}}
            @endif
           --}}

        <div class="panel-body">
        @php
            $array=sizeof($request);
            $array=$array-1;

        @endphp
        <table class="table">
            @if($array>=0)
            @for($i=0;$i<=$array;$i++)
            @php
               $date = Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$request[$i]->start_datetime)->format('Y-m-d');
               $currentdate = Carbon\Carbon::now()->format('Y-m-d');
               // $startdatetime = Carbon\Carbon::createFromDate($request[$i]->start_datetime, '');
               // $currentdatetime=Carbon\Carbon::now('UTC');
               // echo $currentdatetime;
               // echo $currentdatetime;
              $currentdatetime = Carbon\Carbon::now()->format('Y-m-d H:i:s');
               //echo $currentdate;
            @endphp
            @if($date==$currentdate)
            {{-- for show meeting between end and start time && $request[$i]->end_datetime<=$currentdatetime --}}
                @if($request[$i]->start_datetime<$currentdatetime)
                <tr>
                <td>
                {{--Meeting time {{$request[$i]->start_datetime}}--}}
                 Meeting time {{Carbon\Carbon::parse($request[$i]->start_datetime)->addMinute(10)->format('Y-m-d H:i:s')}}
                </td>
                <td>
                {{  link_to($request[$i]->meeting_id, 'Click Here To Join Meeting', ['target'=>'_blank'], array('class' => 'btn btn-primary btn-xs ',)) }}
                </td>
                </tr>
                @endif

               @endif

            @endfor
        @else
            There is no meeting available from your coach.
        @endif
        </table>

        </div>
    </div>
</div>
@endsection