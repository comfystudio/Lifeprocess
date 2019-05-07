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
        <h1>Schedule a session with {{ $coach_user_name }}</h1>
        <p>
            <span>
                <strong class="font-bold">3 Simple steps.</strong>
            </span>
            <span>
                <strong class="font-bold">1.</strong> Choose a Date
            </span>
            <span>
                <strong class="font-bold">2.</strong> Choose a Time
            </span>
            <span>
                <strong class="font-bold">3.</strong> Please select what format you would like to use for your coaching session:
            </span>
        </p>
    </div>
    <div class="form-content">
        {!! Form::open(array('route' => 'bookfreeschedule.store','class'=>'form-horizontal no-margin','role'=>"form",'id'=>'book_session_form')) !!}
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
                    <p class="fyi-msg">FYI:</p>
                </div>
            </div>
            <div class="row step3 no-margin disabled">
                <div class="col-md-2 col-sm-3 col-xs-12">
                    <h4 class="font-semibold no-margin">Step 3</h4>
                </div>
                <div class="col-md-10 col-sm-9 col-xs-12">
                    <p>Please select what format you would like to use for your coaching session:</p>
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
            {!! Form::hidden("coach_schedules_id",'') !!}
            {!! Form::hidden("slot_id",'') !!}
            <div class="row step4 no-margin disabled">
                <div class="col-md-offset-2 col-sm-offset-3 col-md-10 col-sm-9 col-xs-12">
                    {!! Form::submit('Book your Session', ['name' => 'submit']) !!}
                </div>
            </div>
            <input type="hidden" id="cdate" name="cdate">
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
            firstDay:1,
            selectable: false,
            select:false,
            editable: true,
            lazyFetching: true,
            showNonCurrentDates: false,

            loading: function( isLoading, view ) {
              if(isLoading) {// isLoading gives boolean value

              } else {
                  $('#load_data').hide();
              }
            },
            dayRender: function (date, cell,element,timezone) {

                @foreach($schedule as $data)
                {
                        var cur_date = '{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->start_datetime)->format('Y-m-d')}}';

                                @if(isset($final_date) && $final_date!='')
                                    jQuery("td[data-date='"+cur_date+"']").addClass('text-white');
                                    jQuery("td[data-date='"+cur_date+"']").css('background-color','#82CD49');
                                    jQuery('.row.step2').removeClass('disabled');
                                    jQuery('.row.step3').removeClass('disabled');
                                    jQuery('.row.step4').removeClass('disabled');
                                    jQuery('input[name="coach_schedules_id"]').val({{ $data->id }});
                                    jQuery('.row.step1').hide();
                                    jQuery('.row.step2').hide();
                                @else
                                    // var currentdate='{{Carbon\Carbon::now()->addHours(24)->format('Y-m-d')}}';
                                    // if(cur_date>=currentdate)
                                    // {
                                       jQuery("td[data-date='"+cur_date+"']").addClass('font-bold');
                                    // }
                                @endif
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
            var calandar = jQuery('#choose-date');
            if(firstDayWithSlashes!=start)
                jQuery.ajax({

                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                  },
                                type: "POST",
                                url: '{{ route('ajax.renderFreeEvent') }}',
                                data: {start:start,end:end},
                                success: function(response)
                                {
                                    $.each(response.schedule, function (index, value) {

                                    var date = moment(value.start_datetime).format('YYYY-MM-DD');

                                    var  currentdate='{{Carbon\Carbon::now()->setTimezone($coach_timezone)->format('Y-m-d')}}';
                                    if(date>=currentdate)
                                    {

                                     jQuery("td[data-date='"+date+"']").addClass('font-bold');

                                    }
                                    else
                                    {
                                        jQuery("td[data-date='"+date+"']").addClass('');
                                    }
                                    });
                                }
                            });
            },
            dayClick: function(date, jsEvent, view, element,timezone) {

                var date_click = date.format();
                var start = view.intervalStart.format();
                var end = view.intervalEnd.format();
                var cdate=jQuery('#cdate').val();
                if(date_click!=cdate)
                {

                    if(jQuery(this).hasClass('font-bold'))
                    {
                        jQuery('#time-slot option').each(function() {
                            if ( jQuery(this).val() != '' ) {
                                jQuery(this).remove();
                            }
                        });
                        jQuery.ajax({

                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                  },
                                type: "POST",
                                url: '{{ route('ajax.renderFreeTimeslot') }}',
                                data: {start:start,end:end,date_click:date_click},
                                success: function(response)
                                {
                                    jQuery("#cdate").val(date_click);
                                    jQuery("td").each(function(index, el) {
                                        if(jQuery(this).hasClass('text-white')) {
                                            jQuery(this).removeClass('text-white');
                                            jQuery(this).removeAttr('style');
                                        }
                                    });
                                    jQuery("td[data-date='"+date_click+"']").addClass('text-white');
                                    jQuery("td[data-date='"+date_click+"']").css('background-color','#82CD49');
                                    var date_c = moment(date).format('YYYY-MM-DD 00:00:00');
                                    var option = '';
                                    //alert(date_c);
                                    $.each(response.schedule, function (index, value) {

                                        var start_time = moment(value.start_datetime).format('HH:mm');
                                        var start_time = moment(value.start_datetime).format('HH:mm a');
                                        var abc = moment(value.start_datetime).add(20, 'm').format('HH:mm');
                                        var end_time = moment(value.start_datetime).add(20, 'm').format('HH:mm a');
                                        var a = [];var b = [];
                                        a.push(start_time);
                                        b.push(end_time);
                                        option = '<option data-slot="1" data-id="'+value.id+'" value="'+start_time+ '-' +end_time+ '">' +start_time+ ' - ' +end_time+ '</option>';
                                        for(var i=2;i<4;i++){

                                            var insert_start = moment(a[a.length - 1],'HH:mm').add(20, 'm').format('HH:mm a');
                                            var insert_end = moment(b[b.length - 1],'HH:mm').add(20, 'm').format('HH:mm a');
                                            a.push(moment(a[a.length - 1],'HH:mm').add(20, 'm').format('HH:mm'));
                                            b.push(moment(b[b.length - 1],'HH:mm').add(20, 'm').format('HH:mm'));
                                            option += '<option data-slot="'+i+'" data-id="'+value.id+'" value="'+insert_start+ '-' +insert_end+ '">' +insert_start+ ' - ' +insert_end+ '</option>';

                                        }
                                        jQuery('#time-slot').append(option);

                                        if(value.slot1==1){
                                            var teg = jQuery('#time-slot option[data-id='+ value.id +'][data-slot=1]').attr('style','display:none;');
                                        }
                                        if(value.slot2==1){
                                            var teg = jQuery('#time-slot option[data-id='+ value.id +'][data-slot=2]').attr('style','display:none;');
                                        }
                                        if(value.slot3==1){
                                            var teg = jQuery('#time-slot option[data-id='+ value.id +'][data-slot=3]').attr('style','display:none;');
                                        }

                                        jQuery('.row.step2').removeClass('disabled');
                                    });
                                        jQuery('#time-slot').change(function(event) {
                                        var sel_id = jQuery(this).find(':selected').data('id');
                                        var slot = jQuery(this).find(':selected').data('slot');
                                        jQuery('input[name="coach_schedules_id"]').val(sel_id);
                                        jQuery('input[name="slot_id"]').val(slot);
                                        jQuery.ajax({

                                                headers: {
                                                    'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                                                  },
                                                type: "POST",
                                                url: '{{ route('ajax.setFreeFYI') }}',
                                                data: {id:sel_id, slot:slot},
                                                success: function(response)
                                                {
                                                    //alert(response);
                                                    jQuery('.fyi-msg').text('FYI: This will be '+response+' in your coaches timezone.');
                                                }
                                            });

                                        jQuery('.fyi-msg').text('FYI: This will be {{ $coach_user_time }} in your coaches timezone.');
                                        jQuery('.row.step3').removeClass('disabled');

                                    });
                                    jQuery('input:radio[name="meeting_type"]').change(function(){
                                        jQuery('.row.step4').removeClass('disabled');
                                    });

                                }
                            });
                }
            }
            },
        });
    });
    jQuery('#book_session_form input[type="submit"]').click(function(event) {
        event.preventDefault();
        jQuery.ajax({
            url: '{{ route('ajax.checkClientCredit') }}',
            success: function(responseData)
            {

                                var coach_schedules_id = jQuery('input[name="coach_schedules_id"]').val();
                                $.ajax({
                                    type: "POST",
                                    url: '{{ route('bookfreeschedule.store') }}',
                                    data: jQuery('#book_session_form').serialize(),
                                    success: function(response,result)
                                    {
                                                window.location.replace(response.url);

                                    },
                                    headers:
                                    {
                                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
            }
        });
    });
    function check_available_credits() {

    }

</script>
@endpush
@endsection