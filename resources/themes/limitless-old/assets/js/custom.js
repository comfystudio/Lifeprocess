function hideshow(value) {
    if (value != '') {
        jQuery('#proof_view').removeClass('hide');
    } else {
        jQuery('#proof_view').addClass('hide');
    }
}

function openmodal(value) {
    if (value != '') {
        jQuery('#modal_theme_primary').modal({
            show: true
        });
    }
}
//for empty otp textbox
jQuery(document).on("click", ".resend_value", function () {
    jQuery('#otp_value').val('');
});
jQuery(document).ready(function () {
    jQuery('[data-toggle="tooltip"]').tooltip();
    jQuery(".file-icon").click(function () {
        $(this).siblings('input[type="file"]').trigger('click');
    });

    jQuery('.message-listing .msg, #message .client-msg .msg').each(function(index, el) {
        if(jQuery(this).find('div.row').length > 2) {
            jQuery(this).css('height','500px');
        } else {
            jQuery(this).css('height','auto');
        }
    });
});

jQuery("#name").on('keyup', function () {
    var Text = jQuery(this).val();
    Text = Text.toLowerCase();
    Text = Text.replace(/[^a-zA-Z0-9]+/g, '-');
    jQuery("#slug").val(Text);
});

jQuery("#role_name").on('keyup', function () {
    var Text = jQuery(this).val();
    Text = Text.toLowerCase();
    Text = Text.replace(/[^a-zA-Z0-9]+/g, '-');
    jQuery("#slug").val(Text);
});
/* Delete Time Model Open Code */
(function () {
    var laravel = {
        initialize: function () {
            this.methodLinks = $('body');
            this.registerEvents();
        },
        registerEvents: function () {
            //this.methodLinks.on('click', this.handleMethod);
            this.methodLinks.on('click', 'a[data-method]', this.handleMethod);
        },
        handleMethod: function (e) {
            e.preventDefault();
            var link = $(this);
            var csrf_token = jQuery('meta[name="csrf-token"]').attr('content');
            var httpMethod = link.data('method').toUpperCase();
            var allowedMethods = ['PUT', 'DELETE', 'GET', 'POST'];
            var extraMsg = link.data('modal-text');
            var reject = link.data('reject');
            if (reject) {
                var msg = '<i class="fa fa-exclamation-triangle fa-2x" style="vertical-align: middle; color:#f39c12;"></i>' + rejectStatus_msg + extraMsg;
            } else {
                var msg = '<i class="fa fa-exclamation-triangle fa-2x" style="vertical-align: middle; color:#f39c12;"></i>' + delete_msg + extraMsg;
            }

            // If the data-method attribute is not PUT or DELETE,
            // then we don't know what to do. Just ignore.
            if ($.inArray(httpMethod, allowedMethods) === -1) {
                return;
            }
            bootbox.dialog({
                message: msg,
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
                        className: "btn-primary",
                        callback: function () {
                            var form = $('<form>', {
                                'method': 'POST',
                                'action': link.attr('href')
                            });
                            var hiddenInput = $('<input>', {
                                'name': '_method',
                                'type': 'hidden',
                                'value': link.data('method')
                            });
                            var tokenInput = $('<input>', {
                                'name': '_token',
                                'type': 'hidden',
                                'value': csrf_token
                            });
                            form.append(tokenInput);
                            form.append(hiddenInput).appendTo('body').submit();
                        }
                    }
                }
            });
        }
    };
    laravel.initialize();
})();
/* DatePicker Code */
jQuery(".datepicker").datetimepicker({
    format: 'm/d/Y',
    allowBlank: true,
    timepicker: false,
    mask: true,
    validateOnBlur: false,
    showOn: "button",
    scrollInput: false,
});
/* DateTimePicker */
jQuery(".datetimepicker").datetimepicker({
    format: 'd-m-Y H:i:s',
    allowBlank: true,
    timepicker: true,
    mask: true,
    validateOnBlur: false,
    showOn: "button"
});
// for Image change Code
(function ($) {
    $.imageChanger = function (input, site, default_img) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#" + site).css('visibility', 'visible');
                $("#" + site).attr('src', e.target.result);
                if (default_img) {
                    $("#" + default_img).remove();
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    };
})(jQuery);

/* Prevent Form Submit */
// restrictEnterkey();

function restrictEnterkey() {
    jQuery('input[type="text"]:not(.allow-enter), input:checkbox').on('keyup keypress', function (e) {
        var keyCode = e.keyCode || e.which;
        //console.log(keyCode);
        if (keyCode === 13) {
            // if(!$(this).hasClass("allow-enter")){
            e.preventDefault();
            return false;
            // }
        }
    });
    jQuery(document).on("keydown", function (e) {
        if (e.which === 8 && !jQuery(e.target).is("input, textarea")) {
            e.preventDefault();
        }
    });
}

