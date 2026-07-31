<?php

	$link = get_post_meta( get_the_ID(), '_rb_link', true ) ? trim( get_post_meta( get_the_ID(), '_rb_link', true )  ) : get_the_permalink();

?>

<li class="rb-programms__item flex-wrap">
	<div class="col-6 col-m-12 rb-programms__item-img">
		<a href="<?php echo $link; ?>">
			<?php if( has_post_thumbnail() ) : ?>
				<picture class="rb-programms__item-picture">
					<?php the_post_thumbnail( 'rb_programms_2x', array( 'class' => 'rb-img-cover' ) ); ?>
				</picture>
			<?php else: ?>
				<picture class="rb-programms__item-picture">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/nopic.png" alt="<?php echo get_the_title(); ?>">
				</picture>
			<?php endif; ?>
		</a>
	</div>
	<div class="col-6 col-m-12 rb-programms__item-info">
		<h2 class="rb-programms__item-title"><?php the_title(); ?></h2>
		<p class="rb-programms__item-subtitle">
			<?php //echo $arItem["PROPERTIES"]['SUBHEADER']['VALUE']; ?>
		</p>
		<?php 
			$for_whom = get_post_meta( get_the_ID(), '_rb_for_whom', true );
			$rb_price_short_desc = get_post_meta( get_the_ID(), '_rb_price_short_desc', true );
			if( $rb_price_short_desc ) :
		?>
			<div class="rb-programms__item-text">
				<?php echo apply_filters( 'the_content', $rb_price_short_desc ); ?>
			</div>
		<?php 
			elseif( $for_whom ) : 
		?>
			<div class="rb-programms__item-text">
				<span>Кому подходит:</span>
				<?php echo apply_filters( 'the_content', $for_whom ); ?>
			</div>
		<?php endif; ?>
		<div class="rb-programms__item-bot flex-wrap">
			<a href="<?php echo $link; ?>" class="rb-button__grey rb-programms__item-link">
				Подробнее
				<svg width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M0.875 0.75L7.125 7L0.875 13.25" stroke="#717171" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</a>

			<?php 
				$rb_price = get_post_meta( get_the_ID(), '_rb_price', true );
				if( $rb_price ) : 
			?>
				<p class="rb-programms__item-price">
						<!-- <span>от</span>  -->
					<?php echo $rb_price; ?>&nbsp;₽
				</p>
			<?php endif; ?>


		</div>
	</div>
</li>
