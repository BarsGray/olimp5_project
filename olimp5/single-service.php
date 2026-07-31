<?php
	get_header();

	if ( function_exists( 'carbon_get_post_meta' ) ) {

		$rb_services_for_whom = carbon_get_post_meta( get_the_id(), 'rb_services_for_whom' );
		$rb_services_list = carbon_get_post_meta( get_the_id(), 'rb_services_list' );
		$rb_services_result = carbon_get_post_meta( get_the_id(), 'rb_services_result' );
		$rb_services_process = carbon_get_post_meta( get_the_id(), 'rb_services_process' );
		$rb_services_methodic = carbon_get_post_meta( get_the_id(), 'rb_services_methodic' );
		$rb_services_risks = carbon_get_post_meta( get_the_id(), 'rb_services_risks' );
		$rb_services_doctors = carbon_get_post_meta( get_the_id(), 'rb_services_doctors' );
		$rb_services_documents = carbon_get_post_meta( get_the_id(), 'rb_services_documents' );
		$rb_services_protive = carbon_get_post_meta( get_the_id(), 'rb_services_protive' );
		$rb_services_related = carbon_get_post_meta( get_the_id(), 'rb_services_related' );
		$rb_services_infoblock_process = carbon_get_post_meta( get_the_id(), 'rb_services_infoblock_process' );

		$rb_services_main = carbon_get_post_meta( get_the_id(), 'rb_services_main' );
		$service_list = carbon_get_post_meta( get_the_id(), 'rb_doc_services_list' );

	}

	$rb_services_desc = get_post_meta( get_the_ID(), '_rb_services_desc', true );
	$rb_services_for_whom_text = get_post_meta( get_the_ID(), '_rb_services_for_whom_text', true );
	$rb_services_result_title = get_post_meta( get_the_ID(), '_rb_services_result_title', true );
	$rb_services_list_title = get_post_meta( get_the_ID(), '_rb_services_list_title', true );
	$rb_services_list_text = get_post_meta( get_the_ID(), '_rb_services_list_text', true );
	$rb_services_process_title = get_post_meta( get_the_ID(), '_rb_services_process_title', true );
	$rb_services_protive_title = get_post_meta( get_the_ID(), '_rb_services_protive_title', true );
	$rb_services_documents_title = get_post_meta( get_the_ID(), '_rb_services_documents_title', true );
	$rb_services_documents_text = get_post_meta( get_the_ID(), '_rb_services_documents_text', true );
	$rb_services_methodic_subtitle = get_post_meta( get_the_ID(), '_rb_services_methodic_subtitle', true );
	$rb_services_price = get_post_meta( get_the_ID(), '_rb_services_price', true );
	$rb_services_infoblock_title = get_post_meta( get_the_ID(), '_rb_services_infoblock_title', true );


    $rb_services_infoblock_title =  get_post_meta( get_the_ID(), '_rb_services_bot_title', true );
    $rb_services_bot_text =  get_post_meta( get_the_ID(), '_rb_services_bot_text', true );
/* ====== ДОБАВЛЕНО: конструктор (только шорткоды) ====== */
	$svc_builder = function_exists('carbon_get_post_meta')
		? carbon_get_post_meta(get_the_ID(), 'rb_services_builder')
		: array();

	if ( ! function_exists('rb_render_service_shortcodes_slot') ) {
		function rb_render_service_shortcodes_slot( $items, $slot ) {
			if ( empty($items) || !is_array($items) ) return;
			foreach ($items as $it) {
				if ( ($it['_type'] ?? '') !== 'shortcode' ) continue;
				$pos  = $it['pos'] ?? 'after_intro';
				if ( $pos !== $slot ) continue;
				$code = trim($it['code'] ?? '');
				if ( $code ) {
					// Оборачиваем, чтобы не ломать стили вокруг
					echo '<div class="rb-shortcode-block">';
					echo do_shortcode( $code );
					echo '</div>';
				}
			}
		}
	}
	/* ====== /КОНЕЦ ДОБАВЛЕНОГО ====== */
	

