<?php
/**
 * Plugin Name: TeslaFramework
 * Plugin URI: http://teslathemes.com/framework-tour/
 * Description: TeslaFramework plugin part (registers custom post types of the theme)
 * Version: 1.0
 * Author: TeslaThemes
 * Author URI: http://teslathemes.com/
 * License: GPL2
 */

require_once 'tesla_slider.php';
add_action('after_setup_theme','tt_custom_posts_plugin');
function tt_custom_posts_plugin(){
	Tesla_slider::init();
}