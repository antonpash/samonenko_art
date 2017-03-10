<?php

function tt_icons() {
	$icons = array(
			array(
				'type' => 'dropdown',
				'heading' => __( 'Icon library', 'js_composer' ),
				'value' => array(
					esc_html__( 'Font Awesome', 'js_composer' ) => 'fontawesome',
					esc_html__( 'Open Iconic', 'js_composer' ) 	=> 'openiconic',
					esc_html__( 'Typicons', 'js_composer' ) 	=> 'typicons',
					esc_html__( 'Entypo', 'js_composer' ) 		=> 'entypo',
					esc_html__( 'Linecons', 'js_composer' ) 	=> 'linecons',
					esc_html__( 'Ionicon', 'js_composer' ) 		=> 'ionicon'
				),
				'admin_label' 	=> true,
				'param_name' 	=> 'icon_type',
				'description' 	=> esc_html__( 'Select icon library.', 'js_composer' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'js_composer' ),
				'param_name' => 'icon_fontawesome',
				'value' => 'fa fa-adjust', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false,
					// default true, display an "EMPTY" icon?
					'iconsPerPage' => 4000,
					// default 100, how many icons per/page to display, we use (big number) to display all icons in single page
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'fontawesome',
				),
				'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'js_composer' ),
				'param_name' => 'icon_openiconic',
				'value' => 'vc-oi vc-oi-dial', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false, // default true, display an "EMPTY" icon?
					'type' => 'openiconic',
					'iconsPerPage' => 4000, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'openiconic',
				),
				'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'js_composer' ),
				'param_name' => 'icon_typicons',
				'value' => 'typcn typcn-adjust-brightness', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false, // default true, display an "EMPTY" icon?
					'type' => 'typicons',
					'iconsPerPage' => 4000, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'typicons',
				),
				'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'js_composer' ),
				'param_name' => 'icon_entypo',
				'value' => 'entypo-icon entypo-icon-note', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false, // default true, display an "EMPTY" icon?
					'type' => 'entypo',
					'iconsPerPage' => 4000, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'entypo',
				),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'js_composer' ),
				'param_name' => 'icon_linecons',
				'value' => 'vc_li vc_li-heart', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false, // default true, display an "EMPTY" icon?
					'type' => 'linecons',
					'iconsPerPage' => 4000, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'linecons',
				),
				'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
			),
			array(
				'type' => 'iconpicker',
				'heading' => esc_html__( 'Icon', 'js_composer' ),
				'param_name' => 'icon_ionicon',
				'value' => 'vc_li vc_li-heart', // default value to backend editor admin_label
				'settings' => array(
					'emptyIcon' => false, // default true, display an "EMPTY" icon?
					'type' => 'ionicon',
					'iconsPerPage' => 4000, // default 100, how many icons per/page to display
				),
				'dependency' => array(
					'element' => 'icon_type',
					'value' => 'ionicon',
				),
				'description' => esc_html__( 'Select icon from library.', 'js_composer' ),
			));
	return $icons;
}

$tt_icons = tt_icons();

/* Add parameters for rows */
vc_add_params('vc_row', array(
	array(
		'type' => 'checkbox',
		'heading' => __( 'Use Map?', 'js_composer' ),
		'param_name' => 'map_bg',
		'description' => __( 'If checked, map will be used as row background.', 'js_composer' ),
		'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Map latitude', 'js_composer' ),
			'param_name' => 'map_latitude',
			'value' => 44.2661906, // default video url
			'description' => __( 'Set latitude for map', 'js_composer' ),
			'dependency' => array(
				'element' => 'map_bg',
				'not_empty' => true,
			),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Map longitude', 'js_composer' ),
			'param_name' => 'map_longitude',
			'value' => -68.5691898, // default video url
			'description' => __( 'Set longitude for map', 'js_composer' ),
			'dependency' => array(
				'element' => 'map_bg',
				'not_empty' => true,
			),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Map zoom', 'js_composer' ),
			'param_name' => 'map_zoom',
			'value' => 16, // default video url
			'description' => __( 'Set zoom for map', 'js_composer' ),
			'dependency' => array(
				'element' => 'map_bg',
				'not_empty' => true,
			),
		),
		array(
			'type' => 'attach_image',
			'heading' => __( 'Map Pin', 'js_composer' ),
			'param_name' => 'map_pin',
			'value' => '', // default video url
			'description' => __( 'Set pin for map', 'js_composer' ),
			'dependency' => array(
				'element' => 'map_bg',
				'not_empty' => true,
			),
		),
	));

