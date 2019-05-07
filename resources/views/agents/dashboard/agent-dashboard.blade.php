@extends($theme)
@section('title', $title)
@section('content')
<style type="text/css">
    .table-lg>tbody>tr>td {
        padding: 10px 20px;
    }
    .table > tbody > tr > td {
        vertical-align: middle;
    }
</style>
<!-- Main content -->
<div class="content-wrapper">
    <!-- Dashboard content -->
    <div class="panel panel-white">
        <div class="panel-heading"><h5 class="panel-title">{{ $module_title }}</h5></div>
        <div class="panel-body">
            <div class="row text-center">
                <div class="col-xs-3">
                    <p><i class="icon-eye icon-2x display-inline-block text-warning"></i></p>
                    <h5 class="text-semibold no-margin"> {{ $dashboardStatistics->coaches_count or '0' }} </h5>
                    <span class="text-muted text-size-small">Coaches</span>
                </div>
                <div class="col-xs-3">
                    <p><i class="icon-user icon-2x display-inline-block text-info"></i></p>
                    <h5 class="text-semibold no-margin">{{ $dashboardStatistics->clients_count or '0' }}</h5>
                    <span class="text-muted text-size-small">Clients</span>
                </div>
            </div>
            <!-- Widget Data End -->
        </div>
    </div>
    <!-- /dashboard content -->
</div>
<!-- /main content -->
@endsection
