<?php
global $post, $tt_theme;
wc_clear_notices();
$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);
$bg_class = !empty($css) ? vc_shortcode_custom_css_class($css) : '';
$theme_class = $bg_class . ' ' . $el_class;

$i = 0;
$j = $posts;
$posts = array();

while($i < $j) {
    $post = $this->get_posts(1, $order, $i, (int)$category_filter);

    $product_id = wc_get_product_id_by_sku($post[0]->ID);
    $_product = wc_get_product($product_id);

    if($product_id && $_product->is_in_stock()){
        $posts = array_merge($posts, $post);
    }
    else $j++;

    $i++;
}

$filters = $this->get_filters($posts);
$post_thumb_size = $row > 3 ? 'portfolio-thumb' : 'full';
$post_thumb_size = !empty($long) ? 'portfolio-long' : $post_thumb_size;

?>
<?php if (!empty($posts)): ?>
    <div class="<?php echo !empty($fit) ? 'row row-fit' : 'row'; ?>">
        <?php if (!empty($show_filters) && !empty($filters) && empty($category_filter)): ?>
            <div class="align-center uppercase">
                <ul class="portfolio-filters inline-list">
                    <li class="filter-active"><a href="#" cat="0"> <?php esc_html_e('all', 'cre8or'); ?> </a></li>
                    <?php foreach ($filters as $key => $filter) {
                        printf('<li><a href="#" cat="%s">%s</a></li>', esc_html($key), esc_html($filter));
                    } ?>
                </ul>
            </div>
        <?php endif; ?>

        <ul class="portfolio-items clean-list <?php echo esc_attr($theme_class); ?>" data-grid="li">
            <?php
            foreach ($posts as $post) {





                setup_postdata($post);

                ?>

                <li class="<?php echo !empty($row) ? $this->grid_css_class($row) : ''; ?> col-sm-6 col-xs-12"
                    data-grid-filter="<?php echo esc_attr($this->get_filters(null, $post->ID)); ?>">
                    <?php if (has_post_thumbnail()):?>

                        <figure class="text-center">
                            <a href="<?php the_permalink(); ?>">
                                <?php echo get_the_post_thumbnail(null, $post_thumb_size); ?>
                            </a>
                            <figcaption>
                                <div class="portfolio-hint">
                                    <?php the_title('<h4>', '</h4>') ?>
                                    <ul class="inline-list align-center">
                                        <li class="bg-white">
                                            <a href="#"
                                               data-zoom="<?php echo esc_attr(wp_get_attachment_url(get_post_thumbnail_id($post->ID))); ?>"><i
                                                    data-icon="ios-eye-outline" data-icon-size="36"
                                                    data-icon-color="<?php tt_primary('#f4a3c4'); ?>"></i></a>
                                        </li>
                                        <li class="bg-white">
                                            <a href="<?php the_permalink(); ?>"><i data-icon="ios-redo-outline"
                                                                                   data-icon-size="36"
                                                                                   data-icon-color="<?php tt_primary('#f4a3c4'); ?>"></i></a>
                                        </li>
                                    </ul>
                                </div>
                            </figcaption>
                        </figure>
                    <?php endif; ?>
                </li>
            <?php }  ?>
        </ul>
        <?php wp_reset_postdata(); ?>
    </div>
<?php endif; ?>