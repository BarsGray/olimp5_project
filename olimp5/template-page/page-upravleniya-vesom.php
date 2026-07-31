<?php
/*
Template Name: Управление весом
*/

get_header();

$curr_list = array();

if ( function_exists( 'carbon_get_post_meta' ) ) {
   $pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => 'template-page/page-consult.php'
   ));

   $page_id = ($pages && isset($pages[0]->ID)) ? $pages[0]->ID : get_the_ID();
   $curr_val = carbon_get_post_meta($page_id, 'rb_consult_list');
   $curr_list = array_map(function($item) {
       return $item['id'];
   }, $curr_val);
}

$services_cats = get_terms(array(
    'taxonomy' => 'services',
    'hide_empty' => false,
    'exclude' => $curr_list,
));

$services_cats   = get_terms(['taxonomy' => 'services', 'hide_empty' => false, 'exclude' => $curr_list]);
$programms_list  = carbon_get_post_meta(get_the_ID(), 'rb_programms_list');

if (empty($programms_list) || (isset($programms_list[0]['id']) && $programms_list[0]['id'] === 0)) {
    $programs_ids = array();
    $all_meta = get_post_meta(get_the_ID());
    foreach ($all_meta as $meta_key => $meta_value) {
        if (strpos($meta_key, '_rb_programms_list|id|0|') !== false && strpos($meta_key, '|id') !== false) {
            if (isset($meta_value[0]) && is_numeric($meta_value[0])) {
                $programs_ids[] = intval($meta_value[0]);
            }
        }
    }
    $programms_list = array();
    foreach ($programs_ids as $id) {
        $programms_list[] = array('id' => array(array('id' => $id)));
    }
}
$desc_block      = carbon_get_post_meta(get_the_ID(), 'rb_weight_desc');
$doctors_list    = carbon_get_post_meta(get_the_ID(), 'rb_doctors_list');

$rb_services_desc  = carbon_get_post_meta(get_the_ID(), 'rb_services_desc') ?: '';
$rb_services_price = carbon_get_post_meta(get_the_ID(), 'rb_services_price') ?: '';
?>
<div class="rb-service-page rb-page">
<div class="rb-container">

    <section class="rb-service__top flex-wrap">
        <div class="col-6 col-s-12 rb-service__top-text rb-page-header">
            <h1><?php the_title(); ?></h1>

            <picture class="col-6 col-s-12 rb-service__top-img-mob">
                <?php echo get_the_post_thumbnail( get_the_ID(), 'rb_standart', '', array( 'class' => 'rb-img-cover' ) ); ?>
            </picture>
            <div class="rb-text rb-service__top-desc">
                <?php if ($rb_services_desc): ?>
                    <?php echo apply_filters( 'the_content', $rb_services_desc ); ?>
                <?php endif; ?>
            </div>
            <div class="rb-text rb-service__top-price">
                <?php if ($rb_services_price): ?>
                    <?php echo 'Стоимость: ' . $rb_services_price . ' ₽'; ?>
                <?php endif; ?>
            </div>
            <div class="rb-service__top-link rb-button__orange-bg js-open-modal" data-modal="1">
                Записаться на прием
            </div>
        </div>
        <picture class="col-6 col-s-12 rb-service__top-img">
            <?php echo get_the_post_thumbnail( get_the_ID(), 'rb_standart' ); ?>
        </picture>
    </section>
    
    <?php if ($desc_block): ?>
        <div class="rb-weight-description rb-text">
            <?php echo apply_filters('the_content', $desc_block); ?>
        </div>
    <?php endif; ?>


