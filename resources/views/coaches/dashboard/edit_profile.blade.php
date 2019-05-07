@extends($theme)
@section('title', $title)
<style type="text/css">
    div.panel-body > div.proof{
        padding-bottom:10px;
    }
    hr {
        margin: 0 !important;
    }
</style>
@section('content')
<div class="content-wrapper">
    <div class="row">
        <div class="col-md-12">
            {!! Form::model($coach, ['method' => 'POST','route' => ['coach.store.profile', $coach['id']],'class' => 'form-horizontal','files'=>'true']) !!}
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h5 class="panel-title">Profile information</h5>
                </div>
                <div class="panel-body">
                    @include('coaches.dashboard.profile_form')
                </div>
                <div class="form-group">
                    <div class="col-sm-6 text-right">
                        {!! Form::submit(trans('comman.save'), ['name' => 'save','class' => 'btn btn-primary']) !!}
                        {!! Html::decode(link_to(URL::full(), trans('comman.cancel'),array('class' => 'btn btn-warning'))) !!}
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@push('scripts')
<script type="text/javascript">
    jQuery('.select-size-sm').select2();
    jQuery('.select-size').select2({ width: '200px' });
    function readUserURL(input){
        $.imageChanger(input,"staff");
    }    
</script>
{!! ajax_fill_dropdown('country_id','state_id',route('ajax.allstate')) !!}
@endpush
{{-- Popup File --}}
@include('users.popup')
@endsection
