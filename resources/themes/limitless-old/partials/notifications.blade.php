@if (session('status'))
<div class="row">
    <div class="col-sm-12">
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ session('status') }}
        </div>
    </div>
</div>
@endif
@if (session('success'))
<div class="row">
    <div class="col-sm-12">
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ session('success') }}
        </div>
    </div>
</div>
@endif
@if (session('error'))
<div class="row">
    <div class="col-sm-12">
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ session('error') }}
        </div>
    </div>
</div>
@endif
@if (session('warning'))
<div class="row">
    <div class="col-sm-12">
        <div class="alert alert-warning">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ session('warning') }}
        </div>
    </div>
</div>
@endif
@if (session('info'))
<div class="row">
    <div class="col-sm-12">
        <div class="alert alert-info">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ session('info') }}
        </div>
    </div>
</div>
@endif

@if (Session::has('flash.message'))
<div class="row">
    <div class="col-sm-12">
        <div class="alert alert-{{ Session::get('flash.level') }}">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {!! Html::decode(Session::get('flash.message')) !!}
        </div>
    </div>
</div>
@endif