<div class="swiper-slide rb-about__review">
	<div class="rb-about__review-wrap">
		<?php
			$date = date( 'Y-n-d' );
			$rb_reviews_title = get_post_meta( get_the_ID(), '_rb_reviews_title', true );
		?>
	
		<span class="rb-about__reviews--title"><?php echo esc_html( $rb_reviews_title ); ?></span>
		<div class="rb-about__reviews--text"><?php the_content(); ?></div>
		<span class="rb-about__reviews--name"><?php the_title(); ?></span>
		
		<time datetime="<?php echo $date; ?>" class="rb-about__reviews--date"><?php echo date_i18n("F Y"); ?></time>
	</div>
</div>