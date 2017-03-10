<?php get_header(); ?>
<?php
	global $tt_theme;
	$page_portfolio = (int)get_option('page_for_portfolio');
	$current_page_id = get_the_ID();
	$pagination = '';
	if( $page_portfolio === get_the_ID() ) {
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
		query_posts( array(
			'paged' => $paged,
			'post_type' => 'portfolio',
			) );
	}
	if ( have_posts() ) :
		do_action('tt_before_pages', $tt_theme->get_filters( $wp_query->posts ));
		while ( have_posts() ) : the_post();

			if( $page_portfolio ===  $current_page_id ) {
				do_action('tt_portfolio');
			} else {
				if(has_shortcode( get_the_content(), 'vc_row' ) && class_exists('Vc_Manager')):
					the_content();
				else:
					 do_action('tt_content');
				endif;
			}

		endwhile;
		if( $page_portfolio === $current_page_id ) {
			$pagination =   sprintf('<div class="container">%s</div>', $tt_theme->pagination_links());
			wp_reset_query();
		}
		do_action('tt_after_pages');
		echo balanceTags( $pagination );
	else :
		echo sprintf('<section class="box"><div class="container"><div class="row"><div class="col-md-8 col-md-offset-2">%s<h4>%s</h4></div></div></div></section>', get_search_form(false), esc_html__('No posts found.', 'cre8or'));
	endif;
?>

<?php get_footer(); ?>