?>


	<div class="rb-service-page rb-page">
<div class="rb-container">

		    <!-- service start -->
		    <section class="rb-service__top flex-wrap">
	            <div class="col-6 col-s-12 rb-service__top-text rb-page-header">
	                <h1><?php the_title(); ?></h1>
<?php // ВСТАВКА ШОРТКОДОВ ПОСЛЕ H1
					rb_render_service_shortcodes_slot($svc_builder, 'after_title'); ?>
	                <picture class="col-6 col-s-12 rb-service__top-img-mob">
	                	<?php echo get_the_post_thumbnail( get_the_ID(), 'full', '', array( 'class' => 'rb-img-cover' ) ); ?>
	                </picture>
<div class="rb-text rb-service__top-desc">
    <?php
    $text = $rb_services_desc;
    $text = str_replace('&nbsp;', ' ', $text);
    $text = str_replace("\xC2\xA0", ' ', $text);
    echo apply_filters('the_content', $text);
    ?>
</div>
<?php // ВСТАВКА ШОРТКОДОВ ПОСЛЕ ОПИСАНИЯ
					rb_render_service_shortcodes_slot($svc_builder, 'after_intro'); ?>

	                <div class="rb-text rb-service__top-price">
	                	<?php echo ($rb_services_price) ? 'Стоимость: ' . $rb_services_price . ' ₽' : ''; ?>
	                </div>
	                <div class="rb-service__top-link rb-button__orange-bg js-open-modal" data-modal="1">
	                    Записаться на прием
	                </div>
	            </div>
                <picture class="col-6 col-s-12 rb-service__top-img">
                    <?php echo get_the_post_thumbnail( get_the_ID(), 'full' ); ?>
                </picture>
		    </section>
			
		<div class="rb-services">

						<?php // ВСТАВКА ШОРТКОДОВ ПЕРЕД БЛОКОМ "УСЛУГИ"
			rb_render_service_shortcodes_slot($svc_builder, 'before_subservices'); ?>
			
			<!-- Начало блока Якорно меню -->
			<?php
			
$anchor_menu = [];

if ( is_singular('service') ) {
    $anchor_menu = carbon_get_post_meta(get_the_ID(), 'rb_anchor_menu');

} elseif ( is_tax('services') ) {
    $term = get_queried_object();
    if ($term instanceof WP_Term) {
        $anchor_menu = carbon_get_term_meta($term->term_id, 'rb_anchor_menu');
    }
}

if ($anchor_menu):
?>
<div class="rb-container">
  <div class="rb-links">
    <?php foreach ($anchor_menu as $item): ?>
      <a href="<?php echo esc_attr($item['anchor']); ?>">
        <?php echo esc_html($item['title']); ?>
      </a>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>
			<!-- Конец блока Якорно меню -->
	<!-- Начало блока Что это такое? -->		
			<?php
$whou_title = carbon_get_post_meta(get_the_ID(), 'rb_services_whou_is_title');
$whou_items = carbon_get_post_meta(get_the_ID(), 'rb_services_whou_is');

if (!empty($whou_items)) : ?>
    <section class="rb-block rb-whou-is">
        <div class="rb-container">
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
                            <div class="rb-whou-is__item-desc"><?php echo wpautop($item['desc']); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
<!-- Конец блока Что это такое? -->
			<!-- Начало блока услуг -->
<?php
$current_post_id = get_queried_object_id();

