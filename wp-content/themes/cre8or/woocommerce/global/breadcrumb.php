<?php
/**
 * Shop breadcrumb
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! empty( $breadcrumb ) ) {

	echo $wrap_before;

    foreach ( $breadcrumb as $key => $crumb ) {

		echo $before;
        echo '<div itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';

		if ( ! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1 ) {
			echo '<a itemprop="item" href="' . esc_url( $crumb[1] ) . '">' . esc_html( $crumb[0] )
                . '<meta itemprop="name" content="'. esc_html( $crumb[0] ) .'">'
                . '<meta itemprop="position" content="'. ($key + 1) .'">'
                . '</a>';
		} else {
			echo esc_html( $crumb[0] );
            echo '<meta itemprop="name" content="'. esc_html( $crumb[0] ) .'">';
            echo '<meta itemprop="position" content="'. ($key + 1) .'">';
		}

        if ( sizeof( $breadcrumb ) !== $key + 1 ) {
            echo $delimiter;
        }

		echo '</div>';
		echo $after;

	}

    echo $wrap_after;

}
