<?php 

get_header();

/*
Template Name: Главная
*/

if ( function_exists( 'carbon_get_post_meta' ) ) {

	$rb_main_slider = carbon_get_post_meta( get_the_ID(), 'rb_main_slider' ) ? array_map( function( $val ){ return $val['id']; }, carbon_get_post_meta( get_the_ID(), 'rb_main_slider' ) ) : '';

}

$slider_query = new WP_Query(
	array(
		'post_type' => 'slider',
		'order' => 'ASC',
		'posts_per_page' => -1,
		'post__in' => $rb_main_slider
	)
);

if ( $slider_query->have_posts() ) :

?>
	<!-- Swiper -->
	<section class="rb-main__slider rb-slider">
		<div class="rb-container">
			<div class="swiper" id="rbSwiper">
				<div class="swiper-wrapper">
					<?php

						while( $slider_query->have_posts() ) {
							$slider_query->the_post();

							get_template_part( 'template-part/main', 'slider-item' );

						}

					?>
				</div>

				<div class="swiper-button-next"></div>
				<div class="swiper-button-prev"></div>
			</div>
			<div class="swiper-pagination"></div>
		</div>
	</section>

<?php 
	endif; 
	wp_reset_postdata(); 

	// Конструктор: после слайдера
	rb_main_builder_render('after_slider');

	get_template_part( 'template-part/forms/form', 'main-top' );

	// Конструктор: после верхней формы
	rb_main_builder_render('after_form_top');

	$rb_main_about_upper_title = get_post_meta( get_the_ID(), '_rb_main_about_upper_title', true );
	$rb_main_about_title = get_post_meta( get_the_ID(), '_rb_main_about_title', true );
	$rb_main_about_text = get_post_meta( get_the_ID(), '_rb_main_about_text', true );
	$rb_main_about_img_1 = get_post_meta( get_the_ID(), '_rb_main_about_img_1', true );
	$rb_main_about_img_2 = get_post_meta( get_the_ID(), '_rb_main_about_img_2', true );

?>

<!-- main_about start -->
<section class="rb_main__about">

	<?php
		// Конструктор: внутри about в самом начале секции
		rb_main_builder_render('about_top');
	?>

	<?php echo do_shortcode('[advantages]'); ?>

	<?php
		// Конструктор: внутри about после advantages
		rb_main_builder_render('about_after_adv');
	?>

    <div class="rb-container rb_main__about--wrap flex">
        <div class="col-6 col-s-12">
            <div class="rb_main__about--text">

                <span class="rb_main__about--subhead"><?php echo esc_html( $rb_main_about_upper_title ); ?></span>
                <h1 class="rb_main__about--title"><?php echo $rb_main_about_title; ?></h1>
                <div class="rb_main__about--desc"><?php echo esc_html( $rb_main_about_text ); ?></div>

            </div>
        </div>
        <div class="col-6 col-s-12">
            <div class="rb_main__about-image flex">
                <picture>
                    <?php 
                    	if( $rb_main_about_img_1 ) {
                    		echo wp_get_attachment_image( $rb_main_about_img_1, 'large' );
                    	} 
                    ?>
                </picture>
                <picture>
                    <?php 
                    	if( $rb_main_about_img_2 ) {
                    		echo wp_get_attachment_image( $rb_main_about_img_2, 'large' );
                    	} 
                    ?>
                </picture>
            </div>
        </div>
    </div>

	<?php
		// Конструктор: внутри about в конце секции
		rb_main_builder_render('about_bottom');
	?>

</section>
<!-- main_about end -->


<?php
// Конструктор: перед услугами (секцией rb-services)
rb_main_builder_render('services_top');
?>

<!-- cubes start -->
<section class="rb-services">
    <div class="rb-container">
    	<div class="rb-services__header flex">
    		<div class="col-6 col-m-12 col-s-12">
    			<h2 class="rb-title">Услуги</h2>
    		</div>
    		<div class="col-6 col-m-12 col-s-12">
    			<p class="rb-services__header--text">Наш центр предоставляет широкий спектр услуг, включая реабилитационные, восстановительные, профилактические и консультационные.</p>
    		</div>
    	</div>

		<?php
			// Конструктор: внутри услуг — после шапки (заголовок/описание)
			rb_main_builder_render('services_header');
		?>

		<?php
	
			if ( function_exists( 'carbon_get_post_meta' ) ) {

				$rb_main_services_before = carbon_get_post_meta( get_the_ID(), 'rb_main_services_before' );

			}

			if ( $rb_main_services_before ) : 
		?>

			<?php
				// Конструктор: внутри услуг — перед списком кубиков
				rb_main_builder_render('services_inside_top');
			?>

	    	<div class="rb-services__body flex">
	    		<?php 
	    			if( $rb_main_services_before ) {

	    				foreach( $rb_main_services_before as $rb_main_service ) {

	    					get_template_part( 'template-part/main', 'services-item', array(
								'title'      => $rb_main_service['title'],
								'link'       => $rb_main_service['link'],
								'back_color' => $rb_main_service['back_color'],
								'text_color' => $rb_main_service['text_color'],
								'bg_1'       => $rb_main_service['bg_1'],
								'bg_2'       => $rb_main_service['bg_2'],
							) );

	    				}

					}
				?>
			</div>

			<?php
				// Конструктор: внутри услуг — после списка кубиков
				rb_main_builder_render('services_inside_bot');
			?>

		<?php endif; ?>
	</div>
