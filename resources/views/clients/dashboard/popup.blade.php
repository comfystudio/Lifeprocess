{{-- coach Model--}}
<div class="modal fade" id="video_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onClick="window.location.reload()"><span aria-hidden="true"><i class="icon-cancel-circle2"></i></span></button>
                <h4 class="modal-title">&nbsp;</h4>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script type="text/javascript">
    function load_module_video_orRead(module_id, type) {
        var url = "?module_id=" + module_id + "&type=" + type;
        jQuery.get('{!!route('client.program_modules.loadVideo_and_updateModuleProgress')!!}' + url, function (response, status)
        {

            jQuery("#exercise_" + module_id + '_' + response.data.exercise_id).html(response.data.exercise_link);
            ///console.log('hi this is popup');
        });
    }
    jQuery(document).ready(function(){
        $('#video_modal').on('hidden.bs.modal', function (e) {
            jQuery('#video_modal .modal-body').html('');
        });
    });

</script>
@endpush