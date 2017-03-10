<?php
/* Contact info */
class WPBakeryShortCode_TT_Contact_info extends WPBakeryShortCode {
}

/* Fancy Title */
class WPBakeryShortCode_TT_Fancy_Title extends WPBakeryShortCode {
}

/* Contact Form */
class WPBakeryShortCode_TT_Contact_Form extends WPBakeryShortCode {
}

/* Team Member */
class WPBakeryShortCode_TT_Team_Member extends WPBakeryShortCode {
	function social_css_class( $icon ) {
		if( !empty($icon) ) {
			switch ( $icon ) {
				case 'social-facebook':
					return 'bg-facebook';
					break;
				case 'social-twitter':
					return 'bg-twitter';
					break;
				case 'social-instagram-outline':
					return 'bg-instagram';
					break;
				case 'social-dribbble-outline':
					return 'bg-dribbble';
					break;
			}
		}
	}
}

/* Page card */
class WPBakeryShortCode_TT_Page_Card extends WPBakeryShortCode {
}

/* Page card */
class WPBakeryShortCode_TT_Tabs_Icon extends WPBakeryShortCode {
}

/* Page card */
class WPBakeryShortCode_TT_Recent_Blog_Posts extends WPBakeryShortCode {
	function grid_css_class( $settings ) {
		switch ( $settings ) {
			case 1:
				return 'col-md-12 col-sm-6';
				break;
			case 2:
				return 'col-md-6 col-sm-6';
				break;
			case 4:
				return 'col-md-3 col-sm-6';
				break;
			case 6:
				return 'col-md-2 col-sm-6';
				break;
			case 12:
				return 'col-md-1 col-sm-6';
				break;
			
			default:
				return 'col-md-4 col-sm-6';
				break;
		}
	}

	function get_recent_posts( $nr_posts = 3 ) {
		$args = array(
	        'posts_per_page'   => $nr_posts,
	        'offset'           => 0,
	        'category'         => '',
	        'category_name'    => '',
	        'orderby'          => 'post_date',
	        'order'            => 'DESC',
	        'post_type'        => 'post',
	        'post_status'      => 'publish',
	        'suppress_filters' => true 
	    );

	    return get_posts($args);
	}
}

/* Page card */
class WPBakeryShortCode_TT_Portfolio extends WPBakeryShortCode {
	function grid_css_class( $settings ) {
		switch ( $settings ) {
			case 1:
				return 'col-md-12 col-sm-6';
				break;
			case 2:
				return 'col-md-6 col-sm-6';
				break;
			case 4:
				return 'col-md-3 col-sm-6';
				break;
			case 5:
				return 'col-lg-05 col-md-3 col-sm-4 col-xs-6';
				break;
			case 6:
				return 'col-md-2 col-sm-6';
				break;
			case 12:
				return 'col-md-1 col-sm-6';
				break;
			
			default:
				return 'col-md-4 col-sm-6';
				break;
		}
	}

	function get_posts( $nr_posts = 3, $order = 'DESC', $offset = 0, $category_filter = '' ) {
		$args = array(
	        'posts_per_page'   => $nr_posts,
	        'offset'           => $offset,
	        'orderby'          => 'post_date',
	        'order'            => $order,
	        'post_type'        => 'portfolio',
	        'post_status'      => 'publish',
	        'suppress_filters' => true,
            'wp_posts' => array(
                'post_type' => 'product'
            )
	    );

	    if( $category_filter ) {
	    	$args['tax_query'] = array(
				array(
					'taxonomy' => 'portfolio_tax',
					'field' => 'ID',
					'terms' => $category_filter
				)
			);
	    }

	    return get_posts($args);
	}

	function get_filters( $posts = null, $post_id = null ) {
		if( !empty($posts) ) {
			$categories = array();
			$filters = array();
			foreach($posts as $key => $post) {
				$categories[$post->ID] = wp_get_post_terms( $post->ID, 'portfolio_tax' );
				if( !empty( $categories[$post->ID] ) ) {
					foreach( $categories[$post->ID] as $filter ) {
						$filters[$filter->term_id] = $filter->name;						
					}
				}
			}
			return $filters;
		}

		if( !empty( $post_id ) ) {
			$filters = array();
			$categories = wp_get_post_terms( $post_id, 'portfolio_tax' );

			if( !empty( $categories ) ) {
				foreach($categories as $key => $filter) {
					$filters[$filter->term_id] = $filter->name;
				}
				return strtolower( implode(' ', $filters) );
			}
		}
	}
}