<?php
get_header();

$curr_obj = get_queried_object();

$rb_service_image_inner = get_term_meta( $curr_obj->term_id, '_rb_service_image_inner', true );
$term_image = ( isset( $rb_service_image_inner ) && $rb_service_image_inner ) ? $rb_service_image_inner : get_term_meta( $curr_obj->term_id, '_rb_service_image', true );
$rb_service_image_mob = get_term_meta( $curr_obj->term_id, '_rb_service_image_mob', true );
$rb_service_desc = get_term_meta( $curr_obj->term_id, '_rb_service_desc', true ) ?: term_description( $curr_obj->term_id );

$rb_different = get_term_meta( $curr_obj->term_id, '_rb_different', true );
$rb_new = get_term_meta( $curr_obj->term_id, '_rb_new', true );

if ( function_exists( 'carbon_get_term_meta' ) ) {
    $rb_services_add     = carbon_get_term_meta( $curr_obj->term_id, 'rb_services_add' );
    $rb_services_spec    = carbon_get_term_meta( $curr_obj->term_id, 'rb_services_spec' );
    $rb_new_text_blocks  = carbon_get_term_meta( $curr_obj->term_id, 'rb_new_text_blocks' );
    // Билдер (конструктор)
    $rb_builder          = carbon_get_term_meta( $curr_obj->term_id, 'rb_services_term_builder' );
} else {
    $rb_builder = array();
}
if ( function_exists('carbon_get_term_meta') ) {
    $rb_service_price     = carbon_get_term_meta( $curr_obj->term_id, 'rb_service_price' );
    $rb_service_code      = carbon_get_term_meta( $curr_obj->term_id, 'rb_service_code' );
    $rb_service_old_price = carbon_get_term_meta( $curr_obj->term_id, 'rb_service_old_price' );
} else {
    // fallback (если Carbon внезапно не доступен)
    $rb_service_price     = get_term_meta( $curr_obj->term_id, '_rb_service_price', true );
    $rb_service_code      = get_term_meta( $curr_obj->term_id, '_rb_service_code', true );
    $rb_service_old_price = get_term_meta( $curr_obj->term_id, '_rb_service_old_price', true );
}
if ( ! function_exists('rb_format_price') ) {
    function rb_format_price( $value ) {
        if ($value === '' || $value === null) return '';
        // вычищаем все кроме цифр и точки/запятой
        $num = trim(str_replace(',', '.', preg_replace('~[^\d,\.]~u', '', (string)$value )));
        if ($num === '') return esc_html($value);
        $float = (float)$num;
        return number_format($float, 0, '', ' ') . ' ₽';
    }
}
?>

<div class="rb-service rb-page">
<div class="rb-container">

    <!-- Основная секция -->
    <section class="rb-service__top flex-wrap">
        <div class="col-6 col-s-12 rb-service__top-text rb-page-header">
            <h1><?php echo $curr_obj->name; ?></h1>

            <picture class="col-6 col-s-12 rb-service__top-img-mob">
                <?php echo wp_get_attachment_image( $rb_service_image_mob, 'medium', '', array( 'class' => 'rb-img-cover' ) ); ?>
            </picture>
            <div class="rb-text rb-service__top-desc">
                <?php echo apply_filters( 'the_content', $rb_service_desc ); ?>
            </div>
			<?php if ( $rb_service_price || $rb_service_old_price || $rb_service_code ) : ?>
    <div class="rb-service__meta" style="margin: 12px 0 18px;">
        <?php if ( $rb_service_old_price ) : ?>
            <span class="rb-service__old-price" style="margin-right: 10px;">
                <s><?php echo esc_html( rb_format_price($rb_service_old_price) ); ?></s>
            </span>
        <?php endif; ?>

        <?php if ( $rb_service_price ) : ?>
            <span class="rb-service__price" style="font-weight: 600; margin-right: 12px;">
                Стоимость: <?php echo esc_html( rb_format_price($rb_service_price) ); ?>
            </span>
        <?php endif; ?>

        <?php if ( $rb_service_code ) : ?>
            <span class="rb-service__code" style="opacity:.9;">
                Код: <?php echo esc_html( $rb_service_code ); ?>
            </span>
        <?php endif; ?>
    </div>
