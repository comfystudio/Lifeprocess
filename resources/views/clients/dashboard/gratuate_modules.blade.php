@extends($theme)
@section('title', $title)
@section('content')
<input type="hidden" name="program_id_get" id="program_id_get" value="{{ $program_id }}">
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
{{-- {{ print_r($_REQUEST) }} --}}
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
                {{-- Module Title here --}}
                @php
                    $open_panel = '';
                    $active = '';
                    $arraw = 'more-less fa fa-caret-right';
                    if($module->id==Auth::user()->unlocked_module) {
                        $open_panel = 'in';
                        $arraw = 'more-less fa fa-caret-down';
                        $active = 'active';
                    }
                    // else if ((empty($completed_modules[$module->id]['completed_at']) || $completed_modules[$module->id]['completed_at'] == '0000-00-00 00:00:00') && $enable_next) {
                    //     $open_panel = 'in';
                    //     $arraw = 'more-less fa fa-caret-down';
                    //     $active = 'active';
                    // }
                    // dump($enable_next);
                @endphp
                <!-- @if(Auth::user()->unlocked_module>0 && $module->id==Auth::user()->unlocked_module)
                <div class="panel-heading no-padding {{$active}}" role="tab" id="{{ $counter }}">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion " href="#collapse{{ $counter }}" aria-expanded="true" aria-controls="collapseOne">

                                    @if($module->id==Auth::user()->unlocked_module)
                                    {{ trans('comman.module') . ' ' . $module->module_no . ' - ' . $module->module_title }}
                                    @endif


                        </a>
                    </h4>
                    @if(isset($completed_modules[$module->id]) && $completed_modules[$module->id]['status'] == 'reviewed')
                        {!! link_to_route('view.feedback', 'Module Feedback', ['module_id' => Crypt::encryptString($module->id)], ['class' => 'module-feedback hide']) !!}
                    @endif
                </div>
                @elseif(Auth::user()->unlocked_module>0 && $module->id!=Auth::user()->unlocked_module)
                @else
                    <div class="panel-heading no-padding {{$active}}" role="tab" id="{{ $counter }}">
                    <h4 class="panel-title">
                        <a role="button" data-toggle="collapse" data-parent="#accordion " href="#collapse{{ $counter }}" aria-expanded="true" aria-controls="collapseOne">

                            <i class="{{$arraw}}"></i>
                            {{ trans('comman.module') . ' ' . $module->module_no . ' - ' . $module->module_title }}


                            <a href="javascript:void(0);" class="text-green" onclick="return unlock_module({{$module->id}})">Unlock Modules</a>


                        </a>
                    </h4>
                    @if(isset($completed_modules[$module->id]) && $completed_modules[$module->id]['status'] == 'reviewed')
                        {!! link_to_route('view.feedback', 'Module Feedback', ['module_id' => Crypt::encryptString($module->id)], ['class' => 'module-feedback hide']) !!}
                    @endif
                </div>
                @endif -->
                {{-- Module Title here over--}}
                <div id="collapse{{ $counter }}" class="panel-collapse collapse {{ $open_panel }}" role="tabpanel" aria-labelledby="headingOne">
                    <div class="panel-body no-padding">
                        @if(trim($module->introduction_video) != "")
                            <div class="row section no-margin">
                                <div class="col-md-5 col-sm-6 col-xs-6 left">
                                    <p>
                                        @if(isset($completed_modules[$module->id]) && array_key_exists('watch_video', $completed_modules[$module->id]))
                                            <img src="{{asset('themes/limitless/images/client-view/tick.png')}}" class="icon">
                                            @php $enable_next = true; @endphp
                                        @else
                                            <img src="{{asset('themes/limitless/images/client-view/tick-sign.png')}}" class="icon">
                                        @endif
                                        {{ trans('comman.introduction_video') }}
                                    </p>
                                </div>
                                <div class="col-md-7 col-sm-6 col-xs-6 right no-padding">
                                    @if(Auth::user()->unlocked_module!='')
                                        <p><span>|</span> <a href="javascript:void(0);" class="text-green" onclick="load_module_video_orRead({{ $module->id }}, 'video')"><img src="{{asset('themes/limitless/images/client-view/video-img.png')}}" alt="" class="img-responsive pull-right" width="100%"></a></p>
                                    @elseif($enable_next)
                                        <a href="javascript:void(0);" class="fancybox" onclick="return check_delay_between_modules({{$previous_module_id}},{{$module->id}},{{$module->delay_btw_chapter_exercise}},'video')"><img src="{{asset('themes/limitless/images/client-view/video-img.png')}}" alt="" class="img-responsive pull-right" width="100%"></a>
                                        @php
                                            $enable_next = false;
                                        @endphp
                                   @else
                                        <p><span>|</span> <a class="text-black">Please complete earlier exercise </a></p>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if(trim($module->reading_material) != "")
                           <div class="row section no-margin">
                               <div class="col-md-5 col-sm-6 col-xs-6 left">
                                    <p>
                                        @if(Auth::user()->unlocked_module!='')
                                                <img src="{{asset('themes/limitless/images/client-view/tick.png')}}" class="icon">
                                                @php
                                                    $material_read = true;
                                                    $enable_next = true;
                                                @endphp
                                        @else
                                            <img src="{{asset('themes/limitless/images/client-view/tick-sign.png')}}" class="icon">
                                        @endif
                                        {{ trans('comman.reading_material') }}
                                    </p>
                                    {{-- <img src="{{ asset('themes/limitless/images/client-view/tick.png') }}" class="icon">
                                    {{ trans('comman.reading_material') }}  --}}
                                </div>
                                <div class="col-md-7 col-sm-6 col-xs-6 right no-padding">
                                    <p>
                                        <span>|</span>
                                    @if(isset($material_read))
                                        {{-- {!! Html::decode(link_to_asset(AppHelper::path('uploads/module/reading_materials/')->getImageUrl($module->reading_material),trans('comman.download_reading_material') , ['class' => 'text-green', 'target' => '_blank', 'onclick' => "load_module_video_orRead(" . $module->id . " , 'material')"])) !!}  --}}
                                        <a href="{{url('uploads/module/reading_materials/'.$module->reading_material)}}" onclick="load_module_video_orRead({{$module->id}},'material')" download class="text-green">{{trans('comman.download_reading_material')}}</a>
                                    @elseif($enable_next)
                                        {{-- {!! Html::decode(link_to_asset(AppHelper::path('uploads/module/reading_materials/')->getImageUrl($module->reading_material), trans('comman.download_reading_material') , ['class' => 'text-green', 'target' => '_blank', 'onclick' => "return check_delay_between_modules('$previous_module_id', '$module->id', '$module->delay_btw_chapter_exercise', 'material');"])) !!} --}}
                                        <a href="{{url('uploads/module/reading_materials/'.$module->reading_material)}}" onclick="load_module_video_orRead({{$module->id}},'material')" download class="text-green">{{trans('comman.download_reading_material')}}</a>
                                        @php
                                            $enable_next = false;
                                        @endphp
                                    @else
                                        <a class="text-black">Please complete earlier exercise </a>
                                    @endif
                                    </p>
                                </div>
                           </div>
                        @endif
                        @if(count($module->module_exercises) > 0)
                            @foreach ($module->module_exercises as $exercise)
                                <div class="row section no-margin">
                                     @if(isset($completed_modules[$module->id]) && $completed_modules[$module->id]['status'] == 'reviewed')
                                        @php
                                            $enable_next = true;
                                        @endphp
                                        <div class="col-md-5 col-sm-6 col-xs-6 left">
                                            <p>
                                                <img src="{{ asset('themes/limitless/images/client-view/tick.png') }}" class="icon">
                                                {{ trans('comman.exercise') . ' ' . $module->module_no . '.' . $exercise->exercise_no . ' - ' . $exercise->title }}
                                            </p>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-6 right no-padding" id="exercise_{{ $module->id }}_{{ $exercise->id }}">
                                            <p><span>|</span>
                                                {!! link_to_route('view.feedback', 'View Feedback', ['module_id' => Crypt::encryptString($module->id)], ['class' => 'text-green']) !!}
                                            </p>
                                        </div>
                                    @elseif(isset($completed_exercise[$module->id]) && in_array($exercise->id, $completed_exercise[$module->id]))
                                        @php
                                            $enable_next = true;
                                            $page_attr = [];
                                            if(!isset($getPageNoToResumeExercise[$exercise->id]) || $getPageNoToResumeExercise[$exercise->id] == 0) {
                                                $page_attr = ['page' => 1];
                                            } else {
                                                $page_attr = ['page' => $getPageNoToResumeExercise[$exercise->id]];
                                            }
                                        @endphp
                                        <div class="col-md-5 col-sm-6 col-xs-6 left">
                                            <p>
                                                <img src="{{ asset('themes/limitless/images/client-view/login.png') }}" class="icon">
                                                {{ trans('comman.exercise') . ' ' . $module->module_no . '.' . $exercise->exercise_no . ' - ' . $exercise->title }}
                                            </p>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-6 right no-padding" id="exercise_{{ $module->id }}_{{ $exercise->id }}">
                                            <p><span>|</span>
                                            <a href="" class="text-green">Completed-awaiting feedback</a>
                                            @if(isset($completed_modules[$module->id]) && (empty($completed_modules[$module->id]['completed_at']) || $completed_modules[$module->id]['completed_at'] == '0000-00-00 00:00:00'))
                                                 {{--    {!! Html::decode(link_to_route('client-exercises.create', 'Edit', ['module_id' => Crypt::encryptString($module->id), 'exercise_id' => Crypt::encryptString($exercise->id)] + $page_attr, ['class' => 'text-primary'])) !!} --}}
                                            @endif
                                            </p>
                                        </div>
                                    @elseif($enable_next)
                                        @php
                                            $onclickFunction = "";
                                            if($canAccessNextExercise['remaining_exercise'] <= 0) {
                                                $exercisePerDay = $canAccessNextExercise['exercise_to_be_completed_per_day'];
                                                $completedToday = $canAccessNextExercise['total_exercise_completed_today'];
                                                $onclickFunction = "return alert_forExerciseLimitExceedForDay('$exercisePerDay', '$completedToday');";
                                            }
                                            $page_attr = [];
                                            if(!isset($getPageNoToResumeExercise[$exercise->id]) || $getPageNoToResumeExercise[$exercise->id] == 0) {
                                                $page_attr = ['page' => 1];
                                            } else {
                                                $page_attr = ['page' => $getPageNoToResumeExercise[$exercise->id]];
                                            }
                                        @endphp
                                        <div class="col-md-5 col-sm-6 col-xs-6 left">
                                            <p>
                                                <img src="{{ asset('themes/limitless/images/client-view/edit.png') }}" class="icon">
                                                {{ trans('comman.exercise') . ' ' . $module->module_no . '.' . $exercise->exercise_no . ' - ' . $exercise->title }}
                                            </p>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-6 right no-padding" id="exercise_{{ $module->id }}_{{ $exercise->id }}">
                                            <p><span>|</span>
                                            {!! link_to_route('client-exercises.create', 'Click here to continue your work', ['module_id' => Crypt::encryptString($module->id), 'exercise_id' => Crypt::encryptString($exercise->id),'gratuate_session' => 'gratuate'] + $page_attr , ['class' => 'text-black', 'onclick' => $onclickFunction]) !!}
                                             @php
                                                $enable_next = false;
                                             @endphp
                                            </p>
                                        </div>
                                    @else
                                        @php
                                            $enable_next = false;
                                        @endphp
                                        <div class="col-md-5 col-sm-6 col-xs-6 left">
                                            <p>
                                                <img src="{{ asset('themes/limitless/images/client-view/locked.png') }}" class="icon">
                                                {{ trans('comman.exercise') . ' ' . $module->module_no . '.' . $exercise->exercise_no . ' - ' . $exercise->title }}
                                            </p>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-6 right no-padding" id="exercise_{{ $module->id }}_{{ $exercise->id }}">

                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
            @php
                $previous_module_id = $module->id;
            @endphp
        @endforeach
    @else
    <div class="panel panel-default m-b-8">
        <div class="panel-heading no-padding active" role="tab" id="headingOne">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <i class="more-less fa fa-caret-right"></i>
                    {{ trans('comman.no_data_found') }}
                </a>
            </h4>
        </div>
    </div>
    @endif
