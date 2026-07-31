<?php 
get_header();
/*
Template Name: Центр компетенции
*/
if ( function_exists( 'carbon_get_post_meta' ) ) {
    $rb_competations_tabs = carbon_get_post_meta( get_the_ID(), 'rb_competations_tabs' );
    $rb_centers_title = carbon_get_post_meta( get_the_ID(), 'rb_centers_title' );
    $rb_centers_items = carbon_get_post_meta( get_the_ID(), 'rb_centers_items' );

    $rb_services_add = carbon_get_post_meta( get_the_ID(), 'rb_services_add' );
    $rb_services_spec = carbon_get_post_meta( get_the_ID(), 'rb_services_spec' );
    $rb_new_text_blocks = carbon_get_post_meta( get_the_ID(), 'rb_new_text_blocks' );
}

$post_id = get_the_ID();

if (!function_exists('rb_cf_assoc_ids_fallback')) {

    function rb_cf_assoc_ids_fallback($post_id, $prefix) {
        $all = get_post_meta($post_id);
        $ids = [];
        foreach ($all as $k => $vals) {

            if (strpos($k, $prefix) !== 0) continue;
            if (substr($k, -3) !== '|id') continue;
            $id = is_array($vals) ? reset($vals) : $vals;
            $id = (int)$id;
            if ($id > 0) $ids[] = $id;
        }
        $ids = array_values(array_unique(array_filter($ids)));
        return $ids;
    }
}

if (!function_exists('rb_cf_complex_fallback')) {

    function rb_cf_complex_fallback($post_id, $base_prefix, array $fields) {
        $all = get_post_meta($post_id);
        $buf = [];
        
        foreach ($all as $k => $vals) {
            if (strpos($k, $base_prefix) !== 0) continue;
            

            $rest = substr($k, strlen($base_prefix));
            $parts = explode('|', $rest);
            if (count($parts) < 4) continue;
            
            list($field, $i, $g, $suffix) = $parts;
            if (!in_array($field, $fields, true)) continue;
            if ($suffix !== 'value') continue;
            
            $idx = (int)$i;
            $val = is_array($vals) ? reset($vals) : $vals;
            $val = trim((string)$val);
            
            if (!isset($buf[$idx])) $buf[$idx] = [];
            $buf[$idx][$field] = $val;
        }
        
        ksort($buf);
        
        $out = [];
        foreach ($buf as $row) {
            $has = false;
            foreach ($fields as $f) {
                if (isset($row[$f]) && !empty(trim((string)$row[$f]))) { 
                    $has = true; 
                    break; 
                }
            }
            if ($has) $out[] = $row;
        }
        
        return $out;
    }
}

?>

<div class="rb-service rb-page">
<div class="rb-container">
    <!-- about start -->
    <div class="rb-about">
        <section class="rb-about__top">
            <div class="rb-container flex-wrap rb-competations__top-wrap">
                <div class="col-6 col-s-12 rb-about-right">
                    <div class=" no_padding rb-page-header">
                        <h1><?php the_title();?></h1>
                    </div>
                    <picture class="rb-about__top-img2">
                        <?php
                        echo wp_get_attachment_image(
                            get_post_thumbnail_id(),
                            'rb_blog',
                            false,
                            array(
                                'class' => 'rb-img-cover',
                                'sizes' => '(max-width: 576px) 100vw, (max-width: 992px) 100vw, 900px'
                            )
                        );
                        ?>
                    </picture>
					
                    <div class="rb-text rb-service__top-desc">
                        <?php the_content(); ?>
                    </div>
                    <div class="rb-about__top-btn rb-button__orange js-open-modal js-open-modal--spec" data-modal="1">
                        Записаться
                    </div>
                </div>
                <div class="col-6 col-s-12">
                    <picture class="rb-about__top-img">
                        <?php echo get_the_post_thumbnail( get_the_ID(), 'large', array( 'class' => 'rb-img-cover' ) ); ?>
                    </picture>
                </div>
            </div>
        </section>
    <!-- Якорное меню -->
    <?php
$anchor_menu = carbon_get_the_post_meta('rb_anchor_menu');
if ($anchor_menu) : ?>
    <div class="rb-links">

            <?php foreach ($anchor_menu as $item) : ?>
                    <a href="<?php echo esc_url($item['anchor']); ?>">
                        <?php echo esc_html($item['title']); ?>
                    </a>
            <?php endforeach; ?>
    </div>
