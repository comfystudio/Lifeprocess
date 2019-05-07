{{-- coach Model--}}
<div class="modal fade" id="new_coach_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>

{{-- broadcast_mail_popup Model--}}
<div class="modal fade" id="new_broadcast_mail_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">

        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    // for coach model
    jQuery(document).on("change","#lang",function() {
        setdisable();
    });
    setdisable();
    function setdisable () {
        if (jQuery('#lang').val() == "") {
            jQuery(".new_coach_popup").css("opacity", "0.3");
        }else {
           jQuery(".new_coach_popup").css("opacity", "1");
        }
    }

    jQuery('.new_coach_popup').on('click', function (e) {
        e.preventDefault();
        var ext_url = "";
        if (jQuery("#lang").val()) {
            ext_url = '&lang=' + jQuery("#lang").val();
        }
        if (jQuery('#lang').val() != "") {
            jQuery.get('{!!route("coaches.create",array("download"=>"yes", "agency" => "client"))!!}' + ext_url, function (html) {
                jQuery('#new_coach_modal .modal-content').html(html);
                jQuery('#new_coach_modal').modal('show', {backdrop: 'static'});
                coachSubmitDynamic();
            });
        }
    });
    function coachSubmitDynamic() {
        jQuery('#coach_create_form input[type="submit"]').on('click submit', function (e) {
            var $form = jQuery('#coach_create_form');
            jQuery.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data: $form.serialize(),
                success: function (response, status) {
                    console.log(response);
                    jQuery("select[name='coach_id']").append('<option value="' + response.data.id + '">' + response.data.name + '</option>');
                    jQuery("select[name='coach_id']").val(response.data.id);
                    jQuery("select[name='coach_id']").trigger('change');
                    jQuery('#new_coach_modal').modal('hide');
                },
                error: function (xhr, textStatus, errorThrown) {
                    associate_errors(xhr.responseJSON, $form);
                }
            });
            e.preventDefault();
        });
        jQuery('#coach_create_form a.cancel,.close').on('click', function (e) {
            e.preventDefault();
            jQuery('#new_coach_modal').modal('hide');
        });
    }

    jQuery('.broadcast_mail_popup').on('click', function(e){
        e.preventDefault();
        jQuery('#new_broadcast_mail_modal').modal('show', {backdrop: 'static'});
        jQuery.get('{!!route("clients.send-mail.create",array("download"=>"yes"))!!}', function (html) {
                jQuery('#new_broadcast_mail_modal .modal-content').html(html);
                jQuery('.summernote').summernote({
                });
                jQuery('#new_broadcast_mail_modal').modal('show', {backdrop: 'static'});
                broadcastMail();
            });
    });

    function broadcastMail() {
        jQuery('#broadcast_mail_form input[type="submit"]').on('click submit', function (e) {
            var $form = jQuery('#broadcast_mail_form');
            jQuery.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data: $form.serialize() + '&to=' + encodeURIComponent(jQuery('input[name="to[]"]').map(function(){
                        return $(this).val();
                    }).get()),
                success: function (response, status) {
                    // jQuery("select[name='coach_id']").append('<option value="' + response.data.id + '">' + response.data.name + '</option>');
                    // jQuery("select[name='coach_id']").val(response.data.id);
                    // jQuery("select[name='coach_id']").trigger('change');
                    jQuery('#new_broadcast_mail_modal').modal('hide');
                },
                error: function (xhr, textStatus, errorThrown) {
                    associate_errors(xhr.responseJSON, $form);
                }
            });
            e.preventDefault();
        });
        jQuery('#broadcast_mail_form a.cancel,.close').on('click', function (e) {
            e.preventDefault();
            jQuery('#new_broadcast_mail_modal').modal('hide');
        });
    }

</script>
@endpush