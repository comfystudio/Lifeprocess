@extends($theme)
@section('title', $title)
@section('content')
<div class="row">
    {!! Form::open(array('route' => 'faqs.store','class'=>'form-horizontal','role'=>"form")) !!}
    <div class="col-md-12">
       <div class="panel panel-white">
            <div class="panel-heading">
                <div class="col-sm-9"><h5 class="panel-title">{{ trans('comman.faqs') }}</h5></div>
                @if(Request::get("download",false))
                    <div class="pull-right">
                        <button type="button" class="close" ng-click="cancel($event)" aria-label="Close">
                        <span aria-hidden="true" ><i class="icon-cross2"></i></span>
                        </button>
                    </div>
                @endif
                <div class="clearfix"></div>
            </div>
            <div class="panel-body">
                @include('faqs.form')
            </div>
            <div class="form-group">
                <div class="col-sm-6 text-right">
                    {!! Form::submit("Save", ['name' => 'save','class' => 'btn btn-primary']) !!}
                    @if(!Request::get("download",false))
                        {!! Form::submit("Save & Exit", ['name' => 'save_exit','class' => 'btn btn-primary']) !!}
                    @endif
                    {!! link_to(URL::full(), "Cancel",array('class' => 'btn btn-warning cancel')) !!}
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