<?php 
	
	if ( $args ) { 

		$block_title = $args['title'];
		$block_link = $args['link'];
		$rb_block_link_name = 'Перейти';
		$rb_block_bg_color = $args['back_color'];
		$rb_block_text_color = $args['text_color'];
		$rb_bg_1 = $args['bg_1'];	
		$rb_bg_2 = $args['bg_2'];	

	} else {

		$video_arr = get_post_meta( get_the_ID(), '_rb_video', true );
		$block_title = get_the_title();
		$block_link = get_the_permalink();
		$rb_block_link_name = get_post_meta( get_the_ID(), '_rb_block_name', true ) ?: 'Перейти';
		$rb_block_bg_color = get_post_meta( get_the_ID(), '_rb_block_bg_color', true );
		$rb_block_text_color = get_post_meta( get_the_ID(), '_rb_block_text_color', true );	

		$rb_bg_1 = get_post_meta( get_the_ID(), '_rb_bg_1', true );	
		$rb_bg_2 = get_post_meta( get_the_ID(), '_rb_bg_2', true );	

	}

?>


<a class="rb-services__item flex<?php if( ! $video_arr && ! has_post_thumbnail() ) { echo ' rb-services__item--alone'; } ?>" href="<?php echo $block_link; ?>">
	<div class="rb-services__item--info1" <?php if( $rb_block_bg_color ) { echo 'style="background:' . $rb_block_bg_color . ';"'; }?>>
		<h3 class="rb-services__item--title" <?php if( $rb_block_text_color ) { echo 'style="color:' . $rb_block_text_color . ';"'; }?> ><?php echo $block_title; ?></h3>
		<span class="rb-services__item--number" <?php if( $rb_block_text_color ) { echo 'style="color:' . $rb_block_text_color . ';"'; }?> >
			<?php echo $rb_block_link_name; ?>
		</span>
		<picture class="rb-services__item--bg rb-services__item--bg1">
			<?php echo wp_get_attachment_image( $rb_bg_1, 'medium' ); ?>
		</picture>
		<picture class="rb-services__item--bg rb-services__item--bg2">
			<?php echo wp_get_attachment_image( $rb_bg_2, 'medium' ); ?>
		</picture>
	</div>
	<?php if( ( isset( $video_arr ) && $video_arr ) || has_post_thumbnail() ) : ?>
		<div class="rb-services__item--media">
			<?php if ( $video_arr ) : ?>
				<div class="rb-services__item--video">
					<video preload="none" muted autoplay loop >
						<source id="mp4" src="<?php echo $video_arr['SRC']; ?>" type="video/mp4">
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