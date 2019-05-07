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
    <div class="panel panel-white">
        <div class="panel-heading">
            <h3 class="panel-title">Add New User</h3>
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
            {!! Form::open(array('route' => 'users.store','class'=>'form-horizontal','role'=>"form",'files' => true, 'autocomplete' => 'off')) !!}
            @include('users.form')
            <br>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-4 text-center">
                    {!! Form::submit(trans('comman.save'), ['name' => 'save','class' => 'btn btn-primary']) !!}
                    {!! Form::submit(trans('comman.save_exit'), ['name' => 'save_exit','class' => 'btn btn-primary']) !!}
                    {!! link_to(URL::full(), trans('comman.cancel'),array('class' => 'btn btn-warning')) !!}
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