<?php endif; ?>
        <?php if ( $rb_competations_tabs ) : ?>
            <section style="margin-top: 50px;" class="rb-competations__block">
                <ul class="rb-container flex-wrap">
                    <?php foreach( $rb_competations_tabs as $rb_tabs ) : ?>
                        <li class="rb-competations__tabs--item">
                            <span class="rb-competations__tabs--title"><?php echo $rb_tabs['title']; ?></span>
                            <p class="rb-competations__tabs--text"><?php echo esc_html( $rb_tabs['desc'] ); ?></p>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php  
            endif;
            if ( $rb_centers_items ) : 
       ?>       
            <section class="rb-competations__block">
                <div class="rb-container">
                    <h2 class="rb-title rb-competations__block-title"><?php echo $rb_centers_title; ?></h2>
                    <ul class="rb-competations__block-items flex-wrap">
                        <?php foreach ( $rb_centers_items as $rb_center_item ) : ?>
                            <li class="rb-competations__block-item">
                                <span class="rb-competations__block-item--title">
                                    <?php echo $rb_center_item['title']; ?>
                                </span>
                                <a href="<?php echo $rb_center_item['link']; ?>" class="rb-competations__block-btn">
                                    Подробнее
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </section>
        <?php endif; ?>
    </div>
    <!-- about end -->


    <!-- Блок "Что это такое" -->

<?php
if ( ! function_exists('rb_whou_fallback') ) {
    function rb_whou_fallback($post_id) {
        $raw = get_post_meta($post_id);
        $out = [];
        foreach ($raw as $k => $vals) {
            if (strpos($k, '_rb_services_whou_is|') !== 0) continue;
            $parts = explode('|', $k);
            if (count($parts) < 5) continue;
            $field = $parts[1];       
            $idx   = intval($parts[2]);
            $val   = is_array($vals) ? reset($vals) : $vals;
            $out[$idx][$field] = $val;
        }
        ksort($out);
        return array_values($out);
    }
}

$post_id    = get_queried_object_id();
$whou_title = carbon_get_post_meta($post_id, 'rb_services_whou_is_title');

$items = carbon_get_post_meta($post_id, 'rb_services_whou_is');

if ( !is_array($items) || empty($items) ) {
    $items = rb_whou_fallback($post_id);
}
?>

