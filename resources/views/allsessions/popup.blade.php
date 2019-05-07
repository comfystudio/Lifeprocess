{{-- Country Model--}}
<div class="modal fade" id="new_complete_session_modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
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
            jQuery(".complete_session_popup").css("opacity", "0.3");
        }else {
           jQuery(".complete_session_popup").css("opacity", "1");
        }
    }

// jQuery('.complete_session_popup').on('click', function (e) {
    function open_complete_session_popup(client_booked_schedule_id) {
        // e.preventDefault();
        var ext_url = "&client_booked_schedule_id=" + client_booked_schedule_id;
        if (jQuery("#lang").val()) {
            ext_url = '&lang=' + jQuery("#lang").val();
        }
        if (jQuery('#lang').val() != "") {
            jQuery.get('{!!route("update_session_status.create",array("download"=>"yes"))!!}' + ext_url, function (html) {
                jQuery('#new_complete_session_modal .modal-content').html(html);
                jQuery('#new_complete_session_modal').modal('show', {backdrop: 'static'});
                completeSessionDynamic();
            });
        }
    }
    function completeSessionDynamic() {
        jQuery('#complete_session_form input[type="submit"]').on('click submit', function (e) {
            var $form = jQuery('#complete_session_form');
            jQuery.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data: $form.serialize(),
                success: function (response, status) {
                    jQuery('#new_complete_session_modal').modal('hide');
                    if(response.status == 'success') {
                        jQuery('#booked_schedule_' + response.data.booked_schedule_id).html(response.data.content);
                        bootbox.alert(response.message, function(){ 
                            location.reload();    
                         });
                    }                    
                },
                error: function (xhr, textStatus, errorThrown) {
                    associate_errors(xhr.responseJSON, $form);
                }
            });
            e.preventDefault();
        });
        jQuery('#complete_session_form a.cancel,.close').on('click', function (e) {
            e.preventDefault();
            jQuery('#new_complete_session_modal').modal('hide');
        });

        //bind select2 after model open
        jQuery("#contact_methods").select2();
    }

    function autoFill_contact_byMethod(contact_method, booked_user_id) {
        jQuery.ajax({
            url: '{{ route('ajax.contact-detail') }}',
            data: 'contact_method=' + contact_method + '&booked_user_id=' + booked_user_id,
            success: function(response) {
                jQuery("#contact_detail").val(response);
            }
        });
    }
</script>
@endpush