<?php

return array(
	'portfolio' 			=> array(
		'name' 				=> 'Portfolio',
		'term' 				=> 'portfolio',
		'term_plural' 		=> 'portfolio',
		'order' 			=> 'ASC',
		'has_single' 		=> true,
		'post_options' 		=> array( 'supports' => array( 'title', 'editor', 'thumbnail' )),
		'taxonomy_options' 	=> array('show_ui'=>true),
		'options' 			=> array(
			'portfolio_type' => array(
				'type' 			=> 'select',
				'description' 	=> __( 'Select single portfolio view', 'cre8or' ),
				'title' 		=> 'Single portfolio view',
				'label' 		=> array( 
					'one' 	=> esc_html__( 'Portfolio view Nr. 1', 'cre8or' ), 
					'two' 	=> esc_html__( 'Portfolio view Nr. 2', 'cre8or' ), 
					'three' => esc_html__( 'Portfolio view Nr. 3', 'cre8or' ),
					'four' 	=> esc_html__( 'Portfolio view Nr. 4', 'cre8or' ),
					'five' 	=> esc_html__( 'Portfolio view Nr. 5', 'cre8or' )
				)
			),
			'images' => array(
				'type' 			=> 'image',
				'description' 	=> esc_html__('Provide gallery of images used on single portfolio page'),
				'title' 		=> esc_html__('Portfolio images', 'cre8or'),
				'multiple' 		=> true,
				'default' 		=> 'http://placehold.it/350x200/09f/fff.png'
			),
			'portfolio_meta' => array(
				'title' 		=> esc_html__( 'Portfolio meta information', 'cre8or' ),
				'description' 	=> esc_html__ ('This info is optional and can be anything you want, like atuhor, client name or which tools you used to create this item', 'cre8or' ),
				'group' 		=> true,
				'multiple' 		=> true,
				'type' 			=> array(
					'meta_title' => array(
						'type' 			=> 'line',
						'description' 	=> esc_html__( 'Portfolio item meta info title', 'cre8or' ),
						'title' 		=> esc_html__( 'Meta title', 'cre8or' )
					),
					'meta_value' => array(
						'type' 			=> 'line',
						'description' 	=> esc_html__( 'Portfolio item meta info value', 'cre8or' ),
						'title' 		=> esc_html__( 'Meta value', 'cre8or' )
					),
				)
			),
			'portfolio_social' => array(
				'title' 		=> esc_html__( 'Social networks', 'cre8or' ),
				'description' 	=> esc_html__( 'Enable share box. Please provide social network to appear in share box', 'cre8or' ),
				'group' 		=> true,
				'multiple' 		=> true,
				'type' 			=> array(
					'social_network' => array(
						'type' 			=> 'select',
						'description' 	=> esc_html__( 'Select social network', 'cre8or' ),
						'title' 		=> 'Social network',
						'label' 		=> array( 
							'googleplus' 	=> esc_html__( 'Google plus', 'cre8or' ), 
							'facebook' 	=> esc_html__( 'Facebook', 'cre8or' ), 
							'twitter' => esc_html__( 'Twitter', 'cre8or' ),
							'pinterest' 	=> esc_html__( 'Pinterest', 'cre8or' ),
							'linkedin' 	=> esc_html__( 'Linkedin', 'cre8or' )
						)
					)
				)
			),
		),
		'view' 		=> '',
		'output' 	=> array()
	)
);