<?php if ( is_array($items) && !empty($items) ) : ?>
<section class="rb-block rb-whou-is">
  <div class="">
    <?php if ( $whou_title ) : ?>
      <h2 class="rb-title"><?php echo esc_html($whou_title); ?></h2>
    <?php endif; ?>

    <div class="rb-whou-is__wrap">
      <?php foreach ( $items as $item ) :
        $title = isset($item['title']) ? trim($item['title']) : '';
        $desc  = isset($item['desc'])  ? $item['desc'] : '';
        if ($title === '' && trim($desc) === '') continue; ?>
        <div class="rb-whou-is__item" style="margin-bottom: 50px;">
          <?php if ($title !== ''): ?>
            <div class="rb-page-header">
              <h2><?php echo esc_html($title); ?></h2>
            </div>
          <?php endif; ?>

          <?php if (trim($desc) !== ''): ?>
            <div class="rb-service__mid-text">
              <?php echo wpautop( wp_kses_post($desc) ); ?>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>



    <!-- Блок услуг -->
    <?php
    $rb_services_add = carbon_get_post_meta( $post_id, 'rb_services_add' );

    if ( ! empty( $rb_services_add ) ) {
        foreach ( $rb_services_add as $block ) {
            if ( empty( $block['rb_services_add_top'] ) ) {
                continue;
            }

            $services_ids = array_column( $block['services'], 'id' );
            if ( empty( $services_ids ) ) continue;

            $services = get_posts( [
                'post_type'      => 'service',
                'post__in'       => $services_ids,
                'orderby'        => 'post__in',
                'posts_per_page' => -1,
            ] );

            $is_tile = !empty( $block['rb_services_add_diff2'] );
            $block_title = esc_html( $block['title'] ?: 'Все услуги направления' );
            ?>

            <section class="rb-service__all">
                <div class="" id="y-serv">
                    <div class="feedback-block">
                        <div class="rb-page-header"><h2><?= $block_title ?></h2></div>

                        <?php if ( !$is_tile ): ?>
                        <div class="feedback-arrows">
                            <a href="#" class="serv-arrow-prev" tabindex="0" role="button" aria-label="Previous slide">
                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                                    <rect width="32" height="32" rx="4" fill="#F3951D"></rect>
                                    <path d="M19 22L12.5 16L18.5 10" stroke="white"></path>
                                </svg>
                            </a>
                            <a href="#" class="serv-arrow-next" tabindex="0" role="button" aria-label="Next slide">
                                <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                                    <rect width="32" height="32" rx="4" transform="matrix(-1 0 0 1 32 0)" fill="#F3951D"></rect>
                                    <path d="M13 22L19.5 16L13.5 10" stroke="white"></path>
                                </svg>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if ( $is_tile ): ?>
                    <ul class="rb-pricelist__wrap">
                        <?php 
                        $counter = 0;
                        foreach ( $services as $service ) :
                            setup_postdata( $service ); 
                            $counter++;
                            $hidden_class = ($counter > 4) ? ' hidden-by-default' : '';
                        ?>
                            <li class="rb-pricelist__item f-jcsb-center flex-wrap <?php echo $hidden_class; ?>" id="service-<?php echo $service->ID; ?>">
                                <div class="col-10 col-s-12 flex-wrap">
                                    <a style="text-decoration: none;width: -webkit-fill-available;" href="<?php echo get_permalink($service->ID); ?>" class="rb-servicelist__item-link">
                                        <p class="rb-pricelist__item-name col-s-12 col-10"><?php echo get_the_title($service->ID); ?></p>
                                    </a>
                                </div>
                                <div class="col-2 col-s-12 rb-pricelist__item-btn--wrap">
                                    <span class="rb-pricelist__item-btn rb-button__orange-full rb-doctors-page__btn js-open-modal doctor2" data-doctors="" data-programms="" data-service="<?php echo get_the_title($service->ID); ?>" data-servicestax="">Записаться</span>
                                </div>
                            </li>
                        <?php endforeach; wp_reset_postdata(); ?>
                    </ul>
                    
                    <div class="table-button-width"> 
                        <a href="#" class="rb-service__top-link rb-button__orange-bg" id="show-more-services">Показать еще</a>
                    </div>
                    <?php else: ?>
                        <div class="sl-swiper-serv servs swiper">
                        <!-- <div class="sl-swiper-prog swiper"></div> -->
                            <div class="swiper-wrapper">
                                <?php foreach ( $services as $service ) : setup_postdata( $service ); ?>
                                    <div class="swiper-slide">
                                        <?php get_template_part( 'template-part/service', 'item', [
                                            'tax' => get_the_title($post_id),
                                            'post_id' => $service->ID,
                                            'context' => 'popular',
                                        ] ); ?>
                                    </div>
                                <?php endforeach; wp_reset_postdata(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
            <?php
        }
    }
    ?>
    <!-- Конец блока Услуги -->

    <!-- Начало блока Программы -->
    <?php 
$programms_list = carbon_get_post_meta($post_id, 'rb_programms_list');

if (!empty($programms_list)) :
    $program_ids = [];
    foreach ($programms_list as $program_group) {
        if (isset($program_group['id']) && is_array($program_group['id'])) {
            foreach ($program_group['id'] as $program_item) {
                if (isset($program_item['id']) && !empty($program_item['id'])) {
                    $program_ids[] = intval($program_item['id']);
                }
            }
        }
    }
    
    if (!empty($program_ids)) {
        $programs = get_posts([
            'post_type'      => 'programms',
            'post__in'       => $program_ids,
            'orderby'        => 'post__in',
            'posts_per_page' => -1,
            'post_status'    => 'publish'
        ]);
        
        if (!empty($programs)) : ?>
        <section class="rb-programs__all" id="y-programs">
            <div class="rb-container">
                <div class="feedback-block">
                    <div class="rb-page-header"><h2>Программы</h2></div>
                    <div class="feedback-arrows">
                        <a href="#" class="prog-arrow-prev" tabindex="0" role="button" aria-label="Previous slide">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                                <rect width="32" height="32" rx="4" fill="#F3951D"></rect>
                                <path d="M19 22L12.5 16L18.5 10" stroke="white"></path>
                            </svg>
                        </a>
                        <a href="#" class="prog-arrow-next" tabindex="0" role="button" aria-label="Next slide">
                            <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                                <rect width="32" height="32" rx="4" transform="matrix(-1 0 0 1 32 0)" fill="#F3951D"></rect>
                                <path d="M13 22L19.5 16L13.5 10" stroke="white"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="sl-swiper-prog swiper">
                    <div class="swiper-wrapper">
                        <?php foreach ($programs as $program) : 
                            setup_postdata($program);
                            
                           
                            $price = carbon_get_post_meta($program->ID, '_rb_prog_price');
                            if (empty($price)) {
                                $price = get_post_meta($program->ID, '_rb_prog_price', true);
                            }
                            ?>
                            <div class="swiper-slide">
                                <div class="rb-program__item rb-program__item_h">
                                    <picture class="col-12 rb-program__item-image">
                                        <?php echo get_the_post_thumbnail($program->ID, 'medium', ['class' => 'rb-img-cover']); ?>
                                    </picture>
                                    <div class="rb-program__item-info">
                                        <a href="<?php echo get_permalink($program->ID); ?>" class="rb-program__item-title">
                                            <?php echo get_the_title($program->ID); ?>
                                        </a>
                                        <p class="rb-program__item-desc">
                                            <?php 
                                            $excerpt = get_the_excerpt($program->ID);
                                            if (empty($excerpt)) {
                                                $content = get_the_content(null, false, $program->ID);
                                                $excerpt = wp_trim_words($content, 15);
                                            }
                                            echo $excerpt;
                                            ?>
                                        </p>
                                        <div class="rb-program__item-bot">
                                            <?php if (!empty($price)) : ?>
                                                <span class="rb-program__item-price">
                                                    <?php echo esc_html($price); ?>&nbsp;₽
                                                </span>
                                            <?php endif; ?>
                                            <span class="rb-program__item-btn rb-button__orange js-open-modal"
                                                  data-service="<?php echo esc_attr(get_the_title($program->ID)); ?>"
                                                  data-programms="<?php echo esc_attr(get_the_title($program->ID)); ?>">
                                                Записаться
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; 
                        wp_reset_postdata(); ?>
                    </div>
                </div>
            </div>
        </section>
        <?php endif; ?>
    <?php } ?>
<?php endif; ?>
    <!-- Конец блока Программы --> 

    <!-- Блок цен -->
    <?php
    $price_title = carbon_get_post_meta($post_id, 'rb_price_title');
    $price_list = carbon_get_post_meta($post_id, 'rb_price_list');
    if (!empty($price_list)) : ?>
    <div class="" id="y-price">
        <?php if ($price_title): ?>
            <h2 class="rb-title"><?php echo esc_html($price_title); ?></h2>
        <?php endif; ?>
        <div class="col-6 col-m-12">
            <div class="rb-service__mid-text"></div>
        </div>
        <ul class="rb-pricelist__wrap">
            <?php foreach ($price_list as $index => $item): ?>
                <?php
                $is_hidden = $index >= 5 ? ' hidden-by-default' : '';
                
                $service_id = isset($item['service_select']) && !empty($item['service_select']) ? $item['service_select'][0]['id'] : null;
                
                if ($service_id) {
                    $service_post = get_post($service_id);
                    $name = $service_post ? $service_post->post_title : 'Услуга не найдена';
                    
                    $price = carbon_get_post_meta($service_id, '_rb_serv_price');
                    
                    $price_alt1 = get_post_meta($service_id, '_rb_serv_price', true);
                    $price_alt2 = carbon_get_post_meta($service_id, 'rb_serv_price');
                    
                    if (empty($price) && empty($price_alt1) && empty($price_alt2)) {
                        $price = 'Цена не указана';
                    } else {
                        $price = $price ?: $price_alt1 ?: $price_alt2 ?: 'Цена не найдена';
                    }
                } else {
                    $name = 'Услуга не выбрана';
                    $price = '';
                }
                
                $doctor = $item['doctor'];
                
                if (!$service_id) continue;
                ?>
                
                <li class="rb-pricelist__item f-jcsb-center flex-wrap<?php echo $is_hidden; ?>" id="price-<?php echo $index; ?>">
                    <div class="col-10 col-s-12 flex-wrap">
                        <p class="rb-pricelist__item-name col-s-12 col-10" style="padding-right: 30px;">
                            <?php echo esc_html($name); ?>
                        </p>		
                        <span class="rb-pricelist__item-price col-s-12 col-2">
                            <?php echo esc_html($price); ?>
                        </span>
                    </div>
                    <div class="col-2 col-s-12 rb-pricelist__item-btn--wrap">
                        <span class="rb-pricelist__item-btn rb-button__orange-full rb-doctors-page__btn js-open-modal doctor2"
                              data-doctors=""
                              data-programms=""
                              data-service="<?php echo esc_attr($name); ?>"
                              data-servicestax="">
                            Записаться
                        </span>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <?php if (count($price_list) > 5): ?>
        <div class="table-button-width">
            <a href="#" class="rb-service__top-link rb-button__orange-bg" id="show-more-prices">Показать еще</a>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
        
    <!-- Начало блока портфолио -->
    <?php 
    $slides = carbon_get_post_meta($post_id, 'rb_portfolio_list_ttt');
    $portfolio_title = carbon_get_post_meta($post_id, 'rb_portfolio_title');?>
    <?php if (!empty($slides)) : ?>
    <div class="" id="y-portfolio">
      <div class="feedback-block">
        <div class="rb-page-header"><h2><?= esc_html($portfolio_title);?></h2></div>
        <div class="feedback-arrows">
          <a href="#" class="serv-arrow-prev" tabindex="0" role="button" aria-label="Previous slide">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
              <rect width="32" height="32" rx="4" fill="#F3951D"></rect>
              <path d="M19 22L12.5 16L18.5 10" stroke="white"></path>
            </svg>
          </a>
          <a href="#" class="serv-arrow-next" tabindex="0" role="button" aria-label="Next slide">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
              <rect width="32" height="32" rx="4" transform="matrix(-1 0 0 1 32 0)" fill="#F3951D"></rect>
              <path d="M13 22L19.5 16L13.5 10" stroke="white"></path>
            </svg>
          </a>
        </div>
      </div>

      <div class="feedback-wrapper">
        <div class="feedback-swiper swiper">
          <div class="swiper-wrapper">
            <?php foreach ($slides as $item): ?>
            <div class="swiper-slide">
              <div class="feedback-item <?if(empty($item['desc'])){?>shadow-none<?}?>">
                <div class="feedback__list">
                  <div class="feedback-img">
                    <?php echo wp_get_attachment_image($item['image'], 'large'); ?>
                  </div>
                  <div class="feedback__label"><?php echo esc_html($item['label']); ?></div>
                </div>
                <div class="feedback-content">
                  <div class="feedback__col">
                    <div class="feedback__wrap">
                      <div class="feedback__title"><?php echo esc_html($item['name']); ?></div>
                      <div class="feedback-widget"><span><?php echo esc_html($item['position']); ?></span></div>
                    </div>
                    <div class="feedback-asc"><?php echo esc_html($item['desc']); ?></div>
                  </div>
                    <?if(!empty($item['desc'])){?>
                  <div class="feedback__button">
                    <span class="js-open-modal rb-doctors__spec-btn rb-button__orange" data-modal="1">
                      Записаться
                    </span>
                  </div>
                    <?}?>
                </div>
              </div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
    <!-- Конец блока портфолио -->
        
    <!-- Блок специалистов -->
    <?php
$pid = get_queried_object_id();

$doctors_raw = function_exists('carbon_get_post_meta') ? carbon_get_post_meta($pid, 'rb_services_spec') : null;
$doctor_ids = [];

if (is_array($doctors_raw) && !empty($doctors_raw)) {
    foreach ($doctors_raw as $row) {
        $doctor_ids[] = is_array($row) && isset($row['id']) ? (int)$row['id'] : (int)$row;
    }
    $doctor_ids = array_values(array_unique(array_filter($doctor_ids)));
}

if (empty($doctor_ids)) {
    $doctor_ids = rb_cf_assoc_ids_fallback($pid, '_rb_doctors_list|id|');
}

if (!empty($doctor_ids)) : ?>
<br />
<div class="" id="spec">
    <div class="feedback-block">
        <div class="rb-page-header"><h2>Специалисты</h2></div>
        <div class="feedback-arrows">
            <a href="#" class="spec-arrow-prev" role="button" aria-label="Previous slide">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none"><rect width="32" height="32" rx="4" fill="#F3951D"></rect><path d="M19 22L12.5 16L18.5 10" stroke="white"></path></svg>
            </a>
            <a href="#" class="spec-arrow-next" role="button" aria-label="Next slide">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none"><rect width="32" height="32" rx="4" transform="matrix(-1 0 0 1 32 0)" fill="#F3951D"></rect><path d="M13 22L19.5 16L13.5 10" stroke="white"></path></svg>
            </a>
        </div>
    </div>

    <div class="sl-swiper-spec swiper">
        <div class="swiper-wrapper">
            <?php foreach ($doctor_ids as $doc_id) :
                if (get_post_status($doc_id) !== 'publish') continue;
                $name = get_the_title($doc_id);
                $parts = preg_split('/\s+/', trim((string)$name));
                $fio   = esc_html($parts[0] ?? '');
                $fio2  = esc_html(implode(' ', array_slice($parts, 1)));
                $img   = get_the_post_thumbnail($doc_id, 'medium', ['class' => 'rb-img-contain']);
                $occup = get_post_meta($doc_id, '_rb_doc_occup', true);
                $stage = get_post_meta($doc_id, '_rb_doc_stage', true);
                $years = '';
                if (!empty($stage)) {
                    try {
                        $d1 = new DateTime();
                        $d2 = new DateTime(date('Y-m-d', strtotime($stage)));
                        $y  = $d2->diff($d1)->y;
                        if ($y > 0 && function_exists('rb_years_declension')) {
                            $years = $y . ' ' . rb_years_declension($y);
                        } elseif ($y > 0) {
                            $years = $y . ' лет';
                        }
                    } catch (Exception $e) {}
                }
            ?>
            <div class="swiper-slide">
                <div class="rb-doctors__spec-item--wrap">
                    <picture class="rb-doctors__spec-picture"><?= $img ?></picture>
                    <div class="rb-doctors__spec-info">
                        <a href="<?= esc_url(get_permalink($doc_id)) ?>" class="rb-doctors__spec-fio">
                            <?= $fio ?><br><?= $fio2 ?>
                        </a>
                        <?php if (!empty($occup)) : ?>
                            <span class="rb-doctors__spec-occup"><?= esc_html($occup) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($years)) : ?>
                            <p class="rb-doctors__spec-standing"><span>Стаж</span> <?= esc_html($years) ?></p>
                        <?php endif; ?>
                        <span class="js-open-modal rb-doctors__spec-btn rb-button__orange"
                              data-doctors="<?= esc_attr($name) ?>"
                              data-modal="1">
                            Запись на прием
                        </span>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span>
    </div>