<?php endif; ?>
            <div class="rb-service__top-link rb-button__orange-bg js-open-modal" data-doctors="" data-programms="" data-service="" data-servicestax="<?php echo $curr_obj->name; ?>">
                Записаться на прием
            </div>
        </div>
        <?php if( $term_image ) : ?>
            <picture class="col-6 col-s-12 rb-service__top-img">
                <?php echo wp_get_attachment_image( $term_image, 'large', '', array( 'class' => 'rb-img-cover' ) ); ?>
            </picture>
        <?php endif; ?>
							            <?php // Слот: сразу после H1 ?>
            <?php if (function_exists('rb_render_builder_slot')) rb_render_builder_slot($rb_builder, 'after_title'); ?>
		            <?php // Слот: после описания (интро) ?>
            <?php if (function_exists('rb_render_builder_slot')) rb_render_builder_slot($rb_builder, 'after_intro'); ?>

    </section>

    <!-- Якорное меню -->
    <?php
    $anchor_menu = carbon_get_term_meta($curr_obj->term_id, 'rb_anchor_menu');
    if (!empty($anchor_menu)): ?>
    <div class="">
        <div class="rb-links">
            <?php foreach ($anchor_menu as $item): ?>
                <a href="<?php echo esc_attr($item['anchor']); ?>">
                    <?php echo esc_html($item['title']); ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Блок "Что это такое" -->
    <?php
    $whou_title = carbon_get_term_meta($curr_obj->term_id, 'rb_services_whou_is_title');
    $whou_items = carbon_get_term_meta($curr_obj->term_id, 'rb_services_whou_is');

    if (!empty($whou_items)) : ?>
    <section class="rb-block rb-whou-is">
        <div class="">
            <?php if ($whou_title): ?>
                <h2 class="rb-title"><?php echo esc_html($whou_title); ?></h2>
            <?php endif; ?>

            <div class="rb-whou-is__wrap">
                <?php foreach ($whou_items as $item): ?>
                    <div class="rb-whou-is__item">
                        <?php if (!empty($item['title'])): ?>
                            <div class="rb-page-header"><h2><?php echo esc_html($item['title']); ?></h2></div>
                        <?php endif; ?>
                        <?php if (!empty($item['desc'])): ?>
                            <div class="rb-service__mid-text"><p><span style="font-weight: 400;"><?php echo wpautop($item['desc']); ?></span></p></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <?php // Слот: перед блоком «Услуги» ?>
    <?php if (function_exists('rb_render_builder_slot')) rb_render_builder_slot($rb_builder, 'before_services'); ?>
    <!-- Блок услуг -->
    <?php
    $rb_services_add = carbon_get_term_meta( $curr_obj->term_id, 'rb_services_add' );

    if ( ! empty( $rb_services_add ) ) {
        foreach ( $rb_services_add as $block ) {
            // Проверка на чекбокс "Позиция топ"
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
                <div class="js-serv-section" id="y-serv">
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
						<a style="text-decoration: none;width: -webkit-fill-available;" href="<?php echo get_permalink($service->ID); ?>" class="rb-servicelist__item-link">
                            <li class="rb-pricelist__item f-jcsb-center flex-wrap <?php echo $hidden_class; ?>" id="service-<?php echo $service->ID; ?>">
                                <div class="col-10 col-s-12 flex-wrap">
                                    <span style="text-decoration: none;width: -webkit-fill-available;" href="<?php echo get_permalink($service->ID); ?>" class="rb-servicelist__item-link">
                                        <p class="rb-pricelist__item-name col-s-12 col-10"><?php echo get_the_title($service->ID); ?></p>
                                    </span>
                                </div>
                                <div class="col-2 col-s-12 rb-pricelist__item-btn--wrap">
                                    <span class="rb-pricelist__item-btn rb-button__orange-full rb-doctors-page__btn js-open-modal doctor2" data-doctors="" data-programms="" data-service="<?php echo get_the_title($service->ID); ?>" data-servicestax="">Записаться</span>
                                </div>
                            </li>
						</a>
                        <?php endforeach; wp_reset_postdata(); ?>
                    </ul>
                    
                    <div class="table-button-width"> 
                        <a href="#" class="rb-service__top-link rb-button__orange-bg" id="show-more-services">Показать еще</a>
                    </div>
                    <?php else: ?>
                        <div class="sl-swiper-serv swiper">
                            <div class="swiper-wrapper">
                                <?php foreach ( $services as $service ) : setup_postdata( $service ); ?>
                                    <div class="swiper-slide">
                                        <?php get_template_part( 'template-part/service', 'item', [
                                            'tax' => $curr_obj->name,
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
    <?php // Слот: после блока «Услуги» ?>
    <?php if (function_exists('rb_render_builder_slot')) rb_render_builder_slot($rb_builder, 'after_services'); ?>

	<!-- Начало блока Программы -->
	<?php 
		if (function_exists('rb_output_related_programs_slider')) {
			rb_output_related_programs_slider($curr_obj->term_id); 
		}
	?>
	<!-- Конец блока Программы --> 

    <!-- Блок цен -->
<?php
$price_title = carbon_get_term_meta($curr_obj->term_id, 'rb_price_title');
$price_list = carbon_get_term_meta($curr_obj->term_id, 'rb_price_list');
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
                
                $all_meta = get_post_meta($service_id);
                $price_fields = [];
                foreach($all_meta as $key => $value) {
                    if (strpos($key, 'price') !== false || strpos($key, 'serv') !== false || strpos($key, '_rb_') !== false) {
                        $price_fields[$key] = $value[0];
                    }
                }
                
                $debug_info = "ID: $service_id | carbon_get(_rb_serv_price): " . var_export($price, true) . 
                             " | get_post_meta(_rb_serv_price): " . var_export($price_alt1, true) . 
                             " | carbon_get(rb_serv_price): " . var_export($price_alt2, true) . 
                             " | Поля с price/serv/rb: " . json_encode($price_fields);
                
                if (empty($price) && empty($price_alt1) && empty($price_alt2)) {
                    $price = 'DEBUG: ' . $debug_info;
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
						<?php $term = get_queried_object();
$slides = carbon_get_term_meta($term->term_id, 'rb_portfolio_list');
			$portfolio_title = carbon_get_term_meta($term->term_id, 'rb_portfolio_title');?>
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
	    <?php // Слот: перед блоком «Врачи» ?>
    <?php if (function_exists('rb_render_builder_slot')) rb_render_builder_slot($rb_builder, 'before_doctors'); ?>

    <!-- Блок специалистов -->
    <?php
    $doctors = carbon_get_term_meta($curr_obj->term_id, 'rb_services_spec');

    if (!empty($doctors)) : ?>
	<br />
    <div class="" id="spec">
        <div class="feedback-block">
            <div class="rb-page-header"><h2>Специалисты</h2></div>
            <div class="feedback-arrows">
                <a href="#" class="spec-arrow-prev" role="button" aria-label="Previous slide">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="32" height="32" rx="4" fill="#F3951D"></rect>
                        <path d="M19 22L12.5 16L18.5 10" stroke="white"></path>
                    </svg>
                </a>
                <a href="#" class="spec-arrow-next" role="button" aria-label="Next slide">
                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="32" height="32" rx="4" transform="matrix(-1 0 0 1 32 0)" fill="#F3951D"></rect>
                        <path d="M13 22L19.5 16L13.5 10" stroke="white"></path>
                    </svg>
                </a>
            </div>
        </div>

        <div class="sl-swiper-spec swiper">
            <div class="swiper-wrapper">
                <?php foreach ($doctors as $doctor) :
                    if (!get_post_status($doctor['id']) || get_post_status($doctor['id']) !== 'publish') continue;
                    $fio = explode(' ', get_the_title($doctor['id']));
                    $img = get_the_post_thumbnail($doctor['id'], 'medium', ['class' => 'rb-img-contain']);
                    $occup = get_post_meta($doctor['id'], '_rb_doc_occup', true);
                    $stage = get_post_meta($doctor['id'], '_rb_doc_stage', true);
                    $years = '';
                    if ($stage) {
                        $d1 = new DateTime();
                        $d2 = new DateTime(date('Y-m-d', strtotime($stage)));
                        $diff = $d2->diff($d1);
                        $years = $diff->y . ' ' . rb_years_declension($diff->y);
                    }
                ?>
                <div class="swiper-slide">
                    <div class="rb-doctors__spec-item--wrap">
                        <picture class="rb-doctors__spec-picture">
                            <?= $img ?>
                        </picture>
                        <div class="rb-doctors__spec-info">
                            <a href="<?= get_permalink($doctor['id']) ?>" class="rb-doctors__spec-fio">
                                <?= $fio[0] . '<br>' . ($fio[1] ?? '') . ' ' . ($fio[2] ?? '') ?>
                            </a>
                            <span class="rb-doctors__spec-occup"><?= esc_html($occup) ?></span>
                            <?php if ($years): ?>
                                <p class="rb-doctors__spec-standing">
                                    <span>Стаж</span> <?= esc_html($years) ?>
                                </p>
                            <?php endif; ?>
                            <span class="js-open-modal rb-doctors__spec-btn rb-button__orange"
                                  data-doctors="<?= esc_attr(get_the_title($doctor['id'])) ?>"
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
    <?php // Слот: после блока «Врачи» ?>
    <?php if (function_exists('rb_render_builder_slot')) rb_render_builder_slot($rb_builder, 'after_doctors'); ?>

    <!-- Блок "О направлении" -->
    <?php
    $direction_info = carbon_get_term_meta($curr_obj->term_id, 'rb_direction_info');
    $direction_title = carbon_get_term_meta($curr_obj->term_id, 'rb_direction_info_title');

    if ($direction_info): ?>
    <div class="" id="y-naprav">
        <div class="feedback-block">
            <div class="rb-page-header">
                <?php if (!empty($direction_title)): ?>
                    <h2><?= esc_html($direction_title); ?></h2>
                <?php else: ?>
                    <h2>О направлении</h2>
                <?php endif; ?>
            </div>
        </div>

        <div class="rb-accordion">
            <?php foreach ($direction_info as $item): ?>
                <div class="rb-accordion-item">
                    <a href="#" class="rb-accordion__label">
                        <h3><?= esc_html($item['question']); ?></h3>
                        <span class="rb-plus"><span></span><span></span></span>
                    </a>
                    <div class="rb-accordion__content">
                        <?= apply_filters('the_content', $item['answer']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    <?php // Слот: перед нижним инфоблоком / перед формой ?>
    <?php if (function_exists('rb_render_builder_slot')) rb_render_builder_slot($rb_builder, 'before_footer_info'); ?>

    <!-- Форма -->
    <section>
        <?php get_template_part( 'template-part/forms/form', 'programms' ); ?>
    </section>

    <!-- FAQ -->
    <?php
    $faq = carbon_get_term_meta($curr_obj->term_id, 'rb_faq');

    if ($faq): ?>
    <div class="" id="y-qest">
        <div class="feedback-block">
            <div class="rb-page-header"><h2>Частые вопросы</h2></div>
        </div>

        <div class="rb-accordion rb-accordion-qe">
            <?php foreach ($faq as $item): ?>
                <div class="rb-accordion-item">
                    <a href="#" class="rb-accordion__label">
                        <h3><?= esc_html($item['question']); ?></h3>
                        <div class="rb-arrow">
                            <svg width="14" height="9" viewBox="0 0 14 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13 7.75L7 1.25L1 7.25" stroke="#42567D"></path>
                            </svg>
                        </div>
                    </a>
                    <div class="rb-accordion__content">
                        <?= apply_filters('the_content', $item['answer']); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Популярные услуги -->
    <?php
    $popular_services = carbon_get_term_meta( $curr_obj->term_id, 'rb_services_popular' );

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
                    foreach ( $popular_services as $service ) {
                        $post_id = (int) $service['id'];
                        $post    = get_post( $post_id );

                        if ( $post && $post->post_status === 'publish' ) {
                            setup_postdata( $post );

                            echo '<div class="swiper-slide">';
                                get_template_part(
                                    'template-part/service',
                                    'item',
                                    array(
                                        'context' => 'popular',
                                        'tax'     => $curr_obj->name,
                                    )
                                );
                            echo '</div>';

                            wp_reset_postdata();
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    <?php // Пользовательские слоты (если будут использоваться) ?>
    <?php if (function_exists('rb_render_builder_slot')) {
        rb_render_builder_slot($rb_builder, 'custom_1');
        rb_render_builder_slot($rb_builder, 'custom_2');
    } ?>
</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        const label = e.target.closest('.rb-accordion__label');
        if (!label) return;

        e.preventDefault();

        const item = label.closest('.rb-accordion-item');
        const accordion = label.closest('.rb-accordion');
        if (!item || !accordion) return;

        const isActive = item.classList.contains('active');

        accordion.querySelectorAll('.rb-accordion-item').forEach(function (el) {
            el.classList.remove('active');
        });

        if (!isActive) {
            item.classList.add('active');
        }
    });
});
	
</script>

<?php get_footer(); ?>