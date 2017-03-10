<?php

// 'size'=>'half_last',
// 'id'=>'logo_text',
// 'type'=>'text',
// 'note' => "Type the logo text here, then select a color, set a size and font",
// 'color_changer'=>true,
// 'font_changer'=>true,
// 'font_size_changer'=>array(1,10, 'em'),
// 'font_preview'=>array(true, true)

function make_input($size = null, $id = null, $type = null, $note = null, $values = null, $placeholder = null, $class= null) {
        $input_settings = array();
        $f = new ReflectionFunction('make_input');

        foreach ($f->getParameters() as $key => $value) {
               if(!empty($value->name))
                        $input_settings[$value->name] = ${$value->name};
        }

        return $input_settings;
}


return array(
    'favico' => array(
            'dir' => '/images/cre8or.png'
    ),
    'tabs' => array(
        array(
            'title' => esc_html__( 'General Options', 'cre8or' ),
            'icon'  => 1,
            'boxes' => array(
                'Layout' => array(
                    'icon'          => 'customization',
                    'size'          => 'half',
                    'columns'       => true,
                    'description'   => '',
                    'class'         => 'layout-style',
                    'input_fields'  => array(
                        'Layout Style' => make_input('half', 'layout_style', 'radio',
                            esc_html__( 'Set your layout style. This setting will be applied for all pages. Pay attention, only with "Boxed" layout you will be abile to view "Main Background" image or color.', 'cre8or' ), 
                            array('Wide', 'Boxed'), ''),
                    )  
                ),
                'Favicon' => array(
                    'icon'          => 'customization',
                    'size'          => 'half_last',
                    'columns'       => true,
                    'description'   => '',
                    'class'         => '',
                    'input_fields'  => array(
                        'Favicon image' => make_input('half', 'favicon_link', 'image_upload', 
                            esc_html__( 'Here you can upload the favicon icon.', 'cre8or' ) )
                    )    
                ),
                'Background Settings' => array(
                    'icon'          => 'customization',
                    'size'          => 'full',
                    'columns'       => true,
                    'description'   => '',
                    'class'         => '',
                    'input_fields'  => array(
                        'Main background Image'     => make_input( 'half', 'body_background', 'image_upload', '' ),
                        'Background Color'          => make_input( 'half', 'body_color', 'colorpicker', 
                            esc_html__( 'Here you can set a color for body background. If is set image as background, the color will be not visible', 'cre8or' ) ),
                        'Canvas Color'              => make_input( 'half', 'canvas_color', 'colorpicker', 
                            esc_html__( 'Here you can set a color for site canvas. Please make sure that you selected boxed verion layout in order to feel the changes.', 'cre8or' ) ),
                        'Background Repeat'         => make_input( 'half', 'body_background_repeat', 'radio', '', 
                            array('Repeat', 'No-repeat', 'Repeat-X', 'Repeat-Y') ),
                        'Background Position'       => make_input( 'half', 'body_background_position', 'radio', '',
                             array('Scroll', 'Fixed') ),
                        'Background Gradient From'  => make_input( 'half', 'body_gradient_from', 'colorpicker', 
                            esc_html__( 'Here you can change the gradient background (Gradient strarting from this color).', 'cre8or' ) ),
                        'Background Gradient To'    => make_input( 'half', 'body_gradient_to', 'colorpicker', 
                            esc_html__( 'Here you can change the gradient background (Gradient end to this color).', 'cre8or' ) )
                    ) 
                )
            )
        ),
        array(
            'title' => esc_html__( 'Typography', 'cre8or' ),
            'icon'  => 3,
            'boxes' => array(
                'Global Typography' => array(
                    'icon'          => 'customization',
                    'size'          => 'half',
                    'columns'       => true,
                    'description'   => '',
                    'class'         => '',
                    'input_fields'  => array(
                        'Global Typography' =>  array(
                            'size'              => 'half',
                            'id'                => 'global_typo',
                            'type'              => 'text',
                            'note'              => esc_html__( "Here you can change global font color, font family and font size", 'cre8or' ),
                            'color_changer'     => true,
                            'font_changer'      => true,
                            'font_size_changer' => array( 1,300, 'px' ),
                            'font_preview'      => array( false, false ),
                            'hide_input'        => true,
                        )
                    )
                ),
                'Links style' => array(
                    'icon'          => 'customization',
                    'size'          => 'half',
                    'columns'       => true,
                    'description'   => '',
                    'class'         => '',
                    'input_fields'  => array(
                        'Links options' => array(
                            'size'              => 'half',
                            'id'                => 'links_settings',
                            'type'              =>  'text',
                            'note'              => esc_html__( "Here you can change link's font color, font family and font size", 'cre8or' ),
                            'color_changer'     => true,
                            'font_changer'      => true,
                            'font_size_changer' => array(1,300, 'px'),
                            'font_preview'      => array(false, false),
                            'hide_input'        => true
                        )
                    )
                ),
                'Headings style' => array(
                    'icon'          => 'customization',
                    'size'          => 'full',
                    'columns'       => true,
                    'description'   => '',
                    'class'         => '',
                    'input_fields'  => array(
                        'Headings options' => array(
                            'size'              => 'full',
                            'id'                => 'headings_settings',
                            'type'              =>'text',
                            'note'              => esc_html__( "Here you can change color and font family for headings. Also bellow you can adjust heading font size.", 'cre8or' ),
                            'color_changer'     => true,
                            'font_changer'      => true,
                            'font_size_changer' => false,
                            'font_preview'      => array(false, false),
                            'hide_input'        => true
                        ),
                        'Headings 1' => array(
                            'size'              => '1_3',
                            'id'                => 'headings_one_settings',
                            'type'              => 'text',
                            'note'              => "",
                            'color_changer'     => false,
                            'font_changer'      => false,
                            'font_size_changer' => array(1,300, 'px'),
                            'font_preview'      => array(false, false),
                            'hide_input'        => true
                        ),
                        'Headings 2'=>array(
                            'size'              => '1_3',
                            'id'                => 'headings_two_settings',
                            'type'              => 'text',
                            'note'              =>  "",
                            'color_changer'     => false,
                            'font_changer'      => false,
                            'font_size_changer' => array(1,300, 'px'),
                            'font_preview'      => array(false, false),
                            'hide_input'        => true
                        ),
                        'Headings 3' => array(
                            'size'              => '1_3_last',
                            'id'                => 'headings_three_settings',
                            'type'              => 'text',
                            'note'              =>  "",
                            'color_changer'     => false,
                            'font_changer'      => false,
                            'font_size_changer' => array(1,300, 'px'),
                            'font_preview'      => array(false, false),
                            'hide_input'        => true
                        ),
                        'Headings 4' => array(
                            'size'              => '1_3',
                            'id'                => 'headings_four_settings',
                            'type'              => 'text',
                            'note'              => "",
                            'color_changer'     => false,
                            'font_changer'      => false,
                            'font_size_changer' => array(1,300, 'px'),
                            'font_preview'      => array(false, false),
                            'hide_input'        => true
                        ),
                        'Headings 5'=>array(
                            'size'              => '1_3',
                            'id'                => 'headings_five_settings',
                            'type'              => 'text',
                            'note'              => "",
                            'color_changer'     => false,
                            'font_changer'      => false,
                            'font_size_changer' => array(1,300, 'px'),
                            'font_preview'      => array(false, false),
                            'hide_input'        => true
                        ),
                        'Headings 6' => array(
                            'size'              => '1_3_last',
                            'id'                => 'headings_six_settings',
                            'type'              => 'text',
                            'note'              => "",
                            'color_changer'     => false,
                            'font_changer'      => false,
                            'font_size_changer' => array(1,300, 'px'),
                            'font_preview'      => array(false, false),
                            'hide_input'        => true
                        )
                    )   
                )
            )
        ),
        array(
            'title'     => esc_html__( 'Customize defaults', 'cre8or' ),
            'icon'      => 1,
            'boxes'     => array(
                'Main background colors'    => array(
                    'icon'          => 'customization',
                    'size'          => 'full',
                    'columns'       => true,
                    'description'   => 'Overwrite default colors.',
                    'class'         => '',
                    'input_fields'  => array(
                        'Primary' => make_input('1_3', 'primary_color', 'colorpicker',
                            esc_html__( 'Choose primary color for your website. This will affect only specific elements.
                            To return to default color , open colorpicker and click the Clear button.', 'cre8or' ) ),
                        'Secondary' => make_input('1_3', 'secondary_color', 'colorpicker',
                            esc_html__( 'Choose secondary color for your website. This will affect only specific elements.
                            To return to default color , open colorpicker and click the Clear button.', 'cre8or' ) )
                    ) 
                ),
                'Share post feature' => array(
                    'icon'          => 'social',
                    'description'   => esc_html__( "To use this service please select your favorite social networks", 'cre8or' ),
                    'size'          => '1_2',
                    'input_fields'  => array(
                        array(
                            'type'      => 'select',
                            'id'        => 'share_this',
                            'label'     => 'Facebook',
                            'class'     => 'social_search',
                            'multiple'  => true,
                            'options'   => array(
                                'Google'    => 'googleplus',
                                'Facebook'  => 'facebook',
                                'Twitter'   => 'twitter',
                                'Pinterest' => 'pinterest',
                                'Linkedin'  => 'linkedin'
                            )
                        )
                    )
                )
            )
        ),
        array(
            'title' => esc_html__( 'Header', 'cre8or' ),
            'icon'  => 8,
            'boxes' => array(
                'Header Settings'   => array(
                    'icon'              => 'customization',
                    'size'              => 'full',
                    'columns'           => true,
                    'description'       => '',
                    'class'             => '',
                    'input_fields'      => array(
                        'Logo position' => make_input('half', 'logo_position', 'radio', '', array('left', 'center', 'right')),
                        'Menu Box Color' => make_input('half', 'header_color', 'colorpicker', 'Here you can change color for navigation bar.' ),
                        'Menu Box Image' => make_input('full', 'menu_background', 'image_upload', '' )
                    )   
                ),
                'Identity Settings' => array(
                    'icon'          => 'customization',
                    'size'          => 'full',
                    'columns'       => true,
                    'description'   => '',
                    'class'         => 'identity-helper',
                    'input_fields'  => array(
                        'Logo' => make_input('half', 'logo_image', 'image_upload',
                        esc_html__( 'Here you can insert your link to a image logo or upload a new logo image.', 'cre8or' ) ),
                        'Logo As Text' => array(
                            'size'              => 'half',
                            'id'                => 'logo_text',
                            'type'              => 'text',
                            'note'              => esc_html__( "Type the logo text here, then select a color, set a size and font.", 'cre8or' ),
                            'color_changer'     => true,
                            'font_changer'      => true,
                            'font_size_changer' => array(1,300, 'px'),
                            'font_preview'      => array(true, true)
                        )
                    )     
                ),
                'Social Platforms'=>array(
                    'icon'          =>  'social',
                    'description'   =>  esc_html__( "Insert the link to the social share page.", 'cre8or' ),
                    'size'          =>  'half',
                    'columns'       =>  true,
                    'input_fields'  =>  array(
                        array(
                            'id'        => 'social_platforms',
                            'size'      => 'full',
                            'type'      => 'social_platforms',
                            'platforms' => array(
                                'facebook',
                                'twitter',
                                'linkedin',
                                'rss',
                                'dribbble',
                                'google'
                            )
                        )
                    )
                ),
                'Header info' => array(
                    'icon'          => 'customization',
                    'size'          => 'half_last',
                    'columns'       => true,
                    'description'   => '',
                    'class'         => '',
                    'input_fields'  => array(
                        'Header heading'     => make_input('full', 'header_heading', 'text',
                        esc_html__( 'Here you can add a heading for header menu box.', 'cre8or' ) ),
                        'Header description' => make_input('full', 'header_info', 'textarea',
                            esc_html__( 'Here you can add additional info in the header menu box.', 'cre8or' ), '', '')
                    )      
                )
            )
        ),
        array(
            'title' => esc_html__( 'Footer', 'cre8or' ),
            'icon'          => 8,
            'boxes'         => array(
                'Footer info'   => array(
                    'icon'          => 'customization',
                    'size'          => 'full',
                    'columns'       => true,
                    'description'   => '',
                    'class'         => '',
                    'input_fields'  => array(
                        'Footer copyright' => make_input('half', 'footer_info', 'textarea', 
                            esc_html__( 'Insert copyright info', 'cre8or' ), '', esc_html__( 'your content', 'cre8or' ) ),
                    )
                )
            )
        ),
        array(
            'title' => esc_html__( '404 error', 'cre8or' ),
            'icon'  => 8,
            'boxes' => array(
                '404 content page error' => array(
                    'icon'          => '',
                    'size'          => 'full',
                    'description'   => esc_html__( 'Here you can drop your "404 error" page content', 'cre8or' ),
                    'input_fields'  => array(
                        'Erorr emblem' => make_input('full', 'error_emblem', 'image_upload', '' ),
                        '404 error page content' => make_input('full', '404_error', 'textarea',
                            esc_html__( 'Add content for your 404 error page', 'cre8or' ), '', esc_html__( 'your content', 'cre8or' )),
                    )
                )
            )
        ),
        array(
            'title' => esc_html__( 'Developer', 'cre8or' ),
            'icon' => 6,
            'boxes' => array(
                'Custom CSS' => array(
                    'icon'          => 'css',
                    'size'          => 'full',
                    'description'   => esc_html__( 'Here you can write your personal CSS for customizing the classes you choose to modify.', 'cre8or' ),
                    'input_fields'  => array(
                        make_input('half', 'custom_css', 'textarea', '' )
                    )
                ),
                'Custom js' => array(
                    'icon'          => 'css',
                    'size'          => 'full',
                    'description'   => esc_html__( 'Here you can write your personal JS for customizing the classes you choose to modify.', 'cre8or' ),
                    'input_fields'  => array(
                        make_input('half', 'custom_js', 'textarea', '' )
                    )
                )
            )
        )
    ),
    'option_saved_text' => esc_html__( 'Options successfully saved', 'cre8or' ),
    'styles' => array( array('wp-color-picker'),'style','select2' ),
    'scripts' => array( array( 'jquery', 'jquery-ui-core','jquery-ui-datepicker','wp-color-picker' ), 'select2.min','jquery.cookie','tt_options', 'admin_js' )
);