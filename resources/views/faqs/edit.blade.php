@extends($theme)
@section('title', $title)
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            {!! Form::model($faq, ['method' => 'PATCH','route' => ['faqs.update', $faq->id],'class' => 'form-horizontal']) !!}
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h5 class="panel-title">{{trans('comman.edit')}} {{ trans('comman.faqs') }}</h5>
                </div>
                <div class="panel-body">
                    @include('faqs.form')
                </div>
                <div class="form-group">
                    <div class="col-sm-6 text-right">
                        {!! Form::submit(trans('comman.save'), ['name' => 'save','class' => 'btn btn-primary']) !!}
                        {!! Form::submit(trans('comman.save_exit'), ['name' => 'save_exit','class' => 'btn btn-primary']) !!}
                        {!! Html::decode(link_to(URL::full(), trans('comman.cancel'),array('class' => 'btn btn-warning'))) !!}
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@push('scripts')
<script>
    $(document).ready(function() {
        $('#summernote').summernote();
    });
  </script>
@endpush
@endsection