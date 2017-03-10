<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-post bg-white '. $no_thumb ); ?>>
	<div class="post-meta">
		<?php if(has_post_thumbnail()): ?>
			<figure class="post-thumbnail text-center <?php echo !empty( $video_post ) ? 'video-featured' : ''; ?>">
				<?php if( is_singular( 'post' ) ): ?>
					<?php echo balanceTags( $post_thumbnail ); ?>
				<?php else: ?>
					<a href="<?php echo !empty( $video_post ) ? '#' : the_permalink(); ?>" <?php echo !empty( $video_post ) ? sprintf( 'data-zoom="%s"', $video_post) : ''; ?>>
						<?php echo balanceTags($post_thumbnail); ?>
					</a>
					<?php if( $video_post ): ?>
						<i class="bg-white" data-icon="ios-play" data-icon-size="96" data-icon-color="#999999"></i>
					<?php endif; ?>
				<?php endif; ?>
			</figure>
		<?php endif; ?>
		<div class="post-date uppercase">
			<span class="bg-alpha text-white"><a href="<?php the_permalink(); ?>" class="text-white"><?php echo esc_html(get_the_date('d')); ?></a></span>
			<span class="bg-white"><?php echo esc_html(get_the_date('M Y')); ?></span>
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
			<?php the_category(', '); ?>
		</div>
		<?php if(is_singular( 'post' )): ?>
			<h3 class="post-title align-center">
				<?php if( $post_format_icon ): ?>
					<i data-icon="<?php echo balanceTags( $post_format_icon ); ?>" data-icon-size="36" data-icon-color="#9bc7e6"></i>
				<?php endif; ?>
				<?php the_title(); ?>
			</h3>
		<?php else: ?>
			<h3 class="post-title align-center">
				<?php if( $post_format_icon ): ?>
					<i data-icon="<?php echo balanceTags( $post_format_icon ); ?>" data-icon-size="36" data-icon-color="#9bc7e6"></i>
				<?php endif; ?>
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>
		<?php endif; ?>
	</header>
	<div class="post-content">
		<?php if( is_singular('post') ):
				the_content();
			else:
				if( has_excerpt() ) {
					the_excerpt();
				} else {
					the_content();
				}
		
			endif; ?>

		<?php if( is_singular( 'post' ) ):
			echo balanceTags( $post_share );
			
			the_tags( ' <div class="tag-box"><ul class="inline-list"><li><span>' 
				. __( 'Tags:', 'cre8or' ) . '</span></li><li>', 
	                    '</li><li>', '</li></ul></div>' );

			wp_link_pages( array(
					'before'      		=> '<div class="post-pagination">',
					'after'       		=> '</div>',
					'link_before' 		=> '',
					'next_or_number'   	=> 'next',
					'link_after'  		=> ''));

		endif; ?>
	</div>
</article>
<?php if( is_singular('post') && !empty( $recent_post ) ):?>
<div class="recent-posts-container bg-white post-box">
	<h3 class="text-center"><?php esc_html_e( 'Related posts', 'cre8or' ); ?></h3>
	<div class="row">
		<?php global $post; ?>
		<ul class="recent-posts clean-list">
            <?php foreach($recent_post as $post): $post = (object) $post; ?>
            <li class="col-md-6">
                <article class="blog-post bg-white">
                    <div class="post-meta">
                        <?php if( has_post_thumbnail() ): setup_postdata( $post ); ?>
                        <figure class="post-thumbnail">
                            <a href="<?php echo post_permalink(); ?>">
                                <?php echo get_the_post_thumbnail( $post->ID, 'portfolio-thumb', $post->post_title ); ?>
                            </a>
                            <figcaption>
                                <div class="text-center">
                                    <span class="uppercase"><?php esc_html_e( 'View', 'cre8or' ); ?></span>
                                    <i class="align-center" data-icon="ios-eye-outline" data-icon-color="<?php tt_primary( '#f4a3c4' ); ?>"></i>
                                </div>
                            </figcaption>
                        </figure>
                        <?php endif; ?>
                        <div class="post-date uppercase">
                            <span class="bg-grey-four text-white"><?php echo esc_html( get_the_date('d')); ?></span>
                            <span class="bg-white"><?php echo esc_html( get_the_date('M Y')); ?></span>
                        </div>
                    </div>
                    <header class="post-header">
                        <h4 class="post-title"><a href="<?php echo post_permalink(); ?>">
                        	<?php echo balanceTags( $post->post_title); ?></a></h4>
                    </header>
                    <div class="post-content">
                        <p>
                            <?php echo balanceTags( $post->post_excerpt ); ?>
                        </p>
                    </div>
                </article>
            </li>
            <?php endforeach; ?>
            <?php wp_reset_postdata(); ?>
        </ul>
	</div>
</div>
<?php endif; ?>