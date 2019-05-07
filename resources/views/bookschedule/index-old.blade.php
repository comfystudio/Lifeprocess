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

</style>
@section('title', $title)
@section('content')
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
</div>
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
                                                                    title: "Reson",
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
                                    									jQuery('#calendar').fullCalendar('updateEvent', event);
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