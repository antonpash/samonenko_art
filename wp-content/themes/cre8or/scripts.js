jQuery(document).ready(function ($) {
	$('.yith-wcan-reset-navigation').removeClass('button').addClass('button-sm button-outlined text-black');
	$('.widget_price_filter button[type=submit]').removeClass('button').addClass('button-sm button-outlined text-black align-left');
	$('.widget_price_filter button[type=submit]').appendTo('.widget_price_filter .price_slider_amount');
});
