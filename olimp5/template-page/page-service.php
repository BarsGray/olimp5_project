<?php 

get_header();

/*
Template Name: Страница услуги
*/

    if ( function_exists( 'carbon_get_post_meta' ) ) {

        $sections = carbon_get_the_post_meta( 'service_construction' );

    }

    $rb_services_desc = get_post_meta( get_the_ID(), '_rb_services_desc', true );
    $rb_services_btn_hide = get_term_meta( get_the_ID(), '_rb_services_btn_hide', true );
    $rb_services_btn_name = get_post_meta( get_the_ID(), '_rb_services_btn_name', true ) ?: 'Записаться на приём';

?>


    <div class="rb-service-page rb-page">

        <div class="rb-container">

            <!-- service start -->
            <section class="rb-service__top flex-wrap">
                <div class="col-6 col-s-12 rb-service__top-text rb-page-header">
                    <h1><?php the_title(); ?></h1>

                    <picture class="col-6 col-s-12 rb-service__top-img-mob">
                        <?php echo get_the_post_thumbnail( get_the_ID(), 'rb_standart', '', array( 'class' => 'rb-img-cover' ) ); ?>
                    </picture>
                    <div class="rb-text rb-service__top-desc">
                        <?php echo apply_filters( 'the_content', $rb_services_desc ); ?>
                    </div>
                    <?php if ( ! $rb_services_btn_hide ) : ?>
                        <div class="rb-service__top-link rb-button__orange-bg js-open-modal" data-modal="1">
                            <?php echo $rb_services_btn_name; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <picture class="col-6 col-s-12 rb-service__top-img">
                    <?php echo get_the_post_thumbnail( get_the_ID(), 'rb_standart' ); ?>
                </picture>
            </section>
            <?php 
                if ( $sections ) :
                    while ( have_posts() ) : the_post(); 
                    $count = 0;
                    foreach ( $sections as $section ) {
                        switch ( $section['_type'] ) {
                            case 'rb_infoblock':
                                ?>
                                    <section class="rb-service__section">
                                         <div class="flex-wrap">
                                            <h2 class="rb-title col-12 col-m-12"><?php echo esc_html( $section['infoblock_title']); ?></h2>
                                            <div class="rb-service__section--text col-12 col-m-12"><?php echo apply_filters( 'the_content', $section['infoblock_desc']); ?></div>
                                        </div>
                                    </section>
                                <?php
                                break;
                            case 'rb_services_w_text':
                                ?>
                                <section class="rb-service__section">
                                    <h2 class="rb-title"><?php echo esc_html( $section['w_text_title'] ); ?></h2>
                                    <div class="col-6 col-m-12">
                                        <div class="rb-service__mid-text">
                                            <?php echo apply_filters( 'the_content', $section['w_text_desc'] ); ?>
                                        </div>
                                    </div>
                                    <ul class="rb-pricelist__wrap">

                                        <?php foreach( $section['w_text_list'] as $arItem ) :  ?>

                                            <li class="rb-pricelist__item f-jcsb-center flex-wrap" id="<?php echo $arItem['id'];?>">
                                                <div class="col-10 col-s-12 flex-wrap">
                                                    <p class="rb-pricelist__item-name col-s-12 col-10"><?php echo get_the_title( $arItem['id'] ); ?></p>
                                                    <span class="rb-pricelist__item-price col-s-12 col-2"><?php echo get_post_meta( $arItem['id'], '_rb_serv_price', true ); ?>&nbsp₽</span>
                                                </div>
                                                <div class="col-2 col-s-12 rb-pricelist__item-btn--wrap">
                                                    <span class="rb-pricelist__item-btn rb-button__orange-full rb-doctors-page__btn js-open-modal doctor2" data-doctors="<?php echo get_the_title(); ?>" data-programms="" data-service="" data-servicestax="">Записаться</span>
                                                </div>
                                            </li>

                                        <?php endforeach; ?>
                                    </ul>
                                </section>
                                <?php
                                break;
                            case 'rb_services_w_photo':


                                    $services_ids = array_map( function($item){ return $item['id']; }, $section['w_photo_list'] );
                                    $add_services = new WP_Query(
                                        array(
                                            'post_type' => 'service',
                                            'post__in' => $services_ids,
                                        )
                                    );
                                ?>
                                <section class="rb-service__section ">
                                    <h2 class="rb-title"><?php echo esc_html( $section['w_photo_title'] ); ?></h2>
                                    <div class="col-6 col-m-12">
                                        <div class="rb-service__mid-text">
                                            <?php echo apply_filters( 'the_content', $section['w_photo_desc'] ); ?>
                                        </div>
                                    </div>
                                    <?php if( $add_services->have_posts() ) : ?>
                                        <ul class="rb-service__list flex-wrap">
                                            <?php
                                                while( $add_services->have_posts() ) {
                                                    $add_services->the_post();

                                                    get_template_part( 'template-part/service', 'item', array( 'tax' => '' ) );

                                                }
                                            ?>
                                        </ul>
                                    <?php endif; ?>
                                </section>
                                <?php
                                break;
                            case 'rb_serv_doctors':

                                $rb_serv_doctors_title = $section['rb_serv_doctors_title'] ?: 'Специалисты';
                                ?>
                                <section class="rb-service__section rb-doctors">
                                    <h2 class="rb-title"><?php echo $rb_serv_doctors_title; ?></h2>
                                    <ul class="rb-doctors__spec-list flex-wrap">
                                        <?php 
                                            foreach( $section['rb_serv_doctors_list'] as $rb_doctor ) : 

                                                $post_status = get_post_status( $rb_doctor['id'] );

                                                if ( $post_status && $post_status == 'publish' ) :
                                                    ?>
                                                    
                                                    <li class="col-3 col-m-4 col-s-12 rb-doctors__spec-item">
                                                        <div class="rb-doctors__spec-item--wrap"> 
                                                            <picture class="rb-doctors__spec-picture">
                                                                <?php echo get_the_post_thumbnail( $rb_doctor['id'], 'medium', array( 'class' => 'rb-img-contain' ) ); ?>
                                                            </picture>
                                                            <div class="rb-doctors__spec-info">
                                                                <a href="<?php echo get_the_permalink( $rb_doctor['id'] ); ?>" class="rb-doctors__spec-fio">
                                                                    <?php

                                                                        $fio = explode( ' ', get_the_title( $rb_doctor['id'] ) );
                                                                        echo $fio[0] . '<br>' . $fio[1] . ' ' . $fio[2]; 

                                                                    ?>
                                                                </a>
                                                                <span class="rb-doctors__spec-occup"><?php echo get_post_meta( $rb_doctor['id'], '_rb_doc_occup', true ); ; ?></span>
                                                                <?php
                                                                    $stage = get_post_meta( $rb_doctor['id'], '_rb_doc_stage', true );
                                                                    if( $stage ) :
                                                                        $d1 = new DateTime('now');
                                                                        $d2 = new DateTime( date('Y-m-d', strtotime( $stage ) ) );
                                                                        $diff = $d2->diff( $d1 );
                                                                ?>
                                                                    <p class="rb-doctors__spec-standing">
                                                                        <span>Стаж</span>
                                                                        <?php echo $diff->y . ' ' . rb_years_declension( $diff->y ); ?>               
                                                                    </p>
                                                                <?php endif; ?>
                                                                    <span class="rb-doctors__spec-btn rb-button__orange-full js-open-modal doctor2" data-doctors="<?php echo get_the_title( $rb_doctor['id'] ); ?>" data-programms="" data-service="" data-servicestax="">
                                                                        Запись на прием
                                                                    </span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <?php
                                                endif;
                                            endforeach;
                                        ?>
                                    </ul>
                                </section>
                                <?php
                                break;
                            case 'rb_advantages':
                                ?>
                                <section class="rb-service__section">
                                    <ul class="rb-container flex-wrap">
                                        <?php foreach( $section['rb_advantages_tabs'] as $rb_tabs ) : ?>
                                            <li class="rb-competations__tabs--item">
                                                <span class="rb-competations__tabs--title"><?php echo $rb_tabs['title']; ?></span>
                                                <p class="rb-competations__tabs--text"><?php echo esc_html( $rb_tabs['desc'] ); ?></p>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </section>
                                <?php
                                break;
                            case 'rb_serv_programms':

                                    $programms_ids = array_map( function($item){ return $item['id']; }, $section['rb_serv_programms_list'] );

                                    if ( $programms_ids ) : 

                                        $rb_serv_programms_title = $section['rb_serv_programms_title'] ?: 'Программы';
                                ?>

                                    <section class="rb-service__section rb-programms">
                                        <h2 class="rb-title"><?php echo $rb_serv_programms_title; ?></h2>
                                        <div class="rb-programms__list-wrap flex-wrap">
                                            <?php 
                                                $programms_query = new WP_Query(
                                                    array(
                                                        'post_type' => 'programms',
                                                        'posts_per_page' => -1,
                                                        'order' => 'DESC',
                                                        // 'post_parent' => 0,
                                                        'post__in' => $programms_ids,
                                                    )
                                                );

                                            ?>
                                                <ul class="rb-programms__list flex-wrap">
                                                    <?php 
                                                        while( $programms_query->have_posts() ) {
                                                            $programms_query->the_post();

                                                            get_template_part( 'template-part/programms', 'item' );

                                                        }
                                                    ?>
                                                </ul>
                                        </div>
                                    </section>
                                <?php
                                    endif;
                                break;
                             }
                             $count++;
                        }
                    endwhile; 
                endif;
            ?>
        </div>

    </div>
    <?php //get_template_part( 'template-part/forms/form', 'subscribe' ); ?>
<?php get_footer(); ?>