<?php 

get_header();

/*
Template Name: Все программы
*/


if( function_exists( 'carbon_get_post_meta' ) ) {

    $image = carbon_get_post_meta( get_the_ID(), 'image' );
    $image_mob = carbon_get_post_meta( get_the_ID(), 'image_mob' );

}

?>


        <div class="rb-programms rb-page-2">
            <div class="rb-container">
                <section class="rb-school__top">
                    <div class="rb-school__banner js-open-modal">
                        <img class="rb-school__img" src="<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>">
                        <img class="rb-school__img-mob" src="<?php echo wp_get_attachment_image_url( $image_mob, 'large' ); ?>">
                    </div>
                </section>
                <!-- <div class="rb-page-header">
                    <h1><?php //the_title(); ?></h1>
                </div>
                <section class="rb-programms__content">
                        <?php //the_content(); ?>
                    <div class="rb-container flex-wrap rb-programms__content--inner">
                        <div class="col-6 col-s-12 rb-programms__content--inner_left">
                            <div class="rb-programms__content--title">
                                Оздоровительные, реабилитационные, детские и спа-программы Разработаны специально для вас
                            </div>
                            <div class="rb-programms__content--subtitle">
                                Учитывая индивидуальные потребности и особенности вашего организма
                            </div>
                            <p class="rb-programms__content--text">
                                Не упустите возможность улучшить своё самочувствие и насладиться приятными моментами заботы о себе!
                            </p>
                            <div class="rb-button__grey js-open-modal" >
                                Записаться
                            </div>
                        </div>
                        <div class="col-6 col-s-12">
                            <picture class="rb-about__top-img rb-programms__content--img">
                                <?php //the_post_thumbnail( 'large', array( 'class' => 'rb-img-cover' ) ); ?>
                            </picture>
                        </div>
                    </div>
                </section> -->
                <?php 

                    $programms_type_list = get_terms( array( 'taxonomy' => 'programms-type' ) );

                    if ( $programms_type_list && ! is_wp_error( $programms_type_list ) ) :

                ?>
                    <section>

                        <ul class="rb-programms__category flex">
                            <?php 
                                echo '<li class="rb-programms_cat--item rb-programms__curr" data-termid="0">Все</li>';
                                $i == 0;
                                foreach ( $programms_type_list as $programms_type_list_item ) {

                                    $curr_class = '';

                                    // if ( $i  < 1 ) {
                                    //     $curr_class = 'rb-programms__curr';
                                    // }

                                    echo '<li class="rb-programms_cat--item ' . $curr_class . '" data-termid="'.  $programms_type_list_item->term_id . '">' . $programms_type_list_item->name . '</li>';

                                    $i++;
                                }
                            ?>
                        </ul>
                        <div class="rb-programms__list-wrap flex-wrap">
                            <?php 
                                $programms_query = new WP_Query(
                                    array(
                                        'post_type' => 'programms',
                                        'posts_per_page' => 4,
                                        'order' => 'DESC',
                                        'post_parent' => 0,
                                    )
                                );
                            ?>
                            <div class="rb-programms__list--wrap rb-programms__list-curr" data-termid="0">
                                <ul class="rb-programms__list flex-wrap">
                                    
                                    <?php 
                                        while( $programms_query->have_posts() ) {
                                            $programms_query->the_post();

                                            get_template_part( 'template-part/programms', 'item' );

                                        }

                                    ?>

                                </ul>
                                <?php 

                                    $count_pr = $programms_query->found_posts;

                                    if ( $count_pr > 4 ) {

                                        echo '<div class="rb-programms__more">
                                                <span class="rb-button__grey">Показать ещё</span>
                                              </div>';

                                        $programms2 = new WP_Query(
                                            array(
                                                'post_type' => 'programms',
                                                'posts_per_page' => 9999,
                                                'offset' => 4,
                                                'order' => 'DESC',
                                                'post_parent' => 0,
                                            )
                                        );

                                ?>
                                    <ul class="rb-programms__list rb-programms__list--hide flex-wrap ">
                                        
                                        <?php 
                                            while( $programms2->have_posts() ) {
                                                $programms2->the_post();

                                                get_template_part( 'template-part/programms', 'item' );

                                            }

                                        ?>

                                    </ul>
                                <?php } ?>
                            </div>
                            <?php 
                                $ii == 0;
                                foreach ( $programms_type_list as $programms_type_list_item ) :

                                    $curr_class2 = '';

                                    // if ( $ii  < 1 ) {
                                    //     $curr_class2 = 'rb-programms__list-curr';
                                    // }

                                    $programms_query = new WP_Query(
                                        array(
                                            'post_type' => 'programms',
                                            'posts_per_page' => 4,
                                            'order' => 'DESC',
                                            'tax_query' => array(
                                                array(
                                                    'taxonomy' => 'programms-type',
                                                    'field' => 'term_id',
                                                    'terms' => array($programms_type_list_item->term_id)
                                                )
                                            ),
                                            'post_parent' => 0,
                                        )
                                    );
                                    ?>
                                        <div class="rb-programms__list--wrap <?php echo $curr_class2; ?>" data-termid="<?php echo $programms_type_list_item->term_id; ?>">
                                            <ul class="rb-programms__list flex-wrap">
                                                
                                                <?php 
                                                    while( $programms_query->have_posts() ) {
                                                        $programms_query->the_post();

                                                        get_template_part( 'template-part/programms', 'item' );

                                                    }

                                                ?>

                                            </ul>
                                            <?php 

                                                $count_pr = $programms_query->found_posts;

                                                if ( $count_pr > 4 ) {

                                                    echo '<div class="rb-programms__more">
                                                            <span class="rb-button__grey">Показать ещё</span>
                                                          </div>';

                                                    $programms2 = new WP_Query(
                                                        array(
                                                            'post_type' => 'programms',
                                                            'posts_per_page' => 9999,
                                                            'offset' => 4,
                                                            'order' => 'DESC',
                                                            'tax_query' => array(
                                                                array(
                                                                    'taxonomy' => 'programms-type',
                                                                    'field' => 'term_id',
                                                                    'terms' => array($programms_type_list_item->term_id)
                                                                )
                                                            ),
                                                            'post_parent' => 0,
                                                        )
                                                    );

                                            ?>
                                                <ul class="rb-programms__list rb-programms__list--hide flex-wrap ">
                                                    
                                                    <?php 
                                                        while( $programms2->have_posts() ) {
                                                            $programms2->the_post();

                                                            get_template_part( 'template-part/programms', 'item' );

                                                        }

                                                    ?>

                                                </ul>
                                            <?php } ?>
                                        </div>
                                    <?php 
                                    $ii++;
                                endforeach; 
                                ?>
                        </div>
                    </section>
                <?php endif; ?>
            </div>
        </div>

   <section>
        <?php get_template_part( 'template-part/forms/form', 'programms' ); ?>
    </section>
    <section class="footer-disclaimer rb-bg-white">
        <div class="rb-container flex footer-disclaimer__wrapper">
            <div class="footer-disclaimer__sign">
                <svg width="34" height="35" viewBox="0 0 34 35" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0.25" y="0.849609" width="33.5" height="33.5" rx="16.75" fill="#FFD19A"/>
                    <rect x="0.25" y="0.849609" width="33.5" height="33.5" rx="16.75" stroke="#FFD19A" stroke-width="0.5"/>
                    <path d="M15.497 19.1765C15.5692 19.914 15.6917 20.4621 15.8604 20.8237C16.0309 21.1841 16.3346 21.3641 16.7716 21.3639C16.8534 21.3639 16.9265 21.3514 16.9991 21.3368C17.0735 21.3514 17.1463 21.3639 17.2287 21.3639C17.6653 21.3639 17.9686 21.1839 18.1387 20.8237C18.3083 20.4621 18.4287 19.914 18.5025 19.1765L18.8909 13.4822C18.9634 12.3723 18.9998 11.5758 19 11.0925C19.0002 10.4345 18.8246 9.92118 18.4733 9.55247C18.1206 9.18396 17.6572 8.99981 17.0833 9C17.0526 9 17.0297 9.0067 16.9997 9.00787C16.9712 9.0067 16.9477 9 16.9179 9C16.3428 9 15.8803 9.18387 15.5282 9.55247C15.1763 9.92176 15.0002 10.4352 15 11.0928C14.9998 11.5761 15.0363 12.3726 15.1094 13.4824L15.497 19.1765ZM17.0143 23.4453C16.457 23.4453 15.9835 23.6176 15.5903 23.962C15.1974 24.3068 15.0008 24.7249 15.0006 25.2164C15.0006 25.7709 15.1999 26.2074 15.5954 26.5245C15.9927 26.8415 16.4562 27 16.9857 27C17.5248 27 17.9953 26.8435 18.3972 26.5306C18.7987 26.2182 18.9994 25.7794 18.9994 25.217C18.9994 24.7255 18.8073 24.3074 18.423 23.9626C18.039 23.6176 17.5691 23.4451 17.0134 23.4453" fill="#FF9B23"/>
                </svg>
            </div>
            <div class="footer-disclaimer__text">
                <span class="footer-disclaimer__text-title">
                    Уважаемые пациенты!
                </span>
                <p class="footer-disclaimer__text-main">
                    Указанные на сайте цены не являются публичной офертой. Для уточнения стоимости услуг и записи на процедуру обращайтесь по телефону <a href="tel:+74732111540">+7 (473) 211–15–40</a>
                </p>
            </div>
        </div>
    </section>
<?php get_footer(); ?>