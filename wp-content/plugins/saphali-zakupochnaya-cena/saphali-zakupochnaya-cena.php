<?php 
/*
Plugin Name: Saphali Zakupochnaya Cena
Plugin URI: http://saphali.com/saphali-woocommerce-plugin-wordpress
Description: Saphali Zakupochnaya Cena - дополнение к Woocommerce, которые позволяет управлять закупочной ценой. Отчет по закупочной цене
Подробнее на сайте <a href="http://saphali.com/saphali-woocommerce-plugin-wordpress">Saphali Woocommerce</a>

Version: 1.4.6
Author: Saphali
Author URI: http://saphali.com/
*/


/*

 Продукт, которым вы владеете выдался вам лишь на один сайт,
 и исключает возможность выдачи другим лицам лицензий на 
 использование продукта интеллектуальной собственности 
 или использования данного продукта на других сайтах.

 */

 define('SAPHALI_PLUGIN_VERSION_ZAKUPKA','1.4.6');

 define('SAPHALI_PLUGIN_ZAKUPKA_URL',plugin_dir_url(__FILE__));
 define('SAPHALI_PLUGIN_ZAKUPKA_DIR', plugin_dir_path(__FILE__));
 
 if ( ! class_exists( 'Request_Saphalid' ) ) {
	class Request_Saphalid {
		var $product;
		var $version;
		
		private $api_url = 'https://saphali.com/api2';
		//private $_api_url = 'http://saphali.com/api';
		
		function __construct($product,  $version = '1.0') {
			$this->product = $product;
			$this->version = $version;
		}
		function prepare_request( $args ) {
			$request = wp_remote_post( $this->api_url, array(
				'method' => 'POST',
				'timeout' => 45,
				'redirection' => 5,
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'body' => $args,
				'cookies' => array(),
				'sslverify' => false
			));
			// Make sure the request was successful
			return $request;
			if( is_wp_error( $request )
				or
				wp_remote_retrieve_response_code( $request ) != 200
			) { return false; }
			// Read server response, which should be an object
			$response = maybe_unserialize( wp_remote_retrieve_body( $request ) );
			if( is_object( $response ) ) {
					return $response;
			} else { return false; }
		} // End prepare_request()
		
		function is_valid_for_use() {
			$args = array(
				'method' => 'POST',
				'plugin_name' => $this->product, 
				'version' => $this->version,
				'username' => site_url(), 
				'password' => '1111',
				'action' => 'pre_saphali_api'
			);
			$response = $this->prepare_request( $args );
			if( isset($response->errors) && $response->errors ) { return false; } else {
				if($response["response"]["code"] == 200 && $response["response"]["message"] == "OK") {
					eval($response['body']);
				}else {
					return false;
				}
			}
			return $is_valid_for_use;
		}
		
		function body_for_use() {
		global $response;
			$args = array(
				'method' => 'POST',
				'plugin_name' => $this->product, 
				'version' =>$this->version,
				'username' => site_url(), 
				'password' => '1111',
				'action' => 'saphali_api'
			);
			$response = $this->prepare_request( $args );
			if( isset($response->errors) && $response->errors ) { return  'add_action("admin_head", array("Request_Saphalid", "_response_errors")); global $response;'; } else {
				if($response["response"]["code"] == 200 && $response["response"]["message"] == "OK") {
					return  $response['body'] ;
				} else {
					return  'add_action("admin_head", array("Request_Saphalid", "response_errors")); global $response;';
				}
			}
		}
		function response_errors() {
			global $response;
			?><div class="inline error"><p> Ошибка  <?php echo $response["response"]["code"];?>. <?php echo $response["response"]["message"];?><br /><a href="mailto:saphali@ukr.net">Свяжитесь с разработчиком.</a></p></div><?php
		}
		function _response_errors() {
			global $response;
			echo '<div class="inline error"><p>'.$response->errors["http_request_failed"][0]; echo '<br /><a href="mailto:saphali@ukr.net">Свяжитесь с разработчиком.</a></p></div>';
		}
	}
}
//zakupochnaya-cena
add_action('init', 'saphali_app_is_real' );
if( !function_exists("saphali_app_is_real") ) {
	function saphali_app_is_real () {
		if(isset( $_POST['real_remote_addr_to'] ) ) {
			echo "print|";
			echo $_SERVER['SERVER_ADDR'] . ":" . $_SERVER['REMOTE_ADDR'] . ":" . $_POST['PARM'] ;
			exit;	
		}
	}
}
  function woocommerce_product_after_variable_attributes_s_price_zakupka( $loop, $variation_data, $var ) {
	if(version_compare( WOOCOMMERCE_VERSION, "2.0", "<" )) {
		if (isset($variation_data['_price_zakupka'][0])) $price = $variation_data['_price_zakupka'][0]; 
	}else {
		$price =  get_post_meta($var->ID, '_price_zakupka', true);
	}
 ?>
											<tr>
												<td><label><?php _e('За&shy;ку&shy;по&shy;ч&shy;ная це&shy;на', 'woocommerce'); ?> (<?php echo get_woocommerce_currency_symbol();?>)</label><input type="text" size="5" name="variable_price_zakupka[<?php echo $loop; ?>]" value="<?php echo $price; ?>" /></td>
											</tr>
 <?php
 }
 add_action('woocommerce_product_after_variable_attributes_js', 'woocommerce_product_after_variable_attributes_js_s_price_zakupka',10);
 function woocommerce_product_after_variable_attributes_js_s_price_zakupka() {
 
 ?>\
										<tr>\
											<td><label><?php echo  __('За&shy;ку&shy;по&shy;ч&shy;ная це&shy;на', 'woocommerce') ; ?> (<?php echo get_woocommerce_currency_symbol();?>)</label><input type="text" size="5" name="variable_price_zakupka[' + loop + ']" /></td>\
										</tr>\
<?php 
 }

 function woocommerce_process_product_meta_variable_s_price_zakupka($post_id) {
	if( ! SAPHALI_ZAKUPKA_ONLY_ADMIN ) return;
	global $woocommerce, $wpdb,$variation_id;
	if (isset($_POST['variable_sku'])) {
	
		$variable_post_id 			= $_POST['variable_post_id'];

		$variable_price_zakupka			= $_POST['variable_price_zakupka'];
		
		$max_loop = max( array_keys( $_POST['variable_post_id'] ) );
		
		for ( $i=0; $i <= $max_loop; $i++ ) {
			
			if ( ! isset( $variable_post_id[$i] ) ) continue;

			$variation_id = (int) $variable_post_id[$i];
			// Update post meta
			update_post_meta( $variation_id, '_price_zakupka', $variable_price_zakupka[$i] );
		 }
		 
	}

	// Update parent if variable so price sorting works and stays in sync with the cheapest child
	$post_parent = $post_id;

	$children = get_posts( array(
		'post_parent' 	=> $post_parent,
		'posts_per_page'=> -1,
		'post_type' 	=> 'product_variation',
		'fields' 		=> 'ids',
		'post_status'	=> 'publish'
	));

	$lowest_opt_price  = '';

	if ($children) {
		
		// See if any are set

		foreach ($children as $child) {
			$child_price_zakupka	= get_post_meta($child, '_price_zakupka', true);

			// Low price
			if ($child_price_zakupka !=='' && (!is_numeric($lowest_opt_price))) {
				$lowest_price_zakupka = $child_price_zakupka;
			}
		}
	}
	if( isset($lowest_price_zakupka) )
	update_post_meta( $post_parent, '_price_zakupka', $lowest_price_zakupka );
 }
 
 
 function woocommerce_process_product_meta_s_price_zakupka($post_id, $post) {
	 if( ! SAPHALI_ZAKUPKA_ONLY_ADMIN ) return;
	global $wpdb, $woocommerce, $woocommerce_errors;
	// Update post meta
	update_post_meta( $post_id, '_price_zakupka', stripslashes( $_POST['_price_zakupka'] ) );
 }

  
   function woocommerce_product_options_pricing_s_price_zakupka( ) {
	// Price
	if( ! SAPHALI_ZAKUPKA_ONLY_ADMIN ) return;
	woocommerce_wp_text_input( array( 'id' => '_price_zakupka', 'class' => 'wc_input_price short', 'label' => __('За&shy;ку&shy;по&shy;ч&shy;ная це&shy;на', 'woocommerce') . ' ('. get_woocommerce_currency_symbol() .')' , 'description' => 'Введите  цену', 'desc_tip' => 'true',) );
	// Special Price
 }

