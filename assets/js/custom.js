jQuery(document).ready(function ($) {
	jQuery('.js-page-nav-link').click(function(e) {
		e.preventDefault();
		anchor = jQuery(this).attr('href');
		jQuery("html, body").animate({
			'scrollTop':   jQuery(anchor).offset().top
		}, 1000);
	});
}());
