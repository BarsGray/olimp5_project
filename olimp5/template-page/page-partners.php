<?php 

get_header();

/*
Template Name: Партнёры
*/

$partners_desc = get_post_meta( get_the_ID(), '_rb_partners_desc', true );

?>


    <!-- about start -->
        <div class="rb-partners">
            <section class="rb-page__top">
                <div class="rb-container flex-wrap">
                    <div class="col-6 col-s-12  rb-page-header">
                            <h1><?php the_title();?></h1>
                    </div>
                    <div class="col-6 col-s-12">
                        <p class="rb-text">
                            <?php echo esc_html( $partners_desc ); ?>
                        </p>
                    </div>
                </div>
            </section>
            
            <?php  
            	if ( function_exists( 'carbon_get_post_meta' ) ) {

            		$rb_partners_list = carbon_get_post_meta( get_the_ID(), 'rb_partners_list' );

            	}

            	if ( $rb_partners_list ) : 
            ?>
	            <section class="rb-partners__list">
                    <div class="rb-container  flex-wrap">
                    	<?php foreach( $rb_partners_list as $rb_partner ) : ?>
    	                    <div class="col-4 col-m-6 col-s-12">
                                <div class="rb-partners__list-item">
<picture class="rb-partners__img">
    <?php
    echo wp_get_attachment_image(
        $rb_partner['img'],
        'rb_standart', // 660x380
        false,
        array(
            'class' => 'rb-img-contain', // предполагается object-fit: contain
            'sizes' => '(max-width: 576px) 100vw, 220px'
        )
    );
    ?>
</picture>
        	                        <div class="rb-partners__list-info">
        	                            <div class="rb-partners__list-title ">
        	                                <?php echo esc_html( $rb_partner['title'] ); ?>
        	                            </div>
        	                            <div class="rb-partners__list-text rb-text">
        	                                <?php echo  apply_filters( 'the_content', $rb_partner['desc'] ); ?>
        	                            </div>
                                        <?php if( $rb_partner['discount'] || $rb_partner['promo'] ) : ?>
                                            <div class="rb-partners__list-promo flex">
                                                <span class="rb-partners__list-discount"><?php echo esc_html( $rb_partner['discount'] ); ?></span>
                                                <span class="rb-partners__list-promocode"><?php echo esc_html( $rb_partner['promo'] ); ?></span>
                                            </div>
                                        <?php endif; ?>
        	                        </div>
                                </div>
    	                    </div>
    	                <?php endforeach; ?>
                    </div>
	            </section>
	        <?php endif; ?>
                   
            
        </div>
    <!-- about end -->
    <?php //get_template_part( 'template-part/forms/form', 'subscribe2' ); ?>

<?php get_footer(); ?>