$selected_services = carbon_get_post_meta($current_post_id, 'rb_doc_services_list');
$rb_all_services_hide = carbon_get_post_meta($current_post_id, 'rb_all_services_hide');
$show_as_list = carbon_get_post_meta($current_post_id, 'rb_services_show_as_list');
$services_block_title = carbon_get_post_meta($current_post_id, 'rb_services_block_title');
if (empty($services_block_title)) {
    $services_block_title = 'Все услуги направления';
}
if (!empty($selected_services) && !$rb_all_services_hide) :
?>
<section class="rb-service__all js-serv-section">
    <div class="" id="y-serv">
        <div class="feedback-block">
            <div class="rb-page-header"><h2><?php echo esc_html($services_block_title); ?></h2></div>
            
            <?php if (!$show_as_list) : ?>
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
        
        <?php if ($show_as_list) : ?>
        <ul class="rb-pricelist__wrap">
            <?php 
            $counter = 0;
            foreach ($selected_services as $service_item) :
                $service_id = $service_item['id'];
                $counter++;
                $is_hidden = ($counter > 4);
            ?>
            <li class="rb-pricelist__item f-jcsb-center flex-wrap<?php echo $is_hidden ? ' hidden-by-default' : ''; ?>" id="service-<?php echo $service_id; ?>">
                <div class="col-10 col-s-12 flex-wrap">
                    <p class="rb-pricelist__item-name col-s-12 col-10">
                        <a style="text-decoration: none; color: inherit; display: block; width: 100%;" href="<?php echo get_permalink($service_id); ?>">
                            <?php echo get_the_title($service_id); ?>
                        </a>
                    </p>
                </div>
                <div class="col-2 col-s-12 rb-pricelist__item-btn--wrap">
                    <span class="rb-pricelist__item-btn rb-button__orange-full rb-doctors-page__btn js-open-modal doctor2" 
                          data-doctors="" 
                          data-programms="" 
                          data-service="<?php echo get_the_title($service_id); ?>" 
                          data-servicestax="">
                        Записаться
                    </span>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
        
        <?php if ($counter > 4) : ?>
        <div class="table-button-width"> 
            <a href="#" class="rb-service__top-link rb-button__orange-bg" id="show-more-services">Показать еще</a>
        </div>
        <?php endif; ?>
        
        <?php else : ?>
        <div class="sl-swiper-serv swiper">
            <div class="swiper-wrapper">
                <?php foreach ($selected_services as $service_item) : 
                    $service_id = $service_item['id'];
                ?>
                <div class="swiper-slide">
                    <?php get_template_part('template-part/service', 'item', array(
                        'post_id' => $service_id,
                        'context' => 'popular'
                    )); ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php if ($show_as_list) : ?>
<style>
.hidden-by-default {
    display: none !important;
}

.rb-pricelist__item-name a:hover {
    color: #ff6b35 !important;
    text-decoration: underline !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const showMoreBtn = document.getElementById('show-more-services');
    
    if (showMoreBtn) {
        showMoreBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            
            const hiddenItems = document.querySelectorAll('.rb-pricelist__item.hidden-by-default');
            
            if (hiddenItems.length > 0) {
                hiddenItems.forEach((item, index) => {
                    item.classList.remove('hidden-by-default');
                });
                this.style.display = 'none';
            }
        });
    }
    
    setTimeout(() => {
        const hiddenOnLoad = document.querySelectorAll('.rb-pricelist__item.hidden-by-default');
    }, 100);
});
</script>
<?php endif; ?>

<?php
else:
    echo "<!-- DEBUG: Services block NOT displayed -->";
    echo "<!-- DEBUG: selected_services empty = " . (empty($selected_services) ? 'true' : 'false') . " -->";
    echo "<!-- DEBUG: rb_all_services_hide = " . var_export($rb_all_services_hide, true) . " -->";
endif;
?>
<?php // ВСТАВКА ШОРТКОДОВ ПОСЛЕ БЛОКА "УСЛУГИ"
rb_render_service_shortcodes_slot($svc_builder, 'after_subservices'); ?>

<!-- Конец блока услуг -->

<!-- Начало блока Цен -->
<?php 
$price_title = carbon_get_post_meta(get_the_ID(), 'rb_price_title'); 
$price_list = carbon_get_post_meta(get_the_ID(), 'rb_price_list'); 
?>

