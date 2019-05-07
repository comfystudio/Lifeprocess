@extends($theme)

@section('title', $title)
@section('style')
    <style>
        #module-wrapper .bg-cyan {
            background: #BBDDF7;
        }
    </style>
@endsection
@section('content')
<div class="content-wrapper" id="module-wrapper">
    <div class="panel panel-default">
        <div class="panel-heading bg-cyan">
            <h5 class="panel-title">&nbsp;</h5>
            <div class="heading-elements">
                @if(!empty($module_action))
                <div class="text-right">
                    @foreach($module_action as $key=>$action)
                    {!! Html::decode(Html::link($action['url'],$action['title'],$action['attributes'])) !!}
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-6 col-md-2">
                    @if(isset($program->program_icon) && !empty($program->program_icon))
                        {{Html::image(AppHelper::path('uploads/program/icons/')->size('150x150')->getImageUrl($program->program_icon),'Program icon',array("style" => "height: auto; width: 100%; display: block;"))}}
                    @else
                        {{Html::image(AppHelper::size('150x150')->getDefaultImage(),'Program icon',array("style" => "height: auto; width: 100%; display: block;"))}}
                    @endif
                </div>
                <div class="col-xs-6 col-md-10">
                    <h5 class="panel-title"> {{ ($program->program_name) }}
                        {!! Html::decode(link_to_route('programs.edit', '<span class="label label-info"><i class="fa fa-pencil"></i> Edit </span>', [Crypt::encryptString($program->id), '_url'=> request()->getRequestUri()], ['class' => 'pull-right'])) !!}
                    </h5>
                    <hr>
                    <p> {{ $program->sort_description }} </p>
                </div>
            </div>
        </div>
        <div class="panel-body collapsible-group">
            <div class="panel-group ">
                @if(isset($modules) && count($modules) > 0)
                    @foreach($modules as $module)
                        @php
                            ++$counter;
                        @endphp
                        <div class="panel panel-default">
                            <div class="row">
                            <div class="col-md-8">
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
                            <div class="col-md-4" >
                                <div class="clickable panel-heading">
                                    <div class="row">
                                        <div class="col-md-12 text-right">
                                            {!! Html::decode(link_to_route('modules.edit', '<i class="fa fa-pencil"></i> ' , array('program_id' => Crypt::encryptString($program_id), Crypt::encryptString($module->id), '_url'=> request()->getRequestUri()), ['class' => 'btn btn-xs btn-primary','title' => 'Edit'])) !!}

                                            <a data-toggle="collapse" href="#collapse{{ $counter }}" class="clickable btn btn-xs btn-default" title="View"><i class="fa fa-eye"></i> View</a>

                                            {!! Html::decode(link_to_route('modules.destroy', '<i class="fa fa-trash"></i> ' , array('program_id' => Crypt::encryptString($program_id), Crypt::encryptString($module->id)), ['data-method' => 'delete', 'data-modal-text' => trans('comman.module') . ' ?','title' => 'Delete', 'class' => 'btn btn-xs btn-danger'])) !!}

                                            {!! Html::decode(link_to_route('module_exercise.create', 'Add Exercise ' , array('program_id' => Crypt::encryptString($program_id), 'module_id' => Crypt::encryptString($module->id), '_url'=> request()->getRequestUri()), ['class' => 'btn btn-xs btn-info','title' => 'Create Module Exercise'])) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            <div id="collapse{{ $counter }}" class="panel-collapse collapse list-group">
                                @if(trim($module->introduction_video) != "")
                                    <div class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-8"> <i class="fa fa-film"></i> <strong> &nbsp; {{ trans('comman.introduction_video') }} </strong></div>
                                            <div class="col-md-2"></div>
                                            <div class="col-md-2"></div>
                                        </div>
                                    </div>
                                @endif
                                @if(trim($module->reading_material) != "")
                                    <div class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-8"> <i class="fa fa-file-text-o"></i> <strong> &nbsp; {{ trans('comman.reading_material') }} </strong></div>
                                            <div class="col-md-2"></div>
                                            <div class="col-md-2"></div>
                                        </div>
                                    </div>
                                @endif
                                {{-- {{ dump($module->module_exercises)}} --}}
                                @if(count($module->module_exercises) > 0)
                                    @foreach ($module->module_exercises as $exercise)
                                        <div class="list-group-item">
                                            <div class="row">
                                                <div class="col-md-7"> <i class="fa fa-file-o"></i> <strong> &nbsp; - {{ trans('comman.exercise') . ' ' . $module->module_no . '.' . $exercise->exercise_no . ' - ' . $exercise->title }} </strong></div>
                                                <div class="col-md-2">
                                                    {!! Html::decode(link_to_route('module_exercise.show', '<i class="fa fa-eye"></i> ' . trans('comman.view') , array('program_id' => Crypt::encryptString($program_id), 'module_id' => Crypt::encryptString($module->id), Crypt::encryptString($exercise->id), '_url'=> request()->getRequestUri()), ['class' => 'btn btn-xs btn-default','title' => 'View'])) !!}
                                                </div>
                                                <div class="col-md-3 text-right">
                                                    {!! Html::decode(link_to_route('module_exercise.edit', '<i class="fa fa-pencil"></i> ' , array('program_id' => Crypt::encryptString($program_id), 'module_id' => Crypt::encryptString($module->id), Crypt::encryptString($exercise->id), '_url'=> request()->getRequestUri()), ['class' => 'btn btn-xs btn-primary','title' => 'Edit'])) !!}

                                                    {!! Html::decode(link_to_route('module_exercise.destroy', '<i class="fa fa-trash"></i> ' , array('program_id' => Crypt::encryptString($program_id), 'module_id' => Crypt::encryptString($module->id), Crypt::encryptString($exercise->id)), ['data-method' => 'delete', 'data-modal-text' => ' ' . trans('comman.module_exercise') . ' ?','title' => 'Delete', 'class' => 'btn btn-xs btn-danger'])) !!}

                                                    {!! Html::decode(link_to_route('exercise_questions.create', 'Add Question' , array('program_id' => Crypt::encryptString($program_id), 'module_id' => Crypt::encryptString($module->id), 'exercise_id' => Crypt::encryptString($exercise->id), '_url'=> request()->getRequestUri()), ['class' => 'btn btn-xs btn-default','title' => 'Add Exercise Question'])) !!}
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
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
@endsection