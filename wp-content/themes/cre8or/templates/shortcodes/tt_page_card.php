<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$bg_class = !empty($css) ? vc_shortcode_custom_css_class( $css ) : '';
$theme_class = !empty( $bg_class ) || !empty($el_class) ? $bg_class . ' ' . $el_class : '';
$image = wp_get_attachment_image_src( $photo, 'full' );
?>
<article class="blog-post bg-white page-post <?php echo esc_attr( $theme_class ); ?>">
	<div class="post-meta">
		<?php echo !empty( $image[0] ) ? sprintf( '<figure class="post-thumbnail video-featured"><img src="%s" alt="%s"></figure>', esc_attr( $image[0] ), esc_attr( $title ) ) : ''; ?>
	</div>
	<header class="post-header">
		<?php echo !empty( $title ) ? sprintf( '<br><h3 class="post-title align-center">%s</h3>', esc_html( $title ) ) : ''; ?>
	</header>
	<?php echo '<div class="post-content">'.$content.'</div>'; ?>
</article>