<?php 

	$youtube = get_post_meta( get_the_ID(), '_rb_youtube', true );
	$youtube_time = get_post_meta( get_the_ID(), '_rb_duration', true );

?>


<a class="rb-video__item flex-wrap" data-fancybox="video" data-type="iframe" href="<?php echo $youtube; ?>" title="<?php echo get_the_title(); ?>">
	<div class="col-6 rb-video__item-info col-s-12">
		<div class="video__section--box--info">
			<div class="video__section--box--name"><?php echo get_the_title(); ?></div>
			<?php if( $youtube_time ): ?><div class="video__section--box--time">Продолжительность: <?php echo $youtube_time; ?></div><?php endif; ?>
		</div>

		<div class="video__section--box--view">
			<span>
				Смотреть 
				<svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M1.25 1.5L8.75 9L1.25 16.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</span>
		</div>
	</div>
	<div class="col-6 col-s-12">
		<picture class="card__image play_ rb-video__item-img">
			<?php the_post_thumbnail( 'rb-news' ); ?>
		</picture>
	</div>
</a>
