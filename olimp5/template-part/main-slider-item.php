<?php 
	
 $rb_slider_hide_title = get_post_meta( get_the_ID(), '_rb_slider_hide_title', true );
 $rb_slider_subtitle = get_post_meta( get_the_ID(), '_rb_slider_subtitle', true );
 $rb_slider_text = get_post_meta( get_the_ID(), '_rb_slider_text', true );
 $rb_slider_link = get_post_meta( get_the_ID(), '_rb_slider_link', true );
 $rb_slider_img = get_post_meta( get_the_ID(), '_rb_slider_img', true );
 $rb_slider_img_tab = get_post_meta( get_the_ID(), '_rb_slider_img_tab', true ) ?: $rb_slider_img;
 $rb_slider_img_mob = get_post_meta( get_the_ID(), '_rb_slider_img_mob', true );
 $rb_slider_text_color = get_post_meta( get_the_ID(), '_rb_slider_text_color', true ) ?: '#FFFFFF';
 $rb_slider_hide_text = get_post_meta( get_the_ID(), '_rb_slider_hide_text', true );
 $rb_slider_up_btn_mob = get_post_meta( get_the_ID(), '_rb_slider_up_btn_mob', true ) ? 'rb-main-slider-btn-up' : '';
 $rb_slider_img_mob_contain = get_post_meta( get_the_ID(), '_rb_slider_img_mob_contain', true ) ? 'contain' : 'cover';
 $rb_slider_font_size = get_post_meta( get_the_ID(), '_rb_slider_font_size', true );
 $rb_slider_text_on_the_bot = get_post_meta( get_the_ID(), '_rb_slider_text_on_the_bot', true );
 $rb_slider_desc_color = get_post_meta( get_the_ID(), '_rb_slider_desc_color', true ) ?: $rb_slider_text_color;
 $rb_slider_desc_size = get_post_meta( get_the_ID(), '_rb_slider_desc_size', true ) ?: $rb_slider_font_size;
 $rb_slider_btn_offset_mob = get_post_meta( get_the_ID(), '_rb_slider_btn_offset_mob', true );
 $rb_slider_btn_offset_left_desk = get_post_meta( get_the_ID(), '_rb_slider_btn_offset_left_desk', true );
 $rb_slider_title_color = get_post_meta( get_the_ID(), '_rb_slider_title_color', true ) ?: $rb_slider_text_color;
 $rb_slider_title_size = get_post_meta( get_the_ID(), '_rb_slider_title_size', true ) ?: $rb_slider_font_size;
 $rb_slider_text_btn_color = get_post_meta( get_the_ID(), '_rb_slider_text_btn_color', true ) ?: $rb_slider_text_color;
 $rb_slider_utm_link = get_post_meta( get_the_ID(), '_rb_slider_utm_link', true );
 $rb_slider_hide_btn = get_post_meta( get_the_ID(), '_rb_slider_hide_btn', true );
 $rb_slider_btn_text = get_post_meta( get_the_ID(), '_rb_slider_btn_text', true ) ?: 'Перейти';

if ( $rb_slider_utm_link ) {

	 $rb_slider_link =  $rb_slider_link . '?utm_source=' . $rb_slider_utm_link;

}


?>

<div class="swiper-slide" 
     id="<?php echo get_the_ID(); ?>" 
     onclick="window.location.href='<?php echo esc_url($rb_slider_link); ?>'"
     style="cursor:pointer;">
					
	<div class="rb-main__slide">
		<?php if( $rb_slider_hide_text && $rb_slider_hide_btn ): ?>
			<a href="<?php echo esc_url( $rb_slider_link ); ?>" class="rb-main__slide-wrap-link">
		<?php endif; ?>
			<div class="rb-main__slide--info" style="background-image: url('<?php echo wp_get_attachment_image_url( $rb_slider_img, 'full' ); ?>');<?php if ( $rb_slider_hide_text ) { echo 'height:100%;'; }?>" >
				<div class="rb-main__slide--tab-img <?php if ( ! $rb_slider_img_mob ) { echo 'rb-main__slide--tab-img-only'; } ?>" style=" <?php echo 'background-size: ' . $rb_slider_img_mob_contain . ';' ; if ( $rb_slider_img_tab ) : ?>background-image: url('<?php echo wp_get_attachment_image_url( $rb_slider_img_tab, 'large' ); ?>');<?php endif; ?>" >
					<div class="rb-main__slide--mob-img" style="<?php echo 'background-size: ' . $rb_slider_img_mob_contain . ';' ; if ( $rb_slider_img_mob ) : ?>background-image: url('<?php echo wp_get_attachment_image_url( $rb_slider_img_mob, 'large' ); ?>');<?php endif; ?>" >
						<?php if( ! $rb_slider_hide_title ): ?>
							<span class="rb-main__slide--title" style="color:<?php echo $rb_slider_title_color; ?>;font-size:<?php echo $rb_slider_title_size; ?>;">
								<?php the_title(); ?>
							</span>
						<?php endif; ?>

						<?php if( $rb_slider_subtitle ) : ?>
							<p class="rb-main-slider-title-sub" style="color:<?php echo $rb_slider_text_color; ?>;font-size:<?php echo $rb_slider_font_size; ?>;"><?php echo $rb_slider_subtitle; ?></p>
						<?php endif; ?>

						<?php if( $rb_slider_text ) : ?>
							<div class="rb-main__slide--text" style="color:<?php echo $rb_slider_desc_color; ?>;font-size:<?php echo $rb_slider_desc_size; ?>">
								<?php echo $rb_slider_text; ?>
							</div>
						<?php 
							endif; 
							if( $rb_slider_link && ! $rb_slider_hide_btn ) :
						?>
							<span class="rb-main__slide--link <?php if ( $rb_slider_btn_offset_mob ) { echo 'rb-main__slide--link-abs'; } ?><?php echo $rb_slider_up_btn_mob; ?>" href="<?php echo esc_url( $rb_slider_link ); ?>"  style="color:<?php echo $rb_slider_text_btn_color; ?>;border-color: <?php echo $rb_slider_text_btn_color; ?>;bottom: <?php echo $rb_slider_btn_offset_mob; ?>px;left:<?php echo $rb_slider_btn_offset_left_desk; ?>px;"><?php echo $rb_slider_btn_text; ?>
								<svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M1.25 1.5L8.75 9L1.25 16.5" stroke="<?php echo $rb_slider_text_btn_color; ?>" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</span>
						<?php 
							endif; 
							if( $rb_slider_text_on_the_bot ) :
						?>
							<span class="rb-main-slider-btn-sub"><?php echo esc_html( $rb_slider_text_on_the_bot ); ?></span>
						<?php endif; ?>
					</div>
				</div>

			</div>
		<?php if( $rb_slider_hide_text ): ?>
			</a>
		<?php endif; ?>
	</div>
	

</div>