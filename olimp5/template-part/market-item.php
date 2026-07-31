<li class="rb-market__item col-3 col-m-4 col-s-12" id="<?php echo get_the_ID(); ?>">
	<a href="<?php the_permalink(); ?>" class="rb-market__item-link">

		<picture class="rb-market__item-image">
			<?php echo get_the_post_thumbnail( get_the_ID(), 'medium' ); ?>
			<span class="rb-market__item-look">Просмотреть товар</span>
		</picture>

		<div class="rb-market__item-info">
			<?php 

				$rb_market_country = get_post_meta( get_the_ID(), '_rb_market_country', true );
				$rb_market_volume = get_post_meta( get_the_ID(), '_rb_market_volume', true );
				$rb_market_price = get_post_meta( get_the_ID(), '_rb_market_price', true );

				if ( $rb_market_country ) : 
			?>
				<div class="rb-market__item-producer">
					<?php echo esc_html( $rb_market_country ); ?>
				</div>
			<?php endif; ?>
			<span class="rb-market__item-name"><?php the_title(); ?></span>
			<div class="rb-market__item-bottom">
				<?php if ( $rb_market_volume ) : ?>
					<div class="rb-market__item-volume">
						<?php echo esc_html( $rb_market_volume ); ?>
					</div>
				<?php 
					endif; 
					if ( $rb_market_price ) :
				?>
					<div class="rb-market__item-price">
						<?php echo number_format( floatval( str_replace( array( ' ' ), '', $rb_market_price ) ), 0, '.', '.' ); ?> ₽
					</div>	
				<?php endif; ?>
				<button	class="rb-market__item-btn">
					Подробнее
				</button>
			</div>
		</div>

	</a>
</li>