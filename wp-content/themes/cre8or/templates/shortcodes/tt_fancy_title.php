<?php
wc_clear_notices();
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$bg_class = !empty($css) ? vc_shortcode_custom_css_class( $css ) : '';
$theme_class = $bg_class . ' ' . $el_class;
?>
<div class="fancy-title text-center uppercase <?php echo esc_attr( $theme_class ); ?>">

	<?php
		if(stristr($title, "'") != false && stristr($title, ",") != false){
			$title = str_replace("'", "", $title);
			$title = explode(",", $title);
		}
	?>

	<?php echo !empty( $title ) ? sprintf( '<h3>%s</h3>',  is_array($title) ? __(trim($title[0]), trim($title[1])) : $title ) : ''; ?>
	<?php echo !empty( $subtitle ) ? sprintf( '<h4 class="text-grey-four">%s</h4>', esc_html( $subtitle ) ) : ''; ?>
</div>