<?php 

get_header();

/*
Template Name: Консультативное отделение
*/

$curr_list = array();
 
if ( function_exists( 'carbon_get_post_meta' ) ) {

    $curr_val = carbon_get_post_meta( get_the_ID(), 'rb_consult_list' );
    $curr_list = array_map( function($item) { return $item['id']; }, $curr_val );

}

$services_cats = get_terms( 
    array(
        'taxonomy' => 'services',
        'hide_empty' => false,
        'include' => $curr_list,
    ) 
);


?>


    <!-- programms start -->
    <div class="rb-services">
        
        <div class="rb-container">
            
            <div class="rb-container rb-page-header">
                <h1><?php the_title(); ?></h1>
            </div>
            <?php if ( $services_cats ) : ?>
                <div class="rb-services__list flex-wrap">
                    <?php 
                        foreach( $services_cats as $arItem ) : 

                            $rb_services_other_link = get_term_meta( $arItem->term_id, '_rb_services_other_link', true );

                            if ( !$rb_services_other_link ){

                                $rb_services_other_link = get_term_link( $arItem->term_id );

                            }

                        ?>

                        <div class="rb-services__serv flex-wrap">

                            <div class="rb-services__serv-text">
                                <h2 class="rb-services__serv-title"><?php echo $arItem->name; ?></h2>
                                <div class="rb-services__serv-desc rb-text">
                                    <?php echo term_description( $arItem->term_id ); ?>
                                </div>
                                <a href="<?php echo $rb_services_other_link; ?>" class="rb-services__serv-link rb-button__grey">
                                    Подробнее 
                                    <svg width="10" height="18" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1.25 1.5L8.75 9L1.25 16.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </div>
                            <?php 
                                $term_image = get_term_meta( $arItem->term_id, '_rb_service_image', true );
                                if ( $term_image ) :
                            ?>
<picture class="rb-services__serv-img">
    <?php
    echo wp_get_attachment_image(
        $term_image,
        'rb_standart_2x',
        false,
        array(
            'class' => 'rb-img-cover',
            // 'sizes' => '(max-width: 576px) 100vw, (max-width: 992px) 50vw, 330px'
        )
    );
    ?>
</picture>
                            <?php else : ?>
                                <picture class="rb-services__serv-img">
                                    <img class="rb-img-cover" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/nopic.png" alt="">
                                </picture>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    

<?php get_footer(); ?>