<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$bg_class = !empty($css) ? vc_shortcode_custom_css_class( $css ) : '';
$theme_class = !empty($bg_class) || !empty($el_class) ? $bg_class . ' ' . $el_class : '';
$tab_array = (array) vc_param_group_parse_atts( $tab_items );
$icons_to_enqueue = array();
?>
<div class="tabs-box <?php echo esc_attr( $theme_class ); ?>">
	<?php if( !empty( $tab_array ) ): ?>
	<ul class="tab-nav text-center clean-list bg-white">
		<?php foreach ($tab_array as $key => $tab): ?>
		<?php
			$icons_to_enqueue[] = $tab['icon_type'];
			$icon_class = isset( $tab["icon_" . $tab['icon_type']] ) ? esc_attr( $tab["icon_" . $tab['icon_type']] ) : '';
			$data_icon = array();
			if( !empty($icon_class) ) {
				$data_icon[] = !empty( $icon_class ) ? sprintf( 'data-icon="%s"', esc_attr( $icon_class ) ) : '';
			}
			$icon_color = '';
			if( !empty( $tab['icon_color'] ) ) {
				$data_icon[] = !empty( $tab['icon_color'] ) ? sprintf( 'data-icon-color="%s"', esc_attr( $tab['icon_color'] ) ) : '';
				$icon_color = sprintf( 'color: %s !important;', esc_attr( $tab['icon_color'] ) );
			}
			$icon_size = '';
			if( !empty( $tab['icon_size'] ) ) {
				$data_icon[] = !empty( $tab['icon_size'] ) ? sprintf( 'data-icon-size="%s"', esc_attr( $tab['icon_size'] ) ) : '';
				$icon_size = sprintf( 'font-size: %spx !important;', esc_attr( $tab['icon_size'] ) );
			}
			$data_icon = implode(' ', $data_icon);
			$icon_css = !empty( $icon_color ) || !empty( $icon_size ) ? sprintf( 'style="%s"', esc_attr( $icon_size . $icon_color ) ) : '';
		?>

		<li <?php echo ( $key === 0 ) ? 'class="current-tab"' : ''; ?> >
			<?php if( !empty( $tab['icon_type'] ) ): ?>
				<?php echo $tab['icon_type'] == 'ionicon' ? sprintf('<i class="align-center" %s></i>', $data_icon ) : ''; ?>
				<?php echo $tab['icon_type'] != 'ionicon' ? sprintf('<i class="align-center %s" %s></i>', esc_attr( $icon_class ), $icon_css ) : ''; ?>
			<?php endif; ?>
			<?php echo !empty( $tab['tab_title'] ) ? sprintf('<h4>%s</h4>', esc_html( $tab['tab_title'] ) ) : ''; ?>
		</li>
		<?php endforeach; ?>
	</ul>
	<ul class="clean-list tab-content bg-grey-two">
		<?php foreach ($tab_array as $tab): ?>
		<?php echo !empty( $tab['tab_content'] ) ? sprintf(' <li>%s</li>', wpautop( $tab['tab_content'] ) ) : ''; ?>
		<?php endforeach; ?>
	</ul>
	<?php endif; ?>
</div>
<?php
$icons_to_enqueue = array_unique($icons_to_enqueue);
if( !empty($icons_to_enqueue) ) {
	foreach ($icons_to_enqueue as $icon_script) {
		if( $icon_script != 'ionicon' ) {
			vc_icon_element_fonts_enqueue( $icon_script );
		}
	}
}?>