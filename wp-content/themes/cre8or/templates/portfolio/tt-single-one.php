<?php wc_clear_notices(); ?>
<section class="box-grid">
    <div class="container">

        <?php
        dimox_breadcrumbs();
        ?>

        <div class="row">
            <div class="col-md-8">
                <?php if (!empty($images)): ?>
                    <ul class="portfolio-items portfolio-single clean-list">
                        <?php foreach ($images as $key => $image) : if (wp_attachment_is_image($image)): ?>
                            <li>
                                <figure>
                                    <?php $zoom_image = wp_get_attachment_image_src($image, 'full'); ?>
                                    <a href="#" data-zoom="<?php echo esc_attr($zoom_image[0]); ?>">
                                        <?php echo wp_get_attachment_image($image, 'full'); ?>
                                    </a>
                                    <figcaption>
                                        <div class="portfolio-hint">
                                            <ul class="inline-list align-center">
                                                <li>
                                                    <?php if (!empty($zoom_image[0])): ?>
                                                        <i data-icon="ios-eye-outline" data-icon-size="72"
                                                           data-icon-color="<?php tt_primary('#f4a3c4'); ?>"></i></a>
                                                    <?php endif; ?>
                                                </li>
                                            </ul>
                                        </div>
                                    </figcaption>
                                </figure>
                            </li>
                        <?php endif; endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <div class="col-md-4">
                <aside class="portfolio-sidebar" data-sticky-sidebar="true">
                    <?php
                    $product_id = wc_get_product_id_by_sku($id);
                    $_product = wc_get_product($product_id);

                    ?>
                    <?php if (get_the_title($id) != ''): ?>
                        <h2 style="margin-top: 0; padding-top: 15px;"><?php echo get_the_title($id); ?></h2>
                    <?php endif ?>

                    <?php if (!empty($meta)): ?>
                        <ul class="clean-list portfolio-info">
                            <?php foreach ($meta as $key => $value) : ?>
                                <?php if ($key == 'Статус:'): ?>
                                    <?php if ($product_id && $_product->is_in_stock()): ?>
                                        <?php printf('<li><span>%s</span>В наличии</li>', $key); ?>
                                    <?php else: ?>
                                        <?php printf('<li><span>%s</span>Продано</li>', $key); ?>
                                    <?php endif ?>
                                <?php else: ?>
                                    <?php printf('<li><span>%s</span>%s</li>', $key, $value); ?>
                                <?php endif ?>
                            <?php endforeach; ?>

                        </ul>
                    <?php endif; ?>

                    <?php if ($product_id && $_product->is_in_stock()): ?>
                        <?php echo '<p class="portfolio-price">' . $_product->get_price() . ' ' . get_woocommerce_currency_symbol() . '</p>'; ?>
                        <?php printf('<a href="?add-to-cart=%s" rel="nofollow" data-product_id="%s" data-product_sku="" data-quantity="1" class="button-md button-outlined text-black align-center uppercase button add_to_cart_button product_type_simple">' . __('Add to cart', 'woocommerce') . ' <i data-icon="ios-cart" data-icon-color="#000" data-icon-size="28"></i></a>', $product_id, $product_id); ?>
                    <?php endif ?>

                    <?php echo wpautop(get_the_content($id)); ?>

                    <?php if (!empty($socials)): ?>
                        <div class="share-box bg-white">
                            <h5 class="text-center"><?php _e('Share your love:', 'cre8or'); ?></h5>
                            <div class="inline-list align-center">
                                <script type="text/javascript">(function () {
                                        if (window.pluso)if (typeof window.pluso.start == "function") return;
                                        if (window.ifpluso == undefined) {
                                            window.ifpluso = 1;
                                            var d = document, s = d.createElement('script'), g = 'getElementsByTagName';
                                            s.type = 'text/javascript';
                                            s.charset = 'UTF-8';
                                            s.async = true;
                                            s.src = ('https:' == window.location.protocol ? 'https' : 'http') + '://share.pluso.ru/pluso-like.js';
                                            var h = d[g]('body')[0];
                                            h.appendChild(s);
                                        }
                                    })();</script>
                                <div class="pluso" data-background="transparent"
                                     data-options="medium,square,line,horizontal,nocounter,theme=02"
                                     data-services="vkontakte,odnoklassniki,facebook,twitter,google,email"></div>

                            </div>
                        </div>
                    <?php endif; ?>
                </aside>
            </div>
        </div>
    </div>
</section>

<section class="box">
    <div class="row row-fit">
        <div class="col-sm-6">
            <?php if (!empty($prev_post->ID)): ?>
                <div class="project-nav prev-project bg-white">
                    <a href="<?php echo get_the_permalink($prev_post->ID); ?>"></a>
                    <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($prev_post->ID), 'full'); ?>
                    <?php if (!empty($image[0])): ?>
                        <span style="background-image: url( <?php echo esc_attr($image[0]); ?> );"></span>
                    <?php endif ?>
                    <h5 class="text-center uppercase"><?php esc_html_e('Prev project', 'cre8or'); ?></h5>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-sm-6">
            <?php if (!empty($next_post->ID)): ?>
                <div class="project-nav next-project bg-white">
                    <a href="<?php echo get_the_permalink($next_post->ID); ?>"></a>
                    <?php $image = wp_get_attachment_image_src(get_post_thumbnail_id($next_post->ID), 'full'); ?>
                    <?php if (!empty($image[0])): ?>
                        <span style="background-image: url( <?php echo esc_attr($image[0]); ?> );"></span>
                    <?php endif ?>
                    <h5 class="text-center uppercase"><?php esc_html_e('Next project', 'cre8or'); ?></h5>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>