<?php if ($programms_list) : ?>
<section class="rb-service__section rb-programms rb-container">
    <h2 class="rb-title">Программы</h2>

    <div class="rb-programms__list-wrap flex-wrap">
        <ul class="rb-programms__list flex-wrap">
        <?php foreach ($programms_list as $row) :
            if (isset($row['id']) && is_array($row['id'])) {
                foreach ($row['id'] as $assoc) {
                    $post_id = isset($assoc['id']) ? intval($assoc['id']) : 0;
                    if (!$post_id || get_post_status($post_id) !== 'publish') {
                        continue;
                    }

                    $title  = get_the_title($post_id);
                    $link   = get_permalink($post_id);
					
                    // $price  = carbon_get_post_meta($post_id, 'rb_programm_price');
                    // Получение цены для карточки программы
					$price  = carbon_get_post_meta($post_id, '_rb_price'); 
					if (empty($price)) {
						$price = get_post_meta($post_id, '_rb_price', true);
					}

                    $suits  = carbon_get_post_meta($post_id, 'rb_programm_suit');
                    $desc   = get_the_excerpt($post_id) ?: '';
                    $thumb  = get_the_post_thumbnail($post_id, 'rb_programms',
                                                     ['class' => 'rb-img-cover wp-post-image']);
					
        ?>
            <li class="rb-programms__item flex-wrap">
                <div class="col-6 col-m-12 rb-programms__item-img">
                    <a href="<?= esc_url($link) ?>">
                        <picture class="rb-programms__item-picture">
                            <?= $thumb ?: '<img class="rb-img-cover" src="' .
                                           get_stylesheet_directory_uri() .
                                           '/assets/img/nopic.png" alt="">' ?>
                        </picture>
                    </a>
                </div>

                <div class="col-6 col-m-12 rb-programms__item-info">
                    <h2 class="rb-programms__item-title"><?= esc_html($title) ?></h2>
                    <?php if ($desc) : ?>
                        <p class="rb-programms__item-subtitle"><?= esc_html($desc) ?></p>
                    <?php endif; ?>

                    <?php if ($suits) : ?>
                        <div class="rb-programms__item-text">
                            <span>Кому подходит:</span>
                            <ul>
                                <?php foreach ($suits as $s) :
                                    if (empty($s['text'])) continue; ?>
                                    <li><?= esc_html($s['text']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="rb-programms__item-bot flex-wrap">
                        <a href="<?= esc_url($link) ?>" class="rb-button__grey rb-programms__item-link">
                            Подробнее
                            <svg width="8" height="14" viewBox="0 0 8 14" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.875 0.75L7.125 7L0.875 13.25"
                                      stroke="#717171" stroke-width="1.5"
                                      stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
						
						<?php
						//Вывод цены в карточке программы
							if ($price !== '' && $price !== false) : ?>
							<p class="rb-programms__item-price"><?= esc_html($price) ?> ₽</p>
						<?php endif; ?>

                    </div>
                </div>
            </li>
        <?php
                }
            }
        endforeach; ?>
        </ul>
    </div>
</section>
<?php endif; ?>

<?php 
$doctors_ids = array();
$all_meta = get_post_meta(get_the_ID());
foreach ($all_meta as $meta_key => $meta_value) {
    if (strpos($meta_key, '_rb_doctors_list|id|0|') !== false && strpos($meta_key, '|id') !== false) {
        if (isset($meta_value[0]) && is_numeric($meta_value[0])) {
            $doctors_ids[] = intval($meta_value[0]);
        }
    }
}


function plural_years($number) {
    $number = (int)$number;
    $last_two = $number % 100;
    $last_one = $number % 10;

    if ($last_two >= 11 && $last_two <= 14) {
        return 'лет';
    }

    switch ($last_one) {
        case 1:
            return 'год';
        case 2:
        case 3:
        case 4:
            return 'года';
        default:
            return 'лет';
    }
}

function full_years_from_date($date_str) {
    $ts = strtotime($date_str);
    if (!$ts) return 0;

    $start = new DateTime(date('Y-m-d', $ts));
    $now   = new DateTime('today');

    $years = (int)$start->diff($now)->y;
    return max(0, $years);
}
?>

<?php if (!empty($doctors_ids)) : ?>
<div class="" id="spec">
    <div class="feedback-block">
        <div class="rb-page-header"><h2>Врачи центра:</h2></div>
        <div class="feedback-arrows">
            <a href="#" class="spec-arrow-prev" role="button" aria-label="Previous slide" tabindex="0">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="32" height="32" rx="4" fill="#F3951D"></rect>
                    <path d="M19 22L12.5 16L18.5 10" stroke="white"></path>
                </svg>
            </a>
            <a href="#" class="spec-arrow-next" role="button" aria-label="Next slide" tabindex="0">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="32" height="32" rx="4" transform="matrix(-1 0 0 1 32 0)" fill="#F3951D"></rect>
                    <path d="M13 22L19.5 16L13.5 10" stroke="white"></path>
                </svg>
            </a>
        </div>
    </div>

    <div class="sl-swiper-spec swiper">
        <div class="swiper-wrapper">
            <?php foreach ($doctors_ids as $doc_id) :
                if (!get_post($doc_id) || get_post_status($doc_id) !== 'publish') {
                    continue;
                }

                $name       = get_the_title($doc_id);
                $link       = get_permalink($doc_id);
                $position   = get_post_meta($doc_id, '_rb_doc_occup', true);
                $stage_date = get_post_meta($doc_id, '_rb_doc_stage', true);
                $experience = $stage_date ? full_years_from_date($stage_date) : 0;

                $img        = get_the_post_thumbnail($doc_id, 'medium', ['class' => 'rb-img-contain wp-post-image']);
                $name_parts = explode(' ', trim($name));
                if (count($name_parts) >= 2) {
                    $display_name = esc_html($name_parts[0]) . '<br>' . esc_html(implode(' ', array_slice($name_parts, 1)));
                } else {
                    $display_name = esc_html($name);
                }
            ?>
            <div class="swiper-slide">
                <div class="rb-doctors__spec-item--wrap">
                    <picture class="rb-doctors__spec-picture">
                        <?= $img ?: '' ?>
                    </picture>

                    <div class="rb-doctors__spec-info">
                        <a href="<?= esc_url($link); ?>" class="rb-doctors__spec-fio">
                            <?= $display_name ?>
                        </a>

                        <?php if (!empty($position)) : ?>
                            <span class="rb-doctors__spec-occup"><?= esc_html($position); ?></span>
                        <?php endif; ?>

                        <?php if ($experience > 0) : ?>
                            <p class="rb-doctors__spec-standing">
                                <span>Стаж: </span>
                                <span><?= esc_html($experience) . ' ' . plural_years($experience); ?></span>
                            </p>
                        <?php endif; ?>

                        <span class="js-open-modal rb-doctors__spec-btn rb-button__orange"
                              data-doctors="<?= esc_attr($name); ?>" data-modal="1">
                            Запись на прием
                        </span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>


</div>
</div>

<?php get_footer(); ?>
