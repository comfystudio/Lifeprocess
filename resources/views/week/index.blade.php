 @extends($theme)
 <style type="text/css">
#calendar .fc-view > table {
    min-width: 700px;
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
            <h3 class="panel-title">My Week</h3>
        </div>
      <div class="content-wrapper">

        <div class="panel-body">
          <p>This table allows you to save your default week. This will carry through to all future weeks. You have the ability to adjust your availability for specific week on the next tab. Please try to make as many slots available as possible to accommodate clients in different timezones.Your Time zone is <span class="bg label bg-info" style="font-size: 13px; text-transform: none;"></span> {{$coach_timezone}} ({{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',Carbon\Carbon::now($coach_timezone))}}).</p>

          {{-- <div class="alert alert-info" style="font-size: 15px; ">
            Your Time zone is <span class="bg label bg-info" style="font-size: 13px; text-transform: none;"></span> {{$coach_timezone}}<span class="bg label bg-info pull-right" style="font-size: 13px; text-transform: none;">{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',Carbon\Carbon::now($coach_timezone))}}</span>
          </div> --}}

          <input type="hidden" name="form_save" id="form_save" value="0">
          <input type="hidden" name="form_save" id="form_save" value="0">
          <input type="hidden" name="min_slot" id="min_slot" value="{{ $coach_min_slot }}">
          <input type="hidden" name="avail_slot" id="avail_slot" value="{{ count($schedule) }}">
          <div id='calendar'></div>
          <br>
          <div class="row">
            <button id="btn_save" class="btn bg-success btn-labeled heading-btn pull-right"><b><i class="icon-diff-added"></i></b>Save My Default Week</button>
          </div>
        </div>
      </div>
    </div>
</div>
@push('scripts')
<script>
  jQuery(document).ready(function() {
    jQuery('#calendar').fullCalendar({
      header: {
        left: '',
        right : '',
        center: 'title',
        title: 'true',
      },
      titleFormat: '[Template Week]',
      allDaySlot:false,
      columnFormat:'dddd',
      now: '{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',Carbon\Carbon::now($coach_timezone))}}',
      timezone: '{{ $coach_timezone }}',
      //defaultDate: Date(),
      timeFormat: 'H:mm',
      defaultView: 'agendaWeek',
      firstDay:0,
      slotDuration : '01:00:00',
      slotLabelFormat:'H:mm',
      selectable: true,
      //displayEventTime: false,
      selectHelper: true,
      select: function(start, end) {
        // if(start.format('YYYY/MM/DD') < (moment().format('YYYY/MM/DD')))
        // {
        //         bootbox.alert("Session cannot be scheduled for " + start.format('MM/DD/YYYY HH:mm'));
        //                 jQuery('#calendar').fullCalendar('unselect');
        // }
        // else
        {
          var duration=moment.duration(end.diff(start));
          var hours = parseInt(duration.asHours());
          var slots = $("#avail_slot").val();
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
              $("#avail_slot").val(Number(slots)+Number(1));
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
                // if(event.start.format('YYYY/MM/DD') < moment().format('YYYY/MM/DD'))
                // {
                //     bootbox.alert('You cannot delete past Session.');
                //     return false;
                // }
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
                    url: '/week/' + id,
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
          @if($data->coachschedulebooked != null)

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
      var flag = 0;

      jQuery('#btn_save').click(function() {
        var min_slot = $("#min_slot").val();
        var avail_slot = $("#avail_slot").val();

        if(Number(min_slot)>Number(avail_slot)){

            swal({
                    title: '',
                    //type: 'info',
                    html:
                      'our default week only has '+avail_slot+' slots available. Are you able to increase this to '+min_slot+' to make it easier for clients to book sessions?',
                    showCloseButton: true,
                    showCancelButton: true,
                    confirmButtonText:
                      'Set more availability',
                    cancelButtonText:
                      'I will do this later',
            }).then(function () {
                var flag = 1;
                return false;
              },function (dismiss) {
                  if (dismiss === 'cancel') {
                        $("#form_save").val('1');

          jQuery("#btn_save").attr('disabled', 'disabled');
          jQuery('#calendar').fullCalendar('clientEvents', function(event) {
          var id = event.id;
          var start=moment(event.start).format('YYYY-MM-DD H:mm:ss');
          var end=moment(event.end).format('YYYY-MM-DD  H:mm:ss');

          if(id == null)
          {
            jQuery.ajax({
                           type: "POST",
                           url: "{{ route('week.store') }}",
                           data: {'start': start,'end' : end },
                           async: false,
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
                  }
                })
        }
        else{
        $("#form_save").val('1');

        jQuery("#btn_save").attr('disabled', 'disabled');
        jQuery('#calendar').fullCalendar('clientEvents', function(event) {
          var id = event.id;
          var start=moment(event.start).format('YYYY-MM-DD H:mm:ss');
          var end=moment(event.end).format('YYYY-MM-DD  H:mm:ss');
         // alert(end); alert(start);
          if(id == null)
          {
            jQuery.ajax({
                           type: "POST",
                           url: "{{ route('week.store') }}",
                           data: {'start': start,'end' : end },
                           async: false,
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
        }
      });
      window.addEventListener("beforeunload", function (e) {

        //e.preventDefault();

          jQuery('#calendar').fullCalendar('clientEvents', function(event) {
          var id = event.id;
          var form_save = $("#form_save").val();
          //console.log(id);
          if(id != null || form_save == 1){
            e.returnvalue = false;
            return false;
          }

            var confirmationMessage = 'are you want to save your changes before you leave page?';

            (e || window.event).returnValue = confirmationMessage; //Gecko + IE
            //console.log(confirmationMessage);
            return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.

          });
      });
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