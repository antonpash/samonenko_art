<?php
/*
Plugin Name: Saphali WooCommerce Nova Pocta
Plugin URI: http://saphali.com/saphali-woocommerce-plugin-wordpress
Description: Nova Pocta - Позволяет сразу выбрать и оформить доставку в РЕГИОНЫ через транспортные компании при оформлении заказа. Подробнее на сайте <a href="http://saphali.com/saphali-woocommerce-plugin-wordpress">Saphali Woocommerce</a>
Version: 1.0.4
Author: Saphali
Author URI: http://saphali.com/
Text Domain: wc-saphali-nova-pocta
Domain Path: /languages

*/

/*

 Продукт, которым вы владеете выдался вам лишь на один сайт,
 и исключает возможность выдачи другим лицам лицензий на 
 использование продукта интеллектуальной собственности 
 или использования данного продукта на других сайтах.

 */
 
//add_action( 'woocommerce_review_order_after_shipping',  array( 'WC_Free_Shippings', 'review_order_pickup_location' ) );


function woocommerce_nova_pochta_init( ) {
if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) {} else {
	class WC_Free_Shipping extends WC_Shipping_Free_Shipping {}
}
class WC_New_Pochta extends WC_Free_Shipping {
	function __construct() {
        $this->id 			= 'nova_pochta';
        $this->method_title = __('Новая почта', 'wc-saphali-nova-pocta');
		$this->init();
		add_filter('woocommerce_cart_shipping_method_full_label', array($this, 'woocommerce_cart_shipping_method_full_label') );
    } 
	function woocommerce_cart_shipping_method_full_label($full_label) {
		$_full_label = str_replace(' (' . __( 'Free', 'wc-saphali-nova-pocta' ) . ')', '', $full_label);
		if( $this->title ==  $_full_label) {
			$full_label = $_full_label;
		}
		return $full_label;
	}
	function init_form_fields() {
    	global $woocommerce;

    	// Backwards compat

		if(version_compare( WOOCOMMERCE_VERSION, '2.0', '<' )) {
		
			$this->form_fields = array(
			'enabled' => array(
							'title' 		=> __( 'Enable/Disable', 'woocommerce' ),
							'type' 			=> 'checkbox',
							'label' 		=> __( 'Активировать Новую почту', 'wc-saphali-nova-pocta' ),
							'default' 		=> 'yes'
						),
			'title' => array(
							'title' 		=> __( 'Method Title', 'woocommerce' ),
							'type' 			=> 'text',
							'description' 	=> __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
							'default'		=> __( 'Новая почта', 'woocommerce' )
						),
			'min_amount' => array(
							'title' 		=> __( 'Minimum Order Amount', 'woocommerce' ),
							'type' 			=> 'text',
							'description' 	=> __('Users will need to spend this amount to get free shipping. Leave blank to disable.', 'woocommerce'),
							'default' 		=> ''
						),
						'fee' => array(
							'title' 		=> __( 'Delivery Fee', 'woocommerce' ),
							'type' 			=> 'number',
							'custom_attributes' => array(
								'step'	=> 'any',
								'min'	=> '0'
							),
							'description' 	=> __('Добавочная стоимость при выборе этого метода доставки.', 'wc-saphali-nova-pocta'),
							'default'		=> '',
							'desc_tip'      => true,
							'placeholder'	=> '0.00'
						),
			'requires_coupon' => array(
							'title' 		=> __( 'Coupon', 'woocommerce' ),
							'type' 			=> 'checkbox',
							'label' 		=> __( 'Free shipping requires a free shipping coupon', 'woocommerce' ),
							'description' 	=> __('Users will need to enter a valid free shipping coupon code to use this method. If a coupon is used, the minimum order amount will be ignored.', 'woocommerce'),
							'default' 		=> 'no'
						)
			);
		} else {
    	if ( $this->requires_coupon && $this->min_amount )
			$default_requires = 'either';
		elseif ( $this->requires_coupon )
			$default_requires = 'coupon';
		elseif ( $this->min_amount )
			$default_requires = 'min_amount';
		else
			$default_requires = '';
		$this->form_fields = array(
			'enabled' => array(
							'title' 		=> __( 'Enable/Disable', 'woocommerce' ),
							'type' 			=> 'checkbox',
							'label' 		=> __( 'Активировать Новую почту', 'wc-saphali-nova-pocta' ),
							'default' 		=> 'yes'
						),
			'title' => array(
							'title' 		=> __( 'Method Title', 'woocommerce' ),
							'type' 			=> 'text',
							'description' 	=> __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
							'default'		=> __( 'Новая почта', 'wc-saphali-nova-pocta' ),
							'desc_tip'      => true,
						),

			'availability' => array(
							'title' 		=> __( 'Method availability', 'woocommerce' ),
							'type' 			=> 'select',
							'default' 		=> 'all',
							'class'			=> 'availability',
							'options'		=> array(
								'all' 		=> __( 'All allowed countries', 'woocommerce' ),
								'specific' 	=> __( 'Specific Countries', 'woocommerce' )
							)
						),
			'countries' => array(
							'title' 		=> __( 'Specific Countries', 'woocommerce' ),
							'type' 			=> 'multiselect',
							'class'			=> 'chosen_select',
							'css'			=> 'width: 450px;',
							'default' 		=> '',
							'options'		=> $woocommerce->countries->countries
						),
			'requires' => array(
							'title' 		=> __( 'Новая почта (опции задействия)', 'wc-saphali-nova-pocta' ),
							'type' 			=> 'select',
							'default' 		=> $default_requires,
							'options'		=> array(
								'' 				=> __( 'N/A', 'woocommerce' ),
								'coupon'		=> __( 'Купон на бесплатную доставку. Активация новой почты', 'wc-saphali-nova-pocta' ),
								'min_amount' 	=> __( 'A minimum order amount (defined below)', 'woocommerce' ),
								'either' 		=> __( 'A minimum order amount OR a coupon', 'woocommerce' ),
								'both' 			=> __( 'A minimum order amount AND a coupon', 'woocommerce' ),
							)
						),
			'min_amount' => array(
							'title' 		=> __( 'Minimum Order Amount', 'woocommerce' ),
							'type' 			=> 'number',
							'custom_attributes' => array(
								'step'	=> 'any',
								'min'	=> '0'
							),
							'description' 	=> __( 'Покупателям необходимо потратить именно столько, чтобы активировать данный метод доставки. Оставте пустым, чтобы опция была неактивной.', 'wc-saphali-nova-pocta' ),
							'default' 		=> '0',
							'desc_tip'      => true,
							'placeholder'	=> '0.00'
						),
						'fee' => array(
							'title' 		=> __( 'Delivery Fee', 'woocommerce' ),
							'type' 			=> 'number',
							'custom_attributes' => array(
								'step'	=> 'any',
								'min'	=> '0'
							),
							'description' 	=> __('Добавочная стоимость при выборе этого метода доставки.', "wc-saphali-nova-pocta"),
							'default'		=> '',
							'desc_tip'      => true,
							'placeholder'	=> '0.00'
						)
						
			);
		}
			
    }
	function init() {
	global $woocommerce;

		$this->init_settings(); 
		// Define user set variables
        $this->enabled		= $this->settings['enabled'];
		$this->title 		= $this->settings['title'];
		$this->min_amount 	= $this->settings['min_amount'];
		$this->availability = $this->settings['availability'];
		$this->countries 	= $this->settings['countries'];
		$this->fee			= $this->settings['fee'];
		if(version_compare( WOOCOMMERCE_VERSION, '2.0', '<' )) $this->requires_coupon 	= $this->settings['requires_coupon'];
		else  $this->requires_coupon 	= $this->settings['requires'];
				// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		
		// Actions
		add_action('woocommerce_update_options_shipping_'.$this->id, array($this, 'process_admin_options'));
		
		if ( $this->is_availables( ) ) {
			add_action( 'woocommerce_review_order_after_shipping',  array( $this, 'review_order_pickup_location' ) );
			add_action( 'woocommerce_checkout_update_order_meta',   array( $this, 'checkout_update_order_meta' ), 10, 2 );
		}
		add_filter( 'woocommerce_shipping_methods', array( $this, 'add_nova_pocha' ) );

		if ( is_admin() ) {
			add_action( 'woocommerce_admin_order_data_after_shipping_address', array( $this, 'admin_order_pickup_location' ) );
		}
    }
	function calculate_shipping( $package = array() ) {

		$shipping_total = 0;
		$fee = ( trim( $this->fee ) == '' ) ? 0 : $this->fee;

		$shipping_total 	= $this->fee;

		$rate = array(
			'id' 		=> $this->id,
			'label' 	=> $this->title,
			'cost' 		=> $shipping_total
		);

		$this->add_rate($rate);
	}
	public function admin_order_pickup_location() {
		global $post;
				
		$order = new WC_Order( $post->ID );
		if ( !version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) {
			$_is_true = $order->__get( 'nova_pochta' );
			$is_true = ( $order->get_shipping_method() == $this->title && !empty( $_is_true ) ) ;
		} else {
			$is_true = ($order->shipping_method == $this->id && isset( $order->order_custom_fields['_nova_pochta'][0] ) ); 
		}
		if ( $is_true ) {
			if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) {
				$pickup_location_array = maybe_unserialize( $order->order_custom_fields['_nova_pochta'][0] );
			} else {
				$pickup_location_array =  get_post_meta($order->id, '_nova_pochta', true);			
			}
		}
			if(!empty($pickup_location_array)) {
				echo '<div>';
				echo '<h3>'.__( 'Адрес или № склада Новой Почты:', 'wc-saphali-nova-pocta' ).'</h3>';
				
				echo '<div> '.$pickup_location_array .'</div>';
				echo '</div>';
			}
	}
	public function checkout_update_order_meta( $order_id, $posted ) {
	//if(is_array($_POST['shipping_method']) && isset($_POST['shipping_method'][0]) ) $posted['shipping_method'] = $_POST['shipping_method'][0]; elseif(!empty($_POST['shipping_method'])) $posted['shipping_method'] = $_POST['shipping_method'];
		if(is_array($posted['shipping_method']) && isset($posted['shipping_method'][0]) ) $posted['shipping_method'] = $posted['shipping_method'][0]; 
		
		if ( $posted['shipping_method'] == $this->id ) {

			if ( isset($_POST['nova_pochta']) ) {
				update_post_meta( $order_id, '_nova_pochta', esc_attr($_POST["nova_pochta"]) );
				if( is_user_logged_in() ) {
					$ob = wp_get_current_user(); 
					update_user_meta( $ob->data->ID, 'billing_nova_post', $_POST["nova_pochta"] );
				}
			}
		}
	}
	public function is_available(  $package = array()  ) {
		return $this->is_availables(  $package = array()  );
	}
	public function is_availables(  $package = array()  ) {
		
		if ( $this->enabled == "no"  ) {return false;}
		
	if(version_compare( WOOCOMMERCE_VERSION, '2.0', '<' )) {
		global $woocommerce;
		$is_available 		= false;
		$has_coupon 		= false;
		$has_met_min_amount = false;
		if ( $this->requires_coupon == 'yes' && $this->min_amount )
			$default_requires = 'either';
		elseif ( $this->requires_coupon=='yes' )
			$default_requires = 'coupon';
		elseif ( $this->min_amount )
			$default_requires = 'min_amount';
		else
			$default_requires = '';
		if ( in_array( $default_requires, array( 'coupon', 'either' ) ) ) {

			if ( $woocommerce->cart->applied_coupons ) {
				foreach ($woocommerce->cart->applied_coupons as $code) {
					$coupon = new WC_Coupon( $code );

					if ( $coupon->is_valid() && $coupon->enable_free_shipping() )
						$has_coupon = true;
				}
			}
		}

		if ( in_array( $default_requires, array( 'min_amount', 'either' ) ) ) {

			if ( isset( $woocommerce->cart->cart_contents_total ) ) {

				if ( $woocommerce->cart->prices_include_tax )
					$total = $woocommerce->cart->tax_total + $woocommerce->cart->cart_contents_total;
				else
					$total = $woocommerce->cart->cart_contents_total;

				if ( $total >= $this->min_amount )
					$has_met_min_amount = true;
			}
		}
		switch ( $default_requires ) {
			case 'min_amount' :
				if ( $has_met_min_amount ) $is_available = true;
			break;
			case 'coupon' :
				if ( $has_coupon ) $is_available = true;
			break;
			case 'either' :
				if ( $has_met_min_amount && $has_coupon ) $is_available = true;
			break;
			case 'both' :
				if ( $has_met_min_amount || $has_coupon ) $is_available = true;
			break;
			default :
				$is_available = true;
			break;
		}
		
	} else {
		global $woocommerce;
		$is_available 		= false;
		$has_coupon 		= false;
		$has_met_min_amount = false;

		if ( in_array( $this->requires_coupon, array( 'coupon', 'either', 'both' ) ) ) {

			if ( $woocommerce->cart->applied_coupons ) {
				foreach ($woocommerce->cart->applied_coupons as $code) {
					$coupon = new WC_Coupon( $code );

					if ( $coupon->is_valid() && $coupon->enable_free_shipping() )
						$has_coupon = true;
				}
			}
		}

		if ( in_array( $this->requires_coupon, array( 'min_amount', 'either', 'both' ) ) ) {

			if ( isset( $woocommerce->cart->cart_contents_total ) ) {

				if ( $woocommerce->cart->prices_include_tax )
					$total = $woocommerce->cart->tax_total + $woocommerce->cart->cart_contents_total;
				else
					$total = $woocommerce->cart->cart_contents_total;

				if ( $total >= $this->min_amount )
					$has_met_min_amount = true;
					
			}
		}

		switch ( $this->requires_coupon ) {
			case 'min_amount' :
				if ( $has_met_min_amount ) $is_available = true;
			break;
			case 'coupon' :
				if ( $has_coupon ) $is_available = true;
			break;
			case 'both' :
				if ( $has_met_min_amount && $has_coupon ) $is_available = true;
			break;
			case 'either' :
				if ( $has_met_min_amount || $has_coupon ) $is_available = true;
			break;
			default :
				$is_available = true;
			break;
		}
		
	}
		if(!$is_available) $this->enabled = "no";
		
		if ( isset($package['destination']['country']) && ! in_array( $package['destination']['country'], array('UA') ) ) 
			return false;
		return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', $is_available, $package );
	}
	
	public function review_order_pickup_location() {
		global $woocommerce;
		if ( version_compare( WOOCOMMERCE_VERSION, '2.0', '<' ) ) $chosen_shipping_method = $_SESSION['_chosen_shipping_method']; 
		else $chosen_shipping_method = $woocommerce->session->chosen_shipping_method;
		if( isset($_POST['shipping_method']) && is_array($_POST['shipping_method']) && isset($_POST['shipping_method'][0]) ) $posted['shipping_method'] = $_POST['shipping_method'][0]; 
		if(isset($posted['shipping_method']) && is_null($chosen_shipping_method) )$chosen_shipping_method = $posted['shipping_method'];
		if( ! $chosen_shipping_method ) {
			$post_data = explode( '&', $_POST['post_data'] );
			foreach ( $post_data as $data ) {
				if ( strpos( $data, '=' ) !== false ) {
					list( $name, $value ) = explode( '=', $data );
					if( $name == 'shipping_method' || $name == 'shipping_method%5B0%5D' ) {
						$chosen_shipping_method = $value;
						break;
					}
				}
			}	
		}
		if ( $chosen_shipping_method == $this->id ) {
			echo '<tr class="free_shipping">';
			echo '	<th align="right"><div style="width: auto; float: right;">' . __( 'Адрес или № склада Новой Почты', 'wc-saphali-nova-pocta'  ) . ' <span style="color: red" title="обов\'язково">*</span>'  . '</div></th>';
			echo '	<td>';
			do_action( 'woocommerce_review_order_before_'.$this->id, array('nova_pochta') );
			if( is_user_logged_in() ) {
				$ob = wp_get_current_user(); $zn = get_user_meta( $ob->data->ID, 'billing_nova_post', true);
			}
			else $zn = '';
			echo '<input type="text" id="nova_pochta" name="nova_pochta" value="' . $zn . '" />&nbsp;';
			echo '</td>';
			
			echo '</tr>';
		}
	}

	public function add_nova_pocha( $methods ) {
		// since the gateway is always constructed, we'll pass it in to the register filter so it doesn't have to be re-instantiated
		$methods[] = $this;
		return $methods;
	}
}

