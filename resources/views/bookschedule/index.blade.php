 @extends($theme)
 <style type="text/css">
    .fc-time-grid .fc-event, .fc-time-grid .fc-bgevent {
    position: absolute;
    z-index: 1;
    margin: 1px !important;
    padding: 1px !important;
    min-height: 30px !important;

}
.fc-title
{
    text-align: center;
    font-size: 12px;
}
.fc-time
{
    text-align: center;
    font-size: 12px;
}
#choose-date .fc-view > table {
    min-width: 700px;
}
.fc th
{
    padding: 8px 10px !important;
}
#choose-date {
    min-width: 350px;
    width: 65%;
    display: inline-block;
}
#choose-date .fc-view > table {
    min-width: 1px;
}
#choose-date .fc-scroller {
    min-height: 1px;
    height: auto !important;
}
#choose-date .fc-basic-view tbody .fc-row {
    min-height: 1px;
    height: auto !important;
}
#choose-date .fc-toolbar {
    margin-bottom: 0;
    background-color: #3f505e;
    color: #fff;
}
#choose-date .fc-toolbar .fc-left,
#choose-date .fc-toolbar .fc-right {
    width: auto;
}
#choose-date .fc-toolbar .fc-left > .fc-button,
#choose-date .fc-toolbar .fc-right > .fc-button {
    margin-left: 0;
    background-color: transparent;
}
#choose-date .fc-toolbar h2 {
    font-size: 20px;
    margin: 10px 0;
}
#choose-date .fc-event-container{ display : none; }
#choose-date .fc-row.fc-week.fc-widget-content {
    padding: 10px;
}
.fc-day:hover{
    background:lightblue;
}
.fc-slats,
.fc-content-skeleton,
.fc-bgevent-skeleton{
    pointer-events:none;
}
.fc-bgevent,
.fc-event-container{
    pointer-events:auto;
}

</style>
@section('title', $title)
@section('content')
<div class="book-session">
    <div class="tab-title">
        <h1>Schedule a session with {{ $coach_user_name }}
        </h1>
        <p>
            <span>
                <strong class="font-bold">3 Simple steps.</strong>
            </span>
            <span>
                <strong class="font-bold">1.</strong> Choose a Dated
            </span>
            <span>
                <strong class="font-bold">2.</strong> Choose a Time
            </span>
            <span>
                <strong class="font-bold">3.</strong> Choose a Meeting Type
            </span>
        </p>
    </div>

    <!-- var start_time = moment(value.start_datetime).format('HH:00');
                                        var end_time = moment(value.end_datetime).format('HH:00'); -->

{{-- {{ dump(Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$schedule[0]['start_datetime'])->format('H:i')) }}
    {{ dd($schedule[0]['start_datetime']).format('HH:00') }} --}}
    <div class="form-content">
        {!! Form::open(array('route' => 'bookschedule.store','class'=>'form-horizontal no-margin','role'=>"form",'id'=>'book_session_form')) !!}
            <div class="row step1 no-margin">
                <div class="col-md-2 col-sm-3 col-xs-12">
                    <h4 class="font-semibold no-margin">Step 1</h4>
                </div>
                <div class="col-md-10 col-sm-9 col-xs-12">

                <div id="choose-date">
                    </div>
                    <p class="key">
                        <strong class="font-bold">Key:</strong>
                        Dates in bold are available.
                    </p>
                </div>
            </div>
            <div class="row step2 no-margin disabled">
                <div class="col-md-2 col-sm-3 col-xs-12">
                    <h4 class="font-semibold no-margin">Step 2</h4>
                </div>
                <div class="col-md-10 col-sm-9 col-xs-12">
                    <p>Available time slots on your chosen day</p>
                    @if(isset($total_time) && $total_time!='')
                    {!! Form::select('time-slot', [$schedule[0]['id'] => $total_time],$schedule[0]['id'], ['id' => 'time-slot']) !!}
                    @else
                    {!! Form::select('time-slot', ['' => 'Select Available Time'], null, ['id' => 'time-slot']) !!}
                    @endif

                    {{-- <p class="fyi-msg">FYI: This will be 15:00 - 16:00 in your coaches timezone.</p> {{ $coach_user_timezone }} --}}
                    <p class="fyi-msg">FYI:</p> {{-- {{ $coach_user_timezone }} --}}
                </div>
            </div>
            <div class="row step3 no-margin disabled">
                <div class="col-md-2 col-sm-3 col-xs-12">
                    <h4 class="font-semibold no-margin">Step 3</h4>
                </div>
                <div class="col-md-10 col-sm-9 col-xs-12">
                    <p>Choose meeting type</p>
                    <div class="chk-content">
                        <div class="radio">

                          {!! Form::radio('meeting_type', 'video', (isset($meeting_type) && $meeting_type=='video')?'true':null, ['id' => 'video']) !!}
                            <label for="skype""></label>
                            <span>Video</span>
                        </div>

                        <div class="radio">
                            {!! Form::radio('meeting_type', 'audio', (isset($meeting_type) && $meeting_type=='audio')?'true':null, ['id' => 'audio']) !!}
                            <label for="chat"></label>
                            <span>Audio</span>
                        </div>

                        <div class="radio">
                            {!! Form::radio('meeting_type', 'livechat', (isset($meeting_type) && $meeting_type=='livechat')?'true':null, ['id' => 'livechat']) !!}
                            <label for="phone"></label>
                            <span>Live Chat</span>
                        </div>

                    </div>
                </div>
            </div>
            {{--    @php
            $edit='yes';
            @endphp --}}
            @if(isset($edit) && !empty($edit))
            {!! Form::hidden("edit",$edit) !!}
            @else
            {!! Form::hidden("edit",'') !!}
            @endif
            {!! Form::hidden("coach_schedules_id",'') !!}
            <div class="row step4 no-margin disabled">
                <div class="col-md-offset-2 col-sm-offset-3 col-md-10 col-sm-9 col-xs-12">
                    {!! Form::submit('Book your Session', ['name' => 'submit']) !!}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>

