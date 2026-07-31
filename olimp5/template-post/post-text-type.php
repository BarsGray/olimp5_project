<?php

	/*
	 * Template Name: Text type
	 * Template Post Type: post
	 */
   
 	get_header();

	$active_before = get_post_meta( get_the_ID(), '_rb_end_date', true );
	$rb_text_title = get_post_meta( get_the_ID(), '_rb_text_title', true );
	$rb_text = get_post_meta( get_the_ID(), '_rb_text', true );
	$rb_text_additional = get_post_meta( get_the_ID(), '_rb_text_additional', true );

?>

	<div class="rb-news-page rb-page">
		<div class="rb-container">
			<section class="rb-about__top">
                <div class="flex-wrap">
                    <div class="col-6 col-s-12">
                        <div class=" no_padding rb-page-header">
                            <h1><?php the_title(); ?></h1>
                        </div>
                        <picture class="rb-school__top-img2">
                            <?php echo get_the_post_thumbnail( get_the_ID(), 'rb_standart', array( 'class' => 'rb-img-cover' ) ); ?>
                        </picture>
                        <div class="rb-school__top-desc rb-text">
                            <?php the_content(); ?>
                        </div>
                    </div>
                    <div class="col-6 col-s-12">
                        <picture class=".rb-text__top-img">
                            <?php echo get_the_post_thumbnail( get_the_ID(), 'rb_standart_2x', array( 'class' => 'rb-img-cover' ) ); ?>
                        </picture>
                    </div>
                </div>
            </section>
            <?php if ( $rb_text ) : ?>
				<section class="rb-set__infoblock">
					<div class="col-9 col-m-12 col-s-12 rb-title rb-doctors__title">
	                	<?php echo $rb_text_title; ?>
	             	</div>
	             	<div class="col-6 col-s-12 rb-text-desc">
	                	<?php echo apply_filters( 'the_content', $rb_text ); ?>
	             	</div>
	             	<div class="col-6 col-s-12 rb-text-additional">
	                	<?php echo apply_filters( 'the_content', $rb_text_additional ); ?>
	             	</div>
	             	
	             	<span class="rb-set-btn rb-button__orange-full js-open-modal">Записаться на приём</span>
				</section>
			<?php endif; ?>
			<?php get_template_part( 'template-part/forms/form', 'subscribe3' ); ?> 
		</div>

	</div>
<?php get_footer(); ?>