new WC_New_Pochta();

}


class nova_pochta_lite_init {
	var $title;
	var $id;
	var $email_order_id;
	function __construct() {
		$this->id = 'nova_pochta';
		add_filter('woocommerce_order_shipping_to_display', array($this, 'order_shipping_to_display'), 10 , 2 );
		add_action( 'woocommerce_shipping_init', 'woocommerce_nova_pochta_init');
		load_plugin_textdomain( 'wc-saphali-nova-pocta',  false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		add_action( 'woocommerce_thankyou',                     array( $this, 'order_pickup_location' ), 20 );
		add_action( 'woocommerce_view_order',                   array( $this, 'order_pickup_location' ), 20 );
		if( version_compare( WOOCOMMERCE_VERSION, '3.0', '<' ) )
		add_action( 'woocommerce_after_template_part',          array( $this, 'email_pickup_location' ), 10, 3 );
		else 
		add_action( 'woocommerce_after_template_part',          array( $this, 'email_pickup_location' ), 10, 4 );
		
		add_action('woocommerce_order_status_pending_to_processing_notification', array( $this, 'store_order_id' ),1 );
		add_action('woocommerce_order_status_pending_to_completed_notification',  array( $this, 'store_order_id' ),1 );
		add_action('woocommerce_order_status_pending_to_on-hold_notification',    array( $this, 'store_order_id' ),1 );
		add_action('woocommerce_order_status_failed_to_processing_notification',  array( $this, 'store_order_id' ),1 );
		add_action('woocommerce_order_status_processing_to_completed_notification',array( $this, 'store_order_id'),1 );
		add_action('woocommerce_order_status_failed_to_completed_notification',   array( $this, 'store_order_id' ),1 );
		add_action('woocommerce_order_status_completed_notification',             array( $this, 'store_order_id' ),1 );
		add_action('woocommerce_new_customer_note_notification',                  array( $this, 'store_order_id' ),1 );
		add_action( 'woocommerce_after_checkout_validation', array( $this, 'after_checkout_validation' ) );
	}
	public function after_checkout_validation( $posted ) {
		if(is_array($posted['shipping_method']) && isset($posted['shipping_method'][0]) )$posted['shipping_method'] = $posted['shipping_method'][0];
		if ( $posted['shipping_method'] == $this->id ) {
			if(empty($_POST['nova_pochta'])) {
				$this->comp_woocomerce_mess_error( __( 'Будь ласка, вкажіть <strong>Адресу або № складу Нової Пошти</strong>.', 'wc-saphali-nova-pocta' ) );
			}
		}
	}
	function comp_woocomerce_mess_error ($m) {
		if( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
			global $woocommerce;
			$woocommerce->add_error( $m );
		} else {
			wc_add_notice( $m, 'error' );
		}
	}
	function order_shipping_to_display($label , $order) {
		if( empty($this->title) ) {
			$settings = get_option("woocommerce_{$this->id}_settings", array('title' => '') );
			$this->title = $settings["title"];
		}
		
		if ( !version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) {
			$is_true = (  $order->get_shipping_method() == $this->title || strpos( $this->title, $order->get_shipping_method() ) !== false    ) ;
		} else {
			$is_true = ( $order->shipping_method == $this->id ); 
		}

		if ( $is_true ) {
			if( strpos($label, 'via') !== false || strpos($label, 'через') !== false )
			$label = str_replace( array( 'Нова пошта', 'Новая почта', 'Нова Пошта', 'Новая Почта', 'НОВА ПОШТА', 'НОВАЯ ПОЧТА' ), array( 'Нову пошту', 'Новую почту', 'Нову Пошту', 'Новую Почту', 'НОВУ ПОШТУ', 'НОВУЮ ПОЧТУ' ) , $label);
		}
		return $label;
	}
	public function store_order_id( $arg ) {
		if ( is_int( $arg ) ) $this->email_order_id = $arg;
		elseif ( is_array( $arg ) && array_key_exists( 'order_id', $arg ) ) $this->email_order_id = $arg['order_id'];
	}
	public function email_pickup_location( $template_name, $template_path, $located, $args = array() ) {
		if( ( $template_name == 'emails/email-addresses.php' || $template_name == "emails/plain/email-addresses.php") && isset($args["order"]) && is_object($args["order"]) ) {
			$this->email_order_id  = $args["order"]->get_id();
		}
		
		if( empty($this->title) ) {
			$settings = get_option("woocommerce_{$this->id}_settings", array('title' => '') );
			$this->title = $settings["title"];
		}		
		if ( $template_name == 'emails/email-addresses.php' && $this->email_order_id ) {

			$order = new WC_Order( $this->email_order_id );

		if ( !version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) {
			$is_true = ( $order->get_shipping_method() == $this->title  ) ;
		} else {
			$is_true = ($order->shipping_method == $this->id  ); 
		}
		if ( $is_true ) {
			if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) {
				$pickup_location_array = maybe_unserialize( $order->order_custom_fields['_nova_pochta'][0] );
			} else {
				$pickup_location_array =  get_post_meta($order->id, '_nova_pochta', true);			
			}

			if(!empty($pickup_location_array)) {
				echo '<div>';
				echo '<h3>'.__( 'Новая почта', 'wc-saphali-nova-pocta' ).'</h3>';
				
				echo '<div>' . __( 'Адрес или № склада Новой Почты', 'wc-saphali-nova-pocta'  )  . ': '.$pickup_location_array .'</div>';
				echo '</div>';
			}
			}
		} elseif ( $template_name == "emails/plain/email-addresses.php" && $this->email_order_id ) {

			$order = new WC_Order( $this->email_order_id );

		if ( !version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) {
			$is_true = ( $order->get_shipping_method() == $this->title  ) ;
		} else {
			$is_true = ($order->shipping_method == $this->id  ); 
		}
		if ( $is_true ) {
			if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) {
				$pickup_location_array = maybe_unserialize( $order->order_custom_fields['_nova_pochta'][0] );
			} else {
				$pickup_location_array =  get_post_meta($order->id, '_nova_pochta', true);			
			}

			if(!empty($pickup_location_array)) {
				echo "---------------------------------------------------------------------\n";
				echo __( 'Новая почта', 'wc-saphali-nova-pocta' ) . "\n";
				
				echo __( 'Адрес или № склада Новой Почты', 'wc-saphali-nova-pocta'  )  . ': '.$pickup_location_array;
			}
			}
		}
	}
	function order_pickup_location($order_id) {
		if( empty($this->title) ) {
			$settings = get_option("woocommerce_{$this->id}_settings", array('title' => '') );
			$this->title = $settings["title"];
		}
		$order = new WC_Order( $order_id );

		if ( !version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) {
			$is_true = ( $order->get_shipping_method() == $this->title  ) ;
		} else {
			$is_true = ($order->shipping_method == $this->id  ); 
		}
		if ( $is_true ) {
			if ( version_compare( WOOCOMMERCE_VERSION, '2.1.0', '<' ) ) {
				$pickup_location_array = maybe_unserialize( $order->order_custom_fields['_nova_pochta'][0] );
			} else {
				$pickup_location_array =  get_post_meta($order->id, '_nova_pochta', true);			
			}

			if(!empty($pickup_location_array)) {
				echo '<div>';
				echo '<h3>'.__( 'Новая почта', 'wc-saphali-nova-pocta' ).'</h3>';
				
				echo '<div>' . __( 'Адрес или № склада Новой Почты', 'wc-saphali-nova-pocta'  )  . ': '.$pickup_location_array .'</div>';
				echo '</div>';
			}
		}
	}
}

add_action('plugins_loaded', 'nova_pochta_lite_init' );

function nova_pochta_lite_init() {
	new nova_pochta_lite_init();
}





?>