<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$bg_class = !empty($css) ? vc_shortcode_custom_css_class( $css ) : '';
$theme_class = !empty($bg_class) || !empty($el_class) ? $bg_class . ' ' . $el_class : '';
$member_image = wp_get_attachment_image_src( $photo, 'full' );
$social_array = (array) vc_param_group_parse_atts( $socials );
?>

<div class="team-item bg-white <?php echo esc_attr( $theme_class ); ?>">
	<?php echo !empty( $member_image[0] ) ? sprintf('<figure><img src="%s" alt="%s"></figure>', esc_attr( $member_image[0] ), esc_attr( $name ) ) : ''; ?>
	<?php echo !empty( $name ) ? sprintf( '<h4 class="text-center">%s</h4>', esc_html( $name ) ) : ''; ?>
	<?php echo !empty( $position ) ? sprintf( '<span class="team-label align-center">%s</span>', esc_html( $position ) ) : ''; ?>
	<?php if( !empty( $social_array ) ): ?>
	<ul class="inline-list align-center share-socials">
		<?php foreach( $social_array as $social_item ): ?>
			<?php $social_css_class = !empty( $social_item['social_icon'] ) ? $this->social_css_class( $social_item['social_icon'] ) : ''; ?>
			<li>
				<a href="<?php echo !empty( $social_item['social_url'] ) ? $social_item['social_url'] : ''; ?>" class="<?php echo esc_attr( $social_css_class ); ?> text-white">
					<i <?php echo !empty( $social_item['social_icon'] ) ? sprintf('data-icon="%s"', esc_attr( $social_item['social_icon'] ) ) : ''; ?> data-icon-size="18" data-icon-color="#ffffff"></i>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
</div>