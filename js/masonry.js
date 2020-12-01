(function($) {
	var page = $('body.archive.woocommerce-page');

	if (page.length === 0) {
		return;
	}

	var productContainer = $('div.grid-products');
	productContainer.masonry({
		itemSelector: 'div.grid-products-item',
		columnWidth: 'div.grid-products-item'
	});
})(jQuery);
