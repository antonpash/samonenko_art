<section class="box-grid">
	<div class="container">
	<div class="row">
			<div class="col-md-12">
				<div class="bg-white portfolio-intro">
					<h3 class="portfolio-title text-center"><?php echo get_the_title( $id ); ?></h3>
					<hr class="bg-alpha hr-10 align-center">
					<?php if( !empty( $meta ) ): ?>
					<ul class="inline-list portfolio-info align-center">
						<?php foreach ($meta as $key => $value) : ?>
						<?php printf( '<li><span>%s</span>%s</li>', $key, $value ); ?>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
				</div>
				<?php if( !empty( $socials ) ): ?>
				<br>
				<ul class="inline-list align-center share-socials">
					<?php foreach( $socials as $network ): ?>
					<?php echo balanceTags( $theme->share_helper( $network, $id, true ) );  ?>
					<?php endforeach; ?>
				</ul>
				<br>
				<?php endif; ?>
				<br>
			</div>
			<?php if( !empty( $images ) ): ?>
			<ul class="portfolio-items portfolio-single clean-list">
				<?php $i = 0; ?>
				<?php foreach ($images as $key => $image) : if( wp_attachment_is_image( $image ) ): ?>
				<?php $i++; $image_class = ($i +1) % 4 === 0 || $i % 4 === 0  ? 'col-md-6' : 'col-md-12'; ?>
				<li class="<?php echo esc_attr( $image_class ); ?>">
					<div class="bg-white">
						<figure>
							<?php $zoom_image = wp_get_attachment_image_src( $image, 'full' ); ?>
							<a href="#" data-zoom="<?php echo esc_attr( $zoom_image[0] ); ?>">
							<?php echo wp_get_attachment_image( $image, 'full' ); ?>
							</a>
						</figure>
						<?php $image_meta = get_post( $image ); ?>
						<?php if( !empty( $image_meta->post_content ) ): ?>
						<div class="portfolio-caption">
							<?php echo wpautop( $image_meta->post_content ); ?>
						</div>
						<?php endif; ?>
					</div>
				</li>
				<?php endif; endforeach; ?>
			</ul>
			<?php endif; ?>
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
				<h5 class="text-center uppercase"><?php esc_html_e( 'Prev project', 'cre8or' ); ?></h5>
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
				<h5 class="text-center uppercase"><?php esc_html_e( 'Next project', 'cre8or' ); ?></h5>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>