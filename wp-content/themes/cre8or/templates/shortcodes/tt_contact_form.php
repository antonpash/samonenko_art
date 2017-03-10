<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$bg_class = !empty($css) ? vc_shortcode_custom_css_class( $css ) : '';
$theme_class = !empty($bg_class) || !empty($el_class) ? sprintf('class="%s"', esc_html( $bg_class . ' ' . $el_class ) ) : '';
?>
<div <?php echo balanceTags( $theme_class ); ?>>
	<form id="contact-form" action="#" class="contact-form slim-form">
		<input type="hidden" name="receiver" value="<?php echo urlencode($email); ?>">
		<p><input type="text" name="name" required><span class="uppercase font-beta text-beta"><?php esc_html_e('Your name', 'cre8or'); ?></span></p>
		<p><input type="text" name="email" required><span class="uppercase font-beta text-beta"><?php esc_html_e('Your e-mail', 'cre8or'); ?></span></p>
		<p><input type="text" name="phone" required><span class="uppercase font-beta text-beta"><?php esc_html_e('Your phone', 'cre8or'); ?></span></p>
		<p><textarea name="message"></textarea><span class="uppercase font-beta text-beta"><?php esc_html_e('Message', 'cre8or'); ?></span></p>
		<p><button type="submit" class="button-md button-outlined text-black align-right uppercase"><?php esc_html_e('Send Message', 'cre8or'); ?></button></p>
	</form>
</div>