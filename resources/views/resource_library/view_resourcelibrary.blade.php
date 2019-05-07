@extends($theme)
@section('title', $title)
@section('content')
<div class="col-md-12 col-sm-12 ">
    <div class="panel">
        <div class="tab-title">
            <h1>
                View Resource library
            </h1>
        </div>
        <div class="panel-body">
          <table class="table table-bordered">
          <tr>
          <td>Name:</td><td>{{$program->name}}</td>
          </tr>
          <tr>
          <td>Description:</td><td>
          @if(isset($program->description) && !empty($program->description))
          {{$program->description}}
          @endif
          </td>
          </tr>
          <tr>
          <td>File</td><td>

          <div class="col-sm-12">
          @php
          $file=explode('/', $program->file_type);
          @endphp
          @if($file[0]=='image')
          @if(isset($program['files']) && !empty($program['files']))
            {{Html::image(AppHelper::path('uploads/resource/')->size('150x150')->getImageUrl($program['files']),'User Photo',array("class"=>"",'id'=>'staff','height'=>'150','width'=>'150'))}}
            <br>
            <br>
            {!! Html::decode(Html::link(AppHelper::path('uploads/resource/')->getImageUrl($program['files']),'Download',array('id' => $program['files'],'download','class'=>"btn bg-primary btn-labeled heading-btn",'type'=>'button'))) !!}
          @else
          {{Html::image(AppHelper::size('50x50')->getDefaultImage(),'User Photo',array("class"=>"img-circle staff",'id'=>'staff','height'=>'50','width'=>'50'))}}
          @endif
          @else
          {!! Html::decode(Html::link(AppHelper::path('uploads/resource/')->getImageUrl($program['files']),'Download',array('id' => $program['files'],'download','class'=>"btn bg-primary btn-labeled heading-btn",'type'=>'button'))) !!}
          @endif
           </div>
           </div>
           </td>
           </tr>
          </table>
        </div>
    </div>
</div>
@endsection