@push('scripts')

<script>
     jQuery(document).ready(function() {
         jQuery('.app > .app-content > .box').append('<div class="overlay ajax-overlay"><i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i></div>');
            var $loading = jQuery('.ajax-overlay').hide();
            jQuery('#choose-date').fullCalendar({
            header: {
                left: 'prev',
                right: 'next',
                center: 'title',
            },

            columnFormat: 'dd',
            //now: '{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',Carbon\Carbon::now($coach_timezone))}}',

            //timezone: '{{ $coach_timezone }}',
            // defaultDate: Date(),
            firstDay:1,
            selectable: false,
            select:false,
            editable: true,
            lazyFetching: true,

            dayRender: function (date, cell,element) {

                //console.log(date);
                //$fianl_date

                @foreach($schedule as $data)
                {

                   {
                       var cur_date = '{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->start_datetime)->format('Y-m-d')}}';//'{{$data->start_datetime}}';
                       /*if('{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->start_datetime)->format('Y-m-d H:i:s')}}' >= moment().format('YYYY-MM-DD H:m'))
                       {
                            if(date.format('YYYY/MM/DD') == '{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->start_datetime)->format('Y/m/d')}}')
                            {*/
                                @if(isset($final_date) && $final_date!='')
                                    $("td[data-date='"+cur_date+"']").addClass('text-white');
                                    $("td[data-date='"+cur_date+"']").css('background-color','#82CD49');
                                    $('.row.step2').removeClass('disabled');
                                    $('.row.step3').removeClass('disabled');
                                    $('.row.step4').removeClass('disabled');
                                    $('input[name="coach_schedules_id"]').val({{ $data->id }});
                                    $('.row.step1').hide();
                                    $('.row.step2').hide();
                                @else
                                    $("td[data-date='"+cur_date+"']").addClass('font-bold');
                                @endif
                            /*}
                        }*/
                   }

                }
                @endforeach
            },
            viewRender: function(view, element) {

                var start = view.intervalStart.format();
                var end = view.intervalEnd.format();
                var date = new Date();
                var month = date.getMonth() + 1;
                if(month <= 9){
                    month = '0'+month;
                }
            var firstDay = new Date(date.getFullYear(), month , 1);
            var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
            var firstDayWithSlashes = firstDay.getFullYear() + '-' + (month) + '-' + ('0'+firstDay.getDate());
            var calandar = $('#choose-date');


            if(firstDayWithSlashes!=start)
                jQuery.ajax({

                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                  },
                                type: "POST",
                                url: '{{ route('ajax.renderEvent') }}',
                                data: {start:start,end:end},
                                success: function(response)
                                {
                                    console.log(response.schedule);
                                    $.each(response.schedule, function (index, value) {
                                        //console.log(value.start_datetime);
                                        $date = moment(value.start_datetime).format('YYYY-MM-DD');
                                         $("td[data-date='"+$date+"']").addClass('font-bold');



                                    });
                                }
                            });
            },
            dayClick: function(date, jsEvent, view) {
                  $('.fyi-msg').text('');
                var date_click = date.format();

                //alert(date_click);
                var start = view.intervalStart.format();
                var end = view.intervalEnd.format();
                if($(this).hasClass('font-bold')) {
                    $('#time-slot option').each(function() {
                        if ( $(this).val() != '' ) {
                            $(this).remove();
                        }
                    });
                     jQuery.ajax({

                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                  },
                                type: "POST",
                                url: '{{ route('ajax.renderTimeslot') }}',
                                data: {start:start,end:end,date_click:date_click},
                                success: function(response)
                                {

                                    $("td").each(function(index, el) {
                                        if($(this).hasClass('text-white')) {
                                            $(this).removeClass('text-white');
                                            $(this).removeAttr('style');
                                        }
                                    });
                                    $("td[data-date='"+date_click+"']").addClass('text-white');
                                    $("td[data-date='"+date_click+"']").css('background-color','#82CD49');
                                    var date_c = moment(date).format('YYYY-MM-DD 00:00:00');
                                    var option = '';
                                    $.each(response.schedule, function (index, value) {
                                        console.log(value.start_datetime);

                                        var start_time = moment(value.start_datetime).format('H:mm a');
                                        var end_time = moment(value.end_datetime).format('H:mm a');
                                        option = '<option data-id="'+value.id+'" value="'+start_time+ '-' +end_time+ '">' +start_time+ ' - ' +end_time+ '</option>';
                                        $('#time-slot').append(option);
                                        $('.row.step2').removeClass('disabled');
                                    });
                                        $('#time-slot').change(function(event) {
                                            var sel_id = $(this).find(':selected').data('id');
                                            var slot = $(this).find(':selected').data('slot');
                                            $('input[name="coach_schedules_id"]').val(sel_id);
                                            jQuery.ajax({

                                                headers: {
                                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                                  },
                                                type: "POST",
                                                url: '{{ route('ajax.setFYI') }}',
                                                data: {id:sel_id, slot:slot},
                                                success: function(response)
                                                {
                                                    //alert(response);
                                                    $('.fyi-msg').text('FYI: This will be '+response+' in your coaches timezone.');
                                                }
                                            });
                                        console.log(date_click+ " " +$('#time-slot').val().split('-')[0]);
                                        var abc = date_click+ " " +$('#time-slot').val().split('-')[0];

                                        // $('.fyi-msg').text('FYI: This will be {{ $coach_user_time }} in your coaches timezone.');
                                        $('.row.step3').removeClass('disabled');
                                        //$('.row.step4').removeClass('disabled');
                                    });
                                    $('input:radio[name="meeting_type"]').change(function(){
                                        $('.row.step4').removeClass('disabled');
                                    });
                                }
                            });

                }
            },
        });
    });
    $('#book_session_form input[type="submit"]').click(function(event) {
        event.preventDefault();
        var id=$('input[name="edit"]').val();
        console.log($('#book_session_form').serialize());
        console.log(id);
        if(id!='')
        {
                        console.log('enter in id script');
                        jQuery.ajax({
                                    type: "POST",
                                    url: '{{ route('bookschedule.update') }}',
                                    data: $('#book_session_form').serialize(),
                                    success: function(response,result)
                                    {
                                        //alert(response.status);
                                        // console.log(response.url);
                                        // if(response.status == "success")
                                        // {
                                        //     bootbox.alert(response.message,
                                        //     function()
                                        //     {
                                                window.location.replace(response.url);
                                        //     });
                                        // } else {
                                        //     bootbox.alert(response.message,
                                        //     function()
                                        //     {
                                        //         window.location.replace(response.url);
                                        //     });
                                        // }
                                    },
                                    headers:
                                    {
                                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
        }
        else
        {
            console.log('enter in non id script');
            jQuery.ajax({
            url: '{{ route('ajax.checkClientCredit') }}',
            success: function(responseData)
            {
                console.log($('input[name="coach_schedules_id"]').val());
                console.log("responseData : "+responseData.is_available);
                console.log("responseData : "+responseData.my_total_credits);

                if (responseData.is_available == 'false')
                {
                    bootbox.alert("Sorry, you don't have enough credit to book the session.");
                    return false;
                }
                // bootbox.confirm({
                //     message: "Are you sure you want book?",
                //     buttons:{
                //         confirm:
                //         {
                //             label: 'Yes',
                //             className: 'btn-success'
                //         },
                //         cancel:
                //         {
                //             label: 'No',
                //             className: 'btn-danger'
                //         }
                //     },
                //     callback: function (result)
                //     {
                //         //alert(result);
                //         if(result == true)
                //         {
                            // if($('input[name="coach_schedules_id"]').val() != '') {
                            //     var coach_schedules_id = $('input[name="coach_schedules_id"]').val();
                                jQuery.ajax({
                                    type: "POST",
                                    url: '{{ route('bookschedule.store') }}',
                                    data: $('#book_session_form').serialize(),
                                    success: function(response,result)
                                    {
                                        //alert(response.status);
                                        // if(response.status == "success")
                                        // {
                                        //     bootbox.alert(response.message,
                                        //     function()
                                        //     {
                                                window.location.replace(response.url);
                                        //     });
                                        // } else {
                                        //     bootbox.alert(response.message,
                                        //     function()
                                        //     {
                                        //         window.location.replace(response.url);
                                        //     });
                                        // }
                                    },
                                    headers:
                                    {
                                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                            // } else {
                            //     // bootbox.alert('Please select your schedule');
                            // }
                //         }
                //     }
                // });
            }
        });
    }
    });

    jQuery("#choose-date").on("mouseenter",".fc-event", function(){
        console.log("in");
    }).on("mouseleave",".fc-event", function(){

        console.log("out");
    });

    function check_available_credits() {

    }
    function sleep(milliseconds) {
        var start = new Date().getTime();
        for (var i = 0; i < 1e7; i++) {
            if ((new Date().getTime() - start) > milliseconds){
                break;
            }
        }
    }
</script>
<script>
    jQuery('.app > .app-content > .box').append('<div class="overlay ajax-overlay"><i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i></div>');
            var $loading = jQuery('.ajax-overlay').hide();
            jQuery(document).ajaxStart(function () {
                $loading.show();
            }).ajaxStop(function () {
                $loading.hide();
        });
</script>
@endpush
@endsection