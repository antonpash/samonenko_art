<section class="box-grid">
	<div class="row row-fit">
		<div class="col-md-6 col-md-offset-1">
			<div class="project-top-meta">
				<h3 class="portfolio-title"><?php echo get_the_title( $id ); ?></h3>
				<?php echo wpautop( get_the_content( $id ) ); ?>
				<?php if( !empty( $socials ) ): ?>
				<br>
				<div class="share-box bg-white">
					<h5 class="text-center"><?php esc_html_e( 'Share your love:', 'cre8or' ); ?></h5>
					<ul class="inline-list align-center">
						<?php foreach( $socials as $network ): ?>
						<?php echo balanceTags( $theme->share_helper( $network, $id ) );  ?>
						<?php endforeach; ?>
					</ul>
				</div>
				<br>
				<br>
				<?php endif; ?>
			</div>
		</div>
		<div class="col-md-3 col-md-offset-1">
			<?php if( !empty( $meta ) ): ?>
			<div class="project-top-meta">
				<ul class="clean-list portfolio-info">
					<?php foreach ($meta as $key => $value) : ?>
					<?php printf( '<li><span>%s</span>%s</li>', $key, $value ); ?>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<?php if( !empty( $images ) ): ?>
	<div class="row row-fit">
		<ul class="portfolio-items clean-list">
			<?php $i = 0; ?>
			<?php foreach ($images as $key => $image) : if( wp_attachment_is_image( $image ) ): ?>
			<?php $i++; $image_class = $i % 3 === 0 ? 'col-md-8 col-md-offset-2' : 'col-md-6';  ?>
			<li class="<?php echo esc_attr( $image_class ); ?>">
				<figure>
					<?php $zoom_image = wp_get_attachment_image_src( $image, 'full' ); ?>
					<a href="#" data-zoom="<?php echo esc_attr( $zoom_image[0] ); ?>">
					<?php echo wp_get_attachment_image( $image, 'full' ); ?>
					</a>
					<figcaption>
						<div class="portfolio-hint">
							<h4><?php echo get_the_title( $image ); ?></h4>
							<ul class="inline-list align-center">
								<li>
									<?php if( !empty( $zoom_image[0] ) ): ?>
									<i data-icon="ios-eye-outline" data-icon-size="72" data-icon-color="<?php tt_primary( '#f4a3c4' ); ?>"></i>
									<?php endif; ?>
								</li>
							</ul>
						</div>
					</figcaption>
				</figure>
			</li>
			<?php endif; endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>
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