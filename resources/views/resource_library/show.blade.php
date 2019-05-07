@extends($theme)
@section('title', $title)
@section('content')

<div class="tab-content">
    <div class="tab-pane fade in active" id="coach">
      <div class="tab-title">
      <h1 class="no-margin">
        Resource library
        </h1>


            <div id="lifestory">
            <div class="row no-margin left">
 <div class="col-md-12 col-sm-10 col-xs-8 text">
 <br>
   <table class="table table-bordered">
                <thead>
                    <tr>
                        <th style="width: 50px;">
                            {{ trans('comman.no') }}
                        </th>
                        <th>
                            {{ trans('comman.name') }}
                        </th>
                      {{--   <th>
                            {{ trans('comman.status') }}
                        </th>
                        <th>
                            {{ trans('comman.date') }}
                        </th> --}}
                        <th style="width: 180px;" class="text-center">{{ trans('comman.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($programs) && count($programs)>
                    0)
                    @foreach($programs as $program)
                    <tr>
                        <td>
                            {{ ($programs->currentPage()-1) * $programs->PerPage() + $counter + 1 }}
                            @php
                            $counter++;
                            @endphp
                        </td>
                        <td>
                            {{ $program->name }}
                        </td>
                        {{--    <td>
                            {{ trans('comman.'.$program->status) }}
                        </td>
                        <td>
                            {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$program->created_at)->format('m/d/Y') }}
                        </td> --}}
                        <td class="text-center">
                       {{--   {!! Html::decode(link_to_route('resource.view', '<i class="fa fa-eye" aria-hidden="true"></i> View', array(Crypt::encryptString($program->id)), ['class' => 'btn btn-xs btn-success', 'title' => 'show' ])) !!} --}}
                      @php
          $file=explode('/', $program->file_type);
          @endphp
          @if($file[0]=='image')
          @if(isset($program['files']) && !empty($program['files']))
          {{--   {{Html::image(AppHelper::path('uploads/resource/')->size('150x150')->getImageUrl($program['files']),'User Photo',array("class"=>"",'id'=>'staff','height'=>'150','width'=>'150'))}} --}}
          {{--   <br>
            <br> --}}
            {!! Html::decode(Html::link(AppHelper::path('uploads/resource/')->getImageUrl($program['files']),'Download',array('id' => $program['files'],'download','class'=>"btn bg-primary btn-labeled heading-btn",'type'=>'button'))) !!}
          @else
          {{Html::image(AppHelper::size('50x50')->getDefaultImage(),'User Photo',array("class"=>"img-circle staff",'id'=>'staff','height'=>'50','width'=>'50'))}}
          @endif
          @else
          {!! Html::decode(Html::link(AppHelper::path('uploads/resource/')->getImageUrl($program['files']),'Download',array('id' => $program['files'],'download','class'=>"btn bg-primary btn-labeled heading-btn",'type'=>'button'))) !!}
          @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="5" class="text-center">
                            {{ trans('comman.no_data_found') }}
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
                 @if(isset($programs) && count($programs) > 0)
                    {{ $programs->appends(request()->except('page'))->links('vendor.pagination.bootstrap-custom')}}
                @endif
 </div></div></div></div></div></div>


@endsection