/* Add Active Class */
jQuery(function () {
    var pgurl = window.location.href;
    jQuery(".navigation li a").each(function () {
        if (jQuery(this).attr("href") == pgurl) {
            jQuery(this).parent().addClass('active');
        }
    });
    // Bootstrap switch
    // ------------------------------
    $(".switch").bootstrapSwitch();
});
/* For Image Lightbox */
jQuery('[data-popup="lightbox"]').fancybox({
    padding: 3,
    beforeShow: function () {
        this.title = $(this.element).attr('data-title');
    }
});
/* Dynamic Tabindex */
function dynamicTabIndex() {
    var tabvalue = 0;
    jQuery(" select:not(.noCustomTabIndex), .box a:not(.noCustomTabIndex).customTabindex, .box :input:not(.ui-select-search,.noCustomTabIndex)[type=text], .customTabindex , a.customTabindex, textarea.customTabindex, .ui-select-offscreen, button .customTabindex, input[type=checkbox].customTabindex, input[type=radio].customTabindex, input[type=file].customTabindex , .page-header a.customTabindex, input[type=radio].customTabindex,select.select-size,select.select-size-sm,select.customTabindex,span.select2 > span.selection >span.select2-selection").each(function (i) {
        jQuery(this).removeAttr('tabindex');
        tabvalue = tabvalue + 1;
        jQuery(this).attr('tabindex', tabvalue);
        jQuery(this).addClass('data-tabindex');
    });
}
/* Function Used In Set Error in Modal */
function associate_errors(errors, $form) {
    //remove existing error classes and error messages from form groups
    $form.find('.form-group').removeClass('has-error').find('.help-block').text('');
    jQuery("form").find('.help-block').text('');
    jQuery.each(errors, function (index, value) {
        //find each form group, which is given a unique id based on the form field's name
        var $group = $form.find('#' + index).parents('.form-group');
        if ($group.addClass('has-error').find('.help-block').length == 0) {
            $group.find('.form-control').parent('div').append("<span class='help-block'></span>");
        }
        //add the error class and set the error text
        $group.addClass('has-error').find('.help-block').text(value);
    });
}
//Method for Add more validation message while ajax
function associate_errors_multi(errors, $form) {
    //remove existing error classes and error messages from form groups
    jQuery(".dynamic").find('.error').removeClass('errors')
    jQuery(".dynamic").find('.error').text('')
    jQuery.each(errors, function (index, value) {
        if (index.indexOf("ajax_file") >= 0){
            index = index.replace("ajax_file", "value");
        }
        var group = jQuery("div").find("[data-tdname='" + index + "']");
        if (group.addClass('has-error').find('.help-block').length == 0) {
            group.find('.control').parent('div').append("<span class='help-block'></span>");
        }
        group.addClass('has-error').find('.help-block').text(value['0']);
    });
}

