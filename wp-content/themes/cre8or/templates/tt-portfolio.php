<?php global $tt_theme; wc_clear_notices();?>
<li class="col-md-4 col-sm-6 col-xs-12" data-grid-filter="<?php echo esc_attr( $tt_theme->get_filters(null, get_the_ID()) ); ?>">
	<?php if( has_post_thumbnail() ): ?>
		<figure class="text-center">
			<a href="<?php the_permalink(); ?>">
				<?php echo get_the_post_thumbnail( null, 'portfolio-thumb' ); ?>
			</a>
			<figcaption>
				<div class="portfolio-hint">
					<?php
						$meta = get_post_meta( $id, 'slide_options' );
						$meta = !empty( $meta ) ? $meta[0] : null;
						$portfolio_meta = array();

						if( !empty( $meta['portfolio_meta'] ) ) {
							foreach ( $meta['portfolio_meta'] as $key => $value ) {
								if( !empty( $value['meta_title'] ) && !empty( $value[ 'meta_value' ] ) ) {
									$portfolio_meta[ $value['meta_title'] ] = $value[ 'meta_value' ];
								}
							}
						}

//						$product_id = wc_get_product_id_by_sku(get_the_ID());
//						$_product = wc_get_product( $product_id );
					?>

<!--					--><?php //if($product_id && $_product->is_in_stock()): ?>
<!--					--><?php //echo '<h4>'.$_product->get_price().' '.get_woocommerce_currency_symbol().'</h4>';?>
<!--					--><?php //else:?>
					<?php the_title( '<h4>', '</h4>') ?>
<!--					--><?php //endif?>
					<ul class="inline-list align-center">
						<li class="bg-white">
							<?php
									$video_link = get_post_meta( $post->ID, 'portfolio_meta_video' );
									$zoom_icon= !empty( $video_link[0]['portfolio_video'] )
										? 'ios-play-outline' : 'ios-eye-outline';
									$video_link = !empty( $video_link[0]['portfolio_video'] )
										? $video_link[0]['portfolio_video']
										: wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
								?>
								<a href="#" data-zoom="<?php echo esc_attr( $video_link ); ?>"><i data-icon="<?php echo esc_attr( $zoom_icon ); ?>" data-icon-size="36" data-icon-color="<?php tt_primary( '#f4a3c4' ); ?>"></i></a>
						</li>

<!--						--><?php //if($product_id && $_product->is_in_stock()): ?>
<!--						<li class="bg-white">-->
<!--							<a class="button add_to_cart_button product_type_simple" href="?add-to-cart=--><?php //echo $product_id;?><!--" rel="nofollow" data-product_id="--><?php //echo $product_id;?><!--" data-product_sku="" data-quantity="1"><i data-icon="ios-cart-outline" data-icon-size="36" data-icon-color="--><?php //tt_primary( '#f4a3c4' ); ?><!--"></i></a>-->
<!--						</li>-->
<!--						--><?php //endif;?>

						<li class="bg-white">
							<a href="<?php the_permalink(); ?>"><i data-icon="ios-redo-outline" data-icon-size="36" data-icon-color="<?php tt_primary( '#f4a3c4' ); ?>"></i></a>
						</li>
					</ul>
				</div>
			</figcaption>
		</figure>
	<?php endif; ?>
</li>