<?php 

	$terms = get_the_terms( get_the_ID(), 'promo-cat' );
	$color = ( isset($terms) && $terms  ) ? get_term_meta( $terms[0]->term_id, '_rb_promo_color', true ) : '#F3951D30';
	$border_color = ( isset($terms) && $terms  ) ? get_term_meta( $terms[0]->term_id, '_rb_promo_bd_color', true ) : '#F3951D';
	$text_color = ( isset($terms) && $terms  ) ? get_term_meta( $terms[0]->term_id, '_rb_promo_text_color', true ) : '#F3951D';
	$rb_title = get_post_meta( get_the_ID(), '_rb_title', true );
	$rb_desc = get_post_meta( get_the_ID(), '_rb_desc', true );
	$rb_badge = get_post_meta( get_the_ID(), '_rb_badge', true ) ?: $terms[0]->name;
	$rb_time = get_post_meta( get_the_ID(), '_rb_time', true );
	$rb_question = get_post_meta( get_the_ID(), '_rb_question', true );
	$rb_check = get_post_meta( get_the_ID(), '_rb_check', true );
	$rb_check_link = get_post_meta( get_the_ID(), '_rb_check_link', true );

?>

<li class="rb-promo__item flex-wrap">
	<div class="rb-promo__item-info">
		<?php if( $terms && $rb_badge ) : ?>
			<span class="rb-promo__item-badge" style="background: <?php echo $color?>;border: 1px solid <?php echo $border_color; ?>;color: <?php echo $text_color; ?>;"><?php echo $rb_badge; ?></span>
		<?php endif; ?>
		<?php if( $rb_time ) : ?>
			<span class="rb-promo__item-time"><?php echo $rb_time; ?></span>
		<?php endif; ?>
		<h3 class="rb-promo__item-title"><?php echo $rb_title; ?></h3>
		<?php if ( $rb_desc ) : ?>
			<div class="rb-promo__item-desc">
				<?php echo apply_filters( 'the_content', $rb_desc ); ?>
			</div>
		<?php endif; ?>
		<div class="rb-promo__item-link">
			<?php if( $rb_question ) : ?>
				<div class="rb-promo__item-question">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path d="M7.879 5.519C9.05 4.494 10.95 4.494 12.121 5.519C13.293 6.544 13.293 8.206 12.121 9.231C11.918 9.41 11.691 9.557 11.451 9.673C10.706 10.034 10.001 10.672 10.001 11.5V12.25M19 10C19 11.1819 18.7672 12.3522 18.3149 13.4442C17.8626 14.5361 17.1997 15.5282 16.364 16.364C15.5282 17.1997 14.5361 17.8626 13.4442 18.3149C12.3522 18.7672 11.1819 19 10 19C8.8181 19 7.64778 18.7672 6.55585 18.3149C5.46392 17.8626 4.47177 17.1997 3.63604 16.364C2.80031 15.5282 2.13738 14.5361 1.68508 13.4442C1.23279 12.3522 1 11.1819 1 10C1 7.61305 1.94821 5.32387 3.63604 3.63604C5.32387 1.94821 7.61305 1 10 1C12.3869 1 14.6761 1.94821 16.364 3.63604C18.0518 5.32387 19 7.61305 19 10ZM10 15.25H10.008V15.258H10V15.25Z" stroke="#495B80" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					<span class="rb-promo__item-answer"><?php echo $rb_question; ?></span>
				</div>
			<?php endif; ?>
			<?php if ( $rb_check_link ) : ?>
				<a href="<?php echo esc_url( $rb_check_link );?>" class="rb-button__orange-full  rb-promo__btn-modal pr">Узнать подробнее</a>
			<?php else : ?>
				<a class="rb-button__orange-full  rb-promo__btn-modal js-open-modal pr">Узнать подробнее</a>
			<?php endif; ?>
		</div>
	</div>
	<div class="rb-promo__item-img">
			<?php if( has_post_thumbnail() ) : ?>
				<picture class="rb-promo__item-picture <?php if( $rb_check ) { echo 'rb-promo__item-picture--spec'; } ?>">
					<?php the_post_thumbnail( 'large', array( 'class' => 'rb-img-cover' ) ); ?>
				</picture>
			<?php else: ?>
				<picture class="rb-promo__item-picture">
					<img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/nopic.png" alt="<?php echo get_the_title(); ?>">
				</picture>
			<?php endif; ?>
	</div>
</li>