</section><!-- cubes end -->

<?php
// Конструктор: после услуг
rb_main_builder_render('services_bottom');

// Конструктор: перед специалистами
rb_main_builder_render('before_doctors');
?>

<!-- Блок специалистов на главной -->
<?php
$show_doctors = carbon_get_post_meta(get_the_ID(), 'rb_main_doctors_show');
$doctors = carbon_get_post_meta(get_the_ID(), 'rb_main_doctors');
$doctors_title = carbon_get_post_meta(get_the_ID(), 'rb_main_doctors_title') ?: 'Специалисты';

if ($show_doctors && !empty($doctors)) : ?>
<br />

<div class="rb-container" id="spec">
    <div class="feedback-block">
        <div class="rb-page-header"><h2><?= esc_html($doctors_title) ?></h2></div>
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

<?php
// Конструктор: после специалистов
rb_main_builder_render('after_doctors');
?>

<?php 
	$news_query = new WP_Query(
		array(
			'post_type' => 'post',
			'cat' => array(5),
			'posts_per_page' => -1
		)
	);

	// Конструктор: перед новостями
	rb_main_builder_render('before_news');

	if ( $news_query->have_posts() ) :

		$categories = get_terms(array('taxonomy'=>'category'));
		?>
			<section class="rb-news">
			        <div class="rb-news__header rb-container">
						<h2 class="rb-news__title">Новости</h2>
			            <p class="rb-news__text">Что у нас происходит интересного?</p>
			        </div>

			        <div class="rb-news__body">
			            <div class="rb-news__body--wrap">
			                <div class="rb-news__menu rb-container">
								<?php if ( $categories ) : ?>
									<ul>
										<li>
											<span class="category_selector active" data-catid="5">Акции</span>
										</li>
										<li>
											<span class="category_selector" data-catid="140">Новости</span>
										</li>
									</ul>
								<?php endif; ?>
			                </div>
							<div class="rb-news__menu-slider rb-container">

								<div class="rb-news__body--list swiper rb-slider" id="rbSwiperBot">
									<div class="filter_list swiper-wrapper">

										<?php 
											while( $news_query->have_posts() ) {
												$news_query->the_post();
												get_template_part( 'template-part/main', 'blog-item' );
											}
										?>

									</div>

									<div class="swiper-button-next"></div>
									<div class="swiper-button-prev"></div>
									<div class="swiper-pagination"></div>
								</div>

								<div class="rb-news__btn">
									<a href="/akczii/" target="_blank" data-catid="5" class="rb-button__orange rb-news__main-btn rb-news__main-btn--active">Все акции</a>
									<a href="/news/" target="_blank" data-catid="140" class="rb-button__orange rb-news__main-btn">Все новости</a>
								</div>
							</div>
			            </div>
			        </div>
			</section>

		<?php endif; ?>

<?php
// Конструктор: после новостей
rb_main_builder_render('after_news');
?>

<?php 
	$product_query = new WP_Query(
		array(
			'post_type' => 'bio-market22',
			'posts_per_page' => -1
		)
	);

	// Конструктор: перед биомаркетом
	rb_main_builder_render('before_biomarket');

	if ( $product_query->have_posts() ) :
?>

	<section>
		<div class="rb-container">
	    	<div class="rb-services__header flex">
	    		<div class="col-6 col-m-12 col-s-12">
	    			<h2>Биомаркет</h2>
	    		</div>
	    	</div>
	    	<div class="rb-main-bio__body">
	            <div class="rb-main-bio__body--wrap">
					<div class="rb-main-bio__body-slider rb-container">

						<div class="rb-main-bio__body--list swiper rb-slider" id="rbMainBio">
							<div class="filter_list swiper-wrapper">

								<?php 
									while( $product_query->have_posts() ) {
										$product_query->the_post();
										get_template_part( 'template-part/market', 'item' );
									} 
								?>

							</div>
						</div>

						<div class="swiper-button-next"></div>
						<div class="swiper-button-prev"></div>
						<div class="swiper-pagination"></div>
					</div>
	            </div>
	        </div>
		</div>
	</section>

