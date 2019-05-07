@extends($theme)
@section('title', $title)
@section('content')
<div class="content-wrapper">
    <div class="tab-content-bordered content-group">
        {{-- <div class="tab-content"> --}}
            {{-- <div class="tab-pane has-padding active" id="edit_coach"> --}}
                <div class="row">
                    <div class="col-md-12">
                        {!! Form::model($coach, ['method' => 'post','route' => ['coaches.update_save', Crypt::encryptString($coach['id'])],'class' => 'form-horizontal', 'files' => 'true']) !!}
                            <!-- <div class="panel-body"> -->
                                @include('coaches.detail-form')
                            <!-- </div> -->
                            {{-- <div class="form-group">
                                <div class="col-sm-6 text-right">
                                    {!! Form::submit(trans('comman.save'), ['name' => 'save','class' => 'btn btn-primary']) !!}
                                    @if(request()->get('_url'))
                                        {!! Html::decode(link_to(request()->get('_url'), trans('comman.save_exit'),array('class' => 'btn btn-primary'))) !!}
                                    @else
                                        {!! Form::submit(trans('comman.save_exit'), ['name' => 'save_exit','class' => 'btn btn-primary']) !!}
                                    @endif
                                    {!! Html::decode(link_to(URL::full(), trans('comman.cancel'),array('class' => 'btn btn-warning'))) !!}
                                </div>
                            </div> --}}
                        {!! Form::close() !!}
                    </div>
                </div>
            {{-- </div> --}}
        {{-- </div> --}}
    </div>
</div>
{{-- Popup File --}}
@include('coaches.popup')
@endsection