//Code for User view PopUp
(function() {
    var UserInfo = {
        initialize: function() {
            this.methodLinks = jQuery('body');
            this.registerEvents();
        },
        registerEvents: function() {
            this.methodLinks.on('click', 'a[data-user-show]', this.handleMethod);
            this.methodLinks.on('click', '#show_user_modal .close',this.closeMethod);
        },
        handleMethod: function(e) {
            e.preventDefault();
            var user_id = jQuery(this).attr("data-user-show");
            var lang = jQuery(this).attr('data-lang');
            if (jQuery(this).attr("data-noActive")) {
                var noActive = jQuery(this).attr("data-user-show");
                var url = lang +"&flag=true";
            }else{
                var url = lang + "&download=yes";
            }
            jQuery.get(url, function (html) {
                jQuery('#show_user_modal .modal-body').html(html);
                jQuery('#show_user_modal').modal('show', {backdrop: 'static'});
            });
        },
        closeMethod:function(e){
            e.preventDefault();
            jQuery('#show_user_modal').modal('hide');
        }
    };
    UserInfo.initialize();
})();
/* File Upload */
function singleFileUpload() {
    var file = $('.file-upload');
    //console.log(file);
    var form = $('form.form-locker');
    var textbox = file.data('display');
    //console.log(textbox);
    var uploadErrors = [];
    file.fileupload({
        url: '/ajax/uploadfile/',
        dataType: 'json',
        method: 'POST',
        autoUpload: false,
        //acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
        add: function(e, data) {
            $('.' + textbox).find('p').remove();
            $('#' + textbox).val('');
            $('.' + textbox).html('');
            var acceptFileTypes = /^image\/(jpe?g|png)$/i;
            if(!acceptFileTypes.test(data.originalFiles[0]['type'])) {
                uploadErrors.push('The file type allow only jpeg, jpg, png.');
            }else{
                data.submit();
            }
            if(uploadErrors.length > 0){
                $('<p style="color: red;">Upload file error: ' + uploadErrors + '<i class="elusive-remove" style="padding-left:10px;"/></p>')
                .appendTo('.' + textbox);
            }
        },
        done: function(e, data) {
            $('#' + textbox).val(data.files[0].name.toLowerCase());
            $('.' + textbox).html(data.files[0].name.toLowerCase());
        },
        progressall: function(e, data) {
            var progress = parseInt(data.loaded / data.total * 100, 10);
            $('#' + textbox).val(progress + '%');
            $('.' + textbox).html(progress + '%');
        },
        fail: function(e, data) {
            console.log(data);
            $.each(data.messages, function (index, error) {
                $('<p style="color: red;">Upload file error: ' + error + '<i class="elusive-remove" style="padding-left:10px;"/></p>')
                .appendTo('#' + textbox);
            });
            $('#' + textbox).val(data.files[0].name.toLowerCase())
            $('.' + textbox).html(data.files[0].name.toLowerCase());
        }
    });
}
jQuery('body').on("focus", ".modal-dialog", function(e) {
    singleFileUpload();
});
// allow only positive numeric
jQuery(document).on("keypress blur",".allownumeric",function(event) {
   var theEvent = event || window.event;
   var key = theEvent.keyCode || theEvent.which;
   if (key == 37 || key == 46 || key == 38 || key == 39 || key == 9 || key == 40 || key == 8 || key == 46 || key == 123 || key == 16) {
       return;
   } else {
       key = String.fromCharCode(key);
       var regex = /[0-9.]|\./;
       if (!regex.test(key)) {
           theEvent.returnValue = false;
           if (theEvent.preventDefault) theEvent.preventDefault();
       }
   }
});

//bind select2 to dropdown
jQuery('.single-select').select2();

/* Sub Permission toggle Code Start */
jQuery('.permission_title').on('click', function () {
    var data_id = jQuery(this).attr('data-id');
    jQuery(document).find('.sub_' + data_id).slideToggle("slow", "linear");
    jQuery(this).find('i').toggleClass('fa-caret-down fa-caret-up');
});
// For all Permission Jquery - Start

jQuery(".all_allow").on('change', function () {
    var attribute = jQuery(this).attr('data-allow');
    var classname = "." + attribute;
    if (this.checked) {
        jQuery(classname).each(function () {
            jQuery(this).prop('checked', true).trigger('change');
        });
    }else{
        jQuery(classname).each(function () {
            jQuery(this).prop('checked', false).trigger('change');
        });
    }
});
// For deselect when any on is not selected - Start
jQuery('.child_alw').on('click', function () {
        var total_checkbox = jQuery(this).parents('.sub_permission').find(':input.child_alw').length;
        var selected_checkbox = jQuery(this).parents('.sub_permission').find(':input:checked.child_alw').length
        if (total_checkbox == selected_checkbox) {
            jQuery(this).parents('.permission_display').find('.all_per_div :input.parent_alw').prop('checked', true);
        }
        else {
            jQuery(this).parents('.permission_display').find('.all_per_div :input.parent_dny').prop('checked', false);
        }
    });
    jQuery('.child_dny').on('click', function () {
        var total_checkbox = jQuery(this).parents('.sub_permission').find(':input.child_dny').length;
        var selected_checkbox = jQuery(this).parents('.sub_permission').find(':input:checked.child_dny').length
        if (total_checkbox == selected_checkbox) {
            jQuery(this).parents('.permission_display').find('.all_per_div :input.parent_dny').prop('checked', true);
        }
        else {
            jQuery(this).parents('.permission_display').find('.all_per_div :input.parent_alw').prop('checked', false);
        }
    });
function readURL(input) {
        $.imageChanger(input, input.name + "_section");
}

$.imageChanger = function (input, site, default_img) {
    // console.log(input + site + default_img);
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $("#" + site).css('visibility', 'visible');
            if(input.files[0].type=='application/pdf'){
                $("#" + site).attr('src', '/uploads/default/100x100/pdfIcon.jpg');
            }else{
                $("#" + site).attr('src', e.target.result);
            }
           // console.log(e.target.result);
           if (default_img) {
                $("#" + default_img).remove();
            }
        };
    reader.readAsDataURL(input.files[0]);
    }
};
