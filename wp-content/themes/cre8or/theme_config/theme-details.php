<?php
define('THEME_NAME', 'cre8or');
define('THEME_PRETTY_NAME', 'Cre8or');

//Load Textdomain
add_action('after_setup_theme', 'theme_textdomain_setup');
function theme_textdomain_setup(){
	load_theme_textdomain(THEME_NAME, get_template_directory() . '/languages');
}

//content width
if (!isset($content_width))
    $content_width = 736;

//============Theme support=======
//post-thumbnails
add_theme_support('post-thumbnails');
//add feed support
add_theme_support('automatic-feed-links');