<?php if (!empty($price_list)) : ?>
<div class="" id="y-price">
    <?php if ($price_title): ?>
        <h2 class="rb-title"><?php echo esc_html($price_title); ?></h2>
    <?php endif; ?>
    
    <div class="col-6 col-m-12">
        <div class="rb-service__mid-text"></div>
    </div>
    
    <ul class="rb-pricelist__wrap">
<?php
$visible_count = 0; // сколько реально вывели строк
foreach ($price_list as $item):

    $service_id = isset($item['service_select'][0]['id']) ? (int)$item['service_select'][0]['id'] : 0;
    if (!$service_id) continue;

    $service_post = get_post($service_id);
    if (!$service_post) continue;

    $name = $service_post->post_title;

    // Пробуем цену из разных мест (твои варианты)
    $price1 = carbon_get_post_meta($service_id, '_rb_serv_price');              // может вернуть NULL
    $price2 = get_post_meta($service_id, '_rb_serv_price', true);              // строка/пусто
    $price3 = carbon_get_post_meta($service_id, 'rb_serv_price');              // строка/пусто

    // Нормализуем
    $price1 = is_string($price1) ? trim($price1) : '';
    $price2 = is_string($price2) ? trim($price2) : '';
    $price3 = is_string($price3) ? trim($price3) : '';

    $price = $price1 ?: $price2 ?: $price3;

    // Если цены нет — НЕ выводим строку
    if ($price === '') continue;

    $visible_count++;
    $is_hidden = ($visible_count > 5) ? ' hidden-by-default' : '';
    ?>
    
    <li class="rb-pricelist__item f-jcsb-center flex-wrap<?php echo $is_hidden; ?>">
        <div class="col-10 col-s-12 flex">
            <p class="rb-pricelist__item-name col-s-12 col-10" style="padding-right: 20px;">
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

<?php if ($visible_count > 5): ?>
    <div class="table-button-width">
        <a href="#" class="rb-service__top-link rb-button__orange-bg" id="show-more-prices">Показать еще</a>
    </div>
<?php endif; ?>

</div>
<?php endif; ?>
<br />
<br />
<!-- Конец блока Цен -->

