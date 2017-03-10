<?php get_header(); ?>
<?php
	global $tt_theme;
	if ( have_posts() ) :
		while ( have_posts() ) : the_post();
			if(has_shortcode( get_the_content(), 'vc_row' ) && class_exists('Vc_Manager')):
				the_content();
			else:
				 $tt_theme->single_portfolio( get_the_ID() );
			endif;
		endwhile;
	else :
		echo sprintf('<section class="box"><div class="container"><div class="row"><div class="col-md-8 col-md-offset-2">%s<h4>%s</h4></div></div></div></section>', get_search_form(false), __('No posts found.', 'cre8or'));
	endif;
 ?>
<?php get_footer(); ?>