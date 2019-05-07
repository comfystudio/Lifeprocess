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
#calendar .fc-view > table {
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
                <strong class="font-bold">3.</strong> Choose a Meeting Type
            </span>
        </p>
    </div>
    @foreach($schedule as $data)
        {{-- {{ dump($data) }} --}}
    @endforeach
    <div class="form-content">
        {!! Form::open(array('route' => 'bookschedule.store','class'=>'form-horizontal no-margin','role'=>"form",'id'=>'book_session_form')) !!}
            <div class="row step1 no-margin">
                <div class="col-md-2 col-sm-3 col-xs-12">
                    <h4 class="font-semibold no-margin">Step 1</h4>
                </div>
                <div class="col-md-10 col-sm-9 col-xs-12">
                    <div id="choose-date"></div>
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
                    {!! Form::select('time-slot', ['' => 'Select Available Time'], null, ['id' => 'time-slot']) !!}
                    {{-- <p class="fyi-msg">FYI: This will be 15:00 - 16:00 in your coaches timezone.</p> {{ $coach_user_timezone }} --}}
                    <p class="fyi-msg">FYI:</p> {{ $coach_user_timezone }}
                </div>
            </div>
            <div class="row step3 no-margin disabled">
                <div class="col-md-2 col-sm-3 col-xs-12">
                    <h4 class="font-semibold no-margin">Step 3</h4>
                </div>
                <div class="col-md-10 col-sm-9 col-xs-12">
                    <p>Choose meeting type</p>
                    <div class="chk-content">
                        <div class="checkboxFive">
                            {!! Form::checkbox('meet_type[]', 'skype', null, ['id' => 'skype']) !!}
                            <label for="skype""></label>
                        </div>
                        <span>Skype</span>
                        <div class="checkboxFive">
                            {!! Form::checkbox('meet_type[]', 'chat', null, ['id' => 'chat']) !!}
                            <label for="chat"></label>
                        </div>
                        <span>Chat</span>
                        <div class="checkboxFive">
                            {!! Form::checkbox('meet_type[]', 'phone', null, ['id' => 'phone']) !!}
                            <label for="phone"></label>
                        </div>
                        <span>Phone</span>
                    </div>
                </div>
            </div>
            {!! Form::hidden("coach_schedules_id",'') !!}
            <div class="row step4 no-margin disabled">
                <div class="col-md-offset-2 col-sm-offset-3 col-md-10 col-sm-9 col-xs-12">
                    {!! Form::submit('Book your Session', ['name' => 'submit']) !!}
                </div>
            </div>
        {!! Form::close() !!}
    </div>
</div>

