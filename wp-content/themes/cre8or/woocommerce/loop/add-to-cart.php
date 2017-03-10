<?php
/**
 * Loop Add to Cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
//printf( '<a href="?add-to-cart=%s" rel="nofollow" data-product_id="%s" data-product_sku="" data-quantity="1" class="">Добавить в корзину</a>', $product_id, $product_id );
echo apply_filters( 'woocommerce_loop_add_to_cart_link',
	sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" data-quantity="%s" class="button-sm button-outlined text-black align-center uppercase %s product_type_%s"><i data-icon="ios-cart" data-icon-color="#000" data-icon-size="28"></i> '.__( 'Add to cart', 'woocommerce' ).'</a>',
		esc_url( $product->add_to_cart_url() ),
		esc_attr( $product->id ),
		esc_attr( $product->get_sku() ),
		esc_attr( isset( $quantity ) ? $quantity : 1 ),
		$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
		esc_attr( $product->product_type )
	),
$product );