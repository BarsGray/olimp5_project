<?php 

get_header();

/*
Template Name: Школа здоровья
*/

if( function_exists( 'carbon_get_post_meta' ) ) {

    $speakers_list = carbon_get_post_meta( get_the_ID(), 'rb_speakers' );
    $reviews_list = carbon_get_post_meta( get_the_ID(), 'rb_reviews' );
    $image = carbon_get_post_meta( get_the_ID(), 'image' );
    $image_mob = carbon_get_post_meta( get_the_ID(), 'image_mob' );

}


?>
	<div class="rb-school rb-page-2">
         <div class="rb-container">
            <section class="rb-school__top">
                <!-- <div class="flex-wrap rb-school__top-wrap"> -->
                    <a class="rb-school__banner" href="https://olimp5.ru/raspisanie-meropriyatij/">
                        <img class="rb-school__img" src="<?php echo wp_get_attachment_image_url( $image, 'full' ); ?>">
                        <img class="rb-school__img-mob" src="<?php echo wp_get_attachment_image_url( $image_mob, 'large' ); ?>">
                    </a>
                    <!-- <div class="rb-school__top-left">
                        <div class="rb-school-header">
                            <h1><?php //the_title(); ?></h1>
                        </div>
                        <div class="rb-school__top-desc rb-text">
                            <?php //the_content(); ?>
                        </div>
                        <a href="https://olimp5.ru/raspisanie-meropriyatij/" class="rb-school__top-btn rb-button__orange">
                            Расписание мероприятий
                        </a>
                    </div>
                    <div class="rb-school__top-right">
                        <picture class="rb-school__top-img">
                            <?php 
                                // if( has_post_thumbnail() ) : 
                                //     the_post_thumbnail( 'large' );
                                // else : 
                            ?>
                                <img src="<?php //echo get_stylesheet_directory_uri(); ?>/assets/img/school_main.jpg" alt="" class="rb-img-cover">
                            <?php //endif; ?>
                        </picture>
                    </div> -->
                <!-- </div> -->
            </section>
            <section class="rb-school__infoblock">
                 <div class="col-9 col-m-12 col-s-12 rb-title">
                     В рамках <span>«Института здоровой семьи»</span> проводятся онлайн-семинары, конференции и мастер-классы.
                 </div>
                 <div class="flex-wrap rb-school__infoblock-wrap">
                     <p class="col-6 col-m-12 col-s-12 rb-text rb-school__infoblock-desc">
                             Участие бесплатное по предварительной записи. Спикеры – ведущие российские эксперты в различных областях медицины. В программу входит обзорная экскурсия по «Олимп Пять», презентация возможностей 17 мировых курортов в центре нашего города.
                     </p>
                     <p class="col-6 col-m-12 col-s-12 rb-text rb-school__infoblock-desc">
                            Мы верим в важность профилактики и своевременного лечения, и готовы помочь вам оставаться здоровыми и активными как можно дольше.
                     </p>
                 </div>
            </section>
            <?php
               // $school_videos = new WP_Query(
               //      array(
               //          'post_type' => 'videos',
               //          'posts_per_page' => -1,
               //          'tax_query' => array(
               //              array(
               //                  'taxonomy' => 'videos-type',
               //                  'field' => 'term_id',
               //                  'terms' => array(57)
               //              )
               //          )
               //      )
               // );

               $videos_tax = get_terms( array( 'taxonomy' => 'videos-type', 'parent' => 0  ) );

               if( $videos_tax && ! is_wp_error( $videos_tax ) ) :

                    foreach( $videos_tax as $videos_tax_item ) :
                        $children_tax = get_terms( array( 'taxonomy' => 'videos-type', 'parent' => $videos_tax_item->term_id ) );
                        ?>
                           <section class="rb-school__archive rb-school__archive-video">
                                <h2 class="rb-title"><?php echo $videos_tax_item->name; ?></h2>
                                <?php if( $children_tax && ! is_wp_error( $children_tax ) ) : ?>
                                    <div class="rb-news__menu rb-container rb-school__archive-menu">
                                        <ul>
                                            <?php 
                                                $i = 1;
                                                foreach( $children_tax as $children_tax_item ) : 
                                                    ?>
                                                        <li>
                                                            <span class="rb-school__archive-menu--item rb-school__archive-menu--tab <?php if( $i == 1 ) : ?>active<?php endif; ?>" data-tab="<?php echo $children_tax_item->term_id; ?>"><?php echo $children_tax_item->name; ?></span>
                                                        </li> 
                                                    <?php 
                                                    $i++;
                                                endforeach; 
                                            ?>  
                                        </ul>
                                    </div>
                                <?php 
                                    endif; 
                                    if( $children_tax && ! is_wp_error( $children_tax ) ) : 
                                        $i = 1;
                                        foreach( $children_tax as $children_tax_item ) :
                                        ?>
                                            <section class="rb-school__archive-sect <?php if ( $i == 1 ) :?>rb-school__archive-sect-active <?php endif; ?>" data-tab="<?php echo $children_tax_item->term_id; ?>">
                                              <div class="rb-school__archive-list " id="<?php echo $children_tax_item->slug; ?>">
                                                 <div class="flex-wrap">
                                                    <?php 

                                                        $school_videos = new WP_Query(
                                                            array(
                                                                'post_type' => 'videos',
                                                                'posts_per_page' => 8,
                                                                'tax_query' => array(
                                                                    array(
                                                                        'taxonomy' => 'videos-type',
                                                                        'field' => 'term_id',
                                                                        'terms' => array( $children_tax_item->term_id )
                                                                    )
                                                                )
                                                            )
                                                        );

                                                        if ( $school_videos->have_posts() ) :

                                                            while( $school_videos->have_posts() ) : 
                                                                $school_videos->the_post();

                                                                $video_desc = get_post_meta( get_the_ID(), '_rb_desc', true );
                                                                $video_file = get_post_meta( get_the_ID(), '_rb_file', true ) ? wp_get_attachment_url( get_post_meta( get_the_ID(), '_rb_file', true ) ) : '';
                                                                $video_link = get_post_meta( get_the_ID(), '_rb_link', true );
                                                                $rb_not_show_title = get_post_meta( get_the_ID(), '_rb_not_show_title', true );
                                                            ?>
                                                            <div class="rb-school__item">

                                                                <a data-fancybox="video-gallery" data-type="iframe" href="<?php echo esc_url( str_replace( 'video', 'play/embed', $video_link ) ); ?>">
                                                                    <?php if ( $video_link ) : ?>
                                                                        <iframe loading="lazy" width="100%" height="200px" src="<?php echo esc_url( str_replace( 'video', 'play/embed', $video_link ) ); ?>" frameBorder="0" allow="clipboard-write; autoplay" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
                                                                    <?php elseif( has_post_thumbnail() ) : ?>
                                                                        <picture class="rb-school__item-img rb-play <?php if( $rb_not_show_title && 'yes' === $rb_not_show_title ) { echo 'rb-video-without-text'; } ?>">
                                                                            <?php the_post_thumbnail( 'large', array( 'class' => 'rb-img-cover' ) ); ?>
                                                                            <span class="rb-school__item-overlay"></span>

                                                                        </picture>
                                                                    <?php endif; ?>
                                                                    <?php if( !$rb_not_show_title && 'yes' != $rb_not_show_title ) : ?>
                                                                        <div class="rb-school__item-info rb-school__item-info2">
                                                                            <h3 class="rb-school__item-title"><?php the_title(); ?></h3>
                                                                            <?php if ( $video_desc ) : ?>
                                                                                <span class="rb-school__item-fio">
                                                                                    <?php echo esc_html( $video_desc ); ?>
                                                                                </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </a>
                                                                
                                                            </div>
                                                            <?php 
                                                            $i++;
                                                            endwhile; 
                                                        endif; 
                                                        wp_reset_postdata(); 
                                                    ?>
                                                </div>
                                                <?php if ( $children_tax_item->count > 8 ) : ?>
                                                    <div class="rb-school__list--more" data-tax="<?php echo $children_tax_item->term_id; ?>">
                                                        <div class="rb-button__orange">
                                                            Показать ещё
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                              </div>
                                            </section>
                                        <?php endforeach; ?>
                                    <?php else : ?>
                                        <section class="rb-school__archive-sect rb-school__archive-sect-active" >
                                          <div class="rb-school__archive-list swiper" id="<?php echo $videos_tax_item->slug; ?>">
                                             <div class="swiper-wrapper">
                                                <?php 

                                                    $school_videos = new WP_Query(
                                                        array(
                                                            'post_type' => 'videos',
                                                            'posts_per_page' => -1,
                                                            'order' => 'ASC',
                                                            'tax_query' => array(
                                                                array(
                                                                    'taxonomy' => 'videos-type',
                                                                    'field' => 'term_id',
                                                                    'terms' => array($videos_tax_item)
                                                                )
                                                            )
                                                        )
                                                    );

                                                    if ( $school_videos->have_posts() ) :

                                                        while( $school_videos->have_posts() ) : 
                                                            $school_videos->the_post();

                                                            $video_desc = get_post_meta( get_the_ID(), '_rb_desc', true );
                                                            $video_file = get_post_meta( get_the_ID(), '_rb_file', true ) ? wp_get_attachment_url( get_post_meta( get_the_ID(), '_rb_file', true ) ) : '';
                                                            $video_link = get_post_meta( get_the_ID(), '_rb_link', true );
                                                            $rb_not_show_title = get_post_meta( get_the_ID(), '_rb_not_show_title', true );
                                                        ?>
                                                            <div class="rb-school__item swiper-slide">

                                                                <a data-fancybox="video-gallery" data-type="iframe" href="<?php echo esc_url( str_replace( 'video', 'play/embed', $video_link ) ); ?>">
                                                                    <?php if ( $video_link ) : ?>
                                                                        <iframe loading="lazy" width="100%" height="200px" src="<?php echo esc_url( str_replace( 'video', 'play/embed', $video_link ) ); ?>" frameBorder="0" allow="clipboard-write; autoplay" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
                                                                    <?php elseif( has_post_thumbnail() ) : ?>
                                                                        <picture class="rb-school__item-img rb-play <?php if( $rb_not_show_title && 'yes' === $rb_not_show_title ) { echo 'rb-video-without-text'; } ?>">
                                                                            <?php the_post_thumbnail( 'large', array( 'class' => 'rb-img-cover' ) ); ?>
                                                                            <span class="rb-school__item-overlay"></span>

                                                                        </picture>
                                                                    <?php endif; ?>
                                                                    <?php if( !$rb_not_show_title && 'yes' != $rb_not_show_title ) : ?>
                                                                        <div class="rb-school__item-info rb-school__item-info2">
                                                                            <h3 class="rb-school__item-title"><?php the_title(); ?></h3>
                                                                            <?php if ( $video_desc ) : ?>
                                                                                <span class="rb-school__item-fio">
                                                                                    <?php echo esc_html( $video_desc ); ?>
                                                                                </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </a>
                                                                
                                                            </div>
                                                        <?php endwhile; 
                                                    endif; 
                                                    wp_reset_postdata(); 
                                                ?>
                                             </div>
