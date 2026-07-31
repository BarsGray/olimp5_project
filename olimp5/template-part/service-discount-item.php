<div class="rb-service__item swiper-slide">
	<!-- <div> -->

		<picture class="col-12 rb-service__item-image">
			<?php echo get_the_post_thumbnail( get_the_ID(), 'medium', array( 'class' => 'rb-img-cover' ) ); ?>
		</picture>
		<div class="rb-service__item-info">
			<a href="<?php echo get_permalink(); ?>" class="rb-service__item-title"><?php the_title(); ?></a>
			<p class="rb-service__item-desc"><?php echo get_post_meta( get_the_ID(), '_rb_services_desc', true ); ?></p>
			<div class="rb-service__item-bot">
				<?php 
					$price = get_post_meta( get_the_ID(), '_rb_services_price', true );
					if( $price ) : 
				?>
					<span class="rb-service__item-price"><?php echo $price; ?> ₽</span>
				<?php endif; ?>
			</div>
		</div>
	<!-- </div> -->
</div>