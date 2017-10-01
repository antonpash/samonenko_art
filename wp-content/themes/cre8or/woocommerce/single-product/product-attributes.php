<?php
/**
 * Product attributes
 *
 * Used by list_attributes() in the products class.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-attributes.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     3.1.0
 */

if (!defined('ABSPATH')) {
	exit;
}
?>
<?php if ($display_dimensions && $product->has_weight()) : ?>
	<tr>
		<th><?php _e('Weight', 'woocommerce') ?></th>
		<td class="product_weight"><?php echo esc_html(wc_format_weight($product->get_weight())); ?></td>
	</tr>
<?php endif; ?>

<?php if ($display_dimensions && $product->has_dimensions()) : ?>
	<tr>
		<th><?php _e('Dimensions', 'woocommerce') ?></th>
		<td class="product_dimensions"><?php echo esc_html(wc_format_dimensions($product->get_dimensions(false))); ?></td>
	</tr>
<?php endif; ?>

<?php foreach ($attributes as $attribute) : ?>
	<li>
		<i data-icon="<?php echo wc_attribute_label($attribute->get_name()); ?>" data-icon-size="28"
		   data-icon-color="#d7d7d7"></i>
		<?php
		$values = array();

		if ($attribute->is_taxonomy()) {
			$attribute_taxonomy = $attribute->get_taxonomy_object();
			$attribute_values = wc_get_product_terms($product->get_id(), $attribute->get_name(), array('fields' => 'all'));

			foreach ($attribute_values as $attribute_value) {
				$value_name = esc_html($attribute_value->name);

				if ($attribute_taxonomy->attribute_public) {
					$values[] = '<a href="' . esc_url(get_term_link($attribute_value->term_id, $attribute->get_name())) . '" rel="tag">' . $value_name . '</a>';
				} else {
					$values[] = $value_name;
				}
			}
		} else {
			$values = $attribute->get_options();

			if (preg_match("/http/i", $values[0])) {

				// Convert pipes to commas and display values
				$values = array_map('trim', explode(WC_DELIMITER, $values[0]));

				if (preg_match("/portfolio/i", $values[0])) {
					$port_id = explode('/', $values[0]);
					$port_cat = wp_get_post_terms($port_id[count($port_id) - 2], 'portfolio_tax');
					$port_title = get_the_title($port_id[count($port_id) - 2]);
					$value = ($port_title == "" ? 'Оригинал' : $port_title) . ' (' . $port_cat[0]->name . ')';
				} else $value = apply_filters('woocommerce_attribute', wptexturize(implode(', ', $values)), $attribute, $values);

				echo "<a href='{$values[0]}' target='_blank'>{$value}</a>";

			} else {

				foreach ($values as &$value) {
					$value = make_clickable(esc_html($value));
				}

				echo apply_filters('woocommerce_attribute', wptexturize(implode(', ', $values)), $attribute, $values);
			}
		}

		?>
	</li>
<?php endforeach; ?>
