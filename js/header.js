(function($) {
	var header = $('#masthead');

	if (header.length === 0) {
		return;
	}

	var lastScrollTop = 0;

	$(window).on('scroll', function() {
		var st = $(this).scrollTop();
		if (st > lastScrollTop){
			header.addClass('scroll-to-top');
		} else {
			header.removeClass('scroll-to-top');
		}
		lastScrollTop = st;
	})
})(jQuery);
