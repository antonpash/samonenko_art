<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$bg_class = !empty($css) ? vc_shortcode_custom_css_class( $css ) : 'bg-white';
$contact_items = (array) vc_param_group_parse_atts( $contact_items );
$icons_to_enqueue = array();
?>

<div class=" <?php echo esc_attr( $bg_class . ' ' . $el_class ); ?> contact-info">
	<?php if( !empty( $contact_items ) ): ?>
	<ul class="clean-list text-center">
		<?php foreach( $contact_items as $item ):
			$icons_to_enqueue[] = $item['icon_type'];
			$icon_class = isset( $item["icon_" . $item['icon_type']] ) ? esc_attr( $item["icon_" . $item['icon_type']] ) : '';
			$data_icon = array();
			if( !empty($icon_class) ) {
				$data_icon[] = !empty( $icon_class ) ? sprintf( 'data-icon="%s"', esc_attr( $icon_class ) ) : '';
			}
			$icon_color = '';
			if( !empty( $item['icon_color'] ) ) {
				$data_icon[] = !empty( $item['icon_color'] ) ? sprintf( 'data-icon-color="%s"', esc_attr( $item['icon_color'] ) ) : '';
				$icon_color = sprintf( 'color: %s !important;', esc_attr( $item['icon_color'] ) );
			}
			$icon_size = '';
			if( !empty( $item['icon_size'] ) ) {
				$data_icon[] = !empty( $item['icon_size'] ) ? sprintf( 'data-icon-size="%s"', esc_attr( $item['icon_size'] ) ) : '';
				$icon_size = sprintf( 'font-size: %spx !important;', esc_attr( $item['icon_size'] ) );
			}
			$data_icon = implode(' ', $data_icon);
			$icon_css = !empty( $icon_color ) || !empty( $icon_size ) ? sprintf( 'style="%s"', esc_attr( $icon_size . $icon_color ) ) : '';
		?>
		<li>
			<?php echo !empty($item['info_title']) ? sprintf('<h5 class="uppercase text-alpha">%s</h5>', esc_html( $item['info_title'] )) : ''; ?>
			<?php if( !empty( $item['icon_type'] ) ): ?>
				<?php echo $item['icon_type'] == 'ionicon' ? sprintf('<i class="align-center" %s></i>', $data_icon ) : ''; ?>
				<?php echo $item['icon_type'] != 'ionicon' ? sprintf('<i class="align-center %s" %s></i>', esc_attr( $icon_class ), $icon_css ) : ''; ?>
			<?php endif; ?>
			<?php echo !empty( $item['info_text'] ) ? $item['info_text'] : ''; ?>
		</li>
		<?php endforeach; ?>
	</ul>
	<?php
		$icons_to_enqueue = array_unique( $icons_to_enqueue );
		if( !empty( $icons_to_enqueue ) ) {
			foreach ($icons_to_enqueue as $icon_script) {
				if( $icon_script != 'ionicon' ) {
					vc_icon_element_fonts_enqueue( $icon_script );
				}
			}
		}
	endif; ?>
</div>