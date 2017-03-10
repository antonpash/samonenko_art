<section class="box-grid">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<h3 class="portfolio-title text-center"><?php echo get_the_title( $id ); ?></h3>
				
				<?php if( !empty( $images ) ): ?>
				<div class="gallery-box">
					<ul class="clean-list js-flickity row row-fit">
						<?php foreach ($images as $key => $image) : if( wp_attachment_is_image( $image ) ): ?>
						<li class="col-xs-12"><?php echo wp_get_attachment_image( $image, 'full' ); ?></li>
						<?php endif; endforeach; ?>
					</ul>

					<ul class="clean-list row row-fit gallery-nav">
						<?php foreach ($images as $key => $image) : if( wp_attachment_is_image( $image ) ): ?>
						<li class="col-sm-1 col-xs-4"><?php echo wp_get_attachment_image( $image, 'thumbnail' ); ?></li>
						<?php endif; endforeach; ?>
					</ul>
				</div>
				<?php endif; ?>
			</div>

		</div>
		<div class="row">

			<div class="col-md-4">
				<?php if( !empty( $meta ) ): ?>
				<ul class="clean-list portfolio-info">
					<?php foreach ($meta as $key => $value) : ?>
					<?php printf( '<li><span>%s</span>%s</li>', $key, $value ); ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<div class="col-md-8">
				<?php echo wpautop( get_the_content( $id ) ); ?>
				<?php if( !empty( $socials ) ): ?>
				<div class="share-box bg-white">
					<h5 class="text-center"><?php _e( 'Share your love:', 'cre8or' ); ?></h5>
					<ul class="inline-list align-center">
						<?php foreach( $socials as $network ): ?>
						<?php echo balanceTags( $theme->share_helper( $network, $id ) );  ?>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<section class="box">
	<div class="row row-fit">
		<div class="col-sm-6">
			<?php if( !empty( $prev_post->ID ) ): ?>
			<div class="project-nav prev-project bg-white">
				<a href="<?php echo get_the_permalink( $prev_post->ID ); ?>"></a>
				<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $prev_post->ID ), 'full' ); ?>
				<?php if ( !empty( $image[0] ) ): ?>
					<span style="background-image: url( <?php echo esc_attr( $image[0] ); ?> );"></span>
				<?php endif ?>
				<h5 class="text-center uppercase"><?php _e( 'Prev project', 'cre8or' ); ?></h5>
			</div>
		<?php endif; ?>
		</div>
		<div class="col-sm-6">
			<?php if( !empty( $next_post->ID ) ): ?>
			<div class="project-nav next-project bg-white">
				<a href="<?php echo get_the_permalink( $next_post->ID ); ?>"></a>
				<?php $image = wp_get_attachment_image_src( get_post_thumbnail_id( $next_post->ID ), 'full' ); ?>
				<?php if ( !empty( $image[0] ) ): ?>
					<span style="background-image: url( <?php echo esc_attr( $image[0] ); ?> );"></span>
				<?php endif ?>
				<h5 class="text-center uppercase"><?php _e( 'Next project', 'cre8or' ); ?></h5>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>