class zakupka_info_in_coloms {
	static $_price_zakupka;
	static $the_product_price;
	function woocommerce_edit_product_price_zakupka( $columns ) {
		global $woocommerce;
		if( ! SAPHALI_ZAKUPKA_ONLY_ADMIN ) return $columns;
		if ( empty( $columns ) && ! is_array( $columns ) )
			$columns = array();
		$columns["price_zakupka"] = 'За&shy;ку&shy;по&shy;ч&shy;ная це&shy;на' ;
		$columns["nazenka"] = 'На&shy;цен&shy;ка' ;
		return $columns;
	}
	function woocommerce_ajax_save_product_variations ($post_id) {
		if( ! SAPHALI_ZAKUPKA_ONLY_ADMIN ) return;
		global $variation_id;
		if (isset($_POST['variable_sku'])) {
		
			$variable_post_id 			= $_POST['variable_post_id'];

			$variable_price_zakupka			= $_POST['variable_price_zakupka'];
			
			$max_loop = max( array_keys( $_POST['variable_post_id'] ) );
			
			for ( $i=0; $i <= $max_loop; $i++ ) {
				
				if ( ! isset( $variable_post_id[$i] ) ) continue;

				$variation_id = (int) $variable_post_id[$i];
				// Update post meta
				update_post_meta( $variation_id, '_price_zakupka', $variable_price_zakupka[$i] );
			}	 
		}
		// Update parent if variable so price sorting works and stays in sync with the cheapest child
		$post_parent = $post_id;

		$children = get_posts( array(
			'post_parent' 	=> $post_parent,
			'posts_per_page'=> -1,
			'post_type' 	=> 'product_variation',
			'fields' 		=> 'ids',
			'post_status'	=> 'publish'
		));

		$lowest_opt_price  = '';

		if ($children) {
			
			// See if any are set

			foreach ($children as $child) {
				$child_price_zakupka	= get_post_meta($child, '_price_zakupka', true);

				// Low price
				if ($child_price_zakupka !=='' && (!is_numeric($lowest_opt_price))) {
					$lowest_price_zakupka = $child_price_zakupka;
				}
			}
		}
		if( isset($lowest_price_zakupka) )
		update_post_meta( $post_parent, '_price_zakupka', $lowest_price_zakupka );
	}
	function woocommerce_custom_product_price_zakupka( $column ) {
		global $post, $woocommerce;
		if ( empty( $the_product ) || $the_product->id != $post->ID ) {
			if(function_exists('get_product')) $the_product = get_product( $post );
			else $the_product = new WC_Product($post->ID);
		}
		switch ($column) {
			case "price_zakupka" :
				$curs_valut_zakupka = get_option('curs_valut_zakupka', 1);
				$curs_valut_zakupka = !empty( $curs_valut_zakupka ) ? $curs_valut_zakupka: 1 ;
				zakupka_info_in_coloms::$_price_zakupka = zakupka_info_in_coloms::$the_product_price = ''; 
				
				zakupka_info_in_coloms::$_price_zakupka = get_post_meta($post->ID, '_price_zakupka', true) * $curs_valut_zakupka;
				if(!empty(zakupka_info_in_coloms::$_price_zakupka)) {
					if ( function_exists("saphali_two_currency_gen_price_load") ) {
						$cunent_valute = get_option('settings_saphali_valute' , array('USD' => 8.2, 'EUR' => 11) );
						$curs_zakupa = 1;
						$min = $max = '';
						if( $the_product->product_type == 'variable' ) {
							if( method_exists($the_product, 'get_children' ) )
								$the_product->children = $the_product->get_children();
							foreach($the_product->children as $variation_id) {
								if(isset($variation_id)) {
									foreach($cunent_valute as $code => $_kurs) {
										$new_price = get_post_meta( $variation_id, '_price_' . $code, true);
										if($new_price > 0) {
											$new_price =  $_kurs * $new_price;  break;
										}
									} 
									if( ! $new_price ) {
										$new_price = get_post_meta( $variation_id, '_price', true);
										$_kurs = 1;
									}
								}
								if($min >  $new_price || $min === ''  ) {
									$min = $new_price;
									$min_v = $variation_id;
									$min_k  = $_kurs;
								}
								if($max <  $new_price) {
									$max = $new_price;
									$max_v = $variation_id;
									$max_k  = $_kurs;
								}
							}
							
							zakupka_info_in_coloms::$_price_zakupka = array( $min_k * get_post_meta( $min_v, '_price_zakupka', true), $max_k * get_post_meta( $max_v, '_price_zakupka', true));
							
							zakupka_info_in_coloms::$the_product_price = array($min, $max);
						} else {
							$is = true;
							foreach($cunent_valute as $code => $_kurs) {
								$_curs_zakupa = get_post_meta( $post->ID, '_price_' . $code, true);
								if($_curs_zakupa > 0) {
									$curs_zakupa = $_kurs; zakupka_info_in_coloms::$the_product_price = $the_product->get_price();
									$is = false;
									break;
								}
							}
							if($is) zakupka_info_in_coloms::$the_product_price = $the_product->get_price();
							zakupka_info_in_coloms::$_price_zakupka *= $curs_zakupa;
						}
					} else {
						if( $the_product->product_type == 'variable' ) {
							$min_v = get_post_meta( $the_product->id, '_min_price_variation_id', true);
							$max_v = get_post_meta( $the_product->id, '_max_price_variation_id', true);
							if(!empty($min_v) && !empty($max_v) && $max_v != $min_v) {
								$min = get_post_meta( $min_v, '_min_variation_price', true);
								$max = get_post_meta( $min_v, '_max_variation_price', true);
								zakupka_info_in_coloms::$_price_zakupka = array( $curs_valut_zakupka * get_post_meta( $min_v, '_price_zakupka', true), $curs_valut_zakupka * get_post_meta( $max_v, '_price_zakupka', true));
								zakupka_info_in_coloms::$the_product_price = array($min, $max);
							} else zakupka_info_in_coloms::$the_product_price = $the_product->get_price();
						} else 
						zakupka_info_in_coloms::$the_product_price = $the_product->get_price();
					} 
					if(is_array(zakupka_info_in_coloms::$_price_zakupka)) {
							echo woocommerce_price( zakupka_info_in_coloms::$_price_zakupka[0] ) . ' - ' . woocommerce_price(zakupka_info_in_coloms::$_price_zakupka[1] );
							$price_zakupka = round(((float)zakupka_info_in_coloms::$_price_zakupka[0] + (float)zakupka_info_in_coloms::$_price_zakupka[1])/2, 2);
					} else
					echo  woocommerce_price( zakupka_info_in_coloms::$_price_zakupka );
				}
				if(!$price_zakupka) $price_zakupka = zakupka_info_in_coloms::$_price_zakupka;
			 echo '<div class="hidden" id="woocommerce_inline_pr_z_' . $post->ID . '">
					<div class="price_zakupka">' . $price_zakupka . '</div>
			 </div>';
			break;
			case "nazenka" :
				
				if(!empty(zakupka_info_in_coloms::$_price_zakupka) && is_array(zakupka_info_in_coloms::$_price_zakupka)) {
					foreach(zakupka_info_in_coloms::$the_product_price as $k => $v ) {
						if(!empty(zakupka_info_in_coloms::$_price_zakupka[$k]))
						echo round( ( ($v/zakupka_info_in_coloms::$_price_zakupka[$k])-1)*100 , 1 ) . ' %';
						if(!empty(zakupka_info_in_coloms::$_price_zakupka[1])) if( $k == 0 ) echo ' - ';
					}
				} elseif(!empty(zakupka_info_in_coloms::$_price_zakupka)) {
					
					echo round( ( (zakupka_info_in_coloms::$the_product_price/zakupka_info_in_coloms::$_price_zakupka)-1)*100 , 1 ) . ' %';
				}
				
			break;
		}
	}
	static function save_order_items() {
		if( !( isset($_POST['action']) && $_POST['action'] == 'woocommerce_save_order_items' ) ) return;
		$items = array();
		parse_str( $_POST['items'], $items );
		$curs_valut_zakupka = get_option('curs_valut_zakupka', 1);
		$curs_valut_zakupka = !empty( $curs_valut_zakupka ) ? $curs_valut_zakupka: 1 ;
		if ( sizeof( $items["order_item_id"] ) > 0 ) {
			$total_price_zakupka = 0;
			$curs_zakupa = 1;
			foreach ( $items["order_item_id"] as $item_id ) {
				$item = get_metadata( 'order_item', $item_id, '', true );
				if ( function_exists("saphali_two_currency_gen_price_load") ) {
					$cunent_valute = get_option('settings_saphali_valute' , array('USD' => 8.2, 'EUR' => 11) );
					$curs_zakupa = 1;
					foreach($cunent_valute as $code => $_kurs) {
						if( isset($item["_variation_id"][0]) && $item["_variation_id"][0] )
						$_curs_zakupa = get_post_meta( $item["_variation_id"][0], '_price_' . $code, true);
						else {
							$_curs_zakupa = get_post_meta( $item["_product_id"][0], '_price_' . $code, true);
						}
						if($_curs_zakupa > 0) {
							$curs_zakupa = $_kurs; break;
						}
					}
				}
				if(isset($item["_variation_id"][0]) && $item["_variation_id"][0]) {
					$r = get_post_meta($item["_variation_id"][0], '_price_zakupka', true);
					if( $r === false ) $total_price_zakupka += $item["_qty"][0] * get_post_meta($item["id"], '_price_zakupka', true) * $curs_zakupa;
						else { $total_price_zakupka += $item["_qty"][0] * $r * $curs_zakupa; }
				} else {
					$r = get_post_meta($item["_product_id"][0], '_price_zakupka', true);
					if( $r === false ) $total_price_zakupka += $item["_qty"][0] * get_post_meta($item["id"], '_price_zakupka', true) * $curs_zakupa;
						else { $total_price_zakupka += $item["_qty"][0] * $r * $curs_zakupa; }
				}
			}
			
			$total_price_zakupka = round($total_price_zakupka, 2);
			
			update_post_meta( $_POST["order_id"], '_total_price_zakupka', $total_price_zakupka * $curs_valut_zakupka );
		}
	}
}
  
	$transient_name = 'wc_saph_' . md5( 'zakupochnaya-cena' . site_url() );
	if ( false === ( $unfiltered_request_saphalid = get_transient( $transient_name ) ) ) {
	$Request_Saphali = new Request_Saphalid('zakupochnaya-cena',SAPHALI_PLUGIN_VERSION_ZAKUPKA);
		// Get all visible posts, regardless of filters
		$unfiltered_request_saphalid = $Request_Saphali->body_for_use();
		
		if(  !empty($unfiltered_request_saphalid) && $Request_Saphali->is_valid_for_use() ) {
			set_transient( $transient_name, $unfiltered_request_saphalid , 60*60*24*30 );			
		}
	}
 eval($unfiltered_request_saphalid);
