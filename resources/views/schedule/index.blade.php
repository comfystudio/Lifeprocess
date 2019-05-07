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
    	<div class="content-wrapper">
    		<div class="panel-body">
    			<div class="row">
    				<button id="btn_save" class="btn bg-success btn-labeled heading-btn pull-right"><b><i class="icon-diff-added"></i></b>Save</button>
    			</div>
    			<br>
    			<div class="alert alert-info" style="font-size: 15px; ">
    				Your Time zone is <span class="bg label bg-info" style="font-size: 13px; text-transform: none;"></span> {{Auth::user()->timezone}}<span class="bg label bg-info pull-right" style="font-size: 13px; text-transform: none;">{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',Carbon\Carbon::now(Auth::user()->timezone))}}</span>
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
			selectable: true,
			selectHelper: true,
			select: function(start, end) {
				if(start.format('YYYY/MM/DD') < (moment().format('YYYY/MM/DD')))
				{
				        bootbox.alert("Session cannot be schedule for the time " + start.format('MM/DD/YYYY HH:mm'));
                        jQuery('#calendar').fullCalendar('unselect');
				}
				else
				{
				 	var duration=moment.duration(end.diff(start));
					var hours = parseInt(duration.asHours());
					if(hours <= 1)
					{
						var title = 'Avilable';
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
                    bootbox.alert('You cannot delete privious date Session.');
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
						 message: "Are you sure you want to delete scedule ?",
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
						  			url: '/schedule/' + id,
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
                        @if($data->coachschedulebooked->session_status == 'cancelled')
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
 			jQuery('#btn_save').click(function(e) {
 				jQuery("#btn_save").attr('disabled', 'disabled');
 				jQuery('#calendar').fullCalendar('clientEvents', function(event) {
 					var id = event.id;
 					var start=moment(event.start).format('YYYY-MM-DD H:mm:ss');
 					var end=moment(event.end).format('YYYY-MM-DD  H:mm:ss');
 					if(id == null )
 					{
 						jQuery.ajax({
			                     type: "POST",
                					 url: "{{ route('schedule.store') }}",
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