<!--Начало непонятного блока  -->
<?/*php if( $service_list ): ?>
	<section class="rb-doctors-page__price">
		<!-- pricelist start -->
		<div class="rb-pricelist" id="pricelist">
			
			<ul class="rb-pricelist__wrap">

				<?php foreach( $service_list as $arItem ) : ?>

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
		</div><!-- pricelist end -->

	</section>
<?php 
	endif;
	if( $rb_services_for_whom || $rb_services_for_whom_text ) : ?>
<!-- service end -->
	<section class="rb-service__for_whom">
		<h2 class="rb-title">Кому подойдет данная услуга</h2>
		<div class="rb-service-text col-6 col-m-12"><?php echo $rb_services_for_whom_text; ?></div>
		<!-- <ul class="rb-service__for_whom--list flex-wrap"> -->
		<div class="rb-service__for_whom--list rb-service__text">
			<?php 
				$i = 1;
				foreach( $rb_services_for_whom as $for_whom ) {
					
					//echo '<li>' . $for_whom['text'] . '</li>';
					if ( $i === 1 ) {

						echo $for_whom['text'];

					} else {

						echo mb_strtolower( $for_whom['text'] );
					}
					

					if( count( $rb_services_for_whom ) > $i  ) {

						echo ', ';

					}

					$i++;

				}
			?>
		</div>
		<!-- </ul> -->
	</section>
<?php 
	endif;
	if( $rb_services_result ) :
?>

	<section class="rb-service__section">
		<h2 class="rb-title"><?php echo esc_html( $rb_services_result_title ); ?></h2>
		<!-- <ul class="rb-service__process--list flex-wrap"> -->
			<?php 
				// foreach( $rb_services_result as $rb_result ) {
					
				// 	echo '<li class="rb-service__process">
				// 			<span class="rb-service__process--title">' . $rb_result['title'] . '</span>
				// 			<p class="rb-service__process--text">' . $rb_result['desc'] . '</p>
				// 		</li>';

				// }
			?>
		<!-- </ul> -->
		<div class="rb-service__process--list rb-service__text">
			<ul>
				<?php 
					$i = 1;
					foreach( $rb_services_result as $rb_result ) {
						
						echo '<li>' . $rb_result['desc'] . '</li>';
						
						// if ( $i === 1 ) {

						// 	echo $rb_result['desc'];

						// } else {

						// 	echo mb_strtolower( $rb_result['desc'] );
						// }

						// if( count( $rb_services_result ) > $i  ) {

						// 	echo ', ';

						// }

						$i++;

					}
				?>
			</ul>
		</div>
	</section>
<?php 
	endif;
	if( $rb_services_main ) :
		foreach( $rb_services_main as $main_item ) :
?>

			<section class="rb-service__section">
				<div class="flex-wrap">
					<h2 class="rb-title col-12 col-m-12"><?php echo esc_html( $main_item['title']); ?></h2>
					<div class="rb-service__section--text col-12 col-m-12"><?php echo apply_filters( 'the_content', $main_item['desc']); ?></div>
					<div class="rb-pricelist">
			
						<ul class="rb-pricelist__wrap">

							<?php foreach( $main_item['services'] as $arItem ) : ?>

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
				</div>
				</div>

			</section>
<?php 
		endforeach;
	endif;?>
					
					<?
	if( $rb_services_list ) :
?>

	<section class="rb-service__section">
		<div class="flex-wrap">
			<h2 class="rb-title col-6 col-m-12"><?php echo esc_html( $rb_services_list_title ); ?></h2>
			<p class="rb-service__section--text col-6 col-m-12"><?php echo esc_html( $rb_services_list_text ); ?></p>
		</div>
		<ul class="rb-service__list flex-wrap">
			<?php 
				foreach( $rb_services_list as $rb_service ) {
					
					echo '<li class="rb-service__list--item">
							<div class="flex-wrap">
								<span class="rb-service__list--title col-3 col-m-12 col-s-12">	' . $rb_service['title'] . ' </span>
								<div class="rb-service__list--text col-3 col-m-4 col-s-12">	' . $rb_service['desc'] . ' </div>
								<div class="rb-service__list--text col-3 col-m-4 col-s-12">	' . $rb_service['desc2'] . ' </div>
								<div class="rb-service__list--text col-3 col-m-4 col-s-12">	' . $rb_service['desc3'] . ' </div>
							</div>
						</li>';

				}
			?>
		</ul>

	</section>
<?php 
	endif;
	if( $rb_services_infoblock_process ) :
?>

	<section class="rb-service__section">
		<h2 class="rb-title"><?php echo esc_html( $rb_services_infoblock_title ); ?></h2>
		<div class="rb-service__process--list rb-service__text">
			<?php 
				foreach( $rb_services_infoblock_process as $rb_result ) {

					if ( $rb_result['title'] ) {
						echo $rb_result['title'] . ': ';
					}

					echo $rb_result['desc'] . ' ';
					
				}
			?>
		</div>
	</section>
<?php 
	endif;
	if( $rb_services_process ) :
?>

	<section class="rb-service__section">
		<h2 class="rb-title"><?php echo esc_html( $rb_services_process_title ); ?></h2>
		<div class="rb-service__process--list rb-service__text">
			<?php 
				foreach( $rb_services_process as $rb_result ) {

					if ( $rb_result['title'] ) {
						// echo '<span>' . $rb_result['title'] . ': ';
						echo '<span>' . $rb_result['title'] . '</span>';
					}

					echo '<div class="rb-service__process--txt">' . $rb_result['desc'] . '</div>';
					
				}
			?>
		</div>
	</section>
<?php 
	endif;
	if( $rb_services_methodic ) :
?>
	<section class="rb-service__section">
		<h2 class="rb-title">Методика проведения</h2>
		<div class="rb-service__methodics">
			<!-- <span class="rb-service__methodics-title"><?php //echo esc_html( $rb_services_methodic_subtitle ); ?></span> -->
			<!-- <ol class="rb-service__methodics--list flex-wrap"> -->
				<?php 
					// foreach( $rb_services_methodic as $rb_methodic ) {
						
					// 	echo '<li class="rb-service__methodics-item">
					// 			<span class="rb-service__methodics-item--title">' . $rb_methodic['title'] . '</span>';

					// 	if ( $rb_methodic['desc'] ) {
								
					// 		echo '<div class="rb-service__methodics--text">
					// 				<div class="rb-service__methodics-item--desc">' . apply_filters( 'the_content', $rb_methodic['desc'] ) . '</div>
					// 			  	<span class="rb-service__methodics-show">Показать</span>
					// 			  </div>';

					// 	}

					// 	echo '</li>';

					// }
				?>
			<!-- </ol> -->
			<div class="rb-service__process--list rb-service__text">
				<?php 
					$i = 1;
					foreach( $rb_services_methodic as $rb_result ) {

						if ( $i === 1 ) {

							echo $rb_result['title'];

						} else {

							echo mb_strtolower( $rb_result['title'] );
						}

						if( count( $rb_services_methodic ) > $i  ) {

							echo ', ';

						}

						$i++;

					}
				?>
			</div>
		</div>
	</section>
<?php 
	endif;
	if( $rb_services_risks ) :
?>

	<section class="rb-service__section">
		<h2 class="rb-title">Возможные риски и последствия</h2>
		<!-- <ul class="rb-service__risks--list flex-wrap"> -->
			<?php 
				// foreach( $rb_services_risks as $rb_risks ) {
					
				// 	echo '<li class="rb-service__risk rb-service__protive">
				// 			<p class="rb-service__process--text">' . $rb_risks['desc'] . '</p>
				// 		</li>';

				// }
			?>
		<!-- </ul> -->
		<div class="rb-service__risks--list rb-service__text">
			<?php 
				$i = 1;
				foreach( $rb_services_risks as $rb_result ) {
					

					if ( $i === 1 ) {

						echo $rb_result['desc'];

					} else {

						echo mb_strtolower( $rb_result['desc'] );
					}

					if( count( $rb_services_risks ) > $i  ) {

						echo ', ';

					}

					$i++;

				}
			?>
		</div>
	</section>
<?php 
	endif;*/?>
