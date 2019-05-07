@extends($theme)
 <style type="text/css">
#calendar .fc-view > table {
    min-width: 500px;
}
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
.fc th
{
    padding: 8px 10px !important;
}
#calendar2 {
    width: 230px !important;
    margin: 10px !important;
    font-size: 14px !important;
}
#calendar2 table{
font-size: 14px !important;
}
#calendar2 .fc-toolbar {
    text-align: left ;
    margin-bottom: 5px;
}
#calendar2 .fc-toolbar .fc-right {
    width:auto;
}
#calendar2 .fc-toolbar .fc-left {
    float: left;
    width: 40%;
}
#calendar2.fc th {
    padding: 8px 2px !important;
}
#calendar2 .fc-view > table {
    min-width: 200px;
}
#calendar2 .fc-scroller {
    overflow-y: scroll;
    overflow-x: hidden;
    min-height: 225px;
}
#calendar2 .fc-basic-view tbody .fc-row {
    min-height: 30px;
    min-width: 30px;
}
 </style>
@section('title', $title)
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="result">
        </div>
    </div>
    <div class="panel panel-default">
    <div class="panel-heading">
            <h3 class="panel-title">Free 20 minutes Session</h3>
        </div>
      <div class="content-wrapper">

        <div class="panel-body">
          <p>This Tab allows you to adjust your schedule for a specific week. It allows you to make adjustments to your Default week. You can add more availability or remove availability. <b>Note:</b> You cannot remove availability for a session which has allready been booked.</p>

          <br>
          <div class="alert alert-info" style="font-size: 15px; ">
            Your Time zone is <span class="bg label bg-info" style="font-size: 13px; text-transform: none;"></span> {{ $coach_timezone }}<span class="bg label bg-info pull-right" style="font-size: 13px; text-transform: none;">{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',Carbon\Carbon::now($coach_timezone))}}</span>
          </div>
          <input type="hidden" name="form_save" id="form_save" value="0">
          <div class="col-md-12">
          <div class="col-md-8">
          <div id='calendar'>

          </div>
      </div>


          <div class="col-md-4"><div id='datepicker'></div></div>
          </div>
          <div class="row col-md-8">
          <br>
            <button id="btn_save" class="btn bg-success btn-labeled heading-btn pull-right"><b><i class="icon-diff-added"></i></b>Save My Default Week</button>
          </div>

          </div>

        </div>
      </div>
    </div>
</div>
@push('scripts')
<script>
  jQuery(document).ready(function() {

    jQuery('#datepicker').datetimepicker({
        inline: true,
        timepicker:false,
        onSelectDate: function(dateText, inst) {
            var d = new Date(dateText);
            $('#calendar').fullCalendar('gotoDate', d);
        },
        onChangeMonth:function(dateText, inst) {
          var m = new Date(dateText);
          $('#calendar').fullCalendar('gotoDate', m);
        },
    });
    jQuery('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
      },

      titleFormat:'MMMM D YYYY',
      columnFormat:'ddd',
      allDaySlot:false,
      //defaultDate: Date(),
      now: '{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',Carbon\Carbon::now($coach_timezone))}}',
      timezone: '{{ $coach_timezone }}',
      timeFormat: 'H:mm',
      defaultView: 'agendaWeek',
      firstDay:0,
      slotDuration: '00:20:00',
    slotLabelInterval: 20,
    slotLabelFormat: 'h(:mm)a',
    slotMinutes: 20,
      selectable: true,
      selectHelper: true,
      lazyFetching: true,
      select: function(start, end) {
        if(start.format('YYYY/MM/DD') < (moment().format('YYYY/MM/DD')))
        {
                bootbox.alert("Session cannot be schedule for " + start.format('MM/DD/YYYY HH:mm'));
                        jQuery('#calendar').fullCalendar('unselect');
        }
        else
        {
          var duration=moment.duration(end.diff(start));
          var hours = parseInt(duration.asHours());
          if(hours <= 1)
          {
            var title = 'Available';
            var eventData;
            if (title) {
              eventData = {
                title: title,
                start: start,
                end: end
              };
              jQuery('#calendar').fullCalendar('renderEvent', eventData, true);
            }
          }
          else
          {
            bootbox.alert("Session can not more than one hour.");
            jQuery("#calendar").fullCalendar("unselect");
          }
         }

      },
      eventClick: function (event) {
                if(event.start.format('YYYY/MM/DD') < moment().format('YYYY/MM/DD'))
                {
                    bootbox.alert('You cannot delete past Session.');
                    return false;
                }
                if (event.status == 'cancelled') {
                    bootbox.alert('The session you trying to delete is ' + event.title + '. So you cannot delete it.');
                    return false;
                }
        var id = event.id;
        if(event.id != null)
        {
          bootbox.confirm({
             message: "Are you sure you want to delete schedule ?",
              buttons: {
                confirm: {
                  label: 'Yes',
                                className: 'btn-success'
                },
                cancel:{
                  label: 'No',
                                className: 'btn-danger'
                },

              },
              callback: function (result) {
                if(result == true)
                {
                  console.log(id);
                  jQuery.ajax({
                    url: '/free_session/' + id,
                                            data: { "_token": "{{ csrf_token() }}" },
                                            type: 'DELETE',
                                            success: function(result)
                                            {
                                              console.log(result.success);
                                              if(result.success == 'Not Delete')
                                              {
                                                bootbox.alert({
                                                  message: result.message,
                                                  callback: function () {
                                                    // location.reload(true);
                                                  }

                                                })

                                              }
                                              else
                                              {
                                                bootbox.alert({
                                                  message:result.message,
                                                  callback: function (){
                                                    location.reload(true);
                                                  }
                                                })
                                              }

                                            },
                                            error: function(XMLHttpRequest)
                                            {
                                              bootbox.alert({
                                                message: "Error on delete Schedule",
                                                callback: function (){
                                                  location.reload(true);
                                                }

                                              })
                                            }

                  });
                }
              }

          });
        }
        else
        {
          $('#calendar').fullCalendar('removeEvents',event._id);
        }

      },
       editable: false,
       eventLimit: true, // allow "more" link when too many events
       eventDurationEditable:false,

       events: [
        @foreach($schedule as $data)
          @if($data->CoachFreeSessionBooked != null)
                        @if($data->CoachFreeSessionBooked->session_status == 'cancelled')
                            {
                                title: 'Cancelled',
                                start: '{{$data->start_datetime}}',
                                end:'{{$data->end_datetime}}',
                                id:'{{$data->id}}',
                                borderColor:'#FFA812',
                                backgroundColor: '#FFC259',
                                textColor: '#8B5A00',
                                status: 'cancelled'
                            },
                        @else
                            {
                                title: 'Booked',
                                start: '{{$data->start_datetime}}',
                                end:'{{$data->end_datetime}}',
                                id:'{{$data->id}}',
                                color:'#EF5350',
                            },
                        @endif
          @else
              {
                title: 'Available',
                  start: '{{$data->start_datetime}}',
                  end:'{{$data->end_datetime}}',
                  id:'{{$data->id}}',
              },
                    @endif
        @endforeach
       ],

    });
    jQuery(function(){


      jQuery('#btn_save').click(function() {

        $("#form_save").val('1');

        jQuery("#btn_save").attr('disabled', 'disabled');
        jQuery('#calendar').fullCalendar('clientEvents', function(event) {
          var id = event.id;
          var start=moment(event.start).format('YYYY-MM-DD H:mm:ss');
          var end=moment(event.end).format('YYYY-MM-DD  H:mm:ss');
          if(id == null )
          {
            jQuery.ajax({
                           type: "POST",
                           url: "{{ route('free_session.store') }}",
                           data: {'start': start,'end' : end },
                           success: function(_response)
                           {
                            console.log('event added' + start,end);
                            jQuery(".result").html(_response.message);
                            jQuery(".result").addClass("alert alert-success alert-dismissable");
                            window.location.reload();
                           },
                           headers:
                           {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                           }
            });
          }
        });
        jQuery("#btn_save").removeAttr('disabled', 'disabled');
                  return false;
      });
      window.addEventListener("beforeunload", function (e) {

        //e.preventDefault();

          jQuery('#calendar').fullCalendar('clientEvents', function(event) {
          var id = event.id;
          var form_save = $("#form_save").val();
          if(id != null || form_save == 1){
            e.returnvalue = false;
            return undefined;
          }

            var confirmationMessage = 'are you want to save your changes before you leave page?';

            (e || window.event).returnValue = confirmationMessage; //Gecko + IE
            //console.log(confirmationMessage);
            return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.

          });
      });
    });
    jQuery('#calendar2').fullCalendar({
                header: {
                  left: 'prev',
                  right: 'next',
                  center: 'title',
                },
                columnFormat: 'dd',
                defaultDate: Date(),
                firstDay:1,
                selectable: false,
                select:false,
                editable: true,
                dayRender: function (date, cell,element) {
                @foreach($schedule as $data)
                {
                    @if($data->CoachFreeSessionBooked == null)
                    {
                        var cur_date = '{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->start_datetime)->format('Y-m-d')}}';
                        if('{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->start_datetime)->format('Y-m-d H:i')}}' >= moment().format('YYYY-MM-DD H:m'))
                        {
                            if(date.format('YYYY/MM/DD') == '{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->start_datetime)->format('Y/m/d')}}')
                            {
                                $("td[data-date='"+cur_date+"']").addClass('font-bold');
                            }
                        }
                    }
                    @endif
                }
                @endforeach
            },
            dayClick: function(date, jsEvent, view) {
                console.log(date.format());
                dateset = date.format();
                $('#calendar').fullCalendar('option', {
                      defaultDate: dateset
                });
                jQuery('#calendar').fullCalendar('viewRender', eventData, true);

            },
            });
  });
</script>

<script type="text/javascript">
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