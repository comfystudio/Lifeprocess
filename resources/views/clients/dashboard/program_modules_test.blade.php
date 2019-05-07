@extends($theme)
@section('title', $title)
@section('content')
<style type="text/css">
    .btn-warning
    {
        margin-top: 8px;
    }
    .player .video-wrapper, .player .video-wrapper .telecine, .player .video-wrapper object, .player .video-wrapper video
    {
        width: 80%;
    }
    .loadvideo
    {
        display: none;
    }
    #introduction
    {
        display: none;
    }
</style>


{{-- <a href="#" class="text-green openvideo" >Click here to show video </a>
<p class="loadvideo">hello</p>
 --}}
@php
$open_gratuate='display:none';
if(isset($intro_video_watch) && !empty($intro_video_watch))
{
    if($intro_video_watch->is_intro_video_watch=='1')
    {
        $open_video = 'display:none';
        $open_panel='display:none';
        $arraw_intro = 'more-less fa fa-caret-right';
        $active_intro = '';
    }
}
else
{
    $open_video = 'display:block';
    $open_panel='display:block';
    $arraw_intro  = 'more-less fa fa-caret-down';
    $active_intro = 'active';
}
@endphp

<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true" >
    <div class="panel panel-default">
        @if(Auth::user()->is_gratuate=='n')
            <div class="panel-heading no-padding {{$active_intro}}" role="tab">
                <h4 class="panel-title">
                    <a id="open_intro_video" href="#">
                        <i class="{{$arraw_intro}}"></i>
                        Introduction Video
                    </a>
                </h4>
            </div>
        @endif
        <div class="panel-body" id="introduction" style={{$open_panel}}>
            {{-- <p>{{ $program->sort_description }}</p> --}}
            @php
            echo $program->sort_description;
            @endphp
            <br>

            <input type="hidden" id="program_id" value="{{$program->id}}">
            {{--    <a href="javascript:void(0);" class="text-green" onclick="load_video()">Watch Video</a> --}}
            <input type="hidden" id="videolink" value="{{$program->introduction_video}}">
            <input type="hidden" name="intro_video" value="no" id="videowatch">
            <div class="row section no-margin">
                <div data-vimeo-url="{{$program->introduction_video}}" id="intro" style={{$open_video}} data-vimeo-height="540px">
                </div>
                {{-- <button class="btn btn-success refresh">Intro Video Watched</button> --}}
            </div>
        </div>
    </div>
<div class="panel panel-default">
    @if(Auth::user()->is_gratuate=='n')
        <div class="panel-heading no-padding" role="tab">
          <h4 class="panel-title">
            <a id="open_intro_video" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
              <i class="{{$arraw_intro}}"></i>
                Introduction Video
            </a>
          </h4>
        </div>
    @endif
    <div id="collapseOne" class="panel-collapse collapse">
      <div class="panel-body">
        @php
            echo $program->sort_description;
            @endphp
            <br>

            <input type="hidden" id="program_id" value="{{$program->id}}">
            {{--    <a href="javascript:void(0);" class="text-green" onclick="load_video()">Watch Video</a> --}}
            <input type="hidden" id="videolink" value="{{$program->introduction_video}}">
            <input type="hidden" name="intro_video" value="no" id="videowatch">
            <div class="row section no-margin">
                <div data-vimeo-url="{{$program->introduction_video}}" id="intro" style={{$open_video}} data-vimeo-height="540px">
                </div>
                {{-- <button class="btn btn-success refresh">Intro Video Watched</button> --}}
            </div>
      </div>
    </div>
