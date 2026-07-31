<?php 

	$tax = $args['info'];

	$rb_services_other_link = get_term_meta( $tax->term_id, '_rb_services_other_link', true ) ?: get_term_link( $tax->term_id );
	$rb_service_file = get_term_meta( $tax->term_id, '_rb_service_file', true );
	$rb_service_mainp_text = get_term_meta( $tax->term_id, '_rb_service_mainp_text', true ) ?: 'Перейти';
	// $rb_block_text_color = '#121212';
	// $rb_block_bg_color = 

?>


<a class="rb-services__item flex <?php if( ! $rb_service_file && ! has_post_thumbnail() ) { echo ' rb-services__item--alone'; } ?>" href="<?php echo $rb_services_other_link; ?>">
	<div class="rb-services__item--info">
		<h3 class="rb-services__item--title" ><?php echo $tax->name; ?></h3>
		<span class="rb-services__item--number">
			<?php echo $rb_service_mainp_text; ?>
			<svg width="10" height="17" viewBox="0 0 10 17" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M1.25 1L8.75 8.5L1.25 16" stroke="#121212" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
			</svg>
		</span>
	</div>
	<?php if( $rb_service_file ) : ?>
		<div class="rb-services__item--media">
			<?php if ( $rb_service_file ) : ?>
				<div class="rb-services__item--video">
					<video preload="none" muted autoplay loop >
						<source id="mp4" src="<?php echo wp_get_attachment_url($rb_service_file); ?>" type="video/mp4">
						<p>Your browser does not support HTML5 Video!</p>
					</video>
				</div>
			<?php elseif( has_post_thumbnail() ) : ?>
				<picture class="rb-services__item--image">
					<?php echo get_the_post_thumbnail( get_the_ID(), 'media' ); ?>
				</picture>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</a>