</div>
@push('scripts')
<script type="text/javascript">
    function unlock_module(module_id, video_or_material = ''){
        var return_val = false;
        if(module_id!=''){
            jQuery.ajax({
            url: '{{ route('ajax.unloack_module') }}',
            data: 'module_id=' + module_id,
            async: false,
            success: function(data) {
                if(data.success == 'true') {
                    if (video_or_material != '') {
                        load_module_video_orRead(module_id, video_or_material);
                    }
                    return_val = true;
                }
                bootbox.alert(data.message);
                return_val = false;
                window.location.reload();
            }
        });
        }
    }
    function check_delay_between_modules(previous_module_id, next_module_id, delay_btw_chapter_exercise, video_or_material = '') {
        var return_val = false;
        if(delay_btw_chapter_exercise==0)
        {
            if (video_or_material != '') {
                        load_module_video_orRead(next_module_id, video_or_material);
                    }
                    return_val = true;
        }
        else
        {
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
    }
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
        var inputOptions = new Promise(function (resolve) {
  setTimeout(function () {
    resolve({
      'gs': 'Book a mini ‘check-in’ session',
      'um': 'Unlock a module',
      'aq': 'Ask Stanton a question'
    })
  }, 2000)
})
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
            @elseif(isset($last_module_progress->completed_at) && $last_module_progress->is_submited_popup == '1' && Auth::user()->gratuate_option=='')
            swal({
                title: "Congratulations! You have three options. Kindly choose one of them.",
                input: "radio",
                confirmButtonColor: "#66BB6A",
                html: true,
                inputOptions: inputOptions,
                inputValidator: function (result) {
                    return new Promise(function (resolve, reject) {
                      if (result) {
                        resolve()
                      } else {
                        reject('You need to select something!')
                      }
                    })
                  }}).then(function(result){
                    $.ajax({
                        method:'POST',
                        url: "{{route('clients.program.gratuatestatuschange')}}",
                        data:{ _token: "{{csrf_token()}}",id : "{{Auth::id()}}",result:result,program_id:$("#program_id_get").val()},
                        success: function(_response)
                        {
                            window.location.href = _response.redirect_url;
                            console.log('popup disable');
                            console.log(_response.redirect_url);
                        }
                    });
                });
        @endif
        @endif
</script>
@endpush
@include('clients.dashboard.popup')
@endsection