</div>
<?php endif; ?>


    <!-- Блок "О направлении" -->
  <?php
$pid = get_queried_object_id();
$direction_title = function_exists('carbon_get_post_meta') ? carbon_get_post_meta($pid, 'rb_direction_info_title') : '';

$direction_items = function_exists('carbon_get_post_meta') ? carbon_get_post_meta($pid, 'rb_direction_info') : [];

if (empty($direction_items) || !is_array($direction_items)) {
    $all_meta = get_post_meta($pid);
    $temp_items = [];
    
    foreach ($all_meta as $key => $value) {
        if (preg_match('/^_rb_direction_info\|(question|answer)\|(\d+)\|0\|value$/', $key, $matches)) {
            $field = $matches[1]; 
            $index = intval($matches[2]); 
            $val = is_array($value) ? $value[0] : $value;
            $temp_items[$index][$field] = trim($val);
        }
    }
    
    if (!empty($temp_items)) {
        ksort($temp_items);
        $direction_items = array_values($temp_items);
    }
}

if (!empty($direction_items) && is_array($direction_items)) : ?>
<div class="" id="y-naprav">
    <div class="feedback-block">
        <div class="rb-page-header">
            <h2><?= esc_html($direction_title ?: 'О направлении'); ?></h2>
        </div>
    </div>

    <div class="rb-accordion">
        <?php foreach ($direction_items as $row):
            $q = isset($row['question']) ? trim((string)$row['question']) : '';
            $a = isset($row['answer']) ? (string)$row['answer'] : '';
            if ($q === '' && trim(strip_tags($a)) === '') continue;
        ?>
        <div class="rb-accordion-item">
            <a href="#" class="rb-accordion__label">
                <h3><?= esc_html($q) ?></h3>
                <span class="rb-plus"><span></span><span></span></span>
            </a>
            <div class="rb-accordion__content">
                <?= wpautop( wp_kses_post($a) ); ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- FAQ -->
