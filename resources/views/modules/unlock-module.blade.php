@extends($theme)
@section('title', $title)
@section('content')
<style type="text/css">
    .table-lg>tbody>tr>td, .table-lg>tbody>tr>th {
        padding: 7px 15px;
    }
    .table > tbody > tr > td{
        vertical-align: middle;
        border-top: 0;
        font-size: 13px;
    }
    .table > tbody > tr > th {
        font-size: 14px;
        /*font-weight: normal;*/
    }
</style>
    <div class="panel panel-white">
        <div class="panel-heading">
            <div class="col-sm-9"><h5 class="panel-title">{{ $title }}</h5></div>
            <div class="clearfix"></div>
        </div>
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table table-bordered table-lg">
                    <tbody>
                        <tr>
                            <th>Client Name</th>
                            <th>Program</th>
                            <th>Module</th>
                            <th>Reviewed at</th>
                            <th>Unlock</th>
                        </tr>
                        @if(isset($modulesReviewedWithIn90Days) && count($modulesReviewedWithIn90Days) > 0) 
                            @foreach($modulesReviewedWithIn90Days as $module)
                                <tr>
                                    <td>{{ $module->submittedBy->name }}</td>
                                    <td>{{ $module->modules->program->program_name }}</td>
                                    <td>{{ $module->modules->module_no . '. ' . $module->modules->module_title }}</td>
                                    <td>{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $module->reviewed_at)->format('m/d/Y') }}</td>
                                    <td>
                                        {{-- {{ dump($module->submittedBy->id) }} --}}
                                        {!! Html::decode(link_to_route('view.feedback', 'View Feedback', ['module_id' => Crypt::encryptString($module->modules->id), 'user_id' => Crypt::encryptString($module->user_id), 'coach_id' => Crypt::encryptString($module->reviewed_user_id)], ['class' => 'btn btn-xs btn-info'])) !!}
                                        {{-- <a href="#" class="btn btn-xs bg-teal-400">Unlock</a> --}}
                                        {!! Html::decode(link_to_route('unlock.module.reEdit', 'Unlock', ['reviewed_module_id' => Crypt::encryptString($module->id), 'coach_id' => Crypt::encryptString($module->reviewed_user_id),'client_id' => Crypt::encryptString($module->submittedBy->id)], ['class' => 'btn btn-xs bg-teal-400', 'onclick' => 'return confirm_before_unlock(this);'])) !!}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">
                                    No module submitted from last 90 days.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @push('scripts')
        <script type="text/javascript">
            function confirm_before_unlock(this_link) {
                bootbox.dialog({
                    message: '<i class="fa fa-exclamation-triangle fa-2x" style="vertical-align: middle; color:#f39c12;"></i> Are you sure to unlock module feedback?' ,
                    title: please_confirm,
                    buttons: {
                        success: {
                            label: cancel_btn,
                            className: "btn-default",
                            callback: function () {
                            }
                        },
                        danger: {
                            label: ok_btn,
                            className: "btn-success",
                            callback: function () {
                                var form = jQuery('<form>', {
                                    'method': 'POST',
                                    'action': this_link.href
                                });
                                var hiddenInput = jQuery('<input>', {
                                    'name': '_method',
                                    'type': 'hidden',
                                    'value': 'GET'
                                });
                                var tokenInput = jQuery('<input>', {
                                    'name': '_token',
                                    'type': 'hidden',
                                    'value': jQuery('meta[name="csrf-token"]').attr('content')
                                });
                                form.append(tokenInput);
                                form.append(hiddenInput).appendTo('body').submit();
                            }
                        }
                    }
                });
                return false;
            }
        </script>
    @endpush
@endsection
