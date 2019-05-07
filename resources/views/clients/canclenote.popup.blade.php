<div class="modal fade" id="client_admin_note_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>
@push('scripts')
<script type="text/javascript">
    // for country model
    jQuery(document).on("change","#lang",function() {
        setdisable();
    });
    setdisable();
    function setdisable () {
        if (jQuery('#lang').val() == "") {
            jQuery(".client_admin_note_popup").css("opacity", "0.3");
        }else {
           jQuery(".client_admin_note_popup").css("opacity", "1");
        }
    }

function open_popup(id) {
        var ext_url =  "?download=yes" ;
        //var ext_url = "";
        if (jQuery('#lang').val() != "") {
            jQuery.get('client/adminnote/'+id+ext_url, function (html) {
                jQuery('#client_admin_note_modal .modal-content').html(html);
                jQuery('#client_admin_note_modal').modal('show', {backdrop: 'static'});
                noteSubmitDynamic();
            });
        }
    }
function noteSubmitDynamic() {
        jQuery('#client_admin_note input[type="submit"]').on('click submit', function (e) {
            var $form = jQuery('#client_admin_note');
            jQuery.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data: $form.serialize(),
                success: function (response, status) {
                    console.log(response);
                    jQuery('#client_admin_note_modal').modal('hide');
                    location.reload();
                },
                error: function (xhr, textStatus, errorThrown) {
                    associate_errors(xhr.responseJSON, $form);
                }
            });
            e.preventDefault();
        });
        jQuery('#client_admin_note a.cancel,.close').on('click', function (e) {
            e.preventDefault();
            jQuery('#client_admin_note_modal').modal('hide');
        });
    }
</script>
@endpush