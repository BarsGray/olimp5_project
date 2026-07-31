<?php
/*
 * Template Name: Set big type
 * Template Post Type: post
 */
   
 get_header();

	$active_before = get_post_meta( get_the_ID(), '_rb_end_date', true );
	$rb_set_title = get_post_meta( get_the_ID(), '_rb_set_title', true );

	if ( function_exists( 'carbon_get_post_meta' ) ) {
		$rb_set_list = carbon_get_post_meta( get_the_ID(), 'rb_big_set_list' );
	}



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
            <?php if ( $rb_set_list ) : ?>
				<section class="rb-set__infoblock">
					<div class="col-6 col-m-12 col-s-12 rb-title rb-set__title">
                    	<?php echo $rb_set_title; ?>
                 	</div>
                 	<ul class="rb-set-list flex-wrap">
	                 	<?php foreach ( $rb_set_list as $rb_set_item ) : ?>
	                 		<li class="col-4 col-m-6 col-s-12 rb-set-item">
	                 			<div class="rb-set-item-inner">
		                 			<span class="rb-set-item-title"><?php echo $rb_set_item['title']; ?></span>
		                 			<p class="rb-set-item-desc"><?php echo $rb_set_item['text']; ?></p>
		                 			<?php foreach ( $rb_set_item['params'] as $params ) : ?>
		                 				<span class="rb-set-item-title"><?php echo $params['title']; ?></span>
		                 				<p class="rb-set-item-desc"><?php echo $params['text']; ?></p>
		                 			<?php endforeach; ?>
		                 			<?php if( $rb_set_item['price'] ) : ?>
		                 				<p class="rb-set-big-price"><span>Стоимость: </span><span class="rb-set-big-price-result"><?php echo $rb_set_item['price']; ?></span></p>
		                 			<?php endif; ?>
	                 			</div>
	                 		</li>
	                 	<?php endforeach; ?>
                 	</ul>
                 	<span class="rb-set-btn rb-button__orange-full js-open-modal">Записаться на приём</span>
				</section>
			<?php endif; ?>
			<?php get_template_part( 'template-part/forms/form', 'subscribe3' ); ?> 
		</div>

	</div>
<?php get_footer(); ?>