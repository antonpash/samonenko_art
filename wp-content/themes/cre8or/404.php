<?php get_header(); ?>

<section class="box-intro-large">
    <div class="intro-container" data-box-img="<?php echo _go('error_background'); ?>">
        <div class="box-img"><span></span></div>
        
        <div class="container intro-center">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="text-center">
                        <?php echo balanceTags( $tt_theme->lazy_img( _go('error_emblem') ) ); ?>
                        <?php if(_go('404_error')): ?>
                        	<div class="text-center">
                        		<?php echo _go('404_error'); ?>
                        	</div>
                        <?php else: ?>
                            <h3 class="font-alpha uppercase"><?php esc_html_e( 'Sorry, the page not found!', 'cre8or' ); ?></h3>
                            <h6><?php esc_html_e( 'Go to', 'cre8or'); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="uppercase text-alpha"><?php esc_html_e( 'Home page', 'cre8or' ); ?></a> <?php esc_html_e( 'and try again or choose another page.', 'cre8or' ); ?></h6>
                        <?php endif; ?>
                    </div>
                </div>
            </div> <!-- /.row -->
        </div>
    </div>
</section>

<?php get_footer(); ?>