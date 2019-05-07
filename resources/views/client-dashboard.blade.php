@extends($theme)
@section('title', $title)
@section('content')
<!-- Main content -->
<div class="panel-default">
    <div class="tab-title">
        <h1>
            Client Dashboard
        </h1>
    </div>
    <div class="panel panel-white">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-info" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">
                                &times;
                            </span>
                        </button>
                        @if(isset($dashboard_message))
                        {{ $dashboard_message }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /main content -->
@push('scripts')
@endpush
@endsection
