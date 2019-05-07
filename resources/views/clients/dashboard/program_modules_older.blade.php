@extends($theme)
@section('title', $title)
@section('content')
<div class="content-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                @if(count($modules) == $total_reviewd_module_count)
                    <a href=" {!! route('client.certificate') !!}" type="button" class="btn bg-info btn-labeled heading-btn pull-right"><b><i class="fa fa-file-pdf-o"></i></b>{{ trans('comman.saveaspdf')}}</a>
                @endif
            </div>
            <div class="row">
                <div class="col-xs-6 col-md-2">
                    @if(isset($program->program_icon) && !empty($program->program_icon))
                        {{Html::image(AppHelper::path('uploads/program/icons/')->getImageUrl($program->program_icon),'Program icon',array("style" => "height: auto; width: 100%; display: block;"))}}
                    @else
                        {{Html::image(AppHelper::path('uploads/program/icons/')->getDefaultImage(),'Program icon',array("style" => "height: auto; width: 100%; display: block;"))}}
                    @endif
                </div>
                <div class="col-xs-6 col-md-10">
                    <h5 class="panel-title"> {{ ($program->program_name) }}
                    </h5>
                    <hr>
                    <p> {{ $program->sort_description }} </p>
                </div>
            </div>
        </div>
        <div class="panel-body collapsible-group">
            <div class="panel-group ">
                @if(isset($modules) && count($modules) > 0)
                    @php
                        $enable_next = true;
                        $previous_module_id = 0;
                    @endphp
                    @foreach($modules as $module)
                        @php
                            ++$counter;
                        @endphp
                        <div class="panel panel-default">
                            <div class="row">
                            <div class="col-md-9">
                                <div class="clickable panel-heading " data-toggle="collapse" href="#collapse{{ $counter }}">
                                    <div class="row">
                                        <div class="col-md-12" >
                                            <h6 class="panel-title">
                                              <strong>{{ trans('comman.module') . ' ' . $module->module_no . ' - ' . $module->module_title }}</strong>
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3" >
                                <div class="clickable panel-heading">
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            @if(isset($completed_modules[$module->id]) && $completed_modules[$module->id]['status'] == 'reviewed')
                                                {!! link_to_route('view.feedback', 'View Feedback', ['module_id' => Crypt::encryptString($module->id)], ['class' => 'btn btn-success btn-xs']) !!}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            @php
                                $open_panel = '';
                                if($m_id == $module->id) {
                                    $open_panel = ' in';
                                } else if ((empty($completed_modules[$module->id]['completed_at']) || $completed_modules[$module->id]['completed_at'] == '0000-00-00 00:00:00') && $enable_next) {
                                    $open_panel = ' in';
                                }
                                // dump($enable_next);
                            @endphp
                            <div id="collapse{{ $counter }}" class="panel-collapse collapse list-group {{ $open_panel }}">
                                @if(trim($module->introduction_video) != "")
                                    <div class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-8">
                                                @php
                                                    $i_code = '<i class="fa fa-film"></i>';
                                                    // $video_viewed = false;
                                                    if (isset($completed_modules[$module->id]) && array_key_exists('watch_video', $completed_modules[$module->id])) {
                                                        $i_code = '<i class="icon-checkmark" style="color: green;"></i>';
                                                        $enable_next = true;
                                                        // $video_viewed = true;
                                                    }
                                                @endphp
                                                <span id="video_viewed_{{ $module->id }}">
                                                    {!! Html::decode($i_code) !!}
                                                </span>
                                                <strong> &nbsp; {{ trans('comman.introduction_video') }} </strong>
                                            </div>
                                            <div class="col-md-4" id="video_{{ $module->id }}">
                                                @if (isset($completed_modules[$module->id]) && array_key_exists('watch_video', $completed_modules[$module->id]))
                                                    <a href="javascript:void(0);" class="btn btn-default" onclick="load_module_video_orRead({{ $module->id }}, 'video')"><i class="icon-film4"></i>&nbsp; {{ trans('comman.watch') }} </a>
                                                @elseif($enable_next)
                                                    <a href="javascript:void(0);" class="btn btn-default" onclick="return check_delay_between_modules('{{ $previous_module_id }}', '{{$module->id}}', '{{ $module->delay_btw_chapter_exercise }}', 'video');"><i class="icon-film4"></i>&nbsp; {{ trans('comman.watch') }} </a>
                                                    @php
                                                        $enable_next = false;
                                                    @endphp
                                                @else
                                                    <span class="has-stik"> <strong> Please complete earlier exercise </strong></span>
                                                @endif
                                            </div>
                                            {{-- <div class="col-md-2"></div> --}}
                                        </div>
                                    </div>
                                @endif
                                @if(trim($module->reading_material) != "")
                                    <div class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-8">
                                                @php
                                                    $i_code = '<i class="fa fa-file-text-o"></i>';
                                                    $material_read = false;
                                                    if (isset($completed_modules[$module->id]) && array_key_exists('read_material', $completed_modules[$module->id])) {
                                                        $i_code = '<i class="icon-checkmark" style="color: green;"></i>';
                                                        $material_read = true;
                                                        $enable_next = true;
                                                    }
                                                @endphp
                                                <span id="material_read_{{ $module->id }}">
                                                    {!! Html::decode($i_code) !!}
                                                </span>
                                                <strong> &nbsp; {{ trans('comman.reading_material') }} </strong>
                                            </div>
                                            <div class="col-md-4" id="material_link_{{ $module->id }}">
                                                @if($material_read)
                                                    {!! Html::decode(link_to_asset(AppHelper::path('uploads/module/reading_materials/')->getImageUrl($module->reading_material), '<i class="icon-file-eye2"> </i> &nbsp;' . trans('comman.view') , ['class' => 'btn btn-default', 'target' => '_blank', 'onclick' => "load_module_video_orRead(" . $module->id . " , 'material')"])) !!}
                                                @elseif($enable_next)
                                                    {!! Html::decode(link_to_asset(AppHelper::path('uploads/module/reading_materials/')->getImageUrl($module->reading_material), '<i class="icon-file-eye2"> </i> &nbsp;' . trans('comman.view') , ['class' => 'btn btn-default', 'target' => '_blank', 'onclick' => "return check_delay_between_modules('$previous_module_id', '$module->id', '$module->delay_btw_chapter_exercise', 'material');"])) !!}
                                                    @php
                                                        $enable_next = false;
                                                    @endphp
                                                @else
                                                    <span class="has-stik"> <strong> Please complete earlier exercise </strong></span>
                                                @endif
                                            </div>
                                            {{-- <div class="col-md-2"></div> --}}
                                        </div>
                                    </div>
                                @endif
                                {{-- {{ dump($completed_exercise)}} --}}
                                @if(count($module->module_exercises) > 0)
                                    @foreach ($module->module_exercises as $exercise)
                                        <div class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    @if(isset($completed_exercise[$module->id]) && in_array($exercise->id, $completed_exercise[$module->id]) )
                                                        <i class="icon-checkmark" style="color: green;"></i>
                                                        @php
                                                            $enable_next = true;
                                                        @endphp
                                                    @else
                                                        <i class="fa fa-file-o"></i>
                                                    @endif
                                                    <strong> &nbsp; - {{ trans('comman.exercise') . ' ' . $module->module_no . '.' . $exercise->exercise_no . ' - ' . $exercise->title }} </strong>
                                                </div>
                                                <div class="col-md-4" id="exercise_{{ $module->id }}_{{ $exercise->id }}">
                                                    @php
                                                        $page_attr = [];
                                                        if(!isset($getPageNoToResumeExercise[$exercise->id]) || $getPageNoToResumeExercise[$exercise->id] == 0) {
                                                            $page_attr = ['page' => 1];
                                                        } else {
                                                            $page_attr = ['page' => $getPageNoToResumeExercise[$exercise->id]];
                                                        }
                                                    @endphp
                                                    @if(isset($completed_exercise[$module->id]) && in_array($exercise->id, $completed_exercise[$module->id]))
                                                        <h6 style="margin:0;">
                                                            <span class="label label-success">Complete</span> &nbsp;&nbsp;
                                                            @if(isset($completed_modules[$module->id]) && (empty($completed_modules[$module->id]['completed_at']) || $completed_modules[$module->id]['completed_at'] == '0000-00-00 00:00:00'))
                                                                {!! Html::decode(link_to_route('client-exercises.create', '<i class="fa fa-pencil"></i> Edit', ['module_id' => Crypt::encryptString($module->id), 'exercise_id' => Crypt::encryptString($exercise->id)] + $page_attr, ['class' => 'label label-primary'])) !!}
                                                            @endif
                                                        </h6>
                                                        {{-- completed_at --}}
                                                    @elseif($enable_next)
                                                    {{-- {{ dump($canAccessNextExercise) }} --}}
                                                        @php
                                                            $onclickFunction = "return check_delay_between_modules('$previous_module_id', '$module->id', '$module->delay_btw_chapter_exercise');";
                                                            if($canAccessNextExercise['remaining_exercise'] <= 0) {
                                                                $exercisePerDay = $canAccessNextExercise['exercise_to_be_completed_per_day'];
                                                                $completedToday = $canAccessNextExercise['total_exercise_completed_today'];
                                                                $onclickFunction = "return alert_forExerciseLimitExceedForDay('$exercisePerDay', '$completedToday');";
                                                            }
                                                        @endphp
                                                        {!! link_to_route('client-exercises.create', 'Incomplete', ['module_id' => Crypt::encryptString($module->id), 'exercise_id' => Crypt::encryptString($exercise->id)] + $page_attr, ['class' => 'btn btn-warning', 'onclick' => $onclickFunction]) !!}
                                                        @php
                                                            $enable_next = false;
                                                        @endphp
                                                    @else
                                                        <span class="has-stik"> <strong> Please complete earlier exercise </strong></span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-8">
                                                @if((isset($completed_modules[$module->id]) && (!empty($completed_modules[$module->id]['completed_at']) && $completed_modules[$module->id]['completed_at'] != '0000-00-00 00:00:00')) || $enable_next)
                                                    <i class="icon-checkmark" style="color: green;"></i>
                                                    @php
                                                        $enable_next = true;
                                                    @endphp
                                                @else
                                                    <i class="fa fa-file-o"></i>
                                                @endif
                                                <strong> &nbsp; End of exercises </strong>
                                            </div>
                                            <div class="col-md-4">
                                                @if(isset($completed_modules[$module->id]))
                                                    @if(empty($completed_modules[$module->id]['completed_at']) || $completed_modules[$module->id]['completed_at'] == '0000-00-00 00:00:00')
                                                        <h6 style="margin:0;">
                                                            <span class="label label-warning">Incomplete</span> &nbsp;&nbsp;
                                                        </h6>
                                                    @else
                                                        <h6 style="margin:0;">
                                                            <span class="label label-success">Submitted for Review</span> &nbsp;&nbsp;
                                                        </h6>
                                                    @endif
                                                @else
                                                    <h6 style="margin:0;">
                                                        <span class="label label-warning">Incomplete</span> &nbsp;&nbsp;
                                                    </h6>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @php
                            $previous_module_id = $module->id;
                        @endphp
                    @endforeach
                @else
                    <div class="panel panel-default">
                        <div class="clickable panel-heading " data-toggle="collapse" href="#collapse1">
                            <div class="row">
                                <div class="col-md-10">
                                    <h6 class="panel-title">
                                      <strong>{{ trans('comman.no_data_found') }}</strong>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script type="text/javascript">
    function check_delay_between_modules(previous_module_id, next_module_id, delay_btw_chapter_exercise, video_or_material = '') {
        var return_val = false;
        jQuery.ajax({
            url: '{{ route('ajax.check_delay_between_modules') }}',
            data: 'previous_module_id=' + previous_module_id + '&next_module_id=' + next_module_id + '&delay_btw_chapter_exercise=' + delay_btw_chapter_exercise,
            async: false,
            success: function(data) {
                if(data.success == 'true') {
                    if (video_or_material != '') {
                        load_module_video_orRead(next_module_id, video_or_material);
                    }
                    return_val = true;
                }
                bootbox.alert(data.message);
                return_val = false;
            }
        });
        return return_val;
    }
    function alert_forExerciseLimitExceedForDay(exercises_perDay, completedToday) {
        bootbox.alert('You can complete only ' + exercises_perDay + ' exercise(s) per day.');
        return false;
    }
        @php
            if(isset($page))
            {
                $content=strip_tags($page->content);
            }
            else
            {
                $content='Static page client-module-completed not found';
            }
        @endphp
        @if(isset($last_module_progress))
            @if(isset($last_module_progress->completed_at) && $last_module_progress->is_submited_popup == '0')
            swal({
                title: "Good job!",
                text: "{{$content}}",
                confirmButtonColor: "#66BB6A",
                type: "success"
                }).then(function(){
                    $.ajax({
                        method:'POST',
                        url: "{{route('clients.program.statuschange')}}",
                        data:{ _token: "{{csrf_token()}}",id : "{{$last_module_progress->id}}"},
                        success: function(_response)
                        {
                            console.log('popup disable');
                        }
                    });
                });
            @endif
        @endif
</script>
@endpush
@include('clients.dashboard.popup')
@endsection