</div>

        @if(isset($modules) && count($modules) > 0)
        @php
        $enable_next = true;
        $previous_module_id = 0;
        $next_module=0;
        @endphp
        @foreach($modules as $module)
        @php
        ++$counter;
        @endphp

        <div class="panel panel-default">
        @php
            $total_module=count($modules);
            $total_excercise_complete=sizeof($completedmoduleexcercise);
            $lastarraykey=end($total_completed_excercise);

            if(!empty($completedmoduleexcercise))
            {
                $total_excercise_complete=$total_excercise_complete-1;
                $viewdata=$completedmoduleexcercise[$total_excercise_complete];
            }
            else
            {
                $viewdata=array();
            }
            for($i=0;$i<$total_excercise_complete;$i++)
            {
                if($completedmoduleexcercise[$i]['module_exercise_id']=='0')
                {

                    unset($completedmoduleexcercise[$i]);
                }
            }
            $completedmoduleexcercise = array_values($completedmoduleexcercise);
            $total_excercise_complete=count($completedmoduleexcercise);
            for($j=0;$j<$total_excercise_complete;$j++)
            {

                if($completedmoduleexcercise[$j]['module_id']==$previous_module_id)
                {
                    $open_panel = 'in';
                    $arraw = 'more-less fa fa-caret-down';
                    $active = 'active';
                    unset($completedmoduleexcercise[$j]);
                }
            }
            $completedmoduleexcercise = array_values($completedmoduleexcercise);
            $total_excercise_complete=sizeof($completedmoduleexcercise);
            $open_panel = '';
            $active = '';
            $arraw = 'more-less fa fa-caret-right';
            if($program->watch_video=='yes')
            {
                if($lastarraykey['module_id']==$module->id)
                {
                    $open_panel = 'in';
                    $arraw = 'more-less fa fa-caret-down';
                    $active = 'active';
                }
                else
                {
                    $open_panel = '';
                    $arraw = 'more-less fa fa-caret-down';
                    $active = '';
                }
                if(empty($previous_module_id))
                {

                    if ((empty($completed_modules[$module->id]['completed_at']) || $completed_modules[$module->id]['completed_at'] == '0000-00-00 00:00:00') && $enable_next)
                    {
                        $open_panel = 'in';
                        $arraw = 'more-less fa fa-caret-down';
                        $active = 'active';
                    }
                }
            }
            if(isset($intro_video_watch) && !empty($intro_video_watch))
            {
                if($intro_video_watch->is_intro_video_watch=='0')
                {
                    $open_panel = '';
                    $arraw = 'more-less fa fa-caret-down';
                    $active = '';
                }
            }
            else
            {
                $open_panel = '';
                $arraw = 'more-less fa fa-caret-down';
                $active = '';
            }
            if(!empty($modules[$counter]->module_no))
            {
                                //dd($total_excercise_complete);
                if($total_excercise_complete==count($module->module_exercises))
                {
                    $open_panel = '';
                    $arraw = 'more-less fa fa-caret-down';
                    $active = '';
                    $next_module=$modules[$counter]->module_no;
                    $open_gratuate='display:block';
                }
            }
            if($next_module==$module->module_no)
            {
                $open_panel = 'in';
                $arraw = 'more-less fa fa-caret-down';
                $active = '';
            }
            if(Auth::user()->is_gratuate=='y' && Auth::user()->gratuate_option=='um')
            {
                if(Auth::user()->unlocked_module==0)
                {
                    $open_panel = '';
                    $arraw = 'more-less fa fa-caret-down';
                    $active = '';
                }
                else if($module->id==Auth::user()->unlocked_module)
                {
                    $open_panel = 'in';
                    $arraw = 'more-less fa fa-caret-down';
                    $active = 'active';
                }
                else
                {
                   $open_panel = '';
                   $arraw = 'more-less fa fa-caret-down';
                   $active = '';
                }
            }
        @endphp

        @if(Auth::user()->is_gratuate=='y' && Auth::user()->gratuate_option=='um')
            @if(Auth::user()->unlocked_module==0)
                <div class="panel-heading no-padding {{$active}}" role="tab" id="{{ $counter }}">
                    <h4 class="panel-title">
                        <a role="button" href="#">
                            <i class="{{$arraw}}"></i>
                            {{ trans('comman.module') . ' ' . $module->module_no . ' - ' . $module->module_title }}
                        </a>s
                    </h4>
                    <a href="#" class="text-green" onclick="return unlock_module({{$module->id}})">Unlock Modules</a>
                </div>
            @elseif(Auth::user()->unlocked_module>0)
                    @if($module->id==Auth::user()->unlocked_module)
                        <div class="panel-heading no-padding {{$active}}" role="tab" id="{{ $counter }}">
                            <h4 class="panel-title">
                                <a role="button" data-toggle="collapse" data-parent="#accordion " href="#collapse{{ $counter }}" aria-expanded="true" aria-controls="collapseOne">
                                    <i class="{{$arraw}}"></i>
                                    {{ trans('comman.module') . ' ' . $module->module_no . ' - ' . $module->module_title }}
                                </a>
                            </h4>
                            @if(isset($completed_modules[$module->id]) && $completed_modules[$module->id]['status'] == 'reviewed' && $completed_modules[$module->id]['is_gratuate_module'] == 'y')
                            {!! link_to_route('view.feedback', 'Module Feedback', ['module_id' => Crypt::encryptString($module->id),'exercise_id' => Crypt::encryptString($exercise->id)], ['class' => 'module-feedback hide']) !!}
                            @else

                            @endif
                        </div>
                    @endif
            @elseif($module->id!=Auth::user()->unlocked_module)

            @endif
        @else
            <div class="panel-heading no-padding {{$active}}" role="tab" id="{{ $counter }}">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion " href="#collapse{{ $counter }}" aria-expanded="true" aria-controls="collapseOne">
                        <i class="{{$arraw}}"></i>
                        {{ trans('comman.module') . ' ' . $module->module_no . ' - ' . $module->module_title }}
                    </a>
                </h4>
                @php $reviewed=0; @endphp
                @for($i = 0; $i < $total_excercise_complete; $i++)
                    @if($completedmoduleexcercise[$i]['status']=='reviewed')
                        @php
                        $reviewed++;
                        @endphp
                    @endif
                @endfor
                @if($reviewed==$total_module)
                    {!! link_to_route('view.feedback', 'Module Feedback', ['module_id' => Crypt::encryptString($module->id),'exercise_id' => ''], ['class' => 'module-feedback hide']) !!}
                @endif
            </div>
        @endif

        {{-- Module Title here over--}}
        <div id="collapse{{ $counter }}" class="panel-collapse collapse {{ $open_panel }}" role="tabpanel" aria-labelledby="headingOne">
            <div class="panel-body no-padding">
                @if(trim($module->introduction_video) != "")
                    <div class="row section no-margin">
                        <div class="col-md-12 col-sm-6 col-xs-6 left">
                            <p>
                                @if(Auth::user()->is_gratuate=='y' && Auth::user()->unlocked_module!='')
                                    @php $enable_next = true; @endphp
                                @elseif(isset($complete[$module->id]) && array_key_exists('watch_video', $completed_modules[$module->id]))
                                    <img src="{{asset('themes/limitless/images/client-view/tick.png')}}" class="icon">
                                    @php $enable_next = true; @endphp
                                @else
                                    <img src="{{asset('themes/limitless/images/client-view/tick-sign.png')}}" class="icon">
                                @endif
                                    {{ trans('comman.introduction_video') }}
                            </p>
                        </div>

                        <div class="col-md-12 col-sm-6 col-xs-6 right">
                            @if(isset($completed_modules[$module->id]) && array_key_exists('watch_video', $completed_modules[$module->id]))
                                <p>
                                    <a href="#" class="text-green openvideo" >Click here to show video </a>
                                    <div data-vimeo-url="{{$module->introduction_video}}" class="loadvideo" data-vimeo-height="540px">
                                </div>
                                </p>
                            @elseif($enable_next && $program->watch_video=='yes')

                                    <input type="hidden" id="previous_module_id" value="{{$previous_module_id}}">
                                    <input type="hidden" id="next_module_id" value="{{$module->id}}">
                                    <input type="hidden" id="delay_btw_chapter_exercise" value="{{$module->delay_btw_chapter_exercise}}">
                                    <div data-vimeo-url="{{$module->introduction_video}}" id="modulevideo" data-vimeo-height="540px">

                                    </div>

                                   {{--  <button class="btn btn-success refresh">Complete</button> --}}

                                    @php
                                    $enable_next = false;
                                    @endphp
                            @else
                                    <p><span>|</span> <a class="text-black" href="#"> Please complete earlier exercise </a></p>
                            @endif

                        </div>
                @endif
                @if(trim($module->reading_material) != "")
                    <div class="row section no-margin">
                        <div class="col-md-5 col-sm-6 col-xs-6 left">
                            <p>
                                @if(Auth::user()->is_gratuate=='y' && Auth::user()->unlocked_module!='')
                                        @php $enable_next = true; @endphp
                                @elseif(isset($completed_modules[$module->id]) && array_key_exists('read_material', $completed_modules[$module->id]))
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
                        </div>
                        <div class="col-md-7 col-sm-6 col-xs-6 right no-padding">
                                    <p>
                                        <span>|</span>
                                        @if(isset($completed_modules[$module->id]) && array_key_exists('watch_video', $completed_modules[$module->id]))

                                        <a href="{{url('uploads/module/reading_materials/'.$module->reading_material)}}" onclick="load_module_video_orRead({{$module->id}},'material')" download class="text-green">{{trans('comman.download_reading_material')}}</a>

                                        @elseif($enable_next && $program->watch_video=='yes')
                                        <a href="{{url('uploads/module/reading_materials/'.$module->reading_material)}}" onclick="return check_delay_between_modules({{$previous_module_id}},{{$module->id}},{{$module->delay_btw_chapter_exercise}},'material')" download>{{trans('comman.download_reading_material')}}</a>

                                        @php
                                        $enable_next = false;
                                        @endphp
                                        @else
                                        <a href="#" class="refresh">click here if video watched </a> |
                                        <a class="text-black" href="#"> Please complete earlier exercise </a>
                                        @endif
                                    </p>
                        </div>
                    </div>
                @endif
                    @php
                    $lastindex=0;
                    $lastarray=0;
                    @endphp

                    @if(Auth::User()->is_gratuate=='y' && Auth::User()->unlocked_module!='')
                        @if(count($module->module_exercises) > 0)
                            @foreach($module->module_exercises as $exercise)
                                <div class="row section no-margin">
                                    @php
                                    if($lastarray<$total_excercise_complete)
                                    {
                                        @endphp
                                        @for($i =$lastarray; $i<=$total_excercise_complete; $i++)
                                            @php
                                            if($completedmoduleexcercise[$i]['module_exercise_id']==$exercise->id)
                                            {

                                                if($completedmoduleexcercise[$i]['status']=='reviewed')
                                                {
                                                    // echo $val;
                                                    $enable_next = true;
                                                    //echo $exercise->id;
                                                    @endphp
                                                    <div class="col-md-5 col-sm-6 col-xs-6 left">
                                                        <p>
                                                            <img src="{{ asset('themes/limitless/images/client-view/tick.png') }}" class="icon">
                                                            {{ trans('comman.exercise') . ' ' . $module->module_no . '.' . $exercise->exercise_no . ' - ' . $exercise->title }}
                                                        </p>
                                                    </div>
                                                    <div class="col-md-7 col-sm-6 col-xs-6 right no-padding" id="
                                                    exercise_{{ $module->id }}_{{ $exercise->id }}">
                                                        <p><span>|</span>
                                                            {!! link_to_route('view.feedback', 'View Feedback', ['module_id' => Crypt::encryptString($module->id),'exercise_id' => Crypt::encryptString($exercise->id)], ['class' => 'text-green']) !!}
                                                        </p>
                                                    </div>
                                                    @php
                                                    $lastarray++;
                                                    break;
                                                }
                                                else
                                                {
                                                    //echo $exercise->id;
                                                    @endphp
                                                        <div class="col-md-5 col-sm-6 col-xs-6 left">
                                                            <p>
                                                                <img src="{{ asset('themes/limitless/images/client-view/login.png') }}" class="icon">
                                                                {{ trans('comman.exercise') . ' ' . $module->module_no . '.' . $exercise->exercise_no . ' - ' . $exercise->title }}
                                                            </p>
                                                        </div>
                                                        <div class="col-md-7 col-sm-6 col-xs-6 right no-padding" id="exercise_{{ $module->id }}_{{ $exercise->id }}">
                                                            <p><span>|</span>
                                                                <a href="#" class="text-green">Completed-awaiting feedback </a>
                                                                {!! link_to_route('view.excercise', 'View Excercise', ['module_id' => Crypt::encryptString($module->id),'exercise_id' => Crypt::encryptString($exercise->id)], ['class' => 'text-green']) !!}
                                                            </p>
                                                        </div>
                                                    @php
                                                    $lastarray++;
                                                    break;
                                                }

                                            }
                                            else if($enable_next)
                                            {
                                                $onclickFunction = "";
                                                if($canAccessNextExercise['remaining_exercise'] <= 0)
                                                {
                                                    $exercisePerDay = $canAccessNextExercise['exercise_to_be_completed_per_day'];
                                                    $completedToday = $canAccessNextExercise['total_exercise_completed_today'];
                                                            //$onclickFunction = "";
                                                    $onclickFunction = "return alert_forExerciseLimitExceedForDay('$exercisePerDay', '$completedToday');";
                                                }
                                                $page_attr = [];
                                                if(!isset($getPageNoToResumeExercise[$exercise->id]) || $getPageNoToResumeExercise[$exercise->id] == 0)
                                                {
                                                    $page_attr = ['page' => 1];
                                                } else
                                                {
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
                                                        {!! link_to_route('client-exercises.create', 'Click here to continue your work', ['module_id' => Crypt::encryptString($module->id), 'exercise_id' => Crypt::encryptString($exercise->id)] + $page_attr, ['class' => 'text-black', 'onclick' => $onclickFunction]) !!}
                                                        @php
                                                        $enable_next = false;
                                                        @endphp
                                                    </p>
                                                </div>
                                                @php
                                                break;
                                            }
                                            else
                                            {
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
                                                @php
                                                        //$lastarray++;
                                                break;
                                            }
                                            @endphp
                                            @endfor
                                            @php
                            }
                            else
                            {
                                if($enable_next)
                                {

                                    $onclickFunction = "";
                                    if($canAccessNextExercise['remaining_exercise'] <= 0) {
                                        $exercisePerDay = $canAccessNextExercise['exercise_to_be_completed_per_day'];
                                        $completedToday = $canAccessNextExercise['total_exercise_completed_today'];
                                                //$onclickFunction = "";
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
                                            {!! link_to_route('client-exercises.create', 'Click here to continue your work', ['module_id' => Crypt::encryptString($module->id), 'exercise_id' => Crypt::encryptString($exercise->id)] + $page_attr, ['class' => 'text-black', 'onclick' => $onclickFunction]) !!}
                                            @php
                                            $enable_next = false;
                                            @endphp
                                        </p>
                                    </div>
                                    @php
                                               //$lastarray++;
                                               //break;
                                              // break;

                                }
                                else
                                {
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
                                    @php
                                            //$lastarray++;

                                            //break;
                                }

                        }
                        @endphp
                        </div>
                        @endforeach
                        @endif

                        {{-- end gratuate --}}
                        {{-- simple --}}
                        @else
                        @if(count($module->module_exercises) > 0 )
                        @foreach($module->module_exercises as $exercise)
                        <div class="row section no-margin">
                            {{--  $total_excercise_complete=sizeof($completedmoduleexcercise)-1; --}}
                            @php
                                        //echo $total_excercise_complete;
                            // if($viewdata->watch_video=='yes' && $viewdata->reading_material=='yes')
                            //         {

                            if($lastarray<$total_excercise_complete)
                            {
                                @endphp
                                @for($i =$lastarray; $i<=$total_excercise_complete; $i++)
                                @php
                                             //echo $completedmoduleexcercise[$i]['module_exercise_id'];
                                if($completedmoduleexcercise[$i]['module_exercise_id']==$exercise->id)
                                {

                                    if($completedmoduleexcercise[$i]['status']=='reviewed')
                                    {
                                                    // echo $val;
                                        $enable_next = true;
                                                    //echo $exercise->id;
                                        @endphp
                                        <div class="col-md-5 col-sm-6 col-xs-6 left">
                                            <p>
                                                <img src="{{ asset('themes/limitless/images/client-view/tick.png') }}" class="icon">
                                                {{ trans('comman.exercise') . ' ' . $module->module_no . '.' . $exercise->exercise_no . ' - ' . $exercise->title }}
                                            </p>
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-6 right no-padding" id="
                                        exercise_{{ $module->id }}_{{ $exercise->id }}">
                                        <p><span>|</span>
                                            {!! link_to_route('view.feedback', 'View Feedback', ['module_id' => Crypt::encryptString($module->id),'exercise_id' => Crypt::encryptString($exercise->id)], ['class' => 'text-green']) !!}
                                        </p>
                                    </div>
                                    @php
                                    $lastarray++;
                                                    //unset($completedmoduleexcercise[$i]);
                                    break;
                                }
                                else
                                {
                                                    //echo $exercise->id;
                                    @endphp
                                    <div class="col-md-5 col-sm-6 col-xs-6 left">
                                        <p>
                                            <img src="{{ asset('themes/limitless/images/client-view/login.png') }}" class="icon">
                                            {{ trans('comman.exercise') . ' ' . $module->module_no . '.' . $exercise->exercise_no . ' - ' . $exercise->title }}
                                        </p>
                                    </div>
                                    <div class="col-md-7 col-sm-6 col-xs-6 right no-padding" id="exercise_{{ $module->id }}_{{ $exercise->id }}">
                                        <p><span>|</span>
                                            <a href="#" class="text-green">Completed-awaiting feedback</a>
                                            {!! link_to_route('view.excercise', 'View Excercise', ['module_id' => Crypt::encryptString($module->id),'exercise_id' => Crypt::encryptString($exercise->id)], ['class' => 'text-green']) !!}
                                        </p>
                                    </div>
                                    @php
                                    $lastarray++;
                                    break;
                                }
                            }
                            else if($enable_next && $program->watch_video=='yes' && $viewdata['watch_video']=='yes' && $viewdata['read_material']=='yes')
                            {
                                $onclickFunction = "";
                                if($canAccessNextExercise['remaining_exercise'] <= 0) {
                                    $exercisePerDay = $canAccessNextExercise['exercise_to_be_completed_per_day'];
                                    $completedToday = $canAccessNextExercise['total_exercise_completed_today'];
                                                //$onclickFunction = "";
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
                                        {!! link_to_route('client-exercises.create', 'Click here to continue your work', ['module_id' => Crypt::encryptString($module->id), 'exercise_id' => Crypt::encryptString($exercise->id)] + $page_attr, ['class' => 'text-black', 'onclick' => $onclickFunction]) !!}
                                        @php
                                        $enable_next = false;
                                        @endphp
                                    </p>
                                </div>
                                @php
                                               //$lastarray++;
                                break;
                            }
                            else
                            {

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
                                @php
                                            //$lastarray++;
                                break;
                            }


                            @endphp
                            @endfor
                            @php
                                     // $lastindex= $i;
                                     // echo $lastindex;
                        }
                        else
                        {
                            if($enable_next && $program->watch_video=='yes' && $viewdata['watch_video']=='yes' && $viewdata['read_material']=='yes')
                            {
                                $onclickFunction = "";
                                if($canAccessNextExercise['remaining_exercise'] <= 0) {
                                    $exercisePerDay = $canAccessNextExercise['exercise_to_be_completed_per_day'];
                                    $completedToday = $canAccessNextExercise['total_exercise_completed_today'];
                                                //$onclickFunction = "";
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
                                        {!! link_to_route('client-exercises.create', 'Click here to continue your work', ['module_id' => Crypt::encryptString($module->id), 'exercise_id' => Crypt::encryptString($exercise->id)] + $page_attr, ['class' => 'text-black', 'onclick' => $onclickFunction]) !!}
                                        @php
                                        $enable_next = false;
                                        @endphp
                                    </p>
                                </div>
                                @php
                                               //$lastarray++;
                                              // break;
                            }
                            else
                            {
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
                                @php
                                            //$lastarray++;
                                            //break;
                            }
                        }

                        @endphp
                    </div>

                    @endforeach

                    @endif
                    @endif
                    {{-- end simple --}}
                </div>
            </div>
        </div>
        @php
        $previous_module_id = $module->id;
        @endphp
        @endforeach

        {{-- {{$module->module_exercises->id}} --}}
        @if(!empty($last_module_progress))
        @if($last_module_progress->module_id==$module->id)
        @if(Auth::user()->is_gratuate=='n')
        @php
        $complete=sizeof($module->module_exercises);
        $total=$complete-1;
        // dd($total);
        // dd($module->module_exercises[2]->id);
        //dd($completedmoduleexcercise[$total]);
        @endphp
        @if($module->module_exercises[$total]->id==$last_module_progress->module_exercise_id)
        {{-- @if($last_module_progress->reviewed_at!='') --}}
        <div class="panel panel-default">
            <div class="panel-heading no-padding" role="tab">
                <h4 class="panel-title">
                    <a id="open_intro_video" href="#">
                        <i class="more-less fa fa-caret-down"></i>
                        Congratulation Video
                    </a>
                </h4>
            </div>
            <div class="panel-body">
                <input type="hidden" id="program_id" value="{{$program->id}}">
                <input type="hidden" id="videolink" value="{{$program->gratuate_video}}">
                <input type="hidden" name="intro_video" value="no">
                <div class="row section no-margin">
                    <div data-vimeo-url="{{$program->gratuate_video}}" id="gratuate_video" data-vimeo-height="540px">
                    </div>
                </div>
                <div class="col-md-5 col-sm-6 col-xs-6 left">
                    <p>
                     Graduation Certificate
                 </p>
                </div>  <a href="{{route('download.certificate')}}" target="_blank">Download certificate</a>
            </div>
        </div>
     @endif
     @endif
     @endif
     @endif
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
<script src="https://player.vimeo.com/api/player.js"></script>
<script type="text/javascript">



   jQuery('.openvideo').click(function(event) {
        /* Act on the event */
        jQuery('.loadvideo').show();
    });
    
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

        @if($gratuate_video=='1')
        @if(isset($last_module_progress))
        @if(isset($last_module_progress->completed_at) && $last_module_progress->is_submited_popup == '0' && $last_module_progress->status == 'reviewed' && isset($last_module_progress->reviewed_at))
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
                    //console.log('popup disable');
                }
            });
        });
        @elseif(isset($last_module_progress->completed_at) && $last_module_progress->is_submited_popup == '1' && $last_module_progress->status == 'reviewed' && isset($last_module_progress->reviewed_at) && Auth::user()->gratuate_option=='')
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
                        //console.log('popup disable');
                        //console.log(_response.redirect_url);
                    }
                });
            });
            @elseif(isset($last_module_progress->completed_at) && $last_module_progress->is_submited_popup == '1' && $last_module_progress->status == 'reviewed' && isset($last_module_progress->reviewed_at) && Auth::user()->gratuate_option=='aq' && Auth::user()->is_gratuate_question_asked=='n')
            swal({
                title: "Ask Stanton a question",
                input: 'textarea',
                inputPlaceholder: 'Ask Your Question here',
                showCancelButton: true,
                confirmButtonColor: "#66BB6A",
                html: true,
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
                        url: "{{route('clients.program.gratuatequestion')}}",
                        data:{ _token: "{{csrf_token()}}",id : "{{Auth::id()}}",result:result,program_id:$("#program_id_get").val()},
                        success: function(_response)
                        {
                            swal({
                                type: 'success',
                                text: 'Your Mail Sent Successfully :)'
                            })
                            //window.location.href = _response.redirect_url;
                            //console.log('popup disable');
                            //console.log(_response.redirect_url);
                        }
                    });
                });
                @endif
                @endif
                @endif
            </script>
            @endpush
            @include('clients.dashboard.popup')
            @endsection