{{--
<div class="content-wrapper">
    <div class="row">
        <div class="result">
        </div>
    </div>
	<div class="panel panel-default">
		<div class="content-wrapper">
			<div class="panel-body">
				<div class="row">
					<div class="alert alert-info" style="font-size: 15px; ">
                        This will be time <span class="bg label bg-info" style="font-size: 13px; text-transform: none;">{{ $coach_user_time }}</span> in your coach timezone <span class="bg label bg-info" style="font-size: 13px; text-transform: none;">{{ $coach_user_timezone }}.</span>
                    </div>
				</div>
				<div id='calendar'></div>
			</div>
		</div>
	</div>
</div> --}}
@push('scripts')
<script>
	 jQuery(document).ready(function() {
	 	jQuery('#calendar').fullCalendar({
	 		header: {
	 			left: 'prev,next today',
	 			center: 'title',
	 		},
                    allDaySlot:false,
		            defaultDate: Date(),
		            timeFormat: 'H:mm',
		            defaultView: 'agendaWeek',
		            firstDay:1,
		            slotDuration : '01:00:00',
		            slotLabelFormat:'H:mm',
		            selectable: false,
		            select:false,
		            eventClick: function (event){
                        console.log(event.start.format('HH'));
                        console.log(moment().format('HH'));
		            		if(event.start.format('YYYY/MM/DD') < moment().format('YYYY/MM/DD'))
		            		{
                                if(event.title == 'Booked') {
                                    bootbox.alert('You can not cancel privious date Session');
                                    return false;
                                } else {
                                    bootbox.alert('You can not book privious date Session');
                                    return false;
                                }
		            		}
		            		else
		            		{
		            			jQuery.ajax({
		            				url: '{{ route('ajax.checkClientCredit') }}',
                        					success: function(responseData)
                        					{
                        						if(event.title == 'Booked')
                        						{
                                                    var curent_user_id = {{Auth::id()}};
                                                    if(event.user_id == curent_user_id)
                                                    {
                            							bootbox.confirm({
                            								message: 'Schedule is already booked. Do you want to cancel the schedule?',
                            								buttons:
                            								{
                            									confirm:
                            									{
                            										label: 'Yes, I want',
                            										className: 'btn-warning'
                            									},
                            									cancel:
                            									{
                            										label: 'No',
                            										className: 'btn-default'
                            									}
                            								},
                            								callback: function (result)
                            								{
                            									if(result)
                            									{
                            										bootbox.prompt({
                                                                    title: "Reason",
                                                                    inputType: 'textarea',
                                                                    callback: function (result)
                                                                    {
                                                                        if(result)
                                                                        {
                                                                            jQuery.ajax({
                                                                                url: '{{ route('ajax.cancelBookedSchedule') }}',
                                                                                data: 'booked_schedule_id=' + event.id + '&booked_user_id={{Auth::id() }}&schedule=' + moment(event.start).format('YYYY-MM-DD HH:mm:ss') + '&created_at=' + event.created_at +'&reson=' + result,
                                                                                 success: function(_response)
                                                                                 {
                                                                                     if(_response.success == 'false')
                                                                                     {
                                                                                         bootbox.alert(_response.message);
                                                                                     }
                                                                                     else
                                                                                     {
                                                                                         bootbox.alert(_response.message);
                                                                                         jQuery('#my_total_credits').html(_response.final_credits);
                                                                                         jQuery("#my_total_credits").show();
                                                                                         event.title = 'Available';
                                                                                         event.backgroundColor = '#039BE5';
                                                                                         event.borderColor = '#039BE5';
                                                                                         event.created_at = '';
                                                                                         jQuery('#calendar').fullCalendar('updateEvent', event);
                                                                                     }
                                                                                 }

                                                                            });
                                                                        }
                                                                    }
                                                        });
                            									}
                            								}
                            							});
                                                        return false;
                                                    } else {
                                                        bootbox.alert('This scedule is booked by other client. So you cann\'t delete it.');
                                                        return false;
                                                    }
                        						}
                        						if (responseData.is_available == 'false')
                        						{
                        							bootbox.alert("Sorry, you don't have enough credit to book the session.");
                        							return false;
                        						}
                        						var start = moment(event.start).format('YYYY-MM-DD H:mm:ss');
                            					var end = moment(event.start).format('YYYY-MM-DD H:mm:ss');
                            					bootbox.confirm({
                            						message: "Are you sure you want book?",
                            						buttons:{
                            							confirm:
                            							{
                            								label: 'Yes',
                                                			className: 'btn-success'
                            							},
                            							cancel:
                            							{
                            								label: 'No',
                                                			className: 'btn-danger'
                            							}
                            						},
                            						callback: function (result)
                            						{
                            							if(result == true)
                            							{
                                                            console.log(start);
                                                            var coach_schedules_id = event.id;
                            								console.log("id: "+coach_schedules_id);
                            								jQuery.ajax({
                            									type: "POST",
                            									url: '{{ route('bookschedule.store') }}',
                            									data: {'coach_schedules_id': coach_schedules_id },
                            									success: function(response,result)
                            									{
                            										if(response.status == "success")
                            										{
                            											console.log('event added' + coach_schedules_id);
                            											event.title = 'Booked';
                            											event.backgroundColor = '#EF5350';
                                    									event.borderColor = '#EF5350';
                                    									event.created_at = '{{ Carbon\Carbon::now()->format("Y-m-d H:i:s") }}';
                                    									jQuery('#choose-date').fullCalendar('updateEvent', event);
                                    									if (responseData.my_total_credits)
                                    									{
                                    										jQuery("#my_total_credits").html(responseData.my_total_credits - 1);
                                    									}
                                    									if(!(responseData.my_total_credits - 1))
                                    									{
                                    										jQuery("#my_total_credits").hide();
                                    									}
                                    									else
                                    									{
                                    										jQuery("#my_total_credits").show();
                                    									}
                                                                        bootbox.alert(response.message);
                            										} else {
                                                                        bootbox.alert(response.message,
                                                                        function()
                                                                        {
                                                                            window.location.reload(true);
                                                                        });
                                                                    }
                            									},
                            									headers:
                            									{
                            										 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            									}
                            								});
                            							}
                            						}
                            					});


                        					}
		            			});
		            		}
		            },
		            editable: true,
		            eventLimit: true, // allow "more" link when too many events
		            eventDurationEditable:false,
		            events: [
		            		@foreach($schedule as $data)
		            			// console.log('{{ Html::decode($data) }}')
		            			@if($data->coachschedulebooked != null)
		            			{
		            				title: 'Booked',
			                        start: '{{$data->start_datetime}}',
			                        end:'{{$data->end_datetime}}',
			                        id:'{{$data->id}}',
                                    user_id:'{{$data->coachschedulebooked->booked_user_id}}',
			                        color:'#EF5350',
			                        created_at: '{{ $data->coachschedulebooked->created_at }}'
		            			},
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

        jQuery('#choose-date').fullCalendar({
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
                    @if($data->coachschedulebooked == null)
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
                if($(this).hasClass('font-bold')) {
                    $('#time-slot option').each(function() {
                        if ( $(this).val() != '' ) {
                            $(this).remove();
                        }
                    });
                    @foreach($schedule as $data)
                    {
                        @if($data->coachschedulebooked == null)
                        {
                            var cur_date = '{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->start_datetime)->format('Y-m-d')}}';
                            if('{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->start_datetime)->format('Y-m-d H:i')}}' >= moment().format('YYYY-MM-DD H:m'))
                            {
                                if(date.format('YYYY/MM/DD') == '{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->start_datetime)->format('Y/m/d')}}')
                                {
                                    var option = '';
                                    $("td").each(function(index, el) {
                                        if($(this).hasClass('text-white')) {
                                            $(this).removeClass('text-white');
                                            $(this).removeAttr('style');
                                        }
                                    });
                                    $("td[data-date='"+cur_date+"']").addClass('text-white');
                                    $("td[data-date='"+cur_date+"']").css('background-color','#82CD49');
                                    var start = '{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->start_datetime)->format('H:i') }}';
                                    var end = '{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$data->end_datetime)->format('H:i') }}';
                                    option += '<option value="'+ start+ '-' +end + '">' + start + ' - ' + end + '</option>';
                                    $('#time-slot').append(option);
                                    $('.row.step2').removeClass('disabled');
                                    $('#time-slot').change(function(event) {
                                        $('input[name="coach_schedules_id"]').val('{{ $data->id }}');
                                        var time = '{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->start_datetime, 'UTC')->setTimezone($coach_user_timezone) }}';
                                        // console.log(time);
                                        console.log(cur_date+ " " +$('#time-slot').val().split('-')[0]);
                                        $('.fyi-msg').text('FYI: This will be {{ $coach_user_time }} in your coaches timezone.');
                                        $('.row.step3').removeClass('disabled');
                                        $('.row.step4').removeClass('disabled');
                                    });
                                }
                            }
                        }
                        @endif
                    }
                    @endforeach
                }
            },
        });
	});
    $('#book_session_form input[type="submit"]').click(function(event) {
        event.preventDefault();
        jQuery.ajax({
            url: '{{ route('ajax.checkClientCredit') }}',
            success: function(responseData)
            {
                console.log("responseData : "+responseData.is_available);
                console.log("responseData : "+responseData.my_total_credits);
                if (responseData.is_available == 'false')
                {
                    bootbox.alert("Sorry, you don't have enough credit to book the session.");
                    return false;
                }
                bootbox.confirm({
                    message: "Are you sure you want book?",
                    buttons:{
                        confirm:
                        {
                            label: 'Yes',
                            className: 'btn-success'
                        },
                        cancel:
                        {
                            label: 'No',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result)
                    {
                        if(result == true)
                        {
                            if($('input[name="coach_schedules_id"]').val() != '') {
                                var coach_schedules_id = $('input[name="coach_schedules_id"]').val();
                                jQuery.ajax({
                                    type: "POST",
                                    url: '{{ route('bookschedule.store') }}',
                                    data: $('#book_session_form').serialize(),
                                    success: function(response,result)
                                    {
                                        if(response.status == "success")
                                        {
                                            bootbox.alert(response.message);
                                        } else {
                                            bootbox.alert(response.message,
                                            function()
                                            {
                                                window.location.reload(true);
                                            });
                                        }
                                    },
                                    headers:
                                    {
                                         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    }
                                });
                            } else {
                                bootbox.alert('Please select your schedule');
                            }
                        }
                    }
                });
            }
        });
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