<!--Конец непонятного блока  -->							
								
<!--Начало блока портфолио -->							
<?php $slides = carbon_get_post_meta(get_the_ID(), 'rb_portfolio_list');?>

<?php if (!empty($slides)) : ?>
<div class="" id="y-portfolio">
    <div class="feedback-block">
		<div class="rb-page-header">
			<h2>Портфолио</h2>
		</div>
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
							<div class="feedback__label">
								<?php echo esc_html($item['label']); ?>
							</div>
						</div>
						<div class="feedback-content">
							<div class="feedback__col">
								<div class="feedback__wrap">
									<div class="feedback__title">
										<?php echo esc_html($item['name']); ?>
									</div>
									<div class="feedback-widget">
										<span><?php echo esc_html($item['position']); ?></span>
									</div>
								</div>
								<div class="feedback-asc">
									<?php echo esc_html($item['desc']); ?>
								</div>
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
<?php // ВСТАВКА ШОРТКОДОВ ПЕРЕД БЛОКОМ "СПЕЦИАЛИСТЫ"
rb_render_service_shortcodes_slot($svc_builder, 'before_doctors'); ?>

<!-- Начало блока специалисты -->
<?php

	$is_service = is_singular('service');
	$is_direction = is_tax('services');

	$doctors = [];
	if ($is_service) {
		$doctors = carbon_get_post_meta(get_the_ID(), 'rb_services_doctors');
	} elseif ($is_direction) {
		$term = get_queried_object();
		$doctors = carbon_get_term_meta($term->term_id, 'rb_services_spec');
	}

	if (!empty($doctors)) : ?>
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

	<!-- Конец блока специалисты -->