<?php
$faq_items = function_exists('carbon_get_post_meta') ? carbon_get_post_meta($pid, 'rb_faq') : [];

if (empty($faq_items) || !is_array($faq_items)) {
    $all_meta = get_post_meta($pid);
    $temp_faq = [];
    
    foreach ($all_meta as $key => $value) {
        if (preg_match('/^_rb_faq\|(question|answer)\|(\d+)\|0\|value$/', $key, $matches)) {
            $field = $matches[1];
            $index = intval($matches[2]);
            $val = is_array($value) ? $value[0] : $value;
            $temp_faq[$index][$field] = trim($val);
        }
    }
    
    if (!empty($temp_faq)) {
        ksort($temp_faq);
        $faq_items = array_values($temp_faq);
    }
}

if (!empty($faq_items) && is_array($faq_items)) : ?>
<div class="" id="y-qest">
    <div class="feedback-block">
        <div class="rb-page-header"><h2>Частые вопросы</h2></div>
    </div>

    <div class="rb-accordion rb-accordion-qe">
        <?php foreach ($faq_items as $row):
            $q = isset($row['question']) ? trim((string)$row['question']) : '';
            $a = isset($row['answer']) ? (string)$row['answer'] : '';
            if ($q === '' && trim(strip_tags($a)) === '') continue;
        ?>
        <div class="rb-accordion-item">
            <a href="#" class="rb-accordion__label">
                <h3><?= esc_html($q) ?></h3>
                <div class="rb-arrow">
                    <svg width="14" height="9" viewBox="0 0 14 9" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13 7.75L7 1.25L1 7.25" stroke="#42567D"></path></svg>
                </div>
            </a>
            <div class="rb-accordion__content">
                <?= wpautop( wp_kses_post($a) ); ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>


