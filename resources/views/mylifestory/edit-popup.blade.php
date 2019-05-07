<div class="modal fade" id="edit_lifestorey_modal" tabindex="-1" role="dialog">
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
            jQuery(".lifstorey_edit_popup").css("opacity", "0.3");
        }else {
           jQuery(".lifstorey_edit_popup").css("opacity", "1");
        }
    }

// jQuery('.lifstorey_edit_popup').on('click', function (e) {

//     });
    function open_popup(id) {
        var url = "{{route('mylifestory.edit',$id = '~')}}";
        console.log(url);
        var ext_url =  "?download=yes";
        url = url.replace('~',id);

        if (jQuery('#lang').val() != "") {
            jQuery.get(url +ext_url, function (html) {
                jQuery('#edit_lifestorey_modal .modal-content').html(html);
                jQuery('#edit_lifestorey_modal').modal('show', {backdrop: 'static'});
            });
            lifeStorySubmitDynamic();
        }
    }
    var lifeStorySubmitDynamic = function(e) {
        // jQuery('#lifestorey_edit_form input[type="submit"]').on('click submit', function (e) {
            if(!e)
            {
                console.log(e);
                return false
            }
            e.preventDefault();
            var $form = jQuery('#lifestorey_edit_form');
            jQuery.ajax({
                type: $form.attr('method'),
                url: $form.attr('action'),
                data: $form.serialize(),
                success: function (response, status) {
                    console.log(response);
                    jQuery('#edit_lifestorey_modal').modal('hide');
                    location.reload();
                },
                error: function (xhr, textStatus, errorThrown) {
                    associate_errors(xhr.responseJSON, $form);
                    return false;
                }
            });
        // });
        jQuery('#edit_lifestorey_modal a.cancel,.close').on('click', function (e) {
            e.preventDefault();
            jQuery('#edit_lifestorey_modal').modal('hide');
        });
    };
    function lifeStoryCancelDynamic()
    {
        console.log("lifeStoryCancelDynamic");
        jQuery('#edit_lifestorey_modal').modal('hide');
    }

</script>
@endpush