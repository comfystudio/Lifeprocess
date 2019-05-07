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
            {!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', Crypt::encryptString($user->id)],'class' => 'form-horizontal','files'=>'true', 'autocomplete' => 'off']) !!}
            <div class="panel panel-white">
                <div class="panel-heading">
                    <h3 class="panel-title">{{trans('comman.edit')}} {{ trans('comman.user') }}</h3>
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
                <div class="panel-body">
                    @include('users.form')
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
@stop
