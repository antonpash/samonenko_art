<section class="box-grid">
	<div class="row row-fit">
		<div class="col-lg-4 col-md-5">
			<aside class="portfolio-sidebar" data-sticky-sidebar="true">
				<div class="bg-white portfolio-info-bg">
					<h3 class="portfolio-title"><?php echo get_the_title( $id ); ?></h3>
					<?php if( !empty( $meta ) ): ?>
					<ul class="clean-list portfolio-info">
						<?php foreach ($meta as $key => $value) : ?>
							<?php printf( '<li><i data-icon="%s" data-icon-size="28" data-icon-color="#d7d7d7"></i> %s</li>', $key, $value ); ?>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>

					<?php echo wpautop( get_the_content( $id ) ); ?>

					<?php if( !empty( $socials ) ): ?>
					<hr class="bg-grey-two">
					<ul class="inline-list align-center share-socials">
						<?php foreach( $socials as $network ): ?>
							<?php echo balanceTags( $theme->share_helper( $network, $id, true ) );  ?>
						<?php endforeach; ?>
					</ul>
					<?php endif; ?>
				</div>

				<ul class="inline-list align-right mini-nav">
					<?php if( !empty( $prev_post->ID ) ): ?>
					<li>
						<a href="<?php echo get_the_permalink( $prev_post->ID ); ?>"><i data-icon="ios-arrow-left" data-icon-size="48" data-icon-color="#aaaaaa"></i></a>
					</li>
					<?php endif; ?>
					<?php if( !empty( $next_post->ID ) ): ?>
					<li>
						<a href="<?php echo get_the_permalink( $next_post->ID ); ?>"><i data-icon="ios-arrow-right" data-icon-size="48" data-icon-color="#aaaaaa"></i></a>
					</li>
					<?php endif; ?>
				</ul>
			</aside>
		</div>
		<div class="col-lg-8 col-md-7">
			<?php if( !empty( $images ) ): ?>
			<div class="row row-fit">
				<ul class="portfolio-items clean-list">
					<?php $i = 0; ?>
					<?php foreach ($images as $key => $image) : if( wp_attachment_is_image( $image ) ): ?>
						<?php $i++; $image_class = ($i + 1) % 4 === 0 || $i % 4 === 0 ? 'col-md-6' : 'col-md-12';  ?>
						<li class="<?php echo esc_attr( $image_class ); ?>">
							<figure>
								<?php $zoom_image = wp_get_attachment_image_src( $image, 'full' ); ?>
								<a href="#" data-zoom="<?php echo esc_attr( $zoom_image[0] ); ?>">
								<?php echo wp_get_attachment_image( $image, 'full' ); ?>
								</a>
							</figure>
						</li>
					<?php endif; endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>