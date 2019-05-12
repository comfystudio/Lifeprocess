@extends($theme)
@section('title', $title)
@section('content')

<div class="row">
   {!! Form::open(array('route' => 'pages.store','class'=>'form-horizontal','role'=>"form")) !!}
    <div class="col-md-12">
       <div class="panel panel-white">
            <div class="panel-heading">
                <div class="col-sm-9">
                    <h3 class="panel-title">{{ trans('comman.pages') }}</h3>
                </div>
                @if(Request::get("download",false))
                    <div class="pull-right">
                        <button type="button" class="close" ng-click="cancel($event)" aria-label="Close">
                        <span aria-hidden="true" ><i class="icon-cross2"></i></span>
                        </button>
                    </div>
                @else
                    <div class="heading-elements">
                        @if(!empty($module_action))
                        <div class="text-right">
                            @foreach($module_action as $key=>$action)
                            {!! Html::decode(Html::link($action['url'],$action['title'],$action['attributes'])) !!}
                            @endforeach
                        </div>
                        @endif
                    </div>
                @endif
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                @include('pages.form')
            </div>
            <div class="form-group">
                <div class="col-sm-6 text-right">
                    {!! Form::submit("Save", ['name' => 'save','class' => 'btn btn-primary']) !!}
                    @if(!Request::get("download",false))
                    {!! link_to(URL::full(), "Cancel",array('class' => 'btn btn-warning cancel')) !!}
                     @endif
                </div>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#summernote').summernote();
    });
  </script>

@endpush

@endsection