<!-- Популярные услуги -->
<?php
$popular_services = carbon_get_post_meta( $post_id, 'rb_services_popular' );
if ( ! empty( $popular_services ) ) : ?>
<section class="rb-service__all">
    <div class="" id="y-pop">
        <div class="feedback-block">
            <div class="rb-page-header"><h2>Популярные услуги</h2></div>
            <div class="feedback-arrows">
                <a href="#" class="pop-arrow-prev" tabindex="0" role="button" aria-label="Previous slide">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                        <rect width="32" height="32" rx="4" fill="#F3951D"/>
                        <path d="M19 22L12.5 16L18.5 10" stroke="#fff"/>
                    </svg>
                </a>
                <a href="#" class="pop-arrow-next" tabindex="0" role="button" aria-label="Next slide">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                        <rect width="32" height="32" rx="4" transform="matrix(-1 0 0 1 32 0)" fill="#F3951D"/>
                        <path d="M13 22L19.5 16L13.5 10" stroke="#fff"/>
                    </svg>
                </a>
            </div>
        </div>
        <div class="sl-swiper-pop swiper">
            <div class="swiper-wrapper">
                <?php
                // Сохраняем оригинальный $post_id страницы
                $current_page_id = $post_id;
                
                foreach ( $popular_services as $service ) {
                    // ИСПРАВЛЕНИЕ: правильно получаем ID услуги
                    $service_id = isset($service['id']) ? (int) $service['id'] : 0;
                    
                    if ( $service_id ) {
                        $post_service = get_post( $service_id );
                        
                        if ( $post_service && $post_service->post_status === 'publish' ) {
                            // Устанавливаем глобальный $post для текущей услуги
                            global $post;
                            $post = $post_service;
                            setup_postdata( $post );
                            
                            echo '<div class="swiper-slide">';
                                get_template_part(
                                    'template-part/service',
                                    'item',
                                    array(
                                        'context'    => 'popular',
                                        'tax'        => get_the_title( $current_page_id ), // Используем ID текущей страницы
                                        'service_id' => $service_id, // Передаем ID услуги
                                    )
                                );
                            echo '</div>';
                            
                            wp_reset_postdata();
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

</div>
</div>

<?php get_footer(); ?>