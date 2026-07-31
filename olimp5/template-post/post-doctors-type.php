<?php
/*
 * Template Name: Doctors type
 * Template Post Type: post
 */
   
 get_header();

	$active_before = get_post_meta( get_the_ID(), '_rb_end_date', true );
	$rb_doctors_title = get_post_meta( get_the_ID(), '_rb_doctors_title', true );
	$rb_doctors_left = get_post_meta( get_the_ID(), '_rb_doctors_left', true );
	$rb_doctors_right = get_post_meta( get_the_ID(), '_rb_doctors_right', true );

	if ( function_exists( 'carbon_get_post_meta' ) ) {
		$rb_doctors_list = carbon_get_post_meta( get_the_ID(), 'rb_doctors_list' );
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
            <?php if ( $rb_doctors_list ) : ?>
				<section class="rb-set__infoblock">
					<div class="col-9 col-m-12 col-s-12 rb-title rb-doctors__title">
                    	<?php echo $rb_doctors_title; ?>
                 	</div>
                 	<?php

                 		$rb_doctors_list = array_map( function($val){ return $val['id']; }, $rb_doctors_list );

                 		// echo '<pre>';
                 		// print_r( $rb_doctors_list );

	                    $doctors = new WP_Query(
	                        array(
	                            'post_type' => 'doctors',
	                            'posts_per_page' => -1,
	                            'post__in' => $rb_doctors_list,
	                            'meta_query' => array(
	                                array(
	                                    'key'     => '_rb_doc_checkbox',
	                                    'value'   => 'yes',
	                                )
	                            )
	                        )
	                    );

	                    if( $doctors->have_posts() ) : 
	                ?>
	                    <ul class="rb-doctors__spec-list rb-doctors-list flex-wrap">
	                        <?php
	                            while( $doctors->have_posts() ){
	                                $doctors->the_post();

	                                get_template_part( 'template-part/doctors', 'item2' );
	 
	                            }
	                        ?>
	                    </ul>
	                <?php 
	                    endif;
	                    wp_reset_postdata();
                	?>
                 	<div class="flex-wrap rb-doctors-bottom">
                 		<?php if( $rb_doctors_left ) : ?>
                 			<div class="col-6 col-s-12 rb-doctors-left"><?php echo apply_filters( 'the_content', $rb_doctors_left ); ?></div>
                 		<?php endif; ?>
                 		<?php if( $rb_doctors_right ) : ?>
                 			<div class="col-6 col-s-12 rb-doctors-right"><?php echo apply_filters( 'the_content', $rb_doctors_right ); ?></div>
                 		<?php endif; ?>
                 	</div>
                 	<span class="rb-set-btn rb-button__orange-full js-open-modal">Записаться на приём</span>
				</section>
			<?php endif; ?>
			<?php get_template_part( 'template-part/forms/form', 'subscribe3' ); ?> 
		</div>

	</div>
<?php get_footer(); ?>