<?php // ВСТАВКА ШОРТКОДОВ ПОСЛЕ БЛОКА "СПЕЦИАЛИСТЫ"
rb_render_service_shortcodes_slot($svc_builder, 'after_doctors'); ?>

	<!-- Начало блока О напралении -->
									<?php
	$is_term = is_tax('services');
	$direction_info = $is_term
		? carbon_get_term_meta(get_queried_object_id(), 'rb_direction_info')
		: carbon_get_post_meta(get_the_ID(), 'rb_direction_info');
	$title = carbon_get_post_meta( get_the_ID(), 'rb_direction_block_title' ) ?: 'Подробнее о направлении';

	if ($direction_info):
?>

	<div class="" id="y-naprav">
		<div class="feedback-block">
			<div class="rb-page-header"><h2><?= esc_html($title );?></h2></div>
		</div>

		<div class="rb-accordion">
			<?php foreach ($direction_info as $item): ?>
			<div class="rb-accordion-item">
				<a href="#" class="rb-accordion__label">
					<h3><?= esc_html($item['question']); ?></h3>
					<span class="rb-plus">
						<span>
						</span>
						<span>
						</span>
					</span>
				</a>
				<div class="rb-accordion__content">
				<?= apply_filters('the_content', $item['answer']); ?>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>

<!-- Конец блока о направлении -->

<!-- Начало блока формы -->								
   <section>
        <?php get_template_part( 'template-part/forms/form', 'programms' ); ?>
    </section>								
<!-- Конец блока формы -->
								
<!-- Начало блока Faq -->
		<?php
$faq = $is_term
    ? carbon_get_term_meta(get_queried_object_id(), 'rb_faq')
    : carbon_get_post_meta(get_the_ID(), 'rb_faq');

if ($faq):
?>
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
						
