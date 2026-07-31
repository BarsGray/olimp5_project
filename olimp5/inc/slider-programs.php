<?php
// =====
// Вывод блока Программ OLIMP5
// =====

/* Добавление кастомного поля в админке */
use Carbon_Fields\Container;
use Carbon_Fields\Field;

function register_service_programs_fields() {
    Container::make('term_meta', __('Настройки направления услуг'))
        ->where('term_taxonomy', '=', 'services')
        ->add_fields([
			Field::make('text', 'programs_section_title', 'Заголовок блока программ')
                ->set_default_value('Программы направления'),
			Field::make('checkbox', 'disable_all_programs', __('Не выводить Программы по умолчанию'))
                ->set_option_value('yes')
                ->set_default_value(false)
                ->set_help_text('Если включено, блок "Программы" с выборкой по умолчанию не будет выводиться.'),
            Field::make('association', 'service_programs', __('Привязанные программы'))
                ->set_types([
                    [
                        'type'      => 'post',
                        'post_type' => 'programms',
                    ]
                ])
                ->set_help_text('Выберите программы, которые будут отображаться в этом направлении услуг.')
        ]);
}
add_action('carbon_fields_register_fields', 'register_service_programs_fields');

/* Вывод программ */
function rb_output_related_programs_slider($term_id) {
    // Получаем настройки из админки
    $section_title = carbon_get_term_meta($term_id, 'programs_section_title') ?: 'Программы направления';
  //  $disable_all_programs = carbon_get_term_meta($term_id, 'disable_all_programs') === 'yes';
	$disable_all_programs = get_term_meta($term_id, '_disable_all_programs', true) === 'yes';
    $linked_programs = carbon_get_term_meta($term_id, 'service_programs');
	
	// Если отключен вывод и нет привязанных - выходим
    if ($disable_all_programs && empty($linked_programs)) {
        return;
    }

    $programs = [];

    // Если есть привязанные программы, выводим их
    if (!empty($linked_programs)) {
        $program_ids = wp_list_pluck($linked_programs, 'id');

        $programs = get_posts([
            'post_type'      => 'programms',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'post__in'       => $program_ids,
            'orderby'        => 'post__in',
        ]);
    }

    // Если нет привязанных программ и не отключен вывод всех программ
    if (empty($programs) && !$disable_all_programs) {
        $programs = get_posts([
            'post_type'      => 'programms',
            'post_status'    => 'publish',
            'posts_per_page' => 10,
            'orderby'        => 'date',
            'order'          => 'DESC', // Последние добавленные
        ]);

        if (!empty($programs)) {
            $section_title = 'Программы';
        }
    }

    // Если нет программ, ничего не выводим
    if (empty($programs)) {
        return;
    }

    // Вывод слайдера
    ob_start();
    ?>
    <section class="rb-programs__all" id="y-programs">
        <div class="rb-container">
            <div class="feedback-block">
                <div class="rb-page-header"><h2><?php echo esc_html($section_title); ?></h2></div>
                <div class="feedback-arrows">
                    <a href="#" class="prog-arrow-prev" tabindex="0" role="button" aria-label="Previous slide">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                            <rect width="32" height="32" rx="4" fill="#F3951D"/>
                            <path d="M19 22L12.5 16L18.5 10" stroke="white"/>
                        </svg>
                    </a>
                    <a href="#" class="prog-arrow-next" tabindex="0" role="button" aria-label="Next slide">
                        <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                            <rect width="32" height="32" rx="4" transform="matrix(-1 0 0 1 32 0)" fill="#F3951D"/>
                            <path d="M13 22L19.5 16L13.5 10" stroke="white"/>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="sl-swiper-prog swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($programs as $program): ?>
                        <?php
                        $title = get_the_title($program->ID);
                        $link = get_permalink($program->ID);
                        $price = carbon_get_post_meta($program->ID, '_rb_price') ?: get_post_meta($program->ID, '_rb_price', true);
                        $price_from = carbon_get_post_meta($program->ID, '_rb_price_from_hide');
                        $price_link = carbon_get_post_meta($program->ID, '_rb_link') ?: $link;
                        $thumb = has_post_thumbnail($program->ID)
                            ? get_the_post_thumbnail($program->ID, 'medium', ['class' => 'rb-img-cover', 'loading' => 'lazy'])
                            : '<img class="rb-img-cover" src="' . esc_url(get_stylesheet_directory_uri()) . '/assets/img/nopic.png" alt="' . esc_attr($title) . '">';
                        ?>
                        <div class="swiper-slide">
                            <div class="rb-program__item rb-program__item_h">
                                <picture class="col-12 rb-program__item-image"><?php echo $thumb; ?></picture>
                                <div class="rb-program__item-info">
                                    <a href="<?php echo esc_url($price_link); ?>" class="rb-program__item-title"><?php echo esc_html($title); ?></a>
                                    <p class="rb-program__item-desc"></p>
                                    <div class="rb-program__item-bot">
                                        <?php if ($price): ?>
                                            <span class="rb-program__item-price">
                                                <?php if ($price_from) echo '<span>от </span>'; ?>
                                                <?php echo esc_html($price); ?>&nbsp;₽
                                            </span>
                                        <?php endif; ?>
                                        <span class="rb-program__item-btn rb-button__orange js-open-modal"
                                            data-service="<?php echo esc_attr($title); ?>"
                                            data-programms="<?php echo esc_attr($title); ?>">
                                            Записаться
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
            </div>
        </div>
    </section>
    <?php
    echo ob_get_clean();
}