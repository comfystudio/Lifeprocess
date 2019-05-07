<div class="modal fade" id="cancel_reson_modal" tabindex="-1" role="dialog">
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
            jQuery(".cancel-reson").css("opacity", "0.3");
        }else {
           jQuery(".cancel-reson").css("opacity", "1");
        }
    }

function open_popup(id) {
        var ext_url =  "?download=yes" ;
        //var ext_url = "";
        if (jQuery('#lang').val() != "") {
            jQuery.get('cancelbooking/'+id+ext_url, function (html) {
                jQuery('#cancel_reson_modal .modal-content').html(html);
                jQuery('#cancel_reson_modal').modal('show', {backdrop: 'static'});
                noteSubmitDynamic();
            });
        }
    }
     function noteSubmitDynamic() {
        jQuery('#cancel_schedule input[type="submit"]').on('click submit', function (e) {
            var $form = jQuery('#cancel_schedule');
            jQuery.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data: $form.serialize(),
                success: function (response, status) {
                    console.log(response);
                    jQuery('#cancel_reson_modal').modal('hide');
                    location.reload();
                },
                error: function (xhr, textStatus, errorThrown) {
                    associate_errors(xhr.responseJSON, $form);
                }
            });
            e.preventDefault();
        });
        jQuery('#cancel_schedule a.cancel,.close').on('click', function (e) {
            e.preventDefault();
            jQuery('#cancel_reson_modal').modal('hide');
        });
    }
</script>
@endpush
