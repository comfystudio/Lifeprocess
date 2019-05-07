@extends($theme)
@section('title', $title)
@section('style')
@show
<style>
    .panel-footer .panel-flat.border-top-success
    {
        padding-left: 0px;
        padding-right: 0px;
    }
    .panel-footer .panel-heading{
        border:1px solid #DDD;
    }
    .lifestory-save
    {
        background-color: #82cd49;
        border: 0 none;
        border-radius: 0;
        color: #fff;
        padding: 10px 18px;
    }
    .lifestory-cancel
    {
        color: #333;
        background-color: #fcfcfc;
        border-color: #ddd;
        border: 1px solid #ddd;
        padding: 10px 18px;
    }
    .hr {
        background: url('http://i.stack.imgur.com/37Aip.png') no-repeat top center;
        background-size: contain;
        border: 0;
        border-top: 1px solid #8c8c8c;
        text-align:center;
    }
    .hr:after {
        content: '\221E';
        display: inline-block;
        position: relative;
        top: -13px;
        padding: 0 3px;
        background: #fff;
        color: #8c8c8c;
        font-size: 18px;
    }
</style>
@section('content')
<div class="tab-content">
    <div class="tab-pane fade in active" id="coach">
        <div class="tab-title">
            <h1 class="no-margin">
                Group Meeting
            </h1>
        </div>

        <div id="lifestory" style="overflow: hidden;">
            <div class="panel-body col-md-12 col-sm-12">
                {!!html_entity_decode($group->content)!!}
            </div>
        </div>
    </div>
</div>
@endsection