<?php 
	endif;

	// Конструктор: после биомаркета
	rb_main_builder_render('after_biomarket');

	$events_query = new WP_Query(
		array(
			'post_type' => 'events',
			'posts_per_page' => -1,
			'order' => 'ASC',
			'tax_query' => array(
				array(
					'taxonomy' => 'events-type',
					'field'    => 'term_id',
					'terms'    =>  array( 121 ),
					'operator' => 'NOT IN'
				)
			)
		)
	);

	// Конструктор: перед событиями
	rb_main_builder_render('before_events');

	if ( $events_query->have_posts() ) :
?>



	<section class="rb-events">
		<div class="rb-container">
	    	<h2 class="rb-title">Семинары, лекции и мастер-классы</h2>
	    	<div class="rb-main-bio__body">

				<div class="rb-main-bio__body--list swiper rb-slider" id="rbMainEvents">
					<div class="filter_list swiper-wrapper">

						<?php 
							while( $events_query->have_posts() ) {
								$events_query->the_post();
								if( has_post_thumbnail() ) :
									?>
									<div class="swiper-slide rb-events__item">
										<picture>
											<?php the_post_thumbnail( 'large' ); ?>
										</picture>
										<a href="https://olimp5.ru/raspisanie-meropriyatij/" class="rb-events__item-link rb-button__grey">
											Перейти
											<svg width="8" height="15" viewBox="0 0 10 18" fill="none" xmlns="http://www.w3.org/2000/svg">
												<path d="M1.25 1.5L8.75 9L1.25 16.5" stroke="#121212" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
											</svg>
										</a>
									</div>
									<?php 
								endif;
							} 
						?>

					</div>
					<div class="swiper-button-next"></div>
					<div class="swiper-button-prev"></div>	
				</div>

			</div>
		</div>
	</section>

<?php endif; ?>

<?php
// Конструктор: после событий
rb_main_builder_render('after_events');
?>

<section class="rb-main__form-bottom" id="form">
	<?php
		// Конструктор: перед нижней формой (внутри секции)
		rb_main_builder_render('before_form_bottom');
	?>

	<?php get_template_part( 'template-part/forms/form', 'main-bot-new' ); ?>

	<?php
		// Конструктор: после нижней формы (внутри секции)
		rb_main_builder_render('after_form_bottom');
	?>
</section>

<?php 
	$contacts_page = get_pages(
		array(
	    	'meta_key' => '_wp_page_template',
		    'meta_value' => 'template-page/page-contacts.php'
		)
	);

	// Конструктор: перед контактами
	rb_main_builder_render('before_contacts');

	if( $contacts_page ) :

		$phone = get_post_meta( $contacts_page[0]->ID, '_rb_phone', true );
		$email = get_post_meta( $contacts_page[0]->ID, '_rb_email', true );
		$rb_time_1 = get_post_meta( $contacts_page[0]->ID, '_rb_time_1', true );
		$rb_time_2 = get_post_meta( $contacts_page[0]->ID, '_rb_time_2', true );
		$rb_time_3 = get_post_meta( $contacts_page[0]->ID, '_rb_time_3', true );
		$rb_map = get_post_meta( $contacts_page[0]->ID, '_rb_map', true );
?>

<section class="rb-contacts__top">
    <div class="rb-container">
        <div class="rb-contacts__top-wrap flex-wrap">
            <div class="col-6 col-s-12">
                <h2 class="rb-title"><?php echo get_the_title( $contacts_page[0]->ID ); ?></h2>
                <div class="rb-contacts__top-info flex">
                    <div class="col-6">
                        <span class="rb-contacts__top-info--name">Телефон для записи</span>
                        <a class="rb-contacts__top-info--link" href="tel:<?php echo formatPhone( $phone ); ?>"><?php echo $phone; ?></a>
                    </div>
                    <div class="col-6">
                        <span  class="rb-contacts__top-info--name">Электронная почта</span>
                        <a class="rb-contacts__top-info--link" href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
                    </div>
                </div>
                <ul class="rb-contacts__top-worktime flex">
                    <li class="col-4 rb-contacts__worktime-item">
                        <span class="rb-contacts__worktime-days">пн-пт</span>
                        <span class="rb-contacts__worktime-time"><?php echo $rb_time_1; ?></span>
                    </li>
                    <li class="col-4 rb-contacts__worktime-item">
                        <span class="rb-contacts__worktime-days">сб</span>
                        <span class="rb-contacts__worktime-time"><?php echo $rb_time_2; ?></span>
                    </li>
                     <li class="col-4 rb-contacts__worktime-item">
                        <span class="rb-contacts__worktime-days">вс</span>
                        <span class="rb-contacts__worktime-time"><?php echo $rb_time_3; ?></span>
                    </li>
                </ul>
            </div>
            <div class="col-6 col-s-12 rb-contacts__top-map" id="map">
                <?php echo $rb_map; ?>
            </div>
        </div>
    </div>
</section>

<?php endif; ?>

<?php
// Конструктор: после контактов
rb_main_builder_render('after_contacts');

get_footer();