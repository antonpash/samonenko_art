<?php

define('TT_USES_PLUGIN', true);

/**
 *   Include Framework and Plugins
 */

require_once(TEMPLATEPATH . '/tesla_framework/tesla.php');
require_once(TEMPLATEPATH . '/theme_config/helper/custom_meta_box.php');
require_once(TEMPLATEPATH . '/theme_config/tt_icons.php');
require_once(TEMPLATEPATH . '/plugins/tgm-plugin-activation/register-plugins.php');

if (!class_exists('Cre8or')) {
    Class Cre8or
    {
        public $theme_wp;

        public function __construct()
        {
            TT_ENQUEUE::$enabled = FALSE;
            // Actions
            add_action('after_setup_theme', array($this, 'theme_setup'));
            add_action('wp_head', array($this, 'theme_favicon'));
            add_action('wp_footer', array($this, 'custom_js'), 100);
            add_action('wp_enqueue_scripts', array($this, 'enqueue_goods'));
            add_action('wp_enqueue_scripts', array($this, 'request_js'), 99);
            add_action('wp_enqueue_scripts', array($this, 'theme_custom_css'), 99);
            add_action('tt_theme_header', array($this, 'header_view'));
            add_action('tt_content', array($this, 'create_pages_contents'));
            add_action('tt_portfolio', array($this, 'portfolio_content'));
            add_action('tt_before_pages', array($this, 'before_pages'));
            add_action('tt_after_pages', array($this, 'after_pages'));
            add_action('wp', array($this, 'detect_shortcode'));
            add_action('widgets_init', array($this, 'sidebar_init'));
            add_action('wp_ajax_tt_contact_form', array($this, 'tt_contact_form'));
            add_action('wp_ajax_nopriv_tt_contact_form', array($this, 'tt_contact_form'));
            add_action('admin_init', array($this, 'add_admin_settings'));

            if (is_admin()) {
                add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_goods'), 10, 1);
                add_action('load-post.php', array($this, 'generate_custom_meta'));
                add_action('load-post-new.php', array($this, 'generate_custom_meta'));
                add_action('load-post.php', array($this, 'disable_editor_portfolio'));
            }

            // Filters
            add_filter('upload_mimes', array($this, 'tt_mime_types'));
            add_filter('get_search_form', array($this, 'filter_search_form'));
            add_filter('the_content_more_link', array($this, 'read_more_blog'));
            add_filter('get_the_excerpt', array($this, 'read_more_link_excerpt'));
            add_filter('body_gradient', array($this, 'theme_body_gradient'));
            add_filter('display_post_states', array($this, 'add_portfolio_label'), 10, 2);

            // Activate Visual Composer
            add_action('init', array($this, 'start_visual_composer'), 11);

            $this->theme_wp = wp_get_theme();
        }

        /**
         *   Add theme support and register theme support
         */

        public function theme_setup()
        {

            add_theme_support('automatic-feed-links');
            add_theme_support('title-tag');
            add_theme_support('post-thumbnails');
            set_post_thumbnail_size(825, 510, true);

            add_image_size('blog-thumb', 850, 510, true);
            add_image_size('portfolio-thumb', 420, 420, true);
            add_image_size('portfolio-long', 400, 700, true);

            register_nav_menus(array(
                'main_menu' => esc_html__('Primary Menu', 'cre8or'),
                'add_menu' => esc_html__('Secondary Menu', 'cre8or'),
            ));

            add_theme_support('html5', array(
                'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
            ));

            add_theme_support('post-formats', array(
                'aside', 'image', 'video', 'quote', 'link', 'gallery', 'audio'
            ));
        }

        /**
         *   Favicon Picker
         */

        public function theme_favicon()
        {
            if (function_exists('wp_site_icon') && has_site_icon()) {
                wp_site_icon();
            } else {
                $favicon = _go('favicon_link') ? _go('favicon_link') : TT_THEME_URI . '/images/cre8or.png';
                echo "\r\n" . sprintf('<link rel="shortcut icon" href="%s">', $favicon) . "\r\n";
            }
        }

        /**
         *   Extend media uploader
         */

        function tt_mime_types($mimes)
        {
            $mimes['svg'] = 'image/svg+xml';
            return $mimes;
        }

        /**
         *   Enqueue Fonts, Styles and JS (front-end)
         */

        public function enqueue_goods()
        {

            $protocol = is_ssl() ? 'https' : 'http';
            $gfont_changer = array(
                _go('global_typo_font'),
                _go('links_settings_font'),
                _go('logo_text_font'),
                _go('headings_settings_font')
            );

            $default_css = array(
                'base-font' => "$protocol://fonts.googleapis.com/css?family=Rajdhani:400,600|Fira+Sans:300,400,500",
                'main-style' => TT_THEME_URI . "/css/screen.css",
                'theme-style' => get_stylesheet_uri()
            );

            // Google font picker
            foreach ($gfont_changer as $font) {
                $font = str_replace(' ', '+', $font);
                if ($font !== '') {
                    wp_enqueue_style('tt-custom-font-' . $font, "$protocol://fonts.googleapis.com/css?family=$font");
                }
            }

            // Main CSS and Fonts
            foreach ($default_css as $k => $css) {
                wp_enqueue_style('tt-' . $k, $css);
            }
            if (class_exists('Vc_Manager')) {
                wp_enqueue_style("js_composer_front");
            }

            if (is_singular()) {
                wp_enqueue_script("comment-reply");
            }
        }

        public function request_js()
        {
            $default_js = array(
                'tween-max' => TT_THEME_URI . '/js/lib/TweenMax.js',
                'attr-plugin' => TT_THEME_URI . '/js/lib/AttrPlugin.js',
                'snap-svg' => TT_THEME_URI . '/js/lib/snap.svg.js',
                'image-loaded' => TT_THEME_URI . '/js/lib/imageloaded.js',
                'isotope' => TT_THEME_URI . '/js/lib/isotope.js',
                'flickity' => TT_THEME_URI . '/js/lib/flickity.js',
                'modernizr' => TT_THEME_URI . '/js/lib/modernizr.js',
                'fitvids' => TT_THEME_URI . '/js/lib/fitvids.js',
                'options' => TT_THEME_URI . '/js/options.js'
            );

            $register_scripts = array(
                'g-map' => 'http://maps.googleapis.com/maps/api/js?sensor=false',
            );

            foreach ($register_scripts as $key => $script) {
                wp_register_script($key, $script, array('jquery'), $this->theme_wp->get('Version'), true);
            }

            foreach ($default_js as $key => $script) {
                $position = $key === 'modernizr' ? false : true;
                wp_enqueue_script($key, $script, array('jquery'), $this->theme_wp->get('Version'), $position);
            }

            // Transfer data to js
            $send_js = array(
                'dirUri' => get_template_directory_uri(),
                'routerHome' => esc_url(home_url('/'))
            );

            wp_localize_script('options', 'themeOptions', $send_js);

        }

        /**
         *   Add custom JS on the footer
         */

        public function custom_js()
        {
            $custom_js = _go('custom_js');

            if ($custom_js) {
                echo sprintf('<script>%s</script>', $custom_js);
            }
        }

        /**
         *   Enqueue Fonts, Styles and JS (admin)
         */

        public function admin_enqueue_goods($hook)
        {
            if ($hook == 'post-new.php' || $hook == 'post.php') {
                wp_enqueue_style('wp-color-picker');
                wp_enqueue_style('tt-icons', TT_THEME_URI . '/css/admin/icons.css');
            }
            wp_enqueue_style('tt-admin', TT_THEME_URI . '/css/admin/admin.css');
            wp_enqueue_script('admin.js', TT_THEME_URI . '/js/admin/admin.js', array('jquery'), false, true);
        }

        /**
         *   Theme custom CSS
         */

        public function theme_custom_css()
        {
            // Main styles switches
            $custom_css = (_go('layout_style') == 'Boxed') ? '#main-wrap {max-width: 1300px; margin: 0 auto;} ' : '';
            $custom_css .= (_go('body_background') || (_go('body_color') || _go('body_background_repeat')
                    || _go('body_background_position'))) ? 'body {' : '';
            $custom_css .= (_go('body_background')) ? 'background-image: url(' . _go('body_background') . '); ' : '';
            $custom_css .= (_go('body_color')) ? 'background-color: ' . _go('body_color') . '; ' : '';
            $custom_css .= (_go('body_background') && _go('body_background_repeat')) ? 'background-repeat: ' .
                strtolower(_go('body_background_repeat')) . '; ' : '';
            $custom_css .= (_go('body_background') && _go('body_background_position')) ? 'background-attachment: ' .
                strtolower(_go('body_background_position')) . '; ' : '';
            $custom_css .= (_go('body_background') || (_go('body_color') || _go('body_background_repeat')
                    || _go('body_background_position'))) ? '}' : '';
            // Main styles switches
            $custom_css .= (_go('header_color') || _go('menu_background')) ? '.menu-box {' : '';
            $custom_css .= (_go('header_color')) ? 'background-color: ' . _go('header_color') . ' !important;' : '';
            $custom_css .= (_go('menu_background'))
                ? 'background-image: url(' . _go('menu_background') . ') !important;' : '';
            $custom_css .= (_go('header_color') || _go('menu_background')) ? '}' : '';
            // Logo text
            $custom_css .= (_go('logo_text') || (_go('logo_text_color') || _go('logo_text_font')
                    || _go('logo_text_size'))) ? '.logo-text{' : '';
            $custom_css .= (_go('logo_text') && _go('logo_text_color'))
                ? 'color: ' . _go('logo_text_color') . ';' : '';
            $custom_css .= (_go('logo_text') && _go('logo_text_font'))
                ? 'font-family: ' . _go('logo_text_font') . ';' : '';
            $custom_css .= (_go('logo_text') && _go('logo_text_size'))
                ? 'font-size: ' . _go('logo_text_size') . 'px;' : '';
            $custom_css .= (_go('logo_text') || (_go('logo_text_color')
                    || _go('logo_text_font') || _go('logo_text_size'))) ? '}' : '';
            // Page meta
            $page_meta = $this->get_custom_meta('page_meta');
            $custom_css .= !empty($page_meta->bg_color)
                ? '.box-breadcrumbs {background-color: ' . $page_meta->bg_color . ';}' : '';
            $custom_css .= !empty($page_meta->icon_color)
                ? '.breadcrumbs-title, .breadcrumbs-links * {color: ' . $page_meta->icon_color . ';}' : '';

            $custom_css .= (_go('canvas_color'))
                ? sprintf('#main-wrap { background: %s;}', _go('canvas_color')) : '';

            $custom_css .= (_go('primary_color'))
                ? sprintf('.bg-alpha, .vc_btn-alpha, .bg-alpha-hover:hover, .timeline-item > h6:before,
                .air-nav .current, .tabs-nav a:after, .featured-icon figure > a:hover,
                .big-tabs:before, .toggle-item input[type="radio"]:checked + h6,
                .sort-by > ul input[type=radio] + span:before,
                .portfolio-filters > ul > li label input:checked + span:hover,
                button.button-outline:hover,
                .portfolio-filters > ul > li label input:checked + span
                {background: %s !important;}', _go('primary_color')) : '';
            $custom_css .= (_go('primary_color'))
                ? sprintf('body .text-alpha, .loop-facts .featured-icon:hover + *,
                .portfolio-filters > ul > li label input:checked + span:after,
                input[type=text]:focus + span, input[type=email]:focus + span,
                html a:not([class]):hover, input[type=search]:focus + span,
                textarea:focus + span, .big-tabs-content:after,
                .social-networks > li a:hover i, .social-networks > li a:focus i,
                .active-big-tab > .tab-item > div,
                .twitter a {color: %s !important;}', _go('primary_color')) : '';
            $custom_css .= (_go('primary_color')) ? sprintf('a:hover {color: %s}', _go('primary_color')) : '';
            $custom_css .= (_go('primary_color'))
                ? sprintf('.post:not(.format-standard) .post-thumb,
                .widget-search input[type=search]:focus {border-color: %s}', _go('primary_color')) : '';
            $custom_css .= (_go('primary_color'))
                ? sprintf('.big-tabs, .portfolio-filters,
                input[type=text]:focus, input[type=email]:focus,
                input[type=search]:focus, textarea:focus
                {border-bottom-color: %s !important;}', _go('primary_color')) : '';
            $custom_css .= (_go('secondary_color'))
                ? sprintf('.bg-beta {background: %s !important;}', _go('secondary_color')) : '';
            $custom_css .= (_go('secondary_color'))
                ? sprintf('.text-beta {color: %s !important;}', _go('secondary_color')) : '';

            $custom_css .= (_go('global_typo_color'))
                ? sprintf('body {color: %s;}', _go('global_typo_color')) : '';
            $custom_css .= (_go('global_typo_size'))
                ? sprintf('body {font-size: %spx;}', _go('global_typo_size')) : '';
            $custom_css .= (_go('global_typo_font'))
                ? sprintf('body {font-family: %s;}', _go('global_typo_font')) : '';

            $custom_css .= (_go('links_settings_color'))
                ? sprintf('a {color: %s;}', _go('links_settings_color')) : '';
            $custom_css .= (_go('links_settings_size'))
                ? sprintf('a {font-size: %spx;}', _go('links_settings_size')) : '';
            $custom_css .= (_go('links_settings_font'))
                ? sprintf('a {font-family: %s;}', _go('links_settings_font')) : '';

            $custom_css .= (_go('headings_settings_color'))
                ? sprintf('h1, h2, h3, h4, h5, h6 {color: %s;}', _go('headings_settings_color')) : '';
            $custom_css .= (_go('headings_settings_font'))
                ? sprintf('h1, h2, h3, h4, h5, h6 {font-family: %s;}', _go('headings_settings_font')) : '';

            $custom_css .= (_go('headings_one_settings_size'))
                ? sprintf('h1 {font-size: %spx;}', _go('headings_one_settings_size')) : '';
            $custom_css .= (_go('headings_two_settings_size'))
                ? sprintf('h2 {font-size: %spx;}', _go('headings_two_settings_size')) : '';
            $custom_css .= (_go('headings_three_settings_size'))
                ? sprintf('h3 {font-size: %spx;}', _go('headings_three_settings_size')) : '';
            $custom_css .= (_go('headings_four_settings_size'))
                ? sprintf('h4 {font-size: %spx;}', _go('headings_four_settings_size')) : '';
            $custom_css .= (_go('headings_five_settings_size'))
                ? sprintf('h5 {font-size: %spx;}', _go('headings_five_settings_size')) : '';
            $custom_css .= (_go('headings_six_settings_size'))
                ? sprintf('h6 {font-size: %spx;}', _go(' headings_six_settings_size')) : '';

            $custom_css .= (_go('custom_css')) ? _go('custom_css') : '';

            wp_add_inline_style('tt-main-style', $custom_css);
        }

        public function theme_body_gradient($value)
        {
            $form_color = _go('body_gradient_from');
            $to_color = _go('body_gradient_to');

            if (_go('body_color') || _go('body_background')) {
                return '';
            }

            if ($form_color && $to_color) {
                return sprintf('data-animated-bg="%s:20-%s"', $form_color, $to_color);
            }

            return $value;
        }

        /**
         *   Create custom meta box
         */

        public function generate_custom_meta()
        {
            $meta = new GenerateCustomMeta();
            $meta->add_meta_box('page_meta', array(
                'post_type' => array('post'),
                'title' => esc_html__('Post cover', 'cre8or'),
                'position' => 'side',
                'fields' => array(
                    'cover_image' => array('image', esc_html__('Cover image', 'cre8or')),
                    'post_video' => array('text', esc_html__('Insert Youtube ID', 'cre8or'))
                )
            ));

            $meta->add_meta_box('portfolio_meta_video', array(
                'post_type' => array('portfolio'),
                'title' => esc_html__('Portfolio Video', 'cre8or'),
                'position' => 'side',
                'fields' => array(
                    'portfolio_video' => array('text', esc_html__('Insert Youtube or Vimeo ID', 'cre8or'))
                )
            ));
        }

        /**
         *   Generate navigation
         */

        public function theme_navigation($menu_name, $css_class = null, $menu = null)
        {
            $defaults = array(
                'theme_location' => strtolower(str_replace(" ", "_", $menu_name)),
                'menu' => $menu,
                'container' => false,
                'container_class' => '',
                'container_id' => '',
                'menu_class' => $css_class,
                'menu_id' => '',
                'echo' => false,
                'fallback_cb' => array($this, 'menu_callback'),
                'before' => '',
                'after' => '',
                'link_before' => '<span itemprop="name">',
                'link_after' => '</span>',
                'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'depth' => 0,
                'walker' => ''
            );

            $menu_items = wp_nav_menu($defaults);

            if (is_front_page())
                $menu_items = str_replace('current_page_item', '', $menu_items);

            if (is_singular('portfolio')) {
                $menu_items = str_replace('current_page_parent', '', $menu_items);
                $menu_items = str_replace('current_menu_parent', '', $menu_items);
                $menu_items = str_replace('page-item-' . get_option('page_for_portfolio'), 'page-item-'
                    . get_option('page_for_portfolio') . ' current_page_parent', $menu_items);
            }

            return $menu_items;
        }

        /**
         *   Menu callback (default input)
         */

        public function menu_callback($class = '')
        {
            $defaults = array(
                'authors' => '',
                'child_of' => 0,
                'date_format' => get_option('date_format'),
                'depth' => 0,
                'echo' => 0,
                'exclude' => '',
                'include' => '',
                'link_after' => '',
                'link_before' => '',
                'post_type' => 'page',
                'post_status' => 'publish',
                'show_date' => '',
                'sort_column' => 'menu_order, post_title',
                'title_li' => '',
                'walker' => ''
            );

            return sprintf('<ul class="%1$s">%2$s</ul>', $class['menu_class'], wp_list_pages($defaults));
        }

        /**
         *   Lazy images
         */

        public function lazy_img($img, $alt = null, $width = null, $height = null)
        {

            if (is_array($img)) {
                $img = $img[0];
                $width = $img[1];
                $height = $img[2];
            }

            $width = !empty($width) ? sprintf('width="%s"', $width) : '';
            $height = !empty($height) ? sprintf('height="%s"', $height) : '';

            return sprintf('<img src="%s" alt="%s" %s %s>', $img, $alt, $width, $height);
        }

        /**
         *   Main Logo
         */

        public function theme_logo($logo_id = false, $class = '')
        {
            $logo_url = _go('logo_image');
            $logo_text = _go('logo_text');
            $pattern = '<a class="identity logo %s" href="%s" data-logo-color="%s">%s</a>';

            if (!empty($logo_id)) {
                $logo_url = wp_get_attachment_url($logo_id);
            }

            if (!empty($logo_text)) {
                return sprintf($pattern, $class, esc_url(home_url('/')),
                    $this->primary_color('#f498bd', true), '<span class="logo-text">' . $logo_text . '</span>');
            } else {
                if (!empty($logo_url)) {
                    $logo_image = sprintf('<img alt="%s" src="%s">', get_bloginfo('name'), $logo_url);
                } else {
                    $logo_image = sprintf('<img alt="%s" src="%s">',
                        get_bloginfo('name'), TT_THEME_URI . '/images/logo.svg');
                }

                return sprintf($pattern, $class, esc_url(home_url('/')),
                    $this->primary_color('#f498bd', true), $logo_image);
            }
        }

        /**
         *   Helper to invoke template views
         */

        public function tt_view($name = null, array $params = array(), $folder = false)
        {
            global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

            do_action("get_template_part_tt", 'tt', $name);

            $templates = array();
            if (isset($name)) {
                $template_container = isset($folder) ? 'templates/' . $folder : 'templates';
                $templates[] = $template_container . "/tt-{$name}.php";
            }

            $_template_file = locate_template($templates, false, false);

            if (is_array($wp_query->query_vars)) {
                extract($wp_query->query_vars, EXTR_SKIP);
            }
            extract($params, EXTR_SKIP);


            require($_template_file);
        }

        /**
         *   Build class attribute
         */

        private function class_attr($class = null)
        {
            $pattern = 'class="%s"';

            if (isset($class)) {
                return sprintf($pattern, $class);
            }
        }

        /**
         *   Build header
         */

        public function header_view()
        {
            $logo_position = _go('logo_position') ? 'align-' . _go('logo_position') : 'align-center';
            $defaults = array(
                'logo' => $this->theme_logo(false, $logo_position),
                'navigation' => sprintf('<nav class="main-nav" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">%s</nav>',
                    $this->theme_navigation('main_menu', '', 'menu_' . substr(get_locale(), 0, 2))),
                'navigation_desktop' => sprintf('<nav class="second_nav" role="navigation" itemscope itemtype="http://schema.org/SiteNavigationElement">%s
                                        <ul class="social">
                                            <li><a target="_blank" href="https://vk.com/samonenko_art"><i class="fa fa-vk" aria-hidden="true"></i></a></li>
                                            <li><a target="_blank" href="https://www.facebook.com/SamonenkoArt/"><i class="fa fa-facebook-square" aria-hidden="true"></i></a></li>
                                            <li><a target="_blank" href="http://instagram.com/samonenkoolya"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
                                            <li><a target="_blank" href="https://www.behance.net/samonenkoolga"><i class="fa fa-behance" aria-hidden="true"></i></a></li>
                                        </ul>
                                        </nav>',
                    $this->theme_navigation('main_menu', '', 'menu_' . substr(get_locale(), 0, 2))),
                'header_title' => _go('header_heading') ? sprintf('<h5 class="uppercase text-white">%s</h5>',
                    _go('header_heading')) : '',
                'header_content' => _go('header_info') ? sprintf('<p>%s</p>', _go('header_info')) : ''
            );

            // Hide main header
            $page_content = get_queried_object();
            $page_content = !empty($page_content->post_content) ? $page_content->post_content : '';

            if (!empty($page_content) && has_shortcode($page_content, 'tt_header')) {
                return;
            }

            $nav_option = strtolower(_go('navigation'));

            switch ($nav_option) {
                case 'regular':
                    $nav_settings = '';
                    break;
                case 'sticky':
                    $nav_settings = 'data-sticky="true" data-nav-scroll="true"';
                    break;
                case 'sticky hide on scroll':
                    $nav_settings = 'data-sticky="true"';
                    break;
                default:
                    $nav_settings = 'data-sticky="true"';
                    break;
            }

            $defaults['nav_settings'] = $nav_settings;

            $this->tt_view('header', $defaults);
        }

        /**
         *   Global social networks
         */

        public function global_socials()
        {
            $networks = array(
                'social-facebook' => 'facebook',
                'social-twitter' => 'twitter',
                'social-linkedin' => 'linkedin',
                'social-rss' => 'rss',
                'social-dribbble-outline' => 'dribbble',
                'social-googleplus' => 'google'
            );

            $social_buttons = '';

            foreach ($networks as $icon => $item) {
                $network = _go('social_platforms_' . $item);
                if (!empty($network)) {
                    $social_buttons .= sprintf('<li><a href="%s"><i data-icon="%s"
                    data-icon-size="24" data-icon-color="#ffffff"></i></a></li>',
                        $network, $icon);
                }
            }

            if (!empty($social_buttons)) {
                return sprintf('<ul class="social-links inline-list">%s</ul>', $social_buttons);
            }
        }

        /**
         *   Share social networks
         */

        public function social_share($url, $title, $thumb)
        {
            $share_options = _go('share_this');

            $share_defaults = array(
                'googleplus' => array(
                    'class' => 'social-googleplus',
                    'url' => 'https://plus.google.com/share?url=' . $url
                ),
                'facebook' => array(
                    'class' => 'social-facebook',
                    'url' => 'https://www.facebook.com/sharer/sharer.php?u=' . $url
                ),
                'twitter' => array(
                    'class' => 'social-twitter',
                    'url' => 'https://twitter.com/home?status=' . $title
                ),
                'pinterest' => array(
                    'class' => 'social-pinterest-outline',
                    'url' => 'https://pinterest.com/pin/create/button/?url=' . $url . '&media=' . $thumb
                ),
                'linkedin' => array(
                    'class' => 'social-linkedin-outline',
                    'url' => 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $title
                ),
            );

            if (!empty($share_options)) {
                $share_buttons = '';
                foreach ($share_options as $social_network) {
                    $meta = $share_defaults[$social_network];
                    $share_buttons .= sprintf('<li><a href="%s" target="_blank" class="bg-%s text-white">
                    <i data-icon="%s" data-icon-size="18" data-icon-color="#ffffff"></i> %s</a></li>',
                        $meta['url'], $social_network, $meta['class'], esc_html__('Share', 'cre8or'));
                }
                return sprintf('<ul class="inline-list align-center share-socials">%s</ul>', $share_buttons);
            }
        }

        /**
         *   Build content for different page instance
         */

        public function create_pages_contents()
        {
            global $post;

            if (is_home() || is_singular('post') || is_tag() || is_category() || is_archive() || is_search()) {

                $thumb_id = get_post_thumbnail_id($post->ID);
                $thumb_size = is_singular('post') ? 'full' : 'blog-thumb';
                $thumb_array = wp_get_attachment_image_src($thumb_id, $thumb_size, true);
                $alt_meta = get_post_meta($thumb_id, '_wp_attachment_image_alt', true);

                $post_format = get_post_format();

                switch ($post_format) {
                    case 'aside':
                        $post_format_icon = 'ios-compose-outline';
                        break;
                    case 'image':
                        $post_format_icon = 'ios-camera-outline';
                        break;
                    case 'video':
                        $post_format_icon = 'ios-film-outline';
                        break;
                    case 'quote':
                        $post_format_icon = 'ios-mic-outline';
                        break;
                    case 'link':
                        $post_format_icon = 'ios-world-outline';
                        break;
                    case 'gallery':
                        $post_format_icon = 'ios-keypad-outline';
                        break;
                    case 'audio':
                        $post_format_icon = 'ios-musical-notes';
                        break;

                    default:
                        $post_format_icon = null;
                        break;
                }

                $recent_post = '';

                if (is_singular('post')) {
                    $post_format_icon = '';
                    $category = get_the_category($post->ID);
                    $cat = array();
                    if (!empty($category)) {
                        foreach ($category as $key => $value) {
                            $cat[] = $value->term_id;
                        }
                        $category = implode(', ', $cat);
                    }
                    $args = array(
                        'posts_per_page' => 2,
                        'orderby' => 'post_date',
                        'category' => $category,
                        'order' => 'DESC',
                        'post_type' => 'post',
                        'post_status' => 'publish',
                        'exclude' => array($post->ID),
                        'suppress_filters' => true
                    );
                    $recent_post = get_posts($args);
                }
                $video_meta = '';
                if ($post_format === 'video') {
                    $video_meta = get_post_meta($post->ID, 'page_meta');
                    $video_meta = !empty($video_meta[0]['post_video']) ? $video_meta[0]['post_video'] : '';
                }

                $content = do_shortcode(apply_filters('the_content', get_the_content($post->ID)));
                $embeds = get_media_embedded_in_content($content);

                $defaults = array(
                    'post_thumbnail' => $this->lazy_img($thumb_array[0], $alt_meta, $thumb_array[1], $thumb_array[2]),
                    'post_share' => $this->social_share(get_the_permalink($post->ID),
                        get_the_title($post->ID), $thumb_array[0]),
                    'post_format' => $post_format,
                    'post_format_icon' => $post_format_icon,
                    'video_embed' => !empty($embeds[0]) ? $embeds[0] : '',
                    'no_thumb' => !has_post_thumbnail($post->ID) ? 'no-thumbnail' : '',
                    'video_post' => $video_meta,
                    'recent_post' => $recent_post
                );

                $format = get_post_format();

                $posts_format = !empty($format) ? '-' . $format : '';

                $this->tt_view('blog-loop', $defaults);
            } else {
                echo '<section class="box"><div class="container">';
                the_content();
                echo '</div></section>';
            }
        }

        /**
         * Build content for portfolio page
         * @return portfolio view
         */
        function portfolio_content()
        {
            $defaults = array();
            $this->tt_view('portfolio', $defaults);
        }

        /**
         *   Get all kind of posts
         */

        public function tt_get_post($post_type = 'post', $config = false)
        {
            $args = array(
                'posts_per_page' => -1,
                'offset' => 0,
                'category' => '',
                'category_name' => '',
                'orderby' => 'post_date',
                'order' => 'DESC',
                'include' => '',
                'exclude' => '',
                'meta_key' => '',
                'meta_value' => '',
                'post_type' => $post_type,
                'post_mime_type' => '',
                'post_parent' => '',
                'post_status' => 'publish',
                'suppress_filters' => true
            );

            if (!empty($config)) {
                $args = wp_parse_args($config, $args);
            }


            return get_posts($args);
        }

        /**
         *   Before page hook
         */

        public function before_pages($filter = null)
        {
            global $post;
            $page_portfolio = (int)get_option('page_for_portfolio');
            if ($post->ID === $page_portfolio) {
                echo '<section class="box"><div class="container">';

                echo !empty($filter) ? $filter : '';

                echo '<ul class="portfolio-items clean-list" data-grid="li">';
            } else {
                if (is_home() || is_singular('post') || is_tag() || is_category() || is_archive() || is_search()) {
                    if (is_home() || is_singular('post')) {
                        $meta_post_cover = get_post_meta($post->ID, 'page_meta');
                        $meta_post_cover = !empty($meta_post_cover[0]['cover_image'])
                            ? $meta_post_cover[0]['cover_image'] : '';
                        $post_page_id = is_home() ? get_post_thumbnail_id(get_option('page_for_posts'))
                            : $meta_post_cover;
                        $post_page_thumb = !empty($post_page_id) ? wp_get_attachment_url($post_page_id) : '';
                        $post_page_thumb = !empty($post_page_thumb)
                            ? sprintf('data-cover-box="%s"', $post_page_thumb) : '';
                        printf('<section class="box" %s><div class="container"><div class="row">', $post_page_thumb);
                    } else {
                        echo '<section class="box"><div class="container"><div class="row">';
                    }

                    if (is_active_sidebar('sidebar-1') && is_active_sidebar('sidebar-2')) {
                        echo '<div class="col-md-4"><aside class="main-sidebar" data-sticky-sidebar="true">';
                        dynamic_sidebar('sidebar-2');
                        echo '</aside></div>';
                        echo '<div class="col-md-4">';

                    } else if (is_active_sidebar('sidebar-2')) {
                        echo '<div class="col-md-4"><aside class="main-sidebar" data-sticky-sidebar="true">';
                        dynamic_sidebar('sidebar-2');
                        echo '</aside></div>';
                        echo '<div class="col-md-8">';
                    } else if (is_active_sidebar('sidebar-1')) {
                        echo '<div class="col-md-8">';
                    } else {
                        echo '<div class="col-md-10 col-md-offset-1">';
                    }
                }
            }

        }

        /**
         *   After page hook
         */

        public function after_pages()
        {
            global $post;
            $page_portfolio = (int)get_option('page_for_portfolio');
            if ($post->ID === $page_portfolio) {
                echo '</ul></div></section>';
            } else {
                if (is_home() || is_singular('post') || is_tag() || is_category() || is_archive() || is_search()) {
                    echo balanceTags($this->pagination_links());

                    if (is_singular('post')) {
                        comments_template();
                    }

                    echo '</div>';

                    if (is_active_sidebar('sidebar-1')) {
                        echo '<div class="col-md-4"><aside class="main-sidebar" data-sticky-sidebar="true">';
                        dynamic_sidebar('sidebar-1');
                        echo '</aside></div>';
                    }

                    echo '</div></div></section>';

                }
            }

            if (is_page() && (comments_open() || pings_open())) {
                echo '<div class="container"><div class="row"><div class="col-md-8 col-md-offset-2">';
                comments_template();
                echo '</div></div></div>';
            }
        }

        /**
         *   Build read more link
         */

        public function read_more_blog()
        {
            return sprintf('<a href="%s" class="button-md bg-black align-center read-more">%s</a>',
                get_permalink(), esc_html__('Read more', 'cre8or'));
        }

        function read_more_link_excerpt($content)
        {
            return $content . sprintf('<a href="%s" class="button-md bg-black align-center read-more">%s</a>', get_permalink(), esc_html__('Read more', 'cre8or'));
        }

        /**
         *   Build pagination links
         */

        public function pagination_links()
        {
            $paged = get_query_var('paged') ? intval(get_query_var('paged')) : 1;
            $pagenum_link = html_entity_decode(get_pagenum_link());
            $query_args = array();
            $url_parts = explode('?', $pagenum_link);

            if (isset($url_parts[1])) {
                wp_parse_str($url_parts[1], $query_args);
            }

            $pagenum_link = remove_query_arg(array_keys($query_args), $pagenum_link);
            $pagenum_link = trailingslashit($pagenum_link) . '%_%';

            $format = $GLOBALS['wp_rewrite']->using_index_permalinks() && !strpos($pagenum_link, 'index.php')
                ? 'index.php/' : '';
            $format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit('page/%#%', 'paged')
                : '?paged=%#%';

            // Set up paginated links.
            $links = paginate_links(array(
                'base' => $pagenum_link,
                'format' => $format,
                'total' => $GLOBALS['wp_query']->max_num_pages,
                'current' => $paged,
                'mid_size' => 1,
                'add_args' => array_map('urlencode', $query_args),
                'prev_text' => '',
                'next_text' => '',
                'type' => 'array',
            ));

            $pagination_pat = '<div class="row"><div class="col-md-12">
            <ul class="pagination-links inline-list align-center"><li>%s</li></ul></div></div>';

            return !empty($links) ? sprintf($pagination_pat, join("</li>\n\t<li>", $links)) : '';
        }

        /**
         *   Comments pagination
         */

        public function comments_pagination()
        {

            $default = array(
                'echo' => false,
                'prev_text' => '',
                'next_text' => ''
            );

            $pag_links = trim(preg_replace('/\s+/', ' ', paginate_comments_links($default)));

            if ($pag_links) {
                $pag_links = str_replace('</a>', '</a></li><li>', $pag_links);
                $pag_links = str_replace('> <span', '', $pag_links);
                $pag_links = str_replace("current'>", "current bg-alpha'><span>", $pag_links);
                $pag_links = str_replace('</span>', '</span></li><li>', $pag_links);
                $pag_links = sprintf('<li>%s</li>', $pag_links);
                $pag_links = str_replace('<li></li>', '', $pag_links);

                echo sprintf('<div class="row"><div class="col-md-12">
                    <ul class="inline-list align-right pagination-links comments-pagination">%s</ul></div></div>',
                    $pag_links);
            }
        }

        /**
         *   Generate sidebars
         */

        public function sidebar_init()
        {
            register_sidebar(array(
                'name' => esc_html__('Main left sidebar', 'cre8or'),
                'id' => 'sidebar-1',
                'description' => esc_html__('Use this sidebar for left side widgets', 'cre8or'),
                'before_widget' => '<div id="%1$s" class="widget bg-white %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h4 class="widget-title">',
                'after_title' => '</h4>',
            ));

            register_sidebar(array(
                'name' => esc_html__('Main right sidebar', 'cre8or'),
                'id' => 'sidebar-2',
                'description' => esc_html__('Use this sidebar for left side widgets', 'cre8or'),
                'before_widget' => '<div id="%1$s" class="widget col-md-12 col-sm-6 %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h5 class="widget-title">',
                'after_title' => '</h5>',
            ));

            register_sidebar(array(
                'name' => esc_html__('Page optional sidebar', 'cre8or'),
                'id' => 'sidebar-4',
                'description' => esc_html__('Use this sidebar only with Visual Composer', 'cre8or'),
                'before_widget' => '<div id="%1$s" class="widget page-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<h5 class="widget-title">',
                'after_title' => '</h5>',
            ));
        }

        /**
         *   Enable Visual Composer
         */

        public function start_visual_composer()
        {
            if (class_exists('Vc_Manager')) {
                vc_set_shortcodes_templates_dir(TEMPLATEPATH . '/templates/shortcodes');
                include_once(TEMPLATEPATH . '/theme_config/shortcodes.php');
                include_once(TEMPLATEPATH . '/theme_config/tt-map.php');
            }
        }

        /**
         *   Detect shortcode
         */

        public function detect_shortcode($shortcode = null, $content = null)
        {
            $pattern = get_shortcode_regex();
            if (preg_match_all('/' . $pattern . '/s', $content, $matches)
                && array_key_exists(2, $matches)
                && in_array($shortcode, $matches[2])
            ) {
                return true;
            }
        }

        /**
         *   Return shortcode from content
         */

        public function get_content_shortcode($shortcode, $content)
        {
            $pattern = get_shortcode_regex();
            preg_match_all('/' . $pattern . '/s', $content, $matches);

            return $matches;
        }

        /**
         *   Get custom meta box
         */

        public function get_custom_meta($meta)
        {
            global $post;

            if (is_front_page()) {
                $id = get_option('page_on_front');
            } else if (is_home() || is_singular('post')) {
                $id = get_option('page_for_posts');
                ($id === '0') ? $id = $post->ID : '';
            } else if (isset($post->ID)) {
                $id = $post->ID;
            } else {
                return;
            }

            $custom_meta = get_post_meta($id, $meta);
            if (!empty($custom_meta)) return (object)$custom_meta[0];
        }

        /**
         *   Build search form
         */

        public function filter_search_form($form)
        {
            $form = '<form role="search" method="get" action="%s" class="full-inputs widget-search">
                    <p>
                        <input type="hidden" value="post" name="post_type" id="post_type" />
                        <input type="search" name="s" value="%s" placeholder="%s">
                        <button type="submit" class="strip-input">
                            <i data-icon="ios-search-strong" data-icon-size="18"></i>
                        </button>
                    </p>
                </form>';
            return sprintf($form, esc_url(home_url('/')), get_search_query(), esc_html__('Search blog', 'cre8or'));
        }

        /**
         *   Build comments feed
         */

        public function comments()
        {
            $args = array(
                'walker' => null,
                'max_depth' => '',
                'style' => 'ul',
                'callback' => array($this, 'comments_callback'),
                'end-callback' => null,
                'type' => 'all',
                'reply_text' => esc_html__('Reply', 'cre8or'),
                'page' => '',
                'per_page' => '',
                'avatar_size' => 60,
                'reverse_top_level' => null,
                'reverse_children' => '',
                'format' => 'html5', //or html5 @since 3.6
                'short_ping' => true // @since 3.6
            );

            echo '<ul class="clean-list comments-loop">';
            wp_list_comments($args);
            echo '</ul>';
        }

        /**
         *   Comments callbeack
         */

        public function comments_callback($comment, $args, $depth)
        {
            $GLOBALS['comment'] = $comment;
            extract($args, EXTR_SKIP);

            if ('div' == $args['style']) {
                $tag = 'div';
                $add_below = 'comment';
            } else {
                $tag = 'li';
                $add_below = 'div-comment';
            }

            $reply_url = get_comment_reply_link(array_merge($args,
                array(
                    'add_below' => $add_below,
                    'depth' => $depth,
                    'max_depth' => $args['max_depth']
                )));

            echo sprintf('<%s id="comment-%s" class="%s">', $tag, get_comment_ID(), '');
            echo '<div id="div-comment-' . get_comment_ID() . '" class="comment-post clearfix">';
            echo sprintf('<figure>%s</figure>', get_avatar($comment, $args['avatar_size'], false, 'avatar'));
            echo '<div class="comment-content">';
            if ($comment->comment_approved == '0') {
                esc_html_e('Your comment is awaiting moderation', 'cre8or');
            }
            echo !empty($reply_url) ? $reply_url : '';
            echo get_comment_author_url() ? sprintf('<h4><a href="%s" rel="external nofollow">%s</a></h4>', get_comment_author_url(), get_comment_author())
                : sprintf('<h4>%s</h4>', get_comment_author());
            echo sprintf('<span>%s, %s</span>', get_comment_date(), get_comment_time());
            comment_text();
            echo '</div></div>';
        }

        /**
         *   Contact form
         */
        public function tt_contact_form()
        {
            $receiver_mail = is_email(urldecode($_POST['receiver']))
                ? urldecode($_POST['receiver']) : get_option('admin_email');
            $mail_title = '[' . get_bloginfo('name') . '] от ';
            $mail_title .= (!empty($_POST['phone']))
                ? $_POST['name'] . ' ' . $_POST['phone'] : ' ';

            /* SECTION II - CODE */

            if (!empty($_POST['name']) && !empty($_POST ['email']) && !empty($_POST ['message'])) {
                $email = $_POST['email'];
                $message = $_POST['message'];
                $message = wordwrap($message, 70, "\r\n");
                $subject = $mail_title;

                $header = array(
                    esc_html__('From: ', 'cre8or') . $_POST['name'] . " <$email>",
                    esc_html__('Reply-To: ', 'cre8or') . $email
                );

                if (wp_mail($receiver_mail, $subject, $message, $header))
                    $result = esc_html__('Message successfully sent.', 'cre8or');
                else
                    $result = esc_html__('Message could not be sent.', 'cre8or');
            } else {
                $result = esc_html__('Please fill all the fields in the form.', 'cre8or');
            }
            die($result);
        }

        /**
         *   Comment form
         */
        public function comment_form()
        {
            $user = wp_get_current_user();
            $user_identity = $user->exists() ? $user->display_name : '';

            $fields = array(
                'author' => '<p>
                                    <input class="input_line" id="author" name="author" type="text">
                                    <span class="uppercase font-beta text-beta">' . esc_html__('Name', 'cre8or') . '</span>
                                </p>',

                'email' => '<p>
                                    <input class="input_line" id="email" name="email" type="text">
                                    <span class="uppercase font-beta text-beta">' . esc_html__('E-mail', 'cre8or') . '</span>
                                </p>',

                'url' => '<p>
                                    <input class="input_line" id="url" name="url" type="text">
                                    <span class="uppercase font-beta text-beta">' . esc_html__('URL', 'cre8or') . '</span>
                                </p>'
            );

            $comment_field = '<p>
                <textarea id="comment" class="input_area" aria-required="true" name="comment"></textarea>
                <span class="uppercase font-beta text-beta">' . esc_html__('Message', 'cre8or') . '</span>
            </p>
            <p>
                <button class="button-md button-outlined text-black align-right uppercase">'
                . esc_html__('Post comment', 'cre8or') . '</button>
            </p>';

            $must_log_in = '<p class="logged-in-as">'
                . sprintf(
                    wp_kses(__('Logged in as <a href="%1$s">%2$s</a>.
                                <a href="%3$s" title="Log out of this account">Log out?</a>', 'cre8or'),
                        array(
                            'a' => array(
                                'href' => array(),
                                'title' => array()
                            )
                        )),
                    get_edit_user_link(),
                    $user_identity,
                    wp_logout_url()) . '</p>';

            $logged_in_as = '';

            $comment_notes_before = '<p class="comment-notes col-md-12">'
                . esc_html__('If you want to share your opinion, leave a comment.', 'cre8or')
                . '</p>';

            $comment_notes_before = '';

            $comment_notes_after = '<div class="form-allowed-tags col-md-12"><p>'
                . wp_kses(__('You may use these
                                        <abbr title="HyperText Markup Language">HTML</abbr>
                                        tags and attributes:', 'cre8or'),
                    array('abbr' => array('title' => array()))) .
                ' </p><pre>' . allowed_tags() . '</pre>' .
                '</div>';

            $comment_notes_after = '';

            $id_form = 'commentform';
            $id_submit = 'comment-submit';
            $title_reply = esc_html__('Leave a comment', 'cre8or');
            $title_reply_to = esc_html__('Leave a comment to %s', 'cre8or');
            $cancel_reply_link = esc_html__('Cancel reply', 'cre8or');
            $label_submit = 'a';
            $format = 'html5';

            $args = array(
                'fields' => apply_filters('comment_form_default_fields', $fields),
                'comment_field' => $comment_field,
                'must_log_in' => $must_log_in,
                'logged_in_as' => $logged_in_as,
                'comment_notes_before' => $comment_notes_before,
                'comment_notes_after' => $comment_notes_after,
                'id_form' => $id_form,
                'id_submit' => $id_submit,
                'title_reply' => $title_reply,
                'title_reply_to' => $title_reply_to,
                'cancel_reply_link' => $cancel_reply_link,
                'label_submit' => $label_submit,
                'format' => $format
            );

            ob_start();
            comment_form($args);
            $form = str_replace('class="comment-form"', 'class="contact-form slim-form"', ob_get_clean());
            echo balanceTags($form);
        }

        function add_admin_settings()
        {
            if (post_type_exists('portfolio')) {
                add_settings_field(
                    'page_for_portfolio',
                    '',
                    array($this, 'portfolio_page_switcher'),
                    'reading'
                );
                register_setting('reading', 'page_for_portfolio');
            }
        }

        function portfolio_page_switcher()
        {
            echo "<li class='portfolio-switcher'><label for='page_for_portfolio'>"
                . sprintf(esc_html__('Portfolio page: %s'),
                    wp_dropdown_pages(
                        array(
                            'name' => 'page_for_portfolio',
                            'echo' => 0,
                            'show_option_none' => esc_html__('&mdash; Select &mdash;'),
                            'option_none_value' => '0',
                            'selected' => get_option('page_for_portfolio')
                        )))
                . "</label></li>";
        }

        function get_filters($posts = null, $post_id = null)
        {
            if (!empty($posts)) {
                $categories = array();
                $filters = array();
                foreach ($posts as $key => $post) {
                    $categories[$post->ID] = wp_get_post_terms($post->ID, 'portfolio_tax');
                    if (empty($categories[$post->ID]->errors) && !empty($categories[$post->ID])) {
                        foreach ($categories[$post->ID] as $filter) {
                            if ($filter->term_id) {
                                $filters[$filter->term_id] = !empty($filter->name) ? $filter->name : '';
                            }
                        }
                    }
                }

                if (!empty($filters)) {
                    $items = '';
                    $pattern = '<div class="align-center uppercase">
                    <ul class="portfolio-filters inline-list">
                        <li class="filter-active"><a href="#" cat="0">%s</a></li>
                        %s
                    </ul></div>';
                    foreach ($filters as $key => $filter) {
                        $items .= sprintf('<li><a href="#" cat="%s">%s</a></li>', $key, $filter);
                    }

                    return sprintf($pattern, esc_html__('All', 'cre8or'), $items);
                }
            }

            if (!empty($post_id)) {
                $filters = array();
                $categories = wp_get_post_terms($post_id, 'portfolio_tax');

                if (empty($categories->errors) && !empty($categories)) {
                    foreach ($categories as $key => $filter) {
                        $filters[$filter->term_id] = $filter->term_id;
                    }
                    return strtolower(implode(' ', $filters));
                }
            }
        }

        function single_portfolio($id)
        {
            if (is_string(get_post_status($id))) {
                $meta = get_post_meta($id, 'slide_options');
                $meta = !empty($meta) ? $meta[0] : null;
                $portfolio_type = !empty($meta['portfolio_type']) ? $meta['portfolio_type'] : 'one';
                $portfolio_images = !empty($meta['images']) ? $meta['images'] : array();
                $social_items = array();
                $portfolio_meta = array();

                if (!empty($meta['portfolio_social'])) {
                    foreach ($meta['portfolio_social'] as $key => $value) {
                        $social_items[] = $value['social_network'];
                    }
                }

                if (!empty($meta['portfolio_meta'])) {
                    foreach ($meta['portfolio_meta'] as $key => $value) {
                        if (!empty($value['meta_title']) && !empty($value['meta_value'])) {
                            $portfolio_meta[$value['meta_title']] = $value['meta_value'];
                        }
                    }
                }

                $settings = array(
                    'id' => $id,
                    'images' => $portfolio_images,
                    'meta' => $portfolio_meta,
                    'socials' => $social_items,
                    'theme' => $this,
                    'prev_post' => get_previous_post(),
                    'next_post' => get_next_post()
                );

                $this->tt_view('single-' . $portfolio_type, $settings, 'portfolio');
            }
        }

        function multiple_portfolio($id)
        {
            if (is_string(get_post_status($id))) {
                $meta = get_post_meta($id, 'slide_options');
                $meta = !empty($meta) ? $meta[0] : null;
                $portfolio_type = !empty($meta['portfolio_type']) ? $meta['portfolio_type'] : 'one';
                $portfolio_images = !empty($meta['images']) ? $meta['images'] : array();
                $social_items = array();
                $portfolio_meta = array();

                if (!empty($meta['portfolio_social'])) {
                    foreach ($meta['portfolio_social'] as $key => $value) {
                        $social_items[] = $value['social_network'];
                    }
                }

                if (!empty($meta['portfolio_meta'])) {
                    foreach ($meta['portfolio_meta'] as $key => $value) {
                        if (!empty($value['meta_title']) && !empty($value['meta_value'])) {
                            $portfolio_meta[$value['meta_title']] = $value['meta_value'];
                        }
                    }
                }

                $settings = array(
                    'id' => $id,
                    'images' => $portfolio_images,
                    'meta' => $portfolio_meta,
                    'socials' => $social_items,
                    'theme' => $this,
                    'prev_post' => get_previous_post(),
                    'next_post' => get_next_post()
                );
            }
        }

        function share_helper($network, $id, $share_buttons = false)
        {

            $social_settings = array();

            switch ($network) {
                case 'googleplus':
                    $social_settings['social-googleplus'] = 'https://plus.google.com/share?url='
                        . get_the_permalink($id);
                    break;
                case 'facebook':
                    $social_settings['social-facebook'] = 'https://www.facebook.com/sharer/sharer.php?u='
                        . get_the_permalink($id);
                    break;
                case 'twitter':
                    $social_settings['social-twitter'] = 'https://twitter.com/home?status='
                        . get_the_title($id);
                    break;
                case 'pinterest':
                    $social_settings['social-pinterest-outline'] = 'https://pinterest.com/pin/create/button/?url='
                        . get_the_permalink($id) . '&media=' . wp_get_attachment_url(get_post_thumbnail_id($id));
                    break;
                case 'linkedin':
                    $social_settings['social-linkedin-outline'] = 'https://www.linkedin.com/shareArticle?mini=true&url=' . get_the_permalink($id) . '&title=' . get_the_title($id);
                    break;
            }

            if ($share_buttons) {
                $buttons_text = $network === 'twitter' ? esc_html__('Tweet', 'cre8or') : esc_html__('Share', 'cre8or');
                return sprintf('<li><a href="%s" target="_blank" class="bg-%s text-white">
                <i data-icon="%s" data-icon-size="18" data-icon-color="#ffffff"></i> %s</a></li>',
                    reset($social_settings), $network, key($social_settings), $buttons_text);
            }


            if (!empty($social_settings)) {
                return sprintf('<li><a href="%s" target="_blank">
                <i data-icon="%s" data-icon-size="24" data-icon-color="#757575"></i></a></li>',
                    reset($social_settings), key($social_settings));
            }

        }

        function disable_editor_portfolio()
        {
            $current_id = !empty($_GET['post']) ? $_GET['post'] : '';
            if (empty($current_id)) {
                return;
            }
            $portfolio_id = get_option('page_for_portfolio');

            if (!empty($portfolio_id) && $current_id === $portfolio_id) {
                remove_post_type_support('page', 'editor');
            }
        }

        function add_portfolio_label($content, $post)
        {
            if ($post->ID === (int)get_option('page_for_portfolio')) {
                return array('page_for_portfolio' => esc_html__('Portfolio Archive', 'cre8or'));
            } else {
                return $content;
            }
        }

        function primary_color($default_color, $return = false)
        {
            if ($return === true) {
                return esc_attr(_go('primary_color') ? _go('primary_color') : $default_color);
            } else {
                echo esc_attr(_go('primary_color') ? _go('primary_color') : $default_color);
            }
        }

    }
}

global $tt_theme;
$tt_theme = new Cre8or();

function tt_primary($primary)
{
    global $tt_theme;
    $tt_theme->primary_color($primary);
}

function my_login_logo()
{
    echo '
   <style type="text/css">
        #login h1 a { background: url(' . get_bloginfo('template_directory') . '/images/logo.png) no-repeat 0 0 !important; }
    </style>';
}

add_action('login_head', 'my_login_logo');

function my_admin_logo()
{
    echo '
    <style type="text/css">
        #header-logo { background:url(' . get_bloginfo('template_directory') . '/images/logo.png) no-repeat 0 0 !important; }
    </style>';
}

add_action('admin_head', 'my_admin_logo');

function wp_head_product_meta()
{

    if (is_woocommerce() && is_product()) {
        global $product;
        $_product = wc_get_product(false, array('post_name' => $product));

        $categ = $_product->get_categories();
        $term = get_term_by('name', strip_tags(stristr($categ, ",", true) ? stristr($categ, ",", true) : $categ), 'product_cat');

        echo '<meta name="description" itemprop="description" content="' . str_replace("\"", "'", $_product->post->post_title) . '. ' . $term->description . '">';
        echo '<meta property="og:description" content="' . $term->description . '">';
    }

    if (has_post_thumbnail()) {
        echo '<meta property="og:image" content="' . wp_get_attachment_url(get_post_thumbnail_id()) . '" />';
    } else echo '<meta property="og:image" content="http://samonenkoart.com/wp-content/uploads/2016/03/samonenko_art_logo_pack_transparent_02.png" />';

    echo '<script defer src="https://vk.com/js/api/openapi.js?136" type="text/javascript"></script>';

    global $aiosp;
    echo '<meta property="og:title" content="'. ($aiosp->get_aioseop_title(null) != "" ? $aiosp->get_aioseop_title(null) : get_the_title()) .'"/>';
}

remove_action('wp_head', 'wp_generator');
add_action("wp_head", "wp_head_product_meta");

remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);

/*
 * "Хлебные крошки" для WordPress
 * автор: Dimox
 * версия: 2015.09.14
 * лицензия: MIT
*/
function dimox_breadcrumbs() {
    /* === ОПЦИИ === */
    $pos = 1;
    $text['home'] = 'Главная'; // текст ссылки "Главная"
    $text['category'] = 'Архив рубрики "%s"'; // текст для страницы рубрики
    $text['search'] = 'Результаты поиска по запросу "%s"'; // текст для страницы с результатами поиска
    $text['tag'] = 'Записи с тегом "%s"'; // текст для страницы тега
    $text['author'] = 'Статьи автора %s'; // текст для страницы автора
    $text['404'] = 'Ошибка 404'; // текст для страницы 404
    $text['page'] = 'Страница %s'; // текст 'Страница N'
    $text['cpage'] = 'Страница комментариев %s'; // текст 'Страница комментариев N'

    $wrap_before = '<nav itemscope itemtype="http://schema.org/BreadcrumbList" class="breadcrumb" >'; // открывающий тег обертки
    $wrap_after = '</nav><!-- .breadcrumbs -->'; // закрывающий тег обертки
    $sep = '/'; // разделитель между "крошками"
    $sep_before = '<span class="sep">'; // тег перед разделителем
    $sep_after = '</span>'; // тег после разделителя
    $show_home_link = 1; // 1 - показывать ссылку "Главная", 0 - не показывать
    $show_on_home = 0; // 1 - показывать "хлебные крошки" на главной странице, 0 - не показывать
    $show_current = 1; // 1 - показывать название текущей страницы, 0 - не показывать
    $before = '<div itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">'; // тег перед текущей "крошкой"
    $after = '</div>'; // тег после текущей "крошки"
    /* === КОНЕЦ ОПЦИЙ === */

    global $post;
    $home_link = home_url('/');
    $link_before = '<div itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
    $link_after = '</div>';
    $link_attr = ' itemprop="item"';
    $link_in_before = '';
    $link_in_after = '<meta itemprop="name" content="%2$s"><meta itemprop="position" content="%3$s">';
    $link = $link_before . '<a href="%1$s"' . $link_attr . '>' . $link_in_before . '%2$s' . $link_in_after . '</a>' . $link_after;
    $current_link = $link_in_before . '%1$s' . $link_in_after;
    $frontpage_id = get_option('page_on_front');
    $parent_id = $post->post_parent;
    $sep = ' ' . $sep_before . $sep . $sep_after . ' ';

    if (is_home() || is_front_page()) {

        if ($show_on_home) echo $wrap_before . '<a href="' . $home_link . '">' . $text['home'] . '</a>' . $wrap_after;

    } else {

        echo $wrap_before;
        if ($show_home_link) echo sprintf($link, $home_link, $text['home'], $pos++);

        if ( is_category() ) {
            $cat = get_category(get_query_var('cat'), false);
            if ($cat->parent != 0) {
                $cats = get_category_parents($cat->parent, TRUE, $sep);
                $cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
                $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
                if ($show_home_link) echo $sep;
                echo $cats;
            }
            if ( get_query_var('paged') ) {
                $cat = $cat->cat_ID;
                echo $sep . sprintf($link, get_category_link($cat), get_cat_name($cat)) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
            } else {
                if ($show_current) echo $sep . $before . sprintf($text['category'], single_cat_title('', false)) . $after;
            }

        } elseif ( is_search() ) {
            if (have_posts()) {
                if ($show_home_link && $show_current) echo $sep;
                if ($show_current) echo $before . sprintf($text['search'], get_search_query()) . $after;
            } else {
                if ($show_home_link) echo $sep;
                echo $before . sprintf($text['search'], get_search_query()) . $after;
            }

        } elseif ( is_day() ) {
            if ($show_home_link) echo $sep;
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $sep;
            echo sprintf($link, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F'));
            if ($show_current) echo $sep . $before . get_the_time('d') . $after;

        } elseif ( is_month() ) {
            if ($show_home_link) echo $sep;
            echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y'));
            if ($show_current) echo $sep . $before . get_the_time('F') . $after;

        } elseif ( is_year() ) {
            if ($show_home_link && $show_current) echo $sep;
            if ($show_current) echo $before . get_the_time('Y') . $after;

        } elseif ( is_single() && !is_attachment() ) {
            if ($show_home_link) echo $sep;
            if ( get_post_type() != 'post' ) {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                printf($link, $home_link . $slug['slug'] . '/', str_replace('Portfolio', 'Портфолио', $post_type->labels->singular_name), $pos++);
                if ($show_current) {
                    echo $sep . $before . get_the_title();
                    echo sprintf('<meta itemprop="name" content="%1$s"><meta itemprop="position" content="%2$s">', get_the_title(), $pos++);
                    echo $after;
                }
            } else {
                $cat = get_the_category(); $cat = $cat[0];
                $cats = get_category_parents($cat, TRUE, $sep);
                if (!$show_current || get_query_var('cpage')) $cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
                $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
                echo $cats;
                if ( get_query_var('cpage') ) {
                    echo $sep . sprintf($link, get_permalink(), get_the_title()) . $sep . $before . sprintf($text['cpage'], get_query_var('cpage')) . $after;
                } else {
                    if ($show_current) echo $before . get_the_title() . $after;
                }
            }

            // custom post type
        } elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
            $post_type = get_post_type_object(get_post_type());
            if ( get_query_var('paged') ) {
                echo $sep . sprintf($link, get_post_type_archive_link($post_type->name), $post_type->label) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
            } else {
                if ($show_current) echo $sep . $before . $post_type->label . $after;
            }

        } elseif ( is_attachment() ) {
            if ($show_home_link) echo $sep;
            $parent = get_post($parent_id);
            $cat = get_the_category($parent->ID); $cat = $cat[0];
            if ($cat) {
                $cats = get_category_parents($cat, TRUE, $sep);
                $cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . $link_attr .'>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
                echo $cats;
            }
            printf($link, get_permalink($parent), $parent->post_title);
            if ($show_current) echo $sep . $before . get_the_title() . $after;

        } elseif ( is_page() && !$parent_id ) {
            if ($show_current) echo $sep . $before . sprintf($current_link, get_the_title(), get_the_title(), $pos++) . $after;

        } elseif ( is_page() && $parent_id ) {
            if ($show_home_link) echo $sep;
            if ($parent_id != $frontpage_id) {
                $breadcrumbs = array();
                while ($parent_id) {
                    $page = get_page($parent_id);
                    if ($parent_id != $frontpage_id) {
                        $breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
                    }
                    $parent_id = $page->post_parent;
                }
                $breadcrumbs = array_reverse($breadcrumbs);
                for ($i = 0; $i < count($breadcrumbs); $i++) {
                    echo $breadcrumbs[$i];
                    if ($i != count($breadcrumbs)-1) echo $sep;
                }
            }
            if ($show_current) echo $sep . $before . get_the_title() . $after;

        } elseif ( is_tag() ) {
            if ( get_query_var('paged') ) {
                $tag_id = get_queried_object_id();
                $tag = get_tag($tag_id);
                echo $sep . sprintf($link, get_tag_link($tag_id), $tag->name) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
            } else {
                if ($show_current) echo $sep . $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
            }

        } elseif ( is_author() ) {
            global $author;
            $author = get_userdata($author);
            if ( get_query_var('paged') ) {
                if ($show_home_link) echo $sep;
                echo sprintf($link, get_author_posts_url($author->ID), $author->display_name) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
            } else {
                if ($show_home_link && $show_current) echo $sep;
                if ($show_current) echo $before . sprintf($text['author'], $author->display_name) . $after;
            }

        } elseif ( is_404() ) {
            if ($show_home_link && $show_current) echo $sep;
            if ($show_current) echo $before . $text['404'] . $after;

        } elseif ( has_post_format() && !is_singular() ) {
            if ($show_home_link) echo $sep;
            echo get_post_format_string( get_post_format() );
        }

        echo $wrap_after;

    }
} // end of dimox_breadcrumbs()

// Add term page
function tutorialshares_taxonomy_add_new_meta_field() {
    // this will add the custom meta field to the add new term page
    ?>
    <div class="form-field">
        <label for="term_meta[custom_title]"><?php _e( 'Custom Title', 'tutorialshares' ); ?></label>
        <input type="text" name="term_meta[custom_title]" id="term_meta[custom_title]" value="">
        <p class="description"><?php _e( 'Enter a value for this field','tutorialshares' ); ?></p>
    </div>
    <?php
}
add_action( 'product_cat_add_form_fields', 'tutorialshares_taxonomy_add_new_meta_field', 10, 2 );


// Edit term page
function tutorialshares_taxonomy_edit_meta_field($term) {

    // put the term ID into a variable
    $t_id = $term->term_id;

    // retrieve the existing value(s) for this meta field. This returns an array
    $term_meta = get_option( "taxonomy_$t_id" ); ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="term_meta[custom_title]"><?php _e( 'Custom Title', 'tutorialshares' ); ?></label></th>
        <td>
            <input type="text" name="term_meta[custom_title]" id="term_meta[custom_title]" value="<?php echo esc_attr( $term_meta['custom_title'] ) ? esc_attr( $term_meta['custom_title'] ) : ''; ?>">
            <p class="description"><?php _e( 'Enter a value for this field','tutorialshares' ); ?></p>
        </td>
    </tr>
    <?php
}
add_action( 'product_cat_edit_form_fields', 'tutorialshares_taxonomy_edit_meta_field', 10, 2 );

// Save extra taxonomy fields callback function.
function save_taxonomy_custom_meta( $term_id ) {
    if ( isset( $_POST['term_meta'] ) ) {
        $t_id = $term_id;
        $term_meta = get_option( "taxonomy_$t_id" );
        $cat_keys = array_keys( $_POST['term_meta'] );
        foreach ( $cat_keys as $key ) {
            if ( isset ( $_POST['term_meta'][$key] ) ) {
                $term_meta[$key] = $_POST['term_meta'][$key];
            }
        }
        // Save the option array.
        update_option( "taxonomy_$t_id", $term_meta );
    }
}
add_action( 'edited_product_cat', 'save_taxonomy_custom_meta', 10, 2 );
add_action( 'create_product_cat', 'save_taxonomy_custom_meta', 10, 2 );