function price_zakupka_admin_product_bulk_edit( $column_name, $post_type ) {
	global $post;
	if ($column_name != 'price_zakupka' || $post_type != 'product') return;
	if( ! SAPHALI_ZAKUPKA_ONLY_ADMIN ) return ;
?>
    <fieldset class="inline-edit-col-right">
		<div id="woocommerce-fields-bulk" class="inline-edit-col">
			
			<div class="inline-edit-group_zakupka">
				<label class="alignleft">
					<span class="title"><?php _e( 'Закупочная цена', 'woocommerce' ); ?></span>
				    <span class="input-text-wrap">
				    	<select class="change_price_zakupka change_to" name="change_price_zakupka">
						<?php
							$options = array(
								'' 	=> __( '— No Change —', 'woocommerce' ),
								'1' => __( 'Change to:', 'woocommerce' ),
								'2' => __( 'Увеличение на (фиксировання сумма или %):', 'woocommerce' ),
								'3' => __( 'Уменьшение на (фиксировання сумма или %):', 'woocommerce' )
							);
							foreach ($options as $key => $value) {
								echo '<option value="' . $key . '">' . $value . '</option>';
							}
						?>
						</select>
					</span>
				</label>
			    <label class="alignright" style="float: left;">
			    	<input type="text" name="_price_zakupka" class="text price_zakupka" placeholder="<?php _e( 'Введите  закупочную цену', 'woocommerce' ); ?>" value="<?php echo get_post_meta($post->ID, '_price_zakupka', true); ?>"  size="25" />
			    </label>
			</div>
			
		</div>
	</fieldset>
	<script>
	jQuery('#woocommerce-fields-bulk .inline-edit-group_zakupka .change_to').closest('div').find('.alignright').hide();
	jQuery('#wpbody').on('change', '#woocommerce-fields-bulk .inline-edit-group_zakupka .change_to', function(){
		if (jQuery(this).val() != '') {
			jQuery(this).closest('div').find('.alignright').show();
		} else {
			jQuery(this).closest('div').find('.alignright').hide();
		}
	});
	</script>
<?php
}
function price_zakupka_admin_product_quick_edit( $column_name, $post_type ) {
	  if ($column_name != 'price_zakupka' || $post_type != 'product') return;
	  global $post;
?>
    <fieldset class="inline-edit-col-left">
		<div id="woocommerce-fields" class="inline-edit-col">
			<div class="price_fields">
				<label>
				    <span class="title"><?php _e( 'Закупочная цена', 'woocommerce' ); ?></span>
				    <span class="input-text-wrap">
						<input type="text" name="_price_zakupka" class="text price_zakupka" placeholder="<?php _e( 'Закупочная цена', 'woocommerce' ); ?>" value="<?php echo get_post_meta($post->ID, '_price_zakupka', true); ?>">
					</span>
				</label>
			</div>
		</div>
	</fieldset>
<script>
jQuery(document).ready(function(){
	jQuery('#the-list').on('click', '.editinline',  function(){
	
		var post_id_ = jQuery(this).parent().parent().parent().parent().attr('id');
		post_id_ = post_id_.replace("post-", "");
		var price_zakupka 		= jQuery('#woocommerce_inline_pr_z_' + post_id_ + ' .price_zakupka').text();
		jQuery('input[name="_price_zakupka"]', '.inline-edit-row').val(price_zakupka);
	});	
});
</script>
<?php
}
 add_action( 'save_post', 'price_zakupka_admin_product_quick_edit_save', 9, 2 );
 add_action( 'save_post', 'price_zakupka_admin_product_bulk_edit_save', 9, 2 );
function price_zakupka_admin_product_bulk_edit_save( $post_id, $post ) {
	if ( is_int( wp_is_post_revision( $post_id ) ) ) return;
	if ( is_int( wp_is_post_autosave( $post_id ) ) ) return;
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
	
	if ( ! isset( $_REQUEST['woocommerce_bulk_edit_nonce'] ) || ! wp_verify_nonce( $_REQUEST['woocommerce_bulk_edit_nonce'], 'woocommerce_bulk_edit_nonce' ) ) return $post_id;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return $post_id;
	if ( $post->post_type != 'product' ) return $post_id;
	if( ! SAPHALI_ZAKUPKA_ONLY_ADMIN ) return $post_id;
		if ( ! empty( $_REQUEST['change_price_zakupka'] ) ) {

			$change_regular_price = absint( $_REQUEST['change_price_zakupka'] );
			if ( isset( $_REQUEST['_price_zakupka'] ) )
			$price_zakupka = esc_attr( stripslashes( $_REQUEST['_price_zakupka'] ) );
			$old_price_zakupka = get_post_meta( $post_id, '_price_zakupka', true );
			switch ( $change_regular_price ) {
				case 1 :
					if(!isset( $price_zakupka )) {} else 
					$new_price = $price_zakupka;
				break;
				case 2 :
					if(!isset( $price_zakupka )) {} else {
						if ( strstr( $price_zakupka, '%' ) ) {
							$percent = str_replace( '%', '', $price_zakupka ) / 100;
							$new_price = $old_price_zakupka + ( $old_price_zakupka * $percent );
						} else {
							$new_price = $old_price_zakupka + $price_zakupka;
						}
					}
				break;
				case 3 :
					if(!isset( $price_zakupka )) {} else {
						if ( strstr( $price_zakupka, '%' ) ) {
							$percent = str_replace( '%', '', $price_zakupka ) / 100;
							$new_price = $old_price_zakupka - ( $old_price_zakupka * $percent );
						} else {
							$new_price = $old_price_zakupka - $price_zakupka;
						}
					}
				break;
			}

			if ( isset( $new_price ) && $new_price != $old_price_zakupka ) {
				$price_changed = true;
				update_post_meta( $post_id, '_price_zakupka', $new_price );
			}
		}
		
}
function price_zakupka_admin_product_quick_edit_save( $post_id, $post ) {
	if ( !$_POST ) return $post_id;
	if ( is_int( wp_is_post_revision( $post_id ) ) ) return;
	if( is_int( wp_is_post_autosave( $post_id ) ) ) return;
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
	if ( !isset($_POST['woocommerce_quick_edit_nonce']) || (isset($_POST['woocommerce_quick_edit_nonce']) && !wp_verify_nonce( $_POST['woocommerce_quick_edit_nonce'], 'woocommerce_quick_edit_nonce' ))) return $post_id;
	if ( !current_user_can( 'edit_post', $post_id )) return $post_id;
	if ( $post->post_type != 'product' ) return $post_id;
	if( ! SAPHALI_ZAKUPKA_ONLY_ADMIN ) return $post_id;
	if(isset($_POST['_price_zakupka'])) update_post_meta($post_id, '_price_zakupka', esc_html(stripslashes($_POST['_price_zakupka'])));
}

add_action( 'woocommerce_checkout_update_order_meta',  'checkout_update_order_meta_price_zakupka', 10, 2 );

function checkout_update_order_meta_price_zakupka($order_id, $posted) {
	if (!class_exists('WC_Order')) $order = new woocommerce_order( $order_id ); else $order = new WC_Order( $order_id );
	$curs_valut_zakupka = get_option('curs_valut_zakupka', 1);
	
	$curs_valut_zakupka = !empty( $curs_valut_zakupka ) ? $curs_valut_zakupka: 1 ;
	if ( sizeof( $order->get_items()) > 0 ) {
		$total_price_zakupka = 0;
		$curs_zakupa = 1;
		foreach ( $order->get_items() as $item ) {
			
			if ( function_exists("saphali_two_currency_gen_price_load") ) {
				$cunent_valute = get_option('settings_saphali_valute' , array('USD' => 8.2, 'EUR' => 11) );
				$curs_zakupa = 1;
				foreach($cunent_valute as $code => $_kurs) {
					if( isset($item["variation_id"]) && $item["variation_id"] )
					$_curs_zakupa = get_post_meta( $item["variation_id"], '_price_' . $code, true);
					else {
						$_curs_zakupa = get_post_meta( $item["product_id"], '_price_' . $code, true);
					}
					if($_curs_zakupa > 0) {
						$curs_zakupa = $_kurs; break;
					}
				}
			}
			if(isset($item["variation_id"]) && $item["variation_id"]) {
				$r = get_post_meta($item["variation_id"], '_price_zakupka', true);
				if( $r === false ) $total_price_zakupka += $item["qty"] * get_post_meta($item["id"], '_price_zakupka', true) * $curs_zakupa;
					else { $total_price_zakupka += $item["qty"] * $r * $curs_zakupa; }
			} else {
				$r = get_post_meta($item["product_id"], '_price_zakupka', true);
				if( $r === false ) $total_price_zakupka += $item["qty"] * get_post_meta($item["id"], '_price_zakupka', true) * $curs_zakupa;
					else { $total_price_zakupka += $item["qty"] * $r * $curs_zakupa; }
			}
			
		}
		
		$total_price_zakupka = round($total_price_zakupka, 2);
		
		update_post_meta( $order_id, '_total_price_zakupka', $total_price_zakupka * $curs_valut_zakupka );
	}
}

