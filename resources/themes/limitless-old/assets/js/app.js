var $hamburger = $(".fa-align-justify");

$hamburger.on("click", function(event) {
	$hamburger.toggleClass("is-active");
	$('body').toggleClass('navigation-overlay-is-open');
});