@if(request()->get('download'))
   {!! Form::hidden("country_id",request()->get('country_id')) !!}
@endif
<div class="form-group {{ $errors->has('country_id') ? 'has-error' : ''}}">
    {!!  Html::decode(Form::label('country_id', trans("comman.country"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        <?php $classed= array('class' => 'form-control');
            if(request()->get('download')){
                $classed['disabled'] = 'true';
            }
            if(request()->get('agency')){
                unset($classed['disabled']);
            }
        ?>
        @if(request()->get('download'))
            {!! Form::select('country_id',array(""=>trans("comman.select_country"))+$countries,Request::get('country_id',null),$classed) !!}
        @else
            <div class="input-group">
                {!! Form::select('country_id',array(""=>trans("comman.select_country"))+$countries,Request::get('country_id',null),$classed) !!}
                <span class="input-group-addon"><a class="new_country_popup"><i class="icon-plus3"></i></a></a>
            </div>
        @endif
        {!! $errors->first('country_id', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('state') ? 'has-error' : ''}}">
    {!! Html::decode(Form::label('state', trans("comman.state"). ':<span class="has-stik">*</span>', ['class' => 'col-sm-3 control-label'])) !!}
    <div class="col-sm-4">
        {!! Form::text('state', null, ['class' => 'form-control','placeholder'=> trans("comman.state") ]) !!}
        {!! $errors->first('state', '<p class="help-block">:message</p>') !!}
    </div>
</div>
@push('scripts')
<script type="text/javascript">
    jQuery('.app > .app-content > .box').append('<div class="overlay ajax-overlay"><i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i></div>');
    var $loading = jQuery('.ajax-overlay').hide();
    jQuery(document).ajaxStart(function () {
        $loading.show();
    }).ajaxStop(function () {
        $loading.hide();
    });
</script>
{!! ajax_fill_dropdown('lang','country_id',route('ajax.country')) !!}
@endpush