<!--                                              <div class="swiper-button-prev"></div>
                                             <div class="swiper-button-next"></div> -->
                                          </div>
                                        </section>
                                <?php endif; ?>
                           </section>
                    <?php 
                    endforeach;
                endif;  

                if ( $speakers_list ) :

            ?>
                <section class="rb-school__speakers">
                    <h2 class="rb-title">Наши спикеры</h2>

                    <div class="rb-main-bio__body-slider rb-container">

                        <div class="rb-main-bio__body--list swiper rb-slider" id="rbSeminar">
                            <div class="filter_list swiper-wrapper">

                                <?php foreach( $speakers_list as $speakers_item ) : ?>

                                    <div class="rb-school__speakers--item swiper-slide">
                                       
                                        <?php if ( isset( $speakers_item['link'] ) && $speakers_item['link'] ) { echo '<a href="' . esc_url( $speakers_item['link'] ) . '" target="_blank">';  }; ?>

                                            <picture>
                                                <img src="<?php echo wp_get_attachment_image_url( $speakers_item['image'], 'large' ); ?>">
                                            </picture>
                                            <span class="rb-school__speakers--title"><?php echo $speakers_item['title'] ?></span>

                                        <?php if ( isset( $speakers_item['link'] ) && $speakers_item['link'] ) { echo '</a>';  }; ?>
                                        
                                    </div>

                                <?php endforeach; ?>

                            </div>

                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                        </div>

                    </div>

                </section>
            <?php 
                endif;
                if( $reviews_list ) :
            ?>
                <section class="rb-school__reviews">
                    <h2 class="rb-title">Отзывы</h2>


                    <ul class="flex-wrap space-between">
                        <?php foreach( $reviews_list as $reviews_item ) : ?>

                            <li class="rb-school__reviews--item">
                                <div class="rb-school__reviews--text">
                                    <?php echo apply_filters( 'the_content', $reviews_item['desc'] );?>
                                </div>
                                <span class="rb-school__reviews--title"><?php echo $reviews_item['title'] ?></span>
                            </li>

                        <?php endforeach; ?>
                    </ul>
                </section>
            <?php endif; ?>
         </div>
      </div>

      <?php 

         // get_template_part( 'template-part/forms/form', 'subscribe' );

      ?>
<?php get_footer(); ?>