/* TT contact info */
vc_map( array(
	'name' 		=> __( 'Contact info', 'js_composer' ),
	'base' 		=> 'tt_contact_info',
	'category' => __( 'TeslaThemes', 'js_composer' ),
	'description' => __( 'Create contact info block', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'param_group',
			'heading' => __( 'Contact info', 'js_composer' ),
			'param_name' => 'contact_items',
			'description' => __( 'Enter values for graph - value, title and color.', 'js_composer' ),
			'value' => urlencode( json_encode( array(
				array(
					'info_title' 	=> __( 'Mail', 'js_composer' ),
					'icon_type' 	=> 'ionicon',
					'icon_ionicon' 	=> 'ios-email-outline',
					'icon_color'	=> '#5b5b5b',
					'icon_size'		=> 48
				),
				array(
					'info_title' 	=> __( 'Phone', 'js_composer' ),
					'icon_type' 	=> 'ionicon',
					'icon_ionicon' 	=> 'iphone',
					'icon_color'	=> '#5b5b5b',
					'icon_size'		=> 48
				),
				array(
					'info_title' 	=> __( 'Address', 'js_composer' ),
					'icon_type' 	=> 'ionicon',
					'icon_ionicon' 	=> 'ios-location-outline',
					'icon_color'	=> '#5b5b5b',
					'icon_size'		=> 48
				),
			) ) ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __( 'Title', 'js_composer' ),
					'param_name' => 'info_title',
					'description' => __( 'Enter text used as title', 'js_composer' ),
					'admin_label' => true,
				),
				$tt_icons[0],
				$tt_icons[1],
				$tt_icons[2],
				$tt_icons[3],
				$tt_icons[4],
				$tt_icons[5],
				$tt_icons[6],
				array(
					'type' => 'colorpicker',
					'heading' => __( 'Icon Color', 'js_composer' ),
					'param_name' => 'icon_color',
					'description' => __( 'Select icon color', 'js_composer' ),
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Icon size', 'js_composer' ),
					'param_name' => 'icon_size',
					'value' => '18',
					'description' => __( 'Enter icon size (no units required)', 'js_composer' )
				),
				array(
					'type' => 'textarea',
					'heading' => __( 'Contact text', 'js_composer' ),
					'param_name' => 'info_text',
					'admin_label' => true,
					'value' => '',
					'description' => __( 'Enter text used as contact info', 'js_composer' ),

				),
			)
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		)
	)
) );

