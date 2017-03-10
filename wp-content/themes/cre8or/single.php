<?php get_header(); ?>
<?php
	if ( have_posts() ) :
		do_action('tt_before_pages');
		while ( have_posts() ) : the_post();
			if($tt_theme->detect_shortcode('vc_row', get_the_content())):
				the_content();
			else:
				 do_action('tt_content');
			endif;
		endwhile;
		do_action('tt_after_pages');
	else :

	endif;
?>

<?php get_footer(); ?>