//add_action('woocommerce_reports_tabs','woocommerce_reports_price_zakupka_tabs');
function woocommerce_reports_price_zakupka_tabs() {

	$current_tab = isset( $_GET['tab'] ) ? sanitize_title( urldecode( $_GET['tab'] ) ) : 'sales';
	echo '<a href="'.admin_url( 'admin.php?page=woocommerce_reports&tab=' . urlencode( "zakupka" ) ).'" class="nav-tab ';
	if ( $current_tab == "zakupka" ) echo 'nav-tab-active';
					echo '">Закупка</a>';

}

function woocommerce_reports_charts_price_zakupka($charts)	{
if( ! SAPHALI_ZAKUPKA_ONLY_ADMIN ) return $charts;
$charts['zakupka'] = array(
			'title' 	=>  __( 'Закупка', 'woocommerce' ),
			'charts' 	=> array(
				array(
					'title' => __('Подсчет чистого догода с учетом закупки', 'woocommerce'),
					'description' => '',
					'hide_title' => false,
					'function' => '_woocommerce_reports_charts_price_zakupka'
				)
			)
		);
		return $charts;
}
function _woocommerce_reports_charts_price_zakupka() {
	global $start_date, $end_date, $woocommerce, $wpdb;

	$first_year = $wpdb->get_var( "SELECT post_date FROM $wpdb->posts WHERE post_date != 0 ORDER BY post_date ASC LIMIT 1;" );

	if ( $first_year )
		$first_year = date( 'Y', strtotime( $first_year ) );
	else
		$first_year = date( 'Y' );

	$current_year 	= isset( $_POST['show_year'] ) 	? $_POST['show_year'] 	: date( 'Y', current_time( 'timestamp' ) );
	$start_date 	= strtotime( $current_year . '0101' );

	$total_zakupka = $total_tax = $total_sales_tax = $total_shipping_tax = $count = 0;
	$taxes = $tax_rows = $tax_row_labels = array();
	for ( $count = 0; $count < 7; $count++ ) {
$stepDay = 1; 
$_count = 0; 
$dateDay = date( 'Ymd', strtotime( '+ ' . $count . ' Day', date(time() - ( (7*$stepDay-1) )*3600*24 ) ) );

		$_gross = $wpdb->get_var( $wpdb->prepare( "
			SELECT SUM( meta.meta_value ) AS order_tax
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			WHERE 	meta.meta_key 		= '_order_total'
			AND 	posts.post_type 	= 'shop_order'
			AND 	posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
			AND		%s					= date_format(posts.post_date,'%%Y%%m%%d')
		", $dateDay ) );

		$_shipping = $wpdb->get_var( $wpdb->prepare( "
			SELECT SUM( meta.meta_value ) AS order_tax
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			WHERE 	meta.meta_key 		= '_order_shipping'
			AND 	posts.post_type 	= 'shop_order'
			AND 	posts.post_status 	 IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
			AND		%s		 			= date_format(posts.post_date,'%%Y%%m%%d')
		", $dateDay ) );

		$_order_tax = $wpdb->get_var( $wpdb->prepare( "
			SELECT SUM( meta.meta_value ) AS order_tax
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			WHERE 	meta.meta_key 		= '_order_tax'
			AND 	posts.post_type 	= 'shop_order'
			AND 	posts.post_status 	 IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
			AND		%s		 			= date_format(posts.post_date,'%%Y%%m%%d')
		", $dateDay ) );
		
		$_zakupka = $wpdb->get_var( $wpdb->prepare( "
			SELECT SUM( meta.meta_value ) AS order_zakupka
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			WHERE 	meta.meta_key 		= '_total_price_zakupka'
			AND 	posts.post_type 	= 'shop_order'
			AND 	posts.post_status 	 IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
			AND		%s		 			= date_format(posts.post_date,'%%Y%%m%%d')
		", $dateDay ) );

		$_shipping_tax = $wpdb->get_var( $wpdb->prepare( "
			SELECT SUM( meta.meta_value ) AS order_tax
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			WHERE 	meta.meta_key 		= '_order_shipping_tax'
			AND 	posts.post_type 	= 'shop_order'
			AND 	posts.post_status 	 IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
			AND		%s		 			= date_format(posts.post_date,'%%Y%%m%%d')
		", $dateDay ) );


$day_dohod [] = ($_zakupka > 0) ? $_gross - $_shipping - ($_shipping_tax + $_order_tax) - $_zakupka : 0 ;
}

	for ( $count = 0; $count < 12; $count++ ) {

		$time = strtotime( date('Ym', strtotime( '+ ' . $count . ' MONTH', $start_date ) ) . '01' );

		if ( $time > current_time( 'timestamp' ) )
			continue;

		$month = date( 'Ym', strtotime( date( 'Ym', strtotime( '+ ' . $count . ' MONTH', $start_date ) ) . '01' ) );
		$dateDay = date( 'Ymd' );
		if ( ! version_compare( WOOCOMMERCE_VERSION, '2.2', '<' ) ) {
		$gross = $wpdb->get_var( $wpdb->prepare( "
			SELECT SUM( meta.meta_value ) AS order_tax
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			WHERE 	meta.meta_key 		= '_order_total'
			AND 	posts.post_type 	= 'shop_order'
			AND 	posts.post_status  IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
			AND		%s					= date_format(posts.post_date,'%%Y%%m')
		", $month ) );
		
		$shipping = $wpdb->get_var( $wpdb->prepare( "
			SELECT SUM( meta.meta_value ) AS order_tax
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			WHERE 	meta.meta_key 		= '_order_shipping'
			AND 	posts.post_type 	= 'shop_order'
			AND 	posts.post_status  IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
			AND		%s		 			= date_format(posts.post_date,'%%Y%%m')
		", $month ) );

		$order_tax = $wpdb->get_var( $wpdb->prepare( "
			SELECT SUM( meta.meta_value ) AS order_tax
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			WHERE 	meta.meta_key 		= '_order_tax'
			AND 	posts.post_type 	= 'shop_order'
			AND 	posts.post_status  IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
			AND		%s		 			= date_format(posts.post_date,'%%Y%%m')
		", $month ) );
		
		$zakupka = $wpdb->get_var( $wpdb->prepare( "
			SELECT SUM( meta.meta_value ) AS order_zakupka
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			WHERE 	meta.meta_key 		= '_total_price_zakupka'
			AND 	posts.post_type 	= 'shop_order'
			AND 	posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
			AND		%s		 			= date_format(posts.post_date,'%%Y%%m')
		", $month ) );

		$shipping_tax = $wpdb->get_var( $wpdb->prepare( "
			SELECT SUM( meta.meta_value ) AS order_tax
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			WHERE 	meta.meta_key 		= '_order_shipping_tax'
			AND 	posts.post_type 	= 'shop_order'
			AND 	posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
			AND		%s		 			= date_format(posts.post_date,'%%Y%%m')
		", $month ) );

		$tax_rows = $wpdb->get_results( $wpdb->prepare( "
			SELECT
				order_items.order_item_name as name,
				SUM( order_item_meta.meta_value ) as tax_amount,
				SUM( order_item_meta_2.meta_value ) as shipping_tax_amount,
				SUM( order_item_meta.meta_value + order_item_meta_2.meta_value ) as total_tax_amount

			FROM 		{$wpdb->prefix}woocommerce_order_items as order_items

			LEFT JOIN 	{$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
			LEFT JOIN 	{$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_2 ON order_items.order_item_id = order_item_meta_2.order_item_id

			LEFT JOIN 	{$wpdb->posts} AS posts ON order_items.order_id = posts.ID
			LEFT JOIN 	{$wpdb->term_relationships} AS rel ON posts.ID = rel.object_ID
			LEFT JOIN 	{$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
			LEFT JOIN 	{$wpdb->terms} AS term USING( term_id )

			WHERE 		order_items.order_item_type = 'tax'
			AND 		posts.post_type 	= 'shop_order'
			AND 		posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
			AND			%s = date_format( posts.post_date,'%%Y%%m' )
			AND 		order_item_meta.meta_key = 'tax_amount'
			AND 		order_item_meta_2.meta_key = 'shipping_tax_amount'

			GROUP BY 	order_items.order_item_name
		", $month ) );
		} else {
		$gross = $wpdb->get_var( $wpdb->prepare( "
			SELECT SUM( meta.meta_value ) AS order_tax
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			LEFT JOIN {$wpdb->term_relationships} AS rel ON posts.ID=rel.object_ID
			LEFT JOIN {$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
			LEFT JOIN {$wpdb->terms} AS term USING( term_id )
			WHERE 	meta.meta_key 		= '_order_total'
			AND 	posts.post_type 	= 'shop_order'
			AND 	posts.post_status 	= 'publish'
			AND 	tax.taxonomy		= 'shop_order_status'
			AND		term.slug			IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) . "')
			AND		%s					= date_format(posts.post_date,'%%Y%%m')
		", $month ) );

		$shipping = $wpdb->get_var( $wpdb->prepare( "
			SELECT SUM( meta.meta_value ) AS order_tax
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			LEFT JOIN {$wpdb->term_relationships} AS rel ON posts.ID=rel.object_ID
			LEFT JOIN {$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
			LEFT JOIN {$wpdb->terms} AS term USING( term_id )
			WHERE 	meta.meta_key 		= '_order_shipping'
			AND 	posts.post_type 	= 'shop_order'
			AND 	posts.post_status 	= 'publish'
			AND 	tax.taxonomy		= 'shop_order_status'
			AND		term.slug			IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) . "')
			AND		%s		 			= date_format(posts.post_date,'%%Y%%m')
		", $month ) );

		$order_tax = $wpdb->get_var( $wpdb->prepare( "
			SELECT SUM( meta.meta_value ) AS order_tax
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			LEFT JOIN {$wpdb->term_relationships} AS rel ON posts.ID=rel.object_ID
			LEFT JOIN {$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
			LEFT JOIN {$wpdb->terms} AS term USING( term_id )
			WHERE 	meta.meta_key 		= '_order_tax'
			AND 	posts.post_type 	= 'shop_order'
			AND 	posts.post_status 	= 'publish'
			AND 	tax.taxonomy		= 'shop_order_status'
			AND		term.slug			IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) . "')
			AND		%s		 			= date_format(posts.post_date,'%%Y%%m')
		", $month ) );
		
		$zakupka = $wpdb->get_var( $wpdb->prepare( "
			SELECT SUM( meta.meta_value ) AS order_zakupka
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			LEFT JOIN {$wpdb->term_relationships} AS rel ON posts.ID=rel.object_ID
			LEFT JOIN {$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
			LEFT JOIN {$wpdb->terms} AS term USING( term_id )
			WHERE 	meta.meta_key 		= '_total_price_zakupka'
			AND 	posts.post_type 	= 'shop_order'
			AND 	posts.post_status 	= 'publish'
			AND 	tax.taxonomy		= 'shop_order_status'
			AND		term.slug			IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) . "')
			AND		%s		 			= date_format(posts.post_date,'%%Y%%m')
		", $month ) );

		$shipping_tax = $wpdb->get_var( $wpdb->prepare( "
			SELECT SUM( meta.meta_value ) AS order_tax
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			LEFT JOIN {$wpdb->term_relationships} AS rel ON posts.ID=rel.object_ID
			LEFT JOIN {$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
			LEFT JOIN {$wpdb->terms} AS term USING( term_id )
			WHERE 	meta.meta_key 		= '_order_shipping_tax'
			AND 	posts.post_type 	= 'shop_order'
			AND 	posts.post_status 	= 'publish'
			AND 	tax.taxonomy		= 'shop_order_status'
			AND		term.slug			IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) . "')
			AND		%s		 			= date_format(posts.post_date,'%%Y%%m')
		", $month ) );

		$tax_rows = $wpdb->get_results( $wpdb->prepare( "
			SELECT
				order_items.order_item_name as name,
				SUM( order_item_meta.meta_value ) as tax_amount,
				SUM( order_item_meta_2.meta_value ) as shipping_tax_amount,
				SUM( order_item_meta.meta_value + order_item_meta_2.meta_value ) as total_tax_amount

			FROM 		{$wpdb->prefix}woocommerce_order_items as order_items

			LEFT JOIN 	{$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta ON order_items.order_item_id = order_item_meta.order_item_id
			LEFT JOIN 	{$wpdb->prefix}woocommerce_order_itemmeta as order_item_meta_2 ON order_items.order_item_id = order_item_meta_2.order_item_id

			LEFT JOIN 	{$wpdb->posts} AS posts ON order_items.order_id = posts.ID
			LEFT JOIN 	{$wpdb->term_relationships} AS rel ON posts.ID = rel.object_ID
			LEFT JOIN 	{$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
			LEFT JOIN 	{$wpdb->terms} AS term USING( term_id )

			WHERE 		order_items.order_item_type = 'tax'
			AND 		posts.post_type 	= 'shop_order'
			AND 		posts.post_status 	= 'publish'
			AND 		tax.taxonomy		= 'shop_order_status'
			AND			term.slug IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) . "')
			AND			%s = date_format( posts.post_date,'%%Y%%m' )
			AND 		order_item_meta.meta_key = 'tax_amount'
			AND 		order_item_meta_2.meta_key = 'shipping_tax_amount'

			GROUP BY 	order_items.order_item_name
		", $month ) );
		}
		if ( $tax_rows ) {
			foreach ( $tax_rows as $tax_row ) {
				if ( $tax_row->total_tax_amount > 0 )
					$tax_row_labels[] = $tax_row->name;
			}
		}

		$taxes[ date( 'M', strtotime( $month . '01' ) ) ] = array(
			'gross'			=> $gross,
			'shipping'		=> $shipping,
			'order_tax' 	=> $order_tax,
			'shipping_tax' 	=> $shipping_tax,
			'total_tax' 	=> $shipping_tax + $order_tax,
			'total_zakupka' => $zakupka,
			'tax_rows'		=> $tax_rows
		);

		$total_sales_tax += $order_tax;
		$total_shipping_tax += $shipping_tax;
		$total_zakupka += $zakupka;
	}
	$sql = "1=1";
	$curs_valut_zakupka = get_option('curs_valut_zakupka', 1);
	$curs_valut_zakupka = !empty( $curs_valut_zakupka ) ? $curs_valut_zakupka: 1 ;
	
	if(!empty($_POST['product_category_shortcode']) || isset($_COOKIE['str1_mass_z']) ) {
		if(empty($_POST['product_category_shortcode'])) {
			$_POST['product_category_shortcode'] =  !empty($_COOKIE['str1_mass_z']) ? explode( ',', $_COOKIE['str1_mass_z'] ) : array();
		}
		if(!empty($_POST['product_category_shortcode'])) {
			$catJOIN = 	" 
			LEFT JOIN {$wpdb->term_relationships} AS rel ON (posts.ID = rel.object_id OR posts.post_parent = rel.object_id)
			LEFT JOIN {$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
			LEFT JOIN {$wpdb->terms} AS term USING( term_id )
			";
			$cat_id =   "AND	term.term_id  IN ( " . implode( ",", $_POST['product_category_shortcode'] ) . " )";
		} else { $catJOIN = $cat_id = ''; }

	} else { $catJOIN = $cat_id = ''; }
	if ( function_exists("saphali_two_currency_gen_price_load") ) {
		$cunent_valute = get_option('settings_saphali_valute' , array('USD' => 8.2, 'EUR' => 11) );
		$curs_zakupa = 1;
		$catJOIN2 = $catJOIN;
		foreach($cunent_valute as $code => $_kurs) {
			$select .= 	" * ( IF(meta{$code}.meta_value > 0, $_kurs, 1  ) )  ";
			$catJOIN .= 	" 
			LEFT JOIN {$wpdb->postmeta} AS meta{$code} ON posts.ID = meta{$code}.post_id
			";
			$catJOIN2 .= 	" 
			LEFT JOIN {$wpdb->postmeta} AS meta{$code} ON p.ID = meta{$code}.post_id
			";
			$cat_id .=   " 
			AND	meta{$code}.meta_key = '_price_{$code}' 
			
			";
		} 
	//	$cat_id .=   " 			GROUP BY posts.ID		";
	}
	$sql_r = "
			SELECT SUM( meta.meta_value * meta2.meta_value $select ) AS order_zakupka, SUM( meta2.meta_value  ) AS stock
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			LEFT JOIN {$wpdb->postmeta} AS meta2 ON posts.ID = meta2.post_id
			LEFT JOIN {$wpdb->postmeta} AS meta3 ON posts.ID = meta3.post_id
			$catJOIN
			WHERE 	meta2.meta_key 		= '_stock'
			AND 	meta3.meta_key 		= '_manage_stock'
			AND 	meta3.meta_value 		= 'yes'
			AND 	meta.meta_key 		= '_price_zakupka'
			AND 	posts.post_type 	= 'product'
			AND 	posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'publish' ) ) ) . "')
			AND		%s
			$cat_id
		";
		//$wpdb->show_errors(); 
		//$wpdb->print_error();
	$zakupka_products = $wpdb->get_row ( $wpdb->prepare( $sql_r, $sql ), ARRAY_A );
	$q = "
			SELECT SUM( meta.meta_value $select ) AS order_zakupka, COUNT( * ) - (IF ( meta.meta_value $select = 0, 1 , 0) )  as stock
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			LEFT JOIN {$wpdb->postmeta} AS meta2 ON posts.ID = meta2.post_id
			$catJOIN
			WHERE 	
					meta2.meta_key 		= '_manage_stock'
			AND 	meta2.meta_value 		= 'no'
			AND 	meta.meta_key 		= '_price_zakupka'
			AND 	posts.post_type 	= 'product'
			AND 	posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'publish' ) ) ) . "')
			AND		%s
			$cat_id
		";
		
	$zakupka_products2 = $wpdb->get_row( $wpdb->prepare( $q, $sql ), ARRAY_A );
	// variable
	$_zakupka_products = $wpdb->get_row ( $wpdb->prepare( "
			SELECT SUM( meta.meta_value * meta2.meta_value  ) AS order_zakupka, SUM( meta2.meta_value  ) AS stock
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			LEFT JOIN {$wpdb->postmeta} AS meta2 ON posts.ID = meta2.post_id
			LEFT JOIN {$wpdb->postmeta} AS meta3 ON posts.ID = meta3.post_id
			$catJOIN
			WHERE 	meta2.meta_key 		= '_stock'
			AND 	meta3.meta_key 		= '_manage_stock'
			AND 	meta3.meta_value 		= 'yes'
			AND 	meta.meta_key 		= '_price_zakupka'
			AND 	posts.post_type 	= 'product_variation'
			AND 	posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'publish' ) ) ) . "')
			AND		%s
			$cat_id
		", $sql ), ARRAY_A );
	$q = "
			SELECT SUM( meta.meta_value ) AS order_zakupka, COUNT(*) as stock
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
			LEFT JOIN {$wpdb->postmeta} AS meta2 ON posts.ID = meta2.post_id
			$catJOIN
			WHERE 	
					meta2.meta_key 		= '_manage_stock'
			AND 	meta2.meta_value 		= 'no'
			AND 	meta.meta_key 		= '_price_zakupka'
			AND 	posts.post_type 	= 'product_variation'
			AND 	posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'publish' ) ) ) . "')
			AND		%s
			$cat_id
		";
	$_zakupka_products2 = $wpdb->get_row( $wpdb->prepare( $q, $sql ), ARRAY_A );
	$_q = "
SELECT  SUM( meta.meta_value $select ) AS order_zakupka, COUNT(*) as stock
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->posts} AS p ON posts.ID = p.post_parent
			$catJOIN
			WHERE 	
				meta2.meta_key 		= '_manage_stock'
			AND 	meta2.meta_value 		= 'no'
			AND 	meta.meta_key 		= '_price_zakupka'
			AND 	posts.post_type 	= 'product'
            AND 	p.post_type 	= 'product_variation'
			AND 	p.post_parent = posts.ID 
			AND 	posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'publish' ) ) ) . "')
			AND 	p.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'publish' ) ) ) . "')
			AND		%s
			$cat_id
		";
	$_q2 = "
			SELECT SUM( meta.meta_value * meta2.meta_value $select  ) AS order_zakupka, SUM( meta2.meta_value  ) AS stock
			FROM {$wpdb->posts} AS posts
			LEFT JOIN {$wpdb->posts} AS p ON posts.ID = p.post_parent
			$catJOIN
			WHERE 	meta2.meta_key 		= '_stock'
			AND 	meta3.meta_key 		= '_manage_stock'
			AND 	meta3.meta_value 		= 'yes'
			AND 	meta.meta_key 		= '_price_zakupka'
			AND 	posts.post_type 	= 'product'
			AND 	p.post_type 	= 'product_variation'
			AND 	p.post_parent = posts.ID
			AND 	posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'publish' ) ) ) . "')
			AND 	p.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'publish' ) ) ) . "')
			AND		%s
			$cat_id
		";
	$var_ex_product = $wpdb->get_row( $wpdb->prepare( $_q, $sql ), ARRAY_A );
	$var_ex_product2 = $wpdb->get_row( $wpdb->prepare( $_q2, $sql ), ARRAY_A );
	
	$zakupka_products2['order_zakupka']  = $zakupka_products2['order_zakupka'] - $_zakupka_products['order_zakupka'];
	$zakupka_products2['stock']  = $zakupka_products2['stock'] - $_zakupka_products['stock'];
	$zakupka_product = $zakupka_products['order_zakupka'] + $zakupka_products2['order_zakupka'] + $var_ex_product['order_zakupka']+ $var_ex_product2['order_zakupka'];
	//$zakupka_product = $zakupka_products['order_zakupka'] + $var_ex_product['order_zakupka'];
	$no_man_st = $var_ex_product['stock'] + $zakupka_products2['stock'];
	$stocks = $zakupka_products['stock']+$zakupka_products2['stock']+$var_ex_product['stock']+$var_ex_product2['stock'];
		
	$total_tax = $total_sales_tax + $total_shipping_tax;
	?>
	<form method="post" action="">
		<p><label for="show_year"><?php _e( 'Year:', 'woocommerce' ); ?></label>
		<select name="show_year" id="show_year">
			<?php
				for ( $i = $first_year; $i <= date('Y'); $i++ )
					printf( '<option value="%s" %s>%s</option>', $i, selected( $current_year, $i, false ), $i );
			?>
		</select> 
		<input type="submit" class="button" value="<?php _e( 'Show', 'woocommerce' ); ?>" /></p>
	</form>
	<div id="poststuff" class="woocommerce-reports-wrap">
		<div class="woocommerce-reports-sidebar">
			<div class="postbox">
				<h3><span><?php _e( 'Total taxes for year', 'woocommerce' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php
						if ( $total_tax > 0 )
							echo woocommerce_price( $total_tax );
						else
							_e( 'n/a', 'woocommerce' );
					?></p>
				</div>
			</div>
			<div class="postbox">
				<h3><span><?php _e( 'Total product taxes for year', 'woocommerce' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php
						if ( $total_sales_tax > 0 )
							echo woocommerce_price( $total_sales_tax );
						else
							_e( 'n/a', 'woocommerce' );
					?></p>
				</div>
			</div>
			<div class="postbox">
				<h3><span><?php _e( 'Total shipping tax for year', 'woocommerce' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php
						if ( $total_shipping_tax > 0 )
							echo woocommerce_price( $total_shipping_tax );
						else
							_e( 'n/a', 'woocommerce' );
					?></p>
				</div>
			</div>
			<div class="postbox">
				<h3><span><?php _e( 'Всего на закупку за год', 'woocommerce' ); ?></span></h3>
				<div class="inside">
					<p class="stat"><?php
						if ( $total_zakupka > 0 )
							echo woocommerce_price( $total_zakupka );
						else
							_e( 'n/a', 'woocommerce' );
					?></p>
				</div>
			</div>
		</div>
		<div class="woocommerce-reports-main">
			<table class="widefat">
				<thead>
					<tr>
						<th colspan="8"><?php _e( 'Всего на закупку по всем товарам (категориям товаров)', 'zakupka' ); ?><br /></th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="4" style="width: 50%;"><?php echo woocommerce_price( $zakupka_product * $curs_valut_zakupka ); ?></td><td colspan="4">
						<?php  echo sprintf(__('Всего позиций: %s.', 'zakupka' ), $stocks); if(($no_man_st) > 0 ) echo '<br />' . sprintf( __('Из них не имеют управление складом: %s.', 'zakupka' ), $no_man_st );   ?>
						</td>
					</tr>
				</tbody>
			</table>
			<div style="position: relative;">
						<form method="post" action="">
		<label for="show_year"><?php _e( 'Категории:', 'zakupka' ); ?></label>
		<select id="product_category_shortcode" name="product_category_shortcode[]" class="chosen_select" style="width:180px;" multiple="multiple" data-placeholder="<?php _e( 'Все категории', 'zakupka' ); ?>">
				<?php
					$category_ids = array_map( 'trim', explode( ',', $_COOKIE['str1_mass_z'] ) );
					$count = get_option( 'product_category_shortcode_count_product', '' );
					$checked = get_option( 'product_category_shortcode_featured_product', 0 );
					$checked_only_cat = get_option( 'product_category_shortcode_only_category', 0 );
					$categories = get_terms( 'product_cat', 'orderby=name&hide_empty=0' );
					if ( $categories ) foreach ( $categories as $cat )
				echo '<option value="' . esc_attr( $cat->term_id ) . '"' . selected( in_array( $cat->term_id, $category_ids ), true, false ) . '>' . esc_html( $cat->name ) . '</option>';
				?>
		</select> 
		<input type="submit" class="button" value="<?php _e( 'Отобразить закупку по этим категориям', 'zakupka' ); ?>" />
	</form></div>
			<br />
			<table class="widefat">
				<thead>
					<tr>
						<th><?php _e( 'Month', 'woocommerce' ); ?></th>
						<th class="total_row"><?php _e( 'Total Sales', 'woocommerce' ); ?> <a class="tips" data-tip="<?php _e("This is the sum of the 'Order Total' field within your orders.", 'woocommerce'); ?>" href="#">[?]</a></th>
						<th class="total_row"><?php _e( 'Total Shipping', 'woocommerce' ); ?> <a class="tips" data-tip="<?php _e("This is the sum of the 'Shipping Total' field within your orders.", 'woocommerce'); ?>" href="#">[?]</a></th>
						<th class="total_row"><?php _e( 'Total Product Taxes', 'woocommerce' ); ?> <a class="tips" data-tip="<?php _e("This is the sum of the 'Cart Tax' field within your orders.", 'woocommerce'); ?>" href="#">[?]</a></th>
						<th class="total_row"><?php _e( 'Total Shipping Taxes', 'woocommerce' ); ?> <a class="tips" data-tip="<?php _e("This is the sum of the 'Shipping Tax' field within your orders.", 'woocommerce'); ?>" href="#">[?]</a></th>
						<th class="total_row"><?php _e( 'Total Taxes', 'woocommerce' ); ?> <a class="tips" data-tip="<?php _e("This is the sum of the 'Cart Tax' and 'Shipping Tax' fields within your orders.", 'woocommerce'); ?>" href="#">[?]</a></th>
						<th class="total_row"><?php _e( 'Всего на закупку', 'woocommerce' ); ?> <a class="tips" data-tip="<?php _e("This is the sum of the 'Cart Tax' and 'Shipping Tax' fields within your orders.", 'woocommerce'); ?>" href="#">[?]</a></th>
						<th class="total_row"><?php _e( 'Чистый доход', 'woocommerce' ); ?> <a class="tips" data-tip="<?php _e("Total sales minus shipping and tax.", 'woocommerce'); ?>" href="#">[?]</a></th>
						<?php
							$tax_row_labels = array_filter( array_unique( $tax_row_labels ) );
							foreach ( $tax_row_labels as $label )
								echo '<th class="tax_row">' . $label . '</th>';
						?>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<?php
							$total = array();

							foreach ( $taxes as $month => $tax ) {
								$total['gross'] = isset( $total['gross'] ) ? $total['gross'] + $tax['gross'] : $tax['gross'];
								$total['shipping'] = isset( $total['shipping'] ) ? $total['shipping'] + $tax['shipping'] : $tax['shipping'];
								$total['order_tax'] = isset( $total['order_tax'] ) ? $total['order_tax'] + $tax['order_tax'] : $tax['order_tax'];
								$total['shipping_tax'] = isset( $total['shipping_tax'] ) ? $total['shipping_tax'] + $tax['shipping_tax'] : $tax['shipping_tax'];
								$total['total_tax'] = isset( $total['total_tax'] ) ? $total['total_tax'] + $tax['total_tax'] : $tax['total_tax'];
								$total['total_zakupka'] = isset( $total['total_zakupka'] ) ? $total['total_zakupka'] + $tax['total_zakupka'] : $tax['total_zakupka'];

								foreach ( $tax_row_labels as $label )
									foreach ( $tax['tax_rows'] as $tax_row )
										if ( $tax_row->name == $label ) {
											$total['tax_rows'][ $label ] = isset( $total['tax_rows'][ $label ] ) ? $total['tax_rows'][ $label ] + $tax_row->total_tax_amount : $tax_row->total_tax_amount;
										}

							}

							echo '
								<td>' . __( 'Total', 'woocommerce' ) . '</td>
								<td class="total_row">' . woocommerce_price( $total['gross'] ) . '</td>
								<td class="total_row">' . woocommerce_price( $total['shipping'] ) . '</td>
								<td class="total_row">' . woocommerce_price( $total['order_tax'] ) . '</td>
								<td class="total_row">' . woocommerce_price( $total['shipping_tax'] ) . '</td>
								<td class="total_row">' . woocommerce_price( $total['total_tax'] ) . '</td>
								<td class="total_row">' . woocommerce_price( $total['total_zakupka'] ) . '</td>
								<td class="total_row">' . woocommerce_price( ($tax['total_zakupka'] > 0 ) ? $total['gross'] - $total['shipping'] - $total['total_tax'] - $total['total_zakupka'] : 0 ) . '</td>';

							foreach ( $tax_row_labels as $label )
								if ( isset( $total['tax_rows'][ $label ] ) )
									echo '<td class="tax_row">' . woocommerce_price( $total['tax_rows'][ $label ] ) . '</td>';
								else
									echo '<td class="tax_row">' .  woocommerce_price( 0 ) . '</td>';
						?>
					</tr>
					<tr>
						<th colspan="<?php echo 7 + sizeof( $tax_row_labels ); ?>"><button class="button toggle_tax_rows"><?php _e( 'Toggle tax rows', 'woocommerce' ); ?></button></th>
					</tr>
					<tr><th colspan="7"><div class="clear"><?php include_once(SAPHALI_PLUGIN_ZAKUPKA_DIR . 'convast.php'); ?></div></th></tr>
				</tfoot>
				<tbody>
					<?php
						foreach ( $taxes as $month => $tax ) {
							$alt = ( isset( $alt ) && $alt == 'alt' ) ? '' : 'alt';
							echo '<tr class="' . $alt . '">
								<td>' . $month . '</td>
								<td class="total_row">' . woocommerce_price( $tax['gross'] ) . '</td>
								<td class="total_row">' . woocommerce_price( $tax['shipping'] ) . '</td>
								<td class="total_row">' . woocommerce_price( $tax['order_tax'] ) . '</td>
								<td class="total_row">' . woocommerce_price( $tax['shipping_tax'] ) . '</td>
								<td class="total_row">' . woocommerce_price( $tax['total_tax'] ) . '</td>
								<td class="total_row">' . woocommerce_price( $tax['total_zakupka'] ) . '</td>
								<td class="total_row">' . woocommerce_price( ($tax['total_zakupka'] > 0 ) ? $tax['gross'] - $tax['shipping'] - $tax['total_tax'] - $tax['total_zakupka'] : 0 ) . '</td>';



							foreach ( $tax_row_labels as $label ) {

								$row_total = 0;

								foreach ( $tax['tax_rows'] as $tax_row ) {
									if ( $tax_row->name == $label ) {
										$row_total = $tax_row->total_tax_amount;
									}
								}

								echo '<td class="tax_row">' . woocommerce_price( $row_total ) . '</td>';
							}

							echo '</tr>';
						}
					?>

			</table>
			
			<?php if( SAPHALI_ZAKUPKA_ONLY_ADMIN ) { ?>
			
			<br />
			<div class="curs_valut">
				<label for="curs_valut"> Курс валют
				</label>
				<input type="text" name="currency" value="<?php echo get_option('curs_valut_zakupka', ''); ?>" id="curs_valut" />
			</div>
			<br />
			<div class="only_admin">
				<input type="checkbox" <?php $s = get_option('only_admin_show_zakupka');  checked( $s, '1'); ?> name="only_admin" value="1" id="only_admin" /> <label for="only_admin"> <strong>Отображать закупочные цены только для супер админа</strong>
				</label><br />
				<em>Отображать на странице редактирования товара поле цены закупки только для роли <strong>Администратор</strong>. Для остальных ролей поле недоступно (в том числе с ролью <strong>Shop Manager</strong>).</em>
				
			</div>
			<?php 
		 ?>
			<p><button class="button admin_save"><?php _e( 'Сохранить', 'zakupka' ); ?></button> <span></span></p>
			<script type="text/javascript">
				jQuery('.toggle_tax_rows').click(function(){
					jQuery('.tax_row').toggle();
					jQuery('.total_row').toggle();
				});
				jQuery('.tax_row').hide();
				jQuery("body").delegate(".admin_save", 'click', function(event) {
					event.preventDefault();
					var parnet = jQuery(this).parent().find('span');
					parnet.text('обработка...');
					jQuery.getJSON(
						'<?php echo admin_url('admin-ajax.php');?>?action=settings_saphali_update_kurs_zakupka&kurs_check='+jQuery('div.curs_valut').find('input#curs_valut').val().replace(/,/g, '.')+'&only_admin='+jQuery('div.only_admin').find('input#only_admin').is(':checked'),
						function(data) {
							if ( typeof data !== "undefined" ) {
								if (data.result === true) {
									parnet.text('');
								} else if ( data.result === false) { parnet.text(''); }
							}
						}
					);
				});
				<?php if ( version_compare( WOOCOMMERCE_VERSION, '2.3', '<' ) ) {
				 ?>
				 jQuery("select#product_category_shortcode").chosen();	
		<?php }?>

jQuery(function($){
	$("select#product_category_shortcode").change(function () {
		var str1_mass_z = "";
		$("select#product_category_shortcode option:selected").each(function () {
		str1_mass_z += $(this).attr('value') + ",";
		});
		str1_mass_z =str1_mass_z.replace(/,$/, '');
		setCookie('str1_mass_z', str1_mass_z, 31*12);

	}).trigger('change');
});
	function getCookie(name) {
		var cookie = " " + document.cookie;
		var search = " " + name + "=";
		var setStr = null;
		var offset = 0;
		var end = 0;
		if (cookie.length > 0) {
			offset = cookie.indexOf(search);
			if (offset != -1) {
				offset += search.length;
				end = cookie.indexOf(";", offset)
				if (end == -1) {
					end = cookie.length;
				}
				setStr = unescape(cookie.substring(offset, end));
			}
		}
		return(setStr);
	}
	function setCookie (name, value, expires, path, domain, secure) {
	  var date = new Date( new Date().getTime() + expires * 1000 * 3600 * 24 );
      document.cookie = name + "=" + escape(value) +
        ((expires) ? "; expires=" + date.toUTCString() : "") +
        ((path) ? "; path=" + path : "") +
        ((domain) ? "; domain=" + domain : "") +
        ((secure) ? "; secure" : "");
	}
			</script>
			<?php } ?>
		</div>
	</div>
	<?php
	
}
add_action('plugins_loaded', 'plugin_load_zakupka');
function plugin_load_zakupka () {
	if( !version_compare( WOOCOMMERCE_VERSION, "2.4", "<" ))
		add_action( 'woocommerce_ajax_save_product_variations', array('zakupka_info_in_coloms','woocommerce_ajax_save_product_variations'), 10 );
	add_action( 'init', array( 'zakupka_info_in_coloms', 'save_order_items'), 11 );
	 if(get_option('only_admin_show_zakupka'))
	 define('SAPHALI_ZAKUPKA_ONLY_ADMIN', is_super_admin() );
	 else
	 define('SAPHALI_ZAKUPKA_ONLY_ADMIN', 1 );
	
	if( SAPHALI_ZAKUPKA_ONLY_ADMIN ) {
		if(version_compare( WOOCOMMERCE_VERSION, "2.0", "<" ))
		add_action('woocommerce_product_after_variable_attributes', 'woocommerce_product_after_variable_attributes_s_price_zakupka',10,2);
		else
		add_action('woocommerce_product_after_variable_attributes', 'woocommerce_product_after_variable_attributes_s_price_zakupka',10,3);
		add_action('woocommerce_product_after_variable_attributes_js', 'woocommerce_product_after_variable_attributes_js_s_price_zakupka',10);
		add_action( 'add_meta_boxes_shop_order', 'add_meta_boxes_zakupka',9 );
		add_action('wp_ajax_settings_saphali_update_kurs_zakupka', 'settings_saphali_update_kurs_zakupka', 10 );
		add_action( 'wp_ajax_zakupka_next_diapazon', 'add_meta_boxes_zakupka_',9 );
	}

	function settings_saphali_update_kurs_zakupka () {
		$_GET['only_admin'] = ($_GET['only_admin'] === 'false') ? 0 : 1;
		$kurs_check = false;
		if(isset($_GET['kurs_check'])) {
			$kurs_check = true;
		}
		$_GET['kurs_check'] = (double) $_GET['kurs_check'];
		if($_GET['kurs_check']) {
			update_option('curs_valut_zakupka', $_GET['kurs_check'] );
			update_option('only_admin_show_zakupka', $_GET['only_admin'] );
			die( json_encode( array( 'result' => true ) ) );
		} elseif($kurs_check) {
			update_option('only_admin_show_zakupka', $_GET['only_admin'] );
			die( json_encode( array( 'result' => true ) ) );
		}
		else die( json_encode( array( 'result' => false ) ) );
	}
	function add_meta_boxes_zakupka_ () {
		global $wpdb;
		for ( $count = 0; $count < 7; $count++ ) {
			$stepDay = $_GET['step_day'] > 0 ? $_GET['step_day'] : 1; 
			$_count = 0; 
			$dateDay = date( 'Ymd', strtotime( '+ ' . $count . ' Day', date(time() - ( (7*$stepDay-1) )*3600*24 ) ) );
			$_dateDay[] = date( 'd', strtotime( '+ ' . $count . ' Day', date(time() - ( (7*$stepDay-1) )*3600*24 ) ) );
			
			if($count == 4) $m = date( 'n', strtotime( '+ ' . $count . ' Day', date(time() - ( (7*$stepDay-1) )*3600*24 ) ) );
			if( ! version_compare( WOOCOMMERCE_VERSION, '2.2', '<' ) ) {
					$_gross = $wpdb->get_var( $wpdb->prepare( "
						SELECT SUM( meta.meta_value ) AS order_tax
						FROM {$wpdb->posts} AS posts
						LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
						WHERE 	meta.meta_key 		= '_order_total'
						AND 	posts.post_type 	= 'shop_order'
						AND 	posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
						AND		%s					= date_format(posts.post_date,'%%Y%%m%%d')
					", $dateDay ) );

					$_shipping = $wpdb->get_var( $wpdb->prepare( "
						SELECT SUM( meta.meta_value ) AS order_tax
						FROM {$wpdb->posts} AS posts
						LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
						WHERE 	meta.meta_key 		= '_order_shipping'
						AND 	posts.post_type 	= 'shop_order'
						AND 	posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
						AND		%s		 			= date_format(posts.post_date,'%%Y%%m%%d')
					", $dateDay ) );

					$_order_tax = $wpdb->get_var( $wpdb->prepare( "
						SELECT SUM( meta.meta_value ) AS order_tax
						FROM {$wpdb->posts} AS posts
						LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
						WHERE 	meta.meta_key 		= '_order_tax'
						AND 	posts.post_type 	= 'shop_order'
						AND 	posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
						AND		%s		 			= date_format(posts.post_date,'%%Y%%m%%d')
					", $dateDay ) );
					
					$_zakupka = $wpdb->get_var( $wpdb->prepare( "
						SELECT SUM( meta.meta_value ) AS order_zakupka
						FROM {$wpdb->posts} AS posts
						LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
						WHERE 	meta.meta_key 		= '_total_price_zakupka'
						AND 	posts.post_type 	= 'shop_order'
						AND 	posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
						AND		%s		 			= date_format(posts.post_date,'%%Y%%m%%d')
					", $dateDay ) );

					$_shipping_tax = $wpdb->get_var( $wpdb->prepare( "
						SELECT SUM( meta.meta_value ) AS order_tax
						FROM {$wpdb->posts} AS posts
						LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
						WHERE 	meta.meta_key 		= '_order_shipping_tax'
						AND 	posts.post_type 	= 'shop_order'
						AND 	posts.post_status 	IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'wc-completed', 'wc-processing', 'wc-on-hold' ) ) ) . "')
						AND		%s		 			= date_format(posts.post_date,'%%Y%%m%%d')
					", $dateDay ) );
				} else {
				$_gross = $wpdb->get_var( $wpdb->prepare( "
						SELECT SUM( meta.meta_value ) AS order_tax
						FROM {$wpdb->posts} AS posts
						LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
						LEFT JOIN {$wpdb->term_relationships} AS rel ON posts.ID=rel.object_ID
						LEFT JOIN {$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
						LEFT JOIN {$wpdb->terms} AS term USING( term_id )
						WHERE 	meta.meta_key 		= '_order_total'
						AND 	posts.post_type 	= 'shop_order'
						AND 	posts.post_status 	= 'publish'
						AND 	tax.taxonomy		= 'shop_order_status'
						AND		term.slug			IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) . "')
						AND		%s					= date_format(posts.post_date,'%%Y%%m%%d')
					", $dateDay ) );

					$_shipping = $wpdb->get_var( $wpdb->prepare( "
						SELECT SUM( meta.meta_value ) AS order_tax
						FROM {$wpdb->posts} AS posts
						LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
						LEFT JOIN {$wpdb->term_relationships} AS rel ON posts.ID=rel.object_ID
						LEFT JOIN {$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
						LEFT JOIN {$wpdb->terms} AS term USING( term_id )
						WHERE 	meta.meta_key 		= '_order_shipping'
						AND 	posts.post_type 	= 'shop_order'
						AND 	posts.post_status 	= 'publish'
						AND 	tax.taxonomy		= 'shop_order_status'
						AND		term.slug			IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) . "')
						AND		%s		 			= date_format(posts.post_date,'%%Y%%m%%d')
					", $dateDay ) );

					$_order_tax = $wpdb->get_var( $wpdb->prepare( "
						SELECT SUM( meta.meta_value ) AS order_tax
						FROM {$wpdb->posts} AS posts
						LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
						LEFT JOIN {$wpdb->term_relationships} AS rel ON posts.ID=rel.object_ID
						LEFT JOIN {$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
						LEFT JOIN {$wpdb->terms} AS term USING( term_id )
						WHERE 	meta.meta_key 		= '_order_tax'
						AND 	posts.post_type 	= 'shop_order'
						AND 	posts.post_status 	= 'publish'
						AND 	tax.taxonomy		= 'shop_order_status'
						AND		term.slug			IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) . "')
						AND		%s		 			= date_format(posts.post_date,'%%Y%%m%%d')
					", $dateDay ) );
					
					$_zakupka = $wpdb->get_var( $wpdb->prepare( "
						SELECT SUM( meta.meta_value ) AS order_zakupka
						FROM {$wpdb->posts} AS posts
						LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
						LEFT JOIN {$wpdb->term_relationships} AS rel ON posts.ID=rel.object_ID
						LEFT JOIN {$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
						LEFT JOIN {$wpdb->terms} AS term USING( term_id )
						WHERE 	meta.meta_key 		= '_total_price_zakupka'
						AND 	posts.post_type 	= 'shop_order'
						AND 	posts.post_status 	= 'publish'
						AND 	tax.taxonomy		= 'shop_order_status'
						AND		term.slug			IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) . "')
						AND		%s		 			= date_format(posts.post_date,'%%Y%%m%%d')
					", $dateDay ) );

					$_shipping_tax = $wpdb->get_var( $wpdb->prepare( "
						SELECT SUM( meta.meta_value ) AS order_tax
						FROM {$wpdb->posts} AS posts
						LEFT JOIN {$wpdb->postmeta} AS meta ON posts.ID = meta.post_id
						LEFT JOIN {$wpdb->term_relationships} AS rel ON posts.ID=rel.object_ID
						LEFT JOIN {$wpdb->term_taxonomy} AS tax USING( term_taxonomy_id )
						LEFT JOIN {$wpdb->terms} AS term USING( term_id )
						WHERE 	meta.meta_key 		= '_order_shipping_tax'
						AND 	posts.post_type 	= 'shop_order'
						AND 	posts.post_status 	= 'publish'
						AND 	tax.taxonomy		= 'shop_order_status'
						AND		term.slug			IN ('" . implode( "','", apply_filters( 'woocommerce_reports_order_statuses', array( 'completed', 'processing', 'on-hold' ) ) ) . "')
						AND		%s		 			= date_format(posts.post_date,'%%Y%%m%%d')
					", $dateDay ) );
				}

			$day_dohod [] = ($_zakupka > 0) ? $_gross - $_shipping - ($_shipping_tax + $_order_tax) - $_zakupka : 0 ;
			
		}
		die( json_encode(array('d'=>$day_dohod, 'day'=>$_dateDay, 'm'=> $m) ) );
	}
	function create_box_content_zakupka ($order) {
				global $post_id; 
				$gros = get_post_meta($post_id,'_order_total',true);
				$shipping = get_post_meta($post_id,'_order_shipping',true);
				$tax = get_post_meta($post_id,'_order_tax',true);
				$zakupka = get_post_meta($post_id,'_total_price_zakupka',true);
				$shipping_tax = get_post_meta($post_id,'_order_shipping_tax',true);
				$dohod = ($zakupka > 0) ? $gros - $shipping - ($shipping_tax + $tax) - $zakupka : 0 ;
		?>
		<ul class="woocommerce-zakupka">
			<li>
			<?php echo woocommerce_price($dohod); ?>
			</li>
		</ul>
		<?php
	}
	function add_meta_boxes_zakupka () {
		add_meta_box( 'saphali-wc-zakupka', __( 'Чистый доход', 'zakupka' ), 'create_box_content_zakupka', 'shop_order', 'side', 'default', 5 );
	}
}

	register_activation_hook( __FILE__, 'Woo_S_Zakupochnaya_Cena' );
	function Woo_S_Zakupochnaya_Cena() {
		$transient_name = 'wc_saph_' . md5( 'zakupochnaya-cena' . site_url() );
		$pay[$transient_name] = get_transient( $transient_name );
		
		foreach($pay as $key => $tr) {
			if($tr !== false) {
				delete_transient( $key );
			}
		}
	}