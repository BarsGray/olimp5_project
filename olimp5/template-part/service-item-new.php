<?php 

	$class = $args['hide'] ? 'rb-service__item--hide' : '';

	$rb_services_checkbox = get_post_meta( get_the_ID(), '_rb_services_checkbox', true );
	$rb_services_price_hide = get_post_meta( get_the_ID(), '_rb_services_price_hide', true );

?>
<li class="rb-service__item <?php echo $class; ?>">
	<div class="rb-service__item-info">
		<a href="<?php echo get_permalink(); ?>" class="rb-service__item-title"><?php the_title(); ?></a>
		<p class="rb-service__item-desc"><?php the_content(); ?></p>
		<div class="rb-service__item-bot">
			<?php 
				$price = get_post_meta( get_the_ID(), '_rb_services_price', true );
				if( $price && ! $rb_services_price_hide ) : 
			?>
				<span class="rb-service__item-price"><?php echo $price; ?> ₽</span>
			<?php endif; ?>
			<?php if( $rb_services_checkbox ) : ?>
				<span class="rb-service__item-btn rb-button__orange">Перейти</span>
			<?php else : ?>
				<span class="rb-service__item-btn rb-button__orange js-open-modal" data-doctors="" data-programms="" data-service="<?php echo get_the_title(); ?>" data-servicestax="<?php echo $args['tax']; ?>">Записаться на прием</span>
			<?php endif; ?>
		</div>
	</div>

</li>