/* TT Fancy Titile */
vc_map( array(
	'name' 		=> __( 'Fancy title', 'js_composer' ),
	'base' 		=> 'tt_fancy_title',
	'category' => __( 'TeslaThemes', 'js_composer' ),
	'description' => __( 'Create fancy title', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Title', 'js_composer' ),
			'param_name' => 'title',
			'value' => '',
			'description' => __( 'Enter the title', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Subtitle', 'js_composer' ),
			'param_name' => 'subtitle',
			'value' => '',
			'description' => __( 'Enter the subtitle', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		)
	)
) );

/* TT Fancy Contact Form */
vc_map( array(
	'name' 		=> __( 'Contact form', 'js_composer' ),
	'base' 		=> 'tt_contact_form',
	'category' => __( 'TeslaThemes', 'js_composer' ),
	'description' => __( 'Create contact form', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Email to receive messages', 'js_composer' ),
			'param_name' => 'email',
			'value' => '',
			'description' => __( 'If Email is not set, will be used admin email', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		)
	)
) );

/* TT Team Meamber */
vc_map( array(
	'name' 		=> __( 'Team member', 'js_composer' ),
	'base' 		=> 'tt_team_member',
	'category' => __( 'TeslaThemes', 'js_composer' ),
	'description' => __( 'Create team member element', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'attach_image',
			'heading' => __( 'Member photo', 'js_composer' ),
			'param_name' => 'photo',
			'value' => '',
			'description' => __( 'Provide team member photo', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Member name', 'js_composer' ),
			'param_name' => 'name',
			'value' => '',
			'description' => __( 'Provide member full name', 'js_composer' ),
			'admin_label' => true
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Member job position', 'js_composer' ),
			'param_name' => 'position',
			'value' => '',
			'description' => __( 'Provide job position for team member', 'js_composer' ),
			'admin_label' => true
		),
		array(
			'type' => 'param_group',
			'heading' => __( 'Member social networks', 'js_composer' ),
			'param_name' => 'socials',
			'description' => __( 'Provide social network accounts for team member', 'js_composer' ),
			'value' => urlencode( json_encode( array(
				array(
					'social_icon' 	=> 'social-facebook',
					'social_url' 	=> '#'
				),
				array(
					'social_icon' 	=> 'social-twitter',
					'social_url' 	=> '#'
				),
				array(
					'social_icon' 	=> 'social-instagram-outline',
					'social_url' 	=> '#'
				),
			) ) ),
			'params' => array(
				array(
					'type' => 'dropdown',
					'heading' => __( 'Social network', 'js_composer' ),
					'value' => array(
						__( 'Facebook', 'js_composer' ) => 'social-facebook',
						__( 'Twitter', 'js_composer' ) => 'social-twitter',
						__( 'Instagram', 'js_composer' ) => 'social-instagram-outline',
						__( 'Dribbble', 'js_composer' ) => 'social-dribbble-outline'
					),
					'admin_label' => true,
					'param_name' => 'social_icon',
					'description' => __( 'Select social network', 'js_composer' ),
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Social network url', 'js_composer' ),
					'param_name' => 'social_url',
					'value' => '',
					'description' => __( 'Provide social network url (used target blank)', 'js_composer' )
				)
			)
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		)
	)
) );

/* TT Page Card */
vc_map( array(
	'name' 		=> __( 'Page card', 'js_composer' ),
	'base' 		=> 'tt_page_card',
	'category' => __( 'TeslaThemes', 'js_composer' ),
	'description' => __( 'Create page card', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'attach_image',
			'heading' => __( 'Page card photo', 'js_composer' ),
			'param_name' => 'photo',
			'value' => '',
			'description' => __( 'Provide a page card photo', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Page card title', 'js_composer' ),
			'param_name' => 'title',
			'value' => '',
			'description' => __( 'Provide page card title', 'js_composer' ),
			'admin_label' => true
		),
		array(
			'type' => 'textarea_html',
			'holder' => 'div',
			'heading' => __( 'Text', 'js_composer' ),
			'param_name' => 'content',
			'value' => __( '<p>I am text block. Click edit button to change this text. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>', 'js_composer' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		)
	)
) );

/* TT Tabs with Icon */
vc_map( array(
	'name' 		=> __( 'Tabs with icon', 'js_composer' ),
	'base' 		=> 'tt_tabs_icon',
	'category' => __( 'TeslaThemes', 'js_composer' ),
	'description' => __( 'Tabbed content', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'param_group',
			'heading' => __( 'Contact info', 'js_composer' ),
			'param_name' => 'tab_items',
			'description' => __( 'Enter values for graph - value, title and color.', 'js_composer' ),
			'value' => urlencode( json_encode( array(
				array(
					'tab_title' 	=> __( 'Photography', 'js_composer' ),
					'icon_type' 	=> 'ionicon',
					'icon_ionicon' 	=> 'ios-camera-outline',
					'icon_color'	=> '#f498bd',
					'icon_size'		=> 48
				),
				array(
					'tab_title' 	=> __( 'Blog', 'js_composer' ),
					'icon_type' 	=> 'ionicon',
					'icon_ionicon' 	=> 'ios-paper-outline',
					'icon_color'	=> '#f498bd',
					'icon_size'		=> 48
				),
				array(
					'tab_title' 	=> __( 'Fresh ideas', 'js_composer' ),
					'icon_type' 	=> 'ionicon',
					'icon_ionicon' 	=> 'ios-lightbulb-outline',
					'icon_color'	=> '#f498bd',
					'icon_size'		=> 48
				),
				array(
					'tab_title' 	=> __( 'Popular', 'js_composer' ),
					'icon_type' 	=> 'ionicon',
					'icon_ionicon' 	=> 'ios-world-outline',
					'icon_color'	=> '#f498bd',
					'icon_size'		=> 48
				)
			) ) ),
			'params' => array(
				array(
					'type' => 'textfield',
					'heading' => __( 'Tab title', 'js_composer' ),
					'param_name' => 'tab_title',
					'description' => __( 'Enter title for tab', 'js_composer' ),
					'admin_label' => true,
				),
				$tt_icons[0],
				$tt_icons[1],
				$tt_icons[2],
				$tt_icons[3],
				$tt_icons[4],
				$tt_icons[5],
				$tt_icons[6],
				array(
					'type' => 'colorpicker',
					'heading' => __( 'Icon Color', 'js_composer' ),
					'param_name' => 'icon_color',
					'description' => __( 'Select icon color', 'js_composer' ),
				),
				array(
					'type' => 'textfield',
					'heading' => __( 'Icon size', 'js_composer' ),
					'param_name' => 'icon_size',
					'value' => '18',
					'description' => __( 'Enter icon size (no units required)', 'js_composer' )
				),
				array(
					'type' => 'textarea',
					'heading' => __( 'Tab content', 'js_composer' ),
					'param_name' => 'tab_content',
					'value' => '',
					'description' => __( 'Enter text used as contact info', 'js_composer' ),

				),
			)
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		)
	)
) );

/* TT Recent Posts */
vc_map( array(
	'name' 		=> __( 'Recent blog posts', 'js_composer' ),
	'base' 		=> 'tt_recent_blog_posts',
	'category' => __( 'TeslaThemes', 'js_composer' ),
	'description' => __( 'Show recent post ', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'textfield',
			'heading' => __( 'Number of posts', 'js_composer' ),
			'param_name' => 'posts',
			'value' => '',
			'description' => __( 'How many post need to show', 'js_composer' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Posts per row', 'js_composer' ),
			'param_name' => 'row',
			'value' => 3,
			'description' => __( 'Provide items per row (1, 2, 3, 4, 6, 12)', 'js_composer' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		)
	)
) );

function portfolio_cat() {
	$terms = get_terms('portfolio_tax');
	$terms_options = array(__('Show all', 'js_composer') => '');

	if( empty( $terms->errors ) ) {
		foreach ($terms as $key => $item) {
			if( !empty( $item )) {
				$terms_options[$item->name] = $item->term_id;
			}
		}
		return $terms_options;
	}
}

/* TT Portfolio */
vc_map( array(
	'name' 		=> __( 'Portfolio', 'js_composer' ),
	'base' 		=> 'tt_portfolio',
	'category' => __( 'TeslaThemes', 'js_composer' ),
	'description' => __( 'Show portfolio post ', 'js_composer' ),
	'params' => array(
		array(
			'type' => 'checkbox',
			'heading' => __( 'Enable filters', 'js_composer' ),
			'param_name' => 'show_filters',
			'description' => __( 'If checked, portfolio filter will be displayed', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Number of posts', 'js_composer' ),
			'param_name' => 'posts',
			'value' => '',
			'description' => __( 'How many post need to show', 'js_composer' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order by category', 'js_composer' ),
			'value' => portfolio_cat(),
			'param_name' => 'category_filter',
			'description' => __( 'Order portfolio by category', 'js_composer' ),
		),
		array(
			'type' => 'dropdown',
			'heading' => __( 'Order portfolio', 'js_composer' ),
			'value' => array(
				__( 'Descendent', 'js_composer' ) => 'DESC',
				__( 'Ascendent', 'js_composer' ) => 'ASC'
			),
			'param_name' => 'order',
			'description' => __( 'Order portfolio items', 'js_composer' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Offset', 'js_composer' ),
			'param_name' => 'offset',
			'value' => '',
			'description' => __( 'Number of portfolio post to be skipped from begin', 'js_composer' ),
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Posts per row', 'js_composer' ),
			'param_name' => 'row',
			'value' => 3,
			'description' => __( 'Provide items per row (1, 2, 3, 4, 5, 6, 12)', 'js_composer' ),
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Fit columns', 'js_composer' ),
			'param_name' => 'fit',
			'description' => __( 'If checked, portfolio items will fit', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
		),
		array(
			'type' => 'checkbox',
			'heading' => __( 'Use long thumbnails', 'js_composer' ),
			'param_name' => 'long',
			'description' => __( 'Change thumbnail size for portfolio items grid', 'js_composer' ),
			'value' => array( __( 'Yes', 'js_composer' ) => 'yes' )
		),
		array(
			'type' => 'textfield',
			'heading' => __( 'Extra class name', 'js_composer' ),
			'param_name' => 'el_class',
			'description' => __( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'js_composer' )
		),
		array(
			'type' => 'css_editor',
			'heading' => __( 'CSS box', 'js_composer' ),
			'param_name' => 'css',
			'group' => __( 'Design Options', 'js_composer' )
		)
	)
) );