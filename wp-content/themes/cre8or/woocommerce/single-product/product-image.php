<?php
/**
 * Single Product Image
 *
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     2.0.14
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $post, $woocommerce, $product;

?>

<?php

$attachment_ids[] = get_post_thumbnail_id($post->ID);
$attachment_ids = array_merge($attachment_ids, $product->get_gallery_attachment_ids());

if (!empty($attachment_ids)) {

    ?>

    <ul class="portfolio-items clean-list">
        <?php

        $i = 0;

        foreach ($attachment_ids as $attachment_id) {

            $i++;
            $image_class = ($i + 1) % 3 === 0 || $i % 3 === 0 ? 'col-md-6' : 'col-md-12';
            ?>

            <li class="<?php echo esc_attr($image_class); ?>">
                <figure>
                    <?php $zoom_image = wp_get_attachment_image_src($attachment_id, 'full'); ?>
                    <a href="#" <?php echo $i == 1 ? 'itemprop="image"' : ''; ?> data-zoom="<?php echo esc_attr($zoom_image[0]); ?>">
                        <?php echo $image = wp_get_attachment_image($attachment_id, 'full', 0, $attr = array(
                            'title' => $product->post->post_title,
                            'alt' => $product->post->post_title,
                            'itemprop' => 'image'
                        )); ?>
                    </a>
                </figure>
            </li>

            <?php
        }

        ?>
    </ul>
    <?php
}

?>


<!--<div class="images">-->
<!---->
<!--    --><?php
//
//    if (has_post_thumbnail()) {
//
//        $image_title = esc_attr(get_the_title(get_post_thumbnail_id()));
//        $image_caption = get_post(get_post_thumbnail_id())->post_excerpt;
//        $image_link = wp_get_attachment_url(get_post_thumbnail_id());
//        $image = get_the_post_thumbnail($post->ID, apply_filters('single_product_large_thumbnail_size', 'shop_single'), array(
//            'title' => $product->post->post_title,
//            'alt' => $product->post->post_title
//        ));
//
//        $attachment_count = count($product->get_gallery_attachment_ids());
//
//        if ($attachment_count > 0) {
//            $gallery = '[product-gallery]';
//        } else {
//            $gallery = '';
//        }
//
//        echo apply_filters('woocommerce_single_product_image_html', sprintf('<a href="%s" itemprop="image" class="woocommerce-main-image zoom" title="%s" data-rel="prettyPhoto' . $gallery . '">%s</a>', $image_link, $product->post->post_title, $image), $post->ID);
//
//    } else {
//
//        //echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) ), $post->ID );
//
//    }
//    ?>
<!---->
<!--    --><?php //do_action('woocommerce_product_thumbnails'); ?>
<!---->
<!--</div>-->