<!-- Конец блока Faq -->
			<?
				if( $rb_services_protive ) :
			?>

				<section class="rb-service__section">
					<h2 class="rb-title"><?php echo esc_html( $rb_services_protive_title ); ?></h2>
			    	<!-- <ul class="rb-service__process--list flex-wrap"> -->
			    		<?php 
			    			// foreach( $rb_services_protive as $rb_protive ) {
			    				
			    			// 	echo '<li class="rb-service__process rb-service__protive">
			    			// 			<span class="rb-service__process--title">' . $rb_protive['title'] . '</span>
			    			// 			<p class="rb-service__process--text">' . $rb_protive['desc'] . '</p>
			    			// 		</li>';

			    			// }
			    		?>
			    	<!-- </ul> -->
					<div class="rb-service__process--list rb-service__text">
			    		<?php 
			    			$i = 1;
			    			foreach( $rb_services_protive as $rb_result ) {

			    				if ( $i === 1 ) {

			    					echo $rb_result['desc'];

			    				} else {

			    					echo mb_strtolower( $rb_result['desc'] );
			    				}

			    				if( count( $rb_services_protive ) > $i  ) {

			    					echo ', ';

			    				}

			    				$i++;

			    			}
			    		?>
			    	</div>
			    </section>
			<?php 
				endif;
				if( $rb_services_documents ) :
			?>

				<section class="rb-service__section">
					<h2 class="rb-title"><?php echo esc_html( $rb_services_documents_title ); ?></h2>
			    	<p class="rb-service-text"><?php echo esc_html( $rb_services_documents_text ); ?></p>
			    	<ul class="rb-service__documents flex-wrap">
			    		<?php 
			    			foreach( $rb_services_documents as $rb_documents ) : 
			    				?>
			    				<li class="rb-service__document flex">
			    						<div class="rb-service__document--head">
			    							<span class="rb-service__document--title"><?php echo $rb_documents['title']; ?></span>
			    							<p class="rb-service__document--text"><?php echo $rb_documents['desc']; ?></p>
			    						</div>
			    						<?php if( $rb_documents['diff'] ) : ?>
			    							<span class="rb-service__document--imp">Обязательно</span>
			    						<?php else : ?>
			    							<span class="rb-service__document--noimp">Необязательно</span>
			    						<?php endif; ?>
			    					</li>
			    				<?php
			    			endforeach;
			    		?>
			    	</ul>
			    </section>
			<?php 
				endif;
				if ( $rb_services_related ) :

					$post_tax = wp_get_post_terms( get_the_ID(), 'services' );
					$all_services = new WP_Query(
		    			array(
		    				'post_type' => 'service',
		    				'post__in' => array_map( function($item){ return $item['id']; }, $rb_services_related ),
		    			)
		    		);
			?>

					<section class="rb-service__all js-serv-section">
				        <h2 class="rb-title">Похожие услуги</h2>
				        <ul class="rb-service__list flex-wrap">
			                    <?php 
					    			while( $all_services->have_posts() ) {
					    				$all_services->the_post();

					    				get_template_part( 'template-part/service', 'item', array( 'tax' => $post_tax[0]->name ) ); 

					    			}
					    		?>
				        </ul>
				    </section>
			<?php 
				wp_reset_postdata();
				endif;
					// ПЕРЕД «НИЖНИМ ИНФОБЛОКОМ»
				rb_render_service_shortcodes_slot($svc_builder, 'before_bottom_info');

				if( $rb_services_bot_text ) :
			?>


				<section class="rb-service__section">
					<div class="flex-wrap">
						<?php if( $rb_services_infoblock_title  ) : ?>
			    			<h2 class="rb-title col-12 col-m-12"><?php echo esc_html( $rb_services_infoblock_title  ); ?></h2>
			    		<?php endif; ?>
			    		<div class="rb-service__section--text col-12 col-m-12"><?php echo apply_filters( 'the_content', $rb_services_bot_text  ); ?></div>
			    	</div>

			    </section>
			<?php 
				endif;
			?>
		</div>
<?php
$popular_services = carbon_get_the_post_meta('rb_services_popular_service');

if (!empty($popular_services)): ?>
<section class="rb-service__all">
    <div class="" id="y-pop">
        <div class="feedback-block">
            <div class="rb-page-header"><h2>Популярные услуги</h2></div>
        <div class="feedback-arrows">
            <a href="#" class="pop-arrow-prev" tabindex="0" role="button" aria-label="Previous slide">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                    <rect width="32" height="32" rx="4" fill="#F3951D"/>
                    <path d="M19 22L12.5 16L18.5 10" stroke="white"/>
                </svg>
            </a>
            <a href="#" class="pop-arrow-next" tabindex="0" role="button" aria-label="Next slide">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                    <rect width="32" height="32" rx="4" transform="matrix(-1 0 0 1 32 0)" fill="#F3951D"/>
                    <path d="M13 22L19.5 16L13.5 10" stroke="white"/>
                </svg>
            </a>
        </div>
        </div>

        <div class="sl-swiper-pop swiper">
            <div class="swiper-wrapper">
                <?php foreach ($popular_services as $item): ?>
                    <div class="swiper-slide">
                        <?php
                        get_template_part('template-part/service', 'item', array(
                            'post_id' => $item['id'],
                            'context' => 'popular'
                        ));
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>
</div>


	</div>
		<?php
// КАСТОМНЫЕ СЛОТЫ В САМОМ НИЗУ
rb_render_service_shortcodes_slot($svc_builder, 'custom_1');
rb_render_service_shortcodes_slot($svc_builder, 'custom_2');
?>
	<?php //get_template_part( 'template-part/forms/form', 'subscribe' ); ?>
<?php get_footer(); ?>
