{{-- program Model--}}
<div class="modal fade" id="new_program_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    // for program model
    jQuery(document).on("change","#lang",function() {
        setdisable();
    });
    setdisable();
    function setdisable () {
        if (jQuery('#lang').val() == "") {
            jQuery(".new_program_popup").css("opacity", "0.3");
        }else {
           jQuery(".new_program_popup").css("opacity", "1");
        }
    }
    
    jQuery('.new_program_popup').on('click', function (e) {
        e.preventDefault();
        var ext_url = "";
        if (jQuery("#lang").val()) {
            ext_url = '&lang=' + jQuery("#lang").val();
        }
        if (jQuery('#lang').val() != "") {
            jQuery.get('{!!route("programs.create",array("download"=>"yes"))!!}' + ext_url, function (html) {
                jQuery('#new_program_modal .modal-content').html(html);
                jQuery('#new_program_modal').modal('show', {backdrop: 'static'});
                programSubmitDynamic();
            });
        }
    });
    function programSubmitDynamic() {
        jQuery('#programs_create_form input[type="submit"]').on('click submit', function (e) {
            var $form = jQuery('#programs_create_form');
            jQuery.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data: $form.serialize(),
                success: function (response, status) {
                    console.log(response);
                    jQuery("select[name='program_id']").append('<option value="' + response.data.id + '">' + response.data.program_name + '</option>');
                    jQuery("select[name='program_id']").val(response.data.id);
                    jQuery("select[name='program_id']").trigger('change');
                    jQuery('#new_program_modal').modal('hide');
                },
                error: function (xhr, textStatus, errorThrown) {
                    associate_errors(xhr.responseJSON, $form);
                }
            });
            e.preventDefault();
        });
        jQuery('#programs_create_form a.cancel,.close').on('click', function (e) {
            e.preventDefault();
            jQuery('#new_program_modal').modal('hide');
        });
    }
</script>
@endpush