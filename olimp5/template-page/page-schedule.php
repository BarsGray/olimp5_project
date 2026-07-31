<?php

get_header();

/*
Template Name: Расписание
*/

?>
<div class="rb-schedule">
    <div class="rb-container">
        <section class="rb-page__top">
            <div class="flex-wrap">
                <div class="col-12  rb-page-header">
                    <h1><?php the_title(); ?></h1>
                </div>
            </div>
        </section>
        <?php

        $events_tax = get_terms(array('taxonomy' => 'events-type', 'parent' => 0));

        if ($events_tax && !is_wp_error($events_tax)):
            ?>
            <section class="rb-school__archive">
                <div class="rb-news__menu rb-school__archive-menu">
                    <ul>
                        <?php
                        $i = 1;
                        foreach ($events_tax as $events_tax_item):
                            ?>
                            <li>
                                <span
                                    class="rb-school__archive-menu--item rb-schedule__menu--tab <?php if ($i == 1): ?>active<?php endif; ?>"
                                    data-tab="<?php echo $events_tax_item->term_id; ?>"><?php echo $events_tax_item->name; ?></span>
                            </li>
                            <?php
                            $i++;
                        endforeach;
                        ?>
                    </ul>
                </div>

            </section>
            <section class="rb-schedule__list">
                <?php
                $ii = 0;
                foreach ($events_tax as $events_tax_item):
                    if ($ii >= 1) {
                        $rb_schedule_hide = 'rb-schedule__hide';
                    } else {
                        $rb_schedule_hide = '';
                    }
                    ?>
                    <ul class="rb-schedule__list-wrap flex-wrap space-between <?php echo $rb_schedule_hide; ?> "
                        data-tab="<?php echo $events_tax_item->term_id; ?>">
                        <?php
                        $schedule_query = new WP_Query(
                            array(
                                'post_type' => 'events',
                                'posts_per_page' => -1,
                                'order' => 'ASC',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'events-type',
                                        'field' => 'term_id',
                                        'terms' => array($events_tax_item->term_id)
                                    )
                                )
                            )
                        );
                        if ($schedule_query->have_posts()):
                            while ($schedule_query->have_posts()):
                                $schedule_query->the_post();
                                get_template_part('template-part/schedule', 'item');
                            endwhile;
                        endif;
                        wp_reset_postdata();
                        ?>
                    </ul>
                    <?php
                    $ii++;
                endforeach;
                ?>
            </section>
            <?php
        endif;
        ?>

    </div>
</div>

<?php get_footer(); ?>