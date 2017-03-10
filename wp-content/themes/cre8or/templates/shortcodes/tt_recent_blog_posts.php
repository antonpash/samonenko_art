<?php
global $post;
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
$bg_class = !empty($css) ? vc_shortcode_custom_css_class( $css ) : '';
$theme_class = $bg_class . ' ' . $el_class;
$posts = $this->get_recent_posts( $posts );
?>
<div class="row">
<?php if( !empty( $posts )): ?>
<?php foreach( $posts as $post ):
	setup_postdata( $post ); ?>
<div class="<?php echo !empty( $row ) ? $this->grid_css_class( $row ) : ''; ?>">
<article class="blog-post blog-post-grid bg-white <?php echo esc_attr( $theme_class ); ?>">
	<div class="post-meta">
		<?php if( has_post_thumbnail() ): ?>
		<figure class="post-thumbnail">
			<a href="<?php the_permalink(); ?>">
			<?php echo get_the_post_thumbnail(); ?>
			</a>
		</figure>
		<?php endif; ?>
		<div class="post-date uppercase">
			<span class="bg-alpha text-white"><?php echo esc_html( get_the_date('d') ); ?></span>
			<span class="bg-white"><?php echo esc_html( get_the_date('M Y')); ?></span>
		</div>

		<?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
			<div class="post-comments">
				<span class="bg-grey-four text-white">
				<?php comments_popup_link('0', '1', '%', '' ); ?>
				</span>
				<i class="bg-white" data-icon="ios-chatbubble-outline" data-icon-size="18"></i>
			</div>
		<?php endif; ?>
	</div>
	<header class="post-header">
		<div class="post-categories text-center text-grey-three">
			<?php the_category( ', '); ?>
		</div>
		<h3 class="post-title text-center"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
	</header>
	<div class="post-content text-center">
		<?php the_excerpt(); ?>
	</div>
</article>
</div>
<?php endforeach; ?>
<?php endif; ?>
<?php wp_reset_postdata(); ?>
</div>