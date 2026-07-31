<?php 

get_header();

/*
Template Name: Общие программы
*/

$programms = new WP_Query(
    array(
        'post_type' => 'programms',
        'order' => 'ASC',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'programms-type',
                'field' => 'slug',
                'terms' => 'programms-general'
            )
        )
    )
);

?>


        <div class="rb-programms rb-page">
            <div class="rb-container">
                <div class="rb-page-header">
                    <h1><?php the_title(); ?></h1>
                </div>
                <ul class="rb-programms__category flex">
                    <li class="<?php if ( is_page_template( 'template-page/page-programms.php' ) ) { echo 'rb-programms__curr'; } ?>"><a href="/programms/">Все</a></li>
                    <li class="<?php if ( is_page_template( 'template-page/page-programms-general.php' ) ) { echo 'rb-programms__curr'; } ?>">Общие программы</li>
                    <li class="<?php if ( is_page_template( 'template-page/page-programms-weekend.php' ) ) { echo 'rb-programms__curr'; } ?>"><a href="/programms-weekend/">Выходного дня</a></li>
                    <li class="<?php if ( is_page_template( 'template-page/page-programms-medical.php' ) ) { echo 'rb-programms__curr'; } ?>"><a href="/programms-medical/">Лечебные</a></li>
                </ul>
                <div class="rb-programms__list-wrap flex-wrap">

                    <ul class="rb-programms__list flex-wrap">
                        
                        <?php 
                            while( $programms->have_posts() ) {
                                $programms->the_post();

                                get_template_part( 'template-part/programms', 'item' );

                            }

                        ?>

                    </ul>

                </div>
            </div>
        </div>

    <?php get_template_part( 'template-part/forms/form', 'subscribe' ); ?>

<?php get_footer(); ?>