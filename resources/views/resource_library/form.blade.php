
<div class="form-group {{ $errors->has('name') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('name', 'Name'. ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::text('name', null, ['class' => 'form-control','placeholder' => 'name' ]) !!}
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div>
{!! Form::hidden('file_added', isset($program['files'])?$program['files']:'') !!}
<div class="form-group {{ $errors->has('status') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('status', trans("comman.status"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::select('status', $program_status, null, ['class' => 'form-control single-select' ]) !!}
        {!! $errors->first('status', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group {{ $errors->has('files') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('files', 'files'. ':', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::file('files', ['class' => 'form-control', 'onchange' => 'readUserURL(this)', 'accept' => 'image/*,video/*,.doc , .docx , .pdf , .ppt , .pptx , .xlsx , .xls , .csv , .txt']) !!}
        {!! $errors->first('files', '<p class="help-block">:message</p>') !!}
    </div>
    <div class="col-sm-5">
    {{-- {{ ($program['files'])exit; }} --}}
    {{-- {{ $file_type = explode('/' ,$program['file_type']) }}
        @if($file_type[0]=='image') --}}
            @if(isset($program['files'])  && !empty($program['files']))

                @php
                $file_type = (explode('/' ,$program['file_type']))
                @endphp
                @if($file_type[0]=='image')
                {{  Html::image(AppHelper::path('uploads/resource/')->size('50x50')->getImageUrl($program['files']),'User Photo',array("class"=>"img-circle",'id'=>'staff','height'=>'50','width'=>'50')) }}
                @else
                     {!! Html::decode(Html::link(AppHelper::path('uploads/resource/')->getImageUrl($program['files']),'Download',array('id' => $program['files'],'download','class'=>"btn bg-info btn-labeled heading-btn",'type'=>'button'))) !!}

                @endif
            @endif
        {{-- @endif --}}
    </div>
</div>
<div class="form-group {{ $errors->has('description') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('description', 'Description'. ':', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::textarea('description', null, ['class' => 'form-control','placeholder' => 'Description', 'rows' => '9' ]) !!}
        {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
    </div>
</div>

{!! Form::hidden('_edit_url', request()->get('_edit_url', route('resource_library.index'))) !!}