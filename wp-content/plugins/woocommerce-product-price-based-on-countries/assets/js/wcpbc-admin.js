jQuery( function( $ ) {

	$('body').on( 'keyup', '.wcpbc_sale_price[type=text]', function(){
		
		var sale_price_field = $(this);			
		var regular_price_field = $('#' + sale_price_field.attr('id').replace('_sale','_regular') ) ;		

		var sale_price    = parseFloat( accounting.unformat( sale_price_field.val(), woocommerce_admin.mon_decimal_point ) );
		var regular_price = parseFloat( accounting.unformat( regular_price_field.val(), woocommerce_admin.mon_decimal_point ) );		

		if( sale_price >= regular_price ) {
			if ( $(this).parent().find('.wc_error_tip').size() === 0 ) {
				var offset = $(this).position();
				$(this).after( '<div class="wc_error_tip">' + woocommerce_admin.i18_sale_less_than_regular_error + '</div>' );
				$('.wc_error_tip')
					.css('left', offset.left + $(this).width() - ( $(this).width() / 2 ) - ( $('.wc_error_tip').width() / 2 ) )
					.css('top', offset.top + $(this).height() )
					.fadeIn('100');
			}
		} else {
			$('.wc_error_tip').fadeOut('100', function(){ $(this).remove(); } );
		}
		return this;
	});

	$('body').on( 'change', '.wcpbc_sale_price[type=text]', function(){			

		var sale_price_field = $(this);				
		var regular_price_field = $('#' + sale_price_field.attr('id').replace('_sale','_regular') ) ;		

		var sale_price    = parseFloat( accounting.unformat( sale_price_field.val(), woocommerce_admin.mon_decimal_point ) );
		var regular_price = parseFloat( accounting.unformat( regular_price_field.val(), woocommerce_admin.mon_decimal_point ) );

		var sale_price    = parseFloat( accounting.unformat( sale_price_field.val(), woocommerce_admin.mon_decimal_point ) );
		var regular_price = parseFloat( accounting.unformat( regular_price_field.val(), woocommerce_admin.mon_decimal_point ) );

		if( sale_price >= regular_price ) {
			sale_price_field.val( regular_price_field.val() );
		} else {
			$('.wc_error_tip').fadeOut('100', function(){ $(this).remove(); } );
		}
		return this;			

	});		
	
	$('body').on( 'click', '.wcpbc_sale_price_dates[type="radio"], .wcpbc_price_method[type="radio"]', function(){
		var parent_class = '.' + $(this).attr('name') + '_field';					

		parent_class = parent_class.replace('[', '_');
		parent_class = parent_class.replace(']', '');
		
		$(this).closest(parent_class).next('.wcpbc_show_if_manual').toggle( $(this).val() == 'manual' );		
	});

	$('.wcpbc-region-settings').on( 'click', '.select_eur', function(){
		var countries = $(this).data('countries');		
		
		if ( countries instanceof Array ) {
			$( this ).closest( 'td' ).find( 'select option' ).each( function( index, that ) {
				if ( countries.indexOf( $(that).attr('value') ) > -1 ) {
					$(that).attr('selected', 'selected');
				}
			});	
			$( this ).closest( 'td' ).find( 'select' ).trigger('change');
		}

		return false;		
	});
	
	$('#wc_price_based_country_test_mode').on('change', function() {
   		if ($(this).is(':checked')) {
   			$('#wc_price_based_country_test_country').closest('tr').show();
   		} else {
   			$('#wc_price_based_country_test_country').closest('tr').hide();
   		}
   	}).change();
	
	$('#general_coupon_data #discount_type').on('change', function(){
		$('#general_coupon_data #zone_pricing_type').closest('p').toggle( $(this).val()=='fixed_cart' ||  $(this).val()=='fixed_product' );
	});
	
	$(document).ready( function (){
		$('.wcpbc_sale_price_dates[type="radio"][value="manual"], .wcpbc_price_method[type="radio"][value="manual"]').each( function(){			
			var parent_class = '.' + $(this).attr('name') + '_field';
			parent_class = parent_class.replace('[', '_');
			parent_class = parent_class.replace(']', '');
			
			$(this).closest(parent_class).next('.wcpbc_show_if_manual').toggle( $(this).prop( "checked" ) );				
		});		
		
		$('#general_coupon_data #zone_pricing_type').closest('p').toggle( $('#general_coupon_data #discount_type').val()=='fixed_cart' ||  $('#general_coupon_data #discount_type').val()=='fixed_product' );

		// Variation pricing bulk edit	
		$( 'select.variation_actions' ).on('wcpbc_variable_bulk_edit_popup', function(e){
			tb_show( 'Upgrade to Price Based on Country Pro now!', '#TB_inline?width=600&height=350&inlineId=variable-bulk-edit-popup', false );
			$('#TB_window').css({
				width: '680px',
				height: '380px'
			});
		});
				
	});
	
});
