$(document).ready(function() {
	$('.fancybox').fancybox();

	// $("#attachment").click(function () {
	// 	$("#upload-file").trigger('click');
	// });
   $(".file-icon").click(function () {
    $(this).siblings('input[type="file"]').trigger('click');
  });
   // jQuery('.message-listing .msg, #message .client-msg .msg').each(function(index, el) {
   //      if(jQuery(this).find('div.row').length > 2) {
   //          jQuery(this).css('height','500px');
   //      } else {
   //          jQuery(this).css('height','auto');
   //      }
   //  });
	module_feedback();
	// $('#upload-file').on('change',function(event) {

	// });
});
function readURL(input) {
		$.imageChanger(input, input.name + "_section");
}
$(window).resize(function(event) {
	module_feedback();
});

function module_feedback() {
	var view = $(window).width();
	if(view <= 480) {
		$('.panel-default > .panel-heading > a.module-feedback').html('<i class="fa fa-comments-o"></i>');
	} else {
		$('.panel-default > .panel-heading > a.module-feedback').html('Module Feedback');
	}
}

function toggleIcon(e) {
    $(e.target)
        .prev('.panel-heading')
        .find(".more-less")
        .toggleClass('fa fa-caret-right fa fa-caret-down');
    $(e.target)
        .prev('.panel-heading')
        .find(".module-feedback")
        .toggleClass('hide show');
    $(e.target)
        .prev('.panel-heading')
        .toggleClass('active');
    $(e.target)
        .parents('.panel-default')
        .toggleClass('m-b-8');
}
$('.panel-group').on('hidden.bs.collapse', toggleIcon);
$('.panel-group').on('shown.bs.collapse', toggleIcon);

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
