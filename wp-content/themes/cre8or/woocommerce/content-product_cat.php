<?php
/**
 * The template for displaying product category thumbnails within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product_cat.php
 *
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     2.4.0
 */

if (!defined('ABSPATH')) {
    exit;
}


if($category->parent != 0){
    return;
}

global $woocommerce_loop;
$cate = get_queried_object();
$cateID = $cate->term_id;

// Store loop count we're currently on
if (empty($woocommerce_loop['loop'])) {
    $woocommerce_loop['loop'] = 0;
}

// Store column count for displaying the grid
if (empty($woocommerce_loop['columns'])) {
    $woocommerce_loop['columns'] = apply_filters('loop_shop_columns', 4);
}

// Increase loop count
//$woocommerce_loop['loop'] ++;
?>

<li <?php wc_product_cat_class('vc_tta-tab ' . ($category->term_id == $cateID ? 'vc_active' : '')); ?>>
    <?php do_action('woocommerce_before_subcategory', $category); ?>

    <a href="<?php echo get_term_link($category->slug, 'product_cat'); ?>">

        <?php
        /**
         * woocommerce_before_subcategory_title hook
         *
         * @hooked woocommerce_subcategory_thumbnail - 10
         */
        //			do_action( 'woocommerce_before_subcategory_title', $category );
        ?>

        <span class="vc_tta-title-text">
			<?php

                echo $category->name;

            //				if ( $category->count > 0 )
            //					echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">(' . $category->count . ')</mark>', $category );
            ?>
		</span>

        <?php
        /**
         * woocommerce_after_subcategory_title hook
         */
        do_action('woocommerce_after_subcategory_title', $category);
        ?>

    </a>

    <?php do_action('woocommerce_after_subcategory', $category); ?>
</li>