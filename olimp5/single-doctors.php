<?php
	get_header();

	if ( function_exists( 'carbon_get_post_meta' ) ) {

		$rb_services_list = carbon_get_post_meta( get_the_id(), 'rb_doc_services_list' );
		$rb_doc_additional = carbon_get_post_meta( get_the_id(), 'rb_doc_additional' );
		$rb_doc_serv_compl = carbon_get_post_meta( get_the_id(), 'rb_doc_serv_compl' );

	}

	$fio = explode( ' ', get_the_title() );
	$occup = get_post_meta( get_the_ID(), '_rb_doc_occup', true ); 
	$rb_doc_surname = get_post_meta( get_the_ID(), '_rb_doc_surname', true ); 
	$rb_doc_name = get_post_meta( get_the_ID(), '_rb_doc_name', true ); 
	$rb_doc_patronimyc = get_post_meta( get_the_ID(), '_rb_doc_patronimyc', true ); 

	$surname = $rb_doc_surname ?: $fio[0];
	$name = $rb_doc_name ?: $fio[1];
	$patronimyc = $rb_doc_patronimyc ?: $fio[2];


	$rb_doc_review_show = get_post_meta( get_the_ID(), '_rb_doc_review_show', true ); 
	$rb_doc_review_btn_link = get_post_meta( get_the_ID(), '_rb_doc_review_btn_link', true ); 
	$rb_doc_review_code = get_post_meta( get_the_ID(), '_rb_doc_review_code', true ); 


?>

	<!-- doctor start -->
<div class="rb-doctors-page rb-page">

	<div class="rb-container">
		<section class="rb-doctors-page__top flex-wrap">
			<div class="rb-doctors-page__bio col-6 col-s-12">
				<h1 class="rb-page-header"><?php echo $surname . ' <br>'. $name . ' ' . $patronimyc; ?></h1>
				<p class="rb-doctors-page__bio-occup"><?php echo $occup; ?></p>

				<?php //if( $arResult['PROPERTIES']['ORDER']['VALUE'] == 'Y' ) : ?>
					<?php /* <span class="rb-button__orange-full rb-doctors-page__btn js-open-modal doctor2" data-doctors="<?php echo get_the_title(); ?>" data-programms="" data-service="" data-servicestax="">Записаться на прием</span> */ ?>
				<?php
					$doc_id_lk = carbon_get_post_meta(get_the_ID(), 'rb_doc_id_lk');
					$order_btn_params = 'data-doctors="' . get_the_title() . '" data-programms="" data-service="" data-servicestax=""';
					if ($doc_id_lk) {$order_btn_params = "onclick=\"event.preventDefault(); if(window.analyticsInitialized && typeof ym === 'function') { ym(84731377, 'reachGoal', 'doctor_booking_click'); } ONDOC.showModal('/booking-appointment?doctor=" . $doc_id_lk . "');\"";}
				?>
					<span class="rb-button__orange-full rb-doctors-page__btn<?php echo $doc_id_lk ? ' ' : ' js-open-modal ' ?>doctor2"<?php echo $order_btn_params; ?>>Записаться на прием</span>
				<?php //endif; ?>

			</div>
			<div class="rb-doctors-page__photo col-6 col-s-12">
				<?php 
					if( has_post_thumbnail() ) {
						the_post_thumbnail( 'large', array( 'class' => 'rb-img-cover' ) );
					} else {
						echo '<img class="rb-img-cover" src="' . get_stylesheet_directory_uri() . '/assets/img/nopic.png" alt="">';
					} 
				?>

                <?php
                    $video_id = carbon_get_post_meta(get_the_ID(), 'rb_doctors_video');
                    $video_url = wp_get_attachment_url($video_id);
                if ($video_id) : ?>
                    <a class="rb-doctors-page__top_video_link" data-fancybox data-type="video" href="<?php echo esc_url($video_url); ?>"></a>
                <?php endif; ?>
			</div>
		</section>

		<?php
			$rb_doc_diagnosis = get_post_meta( get_the_ID(), '_rb_doc_diagnosis', true );

			if ( $rb_doc_diagnosis ) : 
		?>

			<section class="rb-doctors-page__education single-doctors-education flex-wrap">

				<h2 class="rb-title rb-pricelist__title col-12 col-m-12"><span>Диагнозы и состояния</span>, при которых стоит обратиться к специалисту квалификации</h2>
				<div class="rb-doctors-page__education-wrap col-12 col-m-12">
					<div class="rb-doctors-page__education-item">
						<div class="rb-doctors-page__education-list">
							<?php echo apply_filters( 'the_content', $rb_doc_diagnosis ); ?>
						</div>
					</div>
				</div>

			</section>


		<?php endif; 

			$rb_doc_infoblock = get_post_meta( get_the_ID(), '_rb_doc_infoblock', true );

			if ( $rb_doc_infoblock ) : 
		?>

			<section class="rb-doctors-page__education flex-wrap">

				<h2 class="rb-title rb-pricelist__title col-6 col-m-12"><span>Специализация</span></h2>
				<div class="rb-doctors-page__education-wrap col-6 col-m-12">
					<div class="rb-doctors-page__education-item">
						<div class="rb-doctors-page__education-list">
							<?php echo apply_filters( 'the_content', $rb_doc_infoblock ); ?>
						</div>
					</div>
				</div>

			</section>

		<?php endif; ?>

		
		<?php
			$rb_doc_education_1 = get_post_meta( get_the_ID(), '_rb_doc_education_1', true );
			$rb_doc_education_2 = get_post_meta( get_the_ID(), '_rb_doc_education_2', true );
			$rb_doc_education_3 = get_post_meta( get_the_ID(), '_rb_doc_education_3', true );
			$rb_doc_education_4 = get_post_meta( get_the_ID(), '_rb_doc_education_4', true );

			if ( $rb_doc_education_1 || $rb_doc_education_2 || $rb_doc_education_3 || $rb_doc_education_4 ) : 
		?>

			<section class="rb-doctors-page__education single-doctors-education single-doctors-education_hide flex-wrap">

				<h2 class="rb-title rb-pricelist__title col-12 col-m-12">образование и <span>квалификации</span></h2>

				<div class="rb-doctors-page__education-wrap col-12 col-m-12">
					<div class="single-doctors-education-left_box">
					<?php if ( $rb_doc_education_1 ) : ?>
						<div class="rb-doctors-page__education-item">
							<h3 class="rb-doctors-page__education-name">Сведения из документа об образовании</h3>
							<ul class="rb-doctors-page__education-list">
								<?php echo apply_filters( 'the_content', $rb_doc_education_1 ); ?>
							</ul>
						</div>
					<?php endif; ?>

					<?php if ( $rb_doc_education_2 ) : ?>
						<div class="rb-doctors-page__education-item">
							<h3 class="rb-doctors-page__education-name">Сведения о прохождении интернатуры / ординатуры</h3>
							<ul class="rb-doctors-page__education-list">
								<?php echo apply_filters( 'the_content', $rb_doc_education_2 ); ?>
							</ul>
						</div>
					<?php endif; ?>
				</div>
				<div class="single-doctors-education-right_box">

					<?php if ( $rb_doc_education_3 ) : ?>
						<div class="rb-doctors-page__education-item">
							<h3 class="rb-doctors-page__education-name">Сведения из сертификата специалиста /диплома о переподготовке /усовершенствования</h3>
							<ul class="rb-doctors-page__education-list">
								<?php echo apply_filters( 'the_content', $rb_doc_education_3 ); ?>
							</ul>
						</div>
					<?php endif; ?>

					<?php if ( $rb_doc_education_4 ) : ?>
						<div class="rb-doctors-page__education-item">
							<h3 class="rb-doctors-page__education-name">Сведения о достижениях</h3>
							<ul class="rb-doctors-page__education-list">
								<?php echo apply_filters( 'the_content', $rb_doc_education_4 ); ?>
							</ul>
						</div>
					<?php endif; ?>
				</div>
				<a href="#" class="single-doctors-education_read">Читать</a>
				</div>

			</section>

		<?php 
			endif; ?>





		<?php if( $rb_services_list ): ?>
			<section class="rb-doctors-page__price">
				<!-- pricelist start -->
				<div class="rb-pricelist" id="pricelist">
					<h2 class="rb-title rb-pricelist__title">Услуги врача</h2>
					<ul class="rb-pricelist__wrap">
<?php foreach( $rb_services_list as $index => $arItem ) : 
	$hidden_class = ($index >= 3) ? ' hidden-by-default doctor-services-hidden' : '';
?>

	<li class="rb-pricelist__item f-jcsb-center flex-wrap<?php echo $hidden_class; ?>" id="<?php echo $arItem['id'];?>">
		<div class="col-10 col-s-12 flex-wrap">
			<p class="rb-pricelist__item-name col-s-12 col-10"><?php echo get_the_title( $arItem['id'] ); ?></p>
			<span class="rb-pricelist__item-price col-s-12 col-2"><?php echo get_post_meta( $arItem['id'], '_rb_serv_price', true ); ?>&nbsp;₽</span>
		</div>
		<div class="col-2 col-s-12 rb-pricelist__item-btn--wrap">
			<?php /* <span class="rb-pricelist__item-btn rb-button__orange-full rb-doctors-page__btn js-open-modal doctor2" data-doctors="<?php echo esc_attr(get_the_title()); ?>" data-programms="" data-service="" data-servicestax="">Записаться</span> */ ?>
			<?php
				$doc_id_lk = carbon_get_post_meta(get_the_ID(), 'rb_doc_id_lk');
				$order_btn_params = 'data-doctors="' . get_the_title() . '" data-programms="" data-service="" data-servicestax=""';
				if ($doc_id_lk) {$order_btn_params = "onclick=\"event.preventDefault(); if(window.analyticsInitialized && typeof ym === 'function') { ym(84731377, 'reachGoal', 'doctor_booking_click'); } ONDOC.showModal('/booking-appointment?doctor=" . $doc_id_lk . "');\"";}
			?>
			<span class="rb-pricelist__item-btn rb-button__orange-full rb-doctors-page__btn<?php echo $doc_id_lk ? ' ' : ' js-open-modal ' ?>doctor2"<?php echo $order_btn_params; ?>>Записаться</span>
		</div>
	</li>

<?php endforeach; ?>
					</ul>
					<?php if ( count($rb_services_list) > 3 ) : ?>
	<div class="table-button-width">
		<a href="#" class="rb-service__top-link rb-button__orange-bg" id="show-more-doctor-services">
			Показать еще
		</a>
	</div>
<?php endif; ?>
				</div><!-- pricelist end -->
<style>
	.rb-pricelist {
		display: block;
		flex-wrap: wrap;
		align-items: center;
	}

	.hidden-by-default {
		display: none !important;
	}
</style>
				<script>
document.addEventListener('DOMContentLoaded', function () {
    const showMoreDoctorServicesBtn = document.getElementById('show-more-doctor-services');
    if (showMoreDoctorServicesBtn) {
        showMoreDoctorServicesBtn.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.doctor-services-hidden').forEach(function(item) {
                item.classList.remove('hidden-by-default');
            });
            this.style.display = 'none';
        });
    }

    const showMoreDoctorComplexBtn = document.getElementById('show-more-doctor-complex');
    if (showMoreDoctorComplexBtn) {
        showMoreDoctorComplexBtn.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.doctor-complex-hidden').forEach(function(item) {
                item.classList.remove('hidden-by-default');
            });
            this.style.display = 'none';
        });
    }
});
</script>
			</section>
		<?php 
			endif; 

			if( $rb_doc_serv_compl ): 
		?>
			<section class="rb-doctors-page__price">
				<!-- pricelist start -->
				<div class="rb-pricelist" id="pricelist">
					<h2 class="rb-title rb-pricelist__title">Услуги врача</h2>
					<ul class="rb-pricelist__wrap">

						<?php foreach( $rb_doc_serv_compl as $serv_item ) : ?>

							<li class="rb-pricelist__item f-jcsb-center flex-wrap">
								<p class="rb-pricelist__item-name col-s-12"><?php echo $serv_item['title']; ?></p>
								<span class="rb-button__orange-full rb-doctors-page__btn rb-pricelist__btn">Подробнее</span>
								<?php if( $serv_item['list'] ) : ?>
									<ul class="rb-pricelist__sub">
										<?php foreach( $serv_item['list'] as $arItem ) : ?>

											<li class="rb-pricelist__sub--item f-jcsb-center flex-wrap" id="<?php echo $arItem['id'];?>">
												<div class="flex-wrap f-jcsb rb-pricelist__sub--item-wrap">
													<p class="rb-pricelist__item-name col-10 col-s-12"><?php echo get_the_title( $arItem['id'] ); ?></p>
													<span class="rb-pricelist__item-price  col-2 col-s-12"><?php echo get_post_meta( $arItem['id'], '_rb_serv_price', true ); ?>&nbsp₽</span>
												</div>
												<?php /* <span class="rb-button__orange-full rb-doctors-page__btn js-open-modal doctor2" data-doctors="<?php echo esc_attr(get_the_title()); ?>" data-programms="" data-service="" data-servicestax="">Записаться</span> */ ?>
												<?php
													$doc_id_lk = carbon_get_post_meta(get_the_ID(), 'rb_doc_id_lk');
													$order_btn_params = 'data-doctors="' . get_the_title() . '" data-programms="" data-service="" data-servicestax=""';
													if ($doc_id_lk) {$order_btn_params = "onclick=\"event.preventDefault(); if(window.analyticsInitialized && typeof ym === 'function') { ym(84731377, 'reachGoal', 'doctor_booking_click'); } ONDOC.showModal('/booking-appointment?doctor=" . $doc_id_lk . "');\"";}
												?>
													<span class="rb-button__orange-full rb-doctors-page__btn<?php echo $doc_id_lk ? ' ' : ' js-open-modal ' ?>doctor2"<?php echo $order_btn_params; ?>>Записаться</span>
											</li>

										<?php endforeach; ?>
									</ul>
								<?php endif; ?>
							</li>

						<?php endforeach; ?>
					</ul>
				</div><!-- pricelist end -->

			</section>

		<?php endif; ?>
		 <section class="rew_and_info">
<?php
			if ( function_exists( 'carbon_get_post_meta' ) ) {
	    		$rb_doc_reviews = carbon_get_post_meta( get_the_ID(), 'rb_doc_review_items' );	
	    	}

			if( ( $rb_doc_review_show && $rb_doc_review_show == 'yes' ) || $rb_doc_reviews ) :
				$rb_doc_review_btn_link = get_post_meta( get_the_ID(), '_rb_doc_review_btn_link', true ); 

		?>
		
<?php
// Проверяем, есть ли хотя бы один отзыв с непустым содержимым
$has_real_reviews = false;
if (!empty($rb_doc_reviews)) {
    foreach ($rb_doc_reviews as $review) {
        if (!empty($review['desc']) || !empty($review['title'])) {
            $has_real_reviews = true;
            break;
        }
    }
}

// Выводим блок только если есть кнопка или реальные отзывы
if (!empty($rb_doc_review_btn_link) || $has_real_reviews) : ?>
    <div class="rb-doctors-page__education review_on_page__review_wrap flex-wrap">
        <h2 class="rb-title rb-doctors-page__review-title col-12">Отзывы</h2>
        
        <?php if (!empty($rb_doc_review_btn_link)) : ?>
            <div class="rb-doctors-page__review review_on_page__review">
                <div class="rb-doctors-page__review rb-doctors-page__review-widget">
                    <?php echo $rb_doc_review_code; ?>
                </div>
								<div class="rb-doctors-page__review">
                    <a href="<?php echo esc_url($rb_doc_review_btn_link); ?>" target="_blank" class="rb-button__orange-full rb-doctors-page__btn">Оставить отзыв</a>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ($has_real_reviews) : ?>
            <div class="rb-doctors__reviews-list">
                <div class="swiper" id="rb-doctor__reviews-slider">
                    <div class="swiper-wrapper">
                        <?php foreach ($rb_doc_reviews as $rb_doc_review) : 
                            if (!empty($rb_doc_review['desc']) || !empty($rb_doc_review['title'])) : ?>
                                <div class="swiper-slide rb-about__review">
                                    <div class="rb-doctors__review-wrap">
                                        <?php if (!empty($rb_doc_review['title'])) : ?>
                                            <span class="rb-about__reviews--title"><?php echo esc_html($rb_doc_review['title']); ?></span>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($rb_doc_review['desc'])) : ?>
                                            <div class="rb-about__reviews--text"><?php echo apply_filters('the_content', $rb_doc_review['desc']); ?></div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($rb_doc_review['name'])) : ?>
                                            <span class="rb-about__reviews--name"><?php echo esc_html($rb_doc_review['name']); ?></span>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($rb_doc_review['date'])) : ?>
                                            <time class="rb-about__reviews--date"><?php echo esc_html($rb_doc_review['date']); ?></time>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; 
                        endforeach; ?>
                    </div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
				<?php endif; ?>
				</div>
		<?php endif; ?>
		<?php endif; ?>

		<?php if( $rb_doc_additional ) :
		foreach( $rb_doc_additional as $rb_doc_additional_item ) : ?>

			<div class="rb-doctors-page__education infobloks flex-wrap">

				<h2 class="rb-title rb-pricelist__title"><span><?php echo esc_html( $rb_doc_additional_item['title'] ); ?></span></h2>
				<div class="rb-doctors-page__education-wrap">
					<div class="rb-doctors-page__education-item">
						<div class="rb-doctors-page__education-list">
							<?php echo apply_filters( 'the_content', $rb_doc_additional_item['text'] ); ?>
						</div>
					</div>
				</div>

			</div>

		<?php endforeach;
					endif; ?>
		</section>





<?php // Похожие статьи — из тех же категорий, исключая текущую
// $related_query = null;

$manual_related_ids = array();

if (function_exists( 'carbon_get_post_meta' ) ) {
    $manual_related_items = carbon_get_post_meta( get_the_ID(), 'rb_doctors_related_articles' );

    if ( ! empty( $manual_related_items ) && is_array( $manual_related_items ) ) {
        foreach ( $manual_related_items as $item ) {
            $manual_id = 0;

            if ( is_array( $item ) ) {
                if ( ! empty( $item['id'] ) ) {
                    $manual_id = (int) $item['id'];
                } elseif ( ! empty( $item['value'] ) && preg_match( '~(\d+)$~', (string) $item['value'], $m ) ) {
                    $manual_id = (int) $m[1];
                }
            } else {
                $manual_id = (int) $item;
            }

            if ( $manual_id && $manual_id !== (int) $post_id ) {
                $manual_related_ids[] = $manual_id;
            }
        }
    }

    $manual_related_ids = array_values( array_unique( array_filter( $manual_related_ids ) ) );
}
    // Ручной режим — порядок как выбрали в админке
    $related_query = new WP_Query( array(
        'post_type'           => 'post',
        'posts_per_page'      => 8,
        'post__in'            => $manual_related_ids,
        'orderby'             => 'post__in',
        'ignore_sticky_posts' => 1,
    ) );
?>
    <!-- ══ БЛОК: Похожие статьи ══ -->
    <?php if ($related_query instanceof WP_Query && $related_query->have_posts() ) : ?>
        <div class="rb-article__related">
            <div class="rb-container">
                <div class="rb-article__related-header rb-page-header">
                    <h2 class="rb-article__related-title ">Похожие статьи по теме</h2>
                    <div class="rb-article__related-arrows">
                        <a href="#" class="related-arrow-prev"><svg width="32" height="32" viewBox="0 0 32 32" fill="none"><rect width="32" height="32" rx="4" fill="#F3951D"/><path d="M19 22L12.5 16L18.5 10" stroke="white"/></svg></a>
                        <a href="#" class="related-arrow-next"><svg width="32" height="32" viewBox="0 0 32 32" fill="none"><rect width="32" height="32" rx="4" transform="matrix(-1 0 0 1 32 0)" fill="#F3951D"/><path d="M13 22L19.5 16L13.5 10" stroke="white"/></svg></a>
                    </div>
                </div>

                <div class="swiper sl-swiper-related">
                    <div class="swiper-wrapper">
                        <?php while ( $related_query->have_posts() ) : $related_query->the_post();
                            $r_id   = get_the_ID();
                            $r_date = get_the_date( 'Y-m-d' );
                        ?>
                            <div class="swiper-slide">
                                <a class="rb-related-card" href="<?php the_permalink(); ?>">
                                    <?php if ( has_post_thumbnail() ) : ?>
                                        <div class="rb-related-card__img">
                                            <?php the_post_thumbnail( 'medium' ); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="rb-related-card__info">
                                        <span class="rb-related-card__date"><?php echo rb_format_date_ru( $r_date ); ?></span>
                                        <p class="rb-related-card__title"><?php the_title(); ?></p>
                                        <p class="rb-related-card__excerpt"><?php echo wp_trim_words( get_the_excerpt(), 12, '…' ); ?></p>
                                    </div>
                                </a>
                            </div>
                        <?php endwhile; wp_reset_postdata(); ?>
                    </div>
                </div>

            </div>
        </div>
    <?php endif; ?>







	</div>


	</div>
</div><!-- doctor end -->

<style>
.rb-pricelist {
	display: block;
	flex-wrap: wrap;
	align-items: center;
}

.rb-article__title {
    font-size: 32px;
    font-weight: 800;
    line-height: 1.2;
    text-transform: uppercase;

    margin-bottom: 24px;
}

.rb-article__body {
    font-size: 15px;
    line-height: 1.7;
    color: #333;
}
.rb-article__body h2 {
    font-size: 20px;
    font-weight: 700;
    color: #1a1a2e;
    margin: 32px 0 12px;
}
.rb-article__body h3 {
    font-size: 17px;
    font-weight: 600;
    margin: 24px 0 10px;
}
.rb-article__body p { margin-bottom: 14px; }
.rb-article__body ul,
.rb-article__body ol { padding-left: 20px; margin-bottom: 14px; }
.rb-article__body li { margin-bottom: 6px; }
.rb-article__body img { max-width: 100%; height: auto; border-radius: 8px; }

.rb-article__author-card {
    background: #F9F9F9;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 20px;
}
.rb-article__author-photo {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    overflow: hidden;
    margin-bottom: 10px;
}
.rb-article__author-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.rb-article__author-label {
    font-size: 16px;
    color: #999;
    margin-bottom: 4px;
}
.rb-article__author-name {
    font-size: 15px;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 4px;
    line-height: 1.3;
}
.rb-article__author-name a { color: inherit; text-decoration: none; }
.rb-article__author-name a:hover { color: #F3951D; }
.rb-article__author-area {
    font-size: 16px;
    color: #666;
    margin-bottom: 8px;
    line-height: 1.4;
}
.rb-article__author-bio {
    font-size: 12px;
    color: #888;
    line-height: 1.5;
    margin-bottom: 4px;
}

.rb-article__meta {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding-top: 12px;
    border-top: 1px solid #f0f0f0;
    margin-top: 12px;
}
.rb-article__meta-row {
    display: flex;
    align-items: center;
    gap: 20px;
}
.rb-article__meta-item {

    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 14px;
    color: #888;
    white-space: nowrap;
}
.rb-article__meta-item svg { flex-shrink: 0; }

.rb-article__toc {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 20px;
}
.rb-article__toc-title {
    font-size: 15px;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 0px;
}
.rb-article__toc-list {
    list-style: none;
    padding: 0;
    margin: 0;
    counter-reset: toc-counter;
}
.rb-article__toc-list li {
    counter-increment: toc-counter;
    margin-bottom: 8px;
    display: flex;
    gap: 6px;
    align-items: flex-start;
}
.rb-article__toc-list li::before {
    content: counter(toc-counter) ".";
    font-size: 13px;
    color: #aaa;
    flex-shrink: 0;
    padding-top: 1px;
}
.rb-article__toc-list a {
    font-size: 13px;
    color: #444;
    text-decoration: none;
    line-height: 1.4;
    transition: color 0.2s;
}
.rb-article__toc-list a:hover { color: #F3951D; }
.rb-article__toc-list li.is-active a {
    color: #F3951D;
    font-weight: 600;
}

.olimp-appoint {
    position: relative;
    height: 400px;
    background: url(https://olimp5.devmoab.ru/wp-content/themes/olimp5/assets/img/form.png);
    border-radius: 16px;
    overflow: hidden;
    font-family: 'Manrope', sans-serif;
	background-size: contain;
	background-repeat: no-repeat;
}


.olimp-appoint__title{
  position: absolute;
  width: 630px;
  height: 60px;
  left: 40px;
  top: 51px;

  font-family: 'Bebas Neue', sans-serif;
  font-style: normal;
  font-weight: 700;
  font-size: 60px;
  line-height: 60px;
  display: flex;
  align-items: center;
  text-transform: uppercase;
  color: #F3951D;
  margin: 0;
}

.olimp-appoint__text{
  position: absolute;
  width: 466px;
  height: 50px;
  left: 40px;
  top: 150px;

  font-family: 'Manrope', sans-serif;
  font-style: normal;
  font-weight: 400;
  font-size: 18px;
  line-height: 140%;
  color: #333333;
  margin: 0;
}

.olimp-appoint__btn{
  box-sizing: border-box;

  display: flex;
  flex-direction: row;
  justify-content: center;
  align-items: center;
  padding: 16px 8px;
  gap: 10px;

  position: absolute;
  width: 145px;
  height: 56px;
  left: 40px;
  top: 284px;

  border: 1px solid #495B80;
  border-radius: 9px;

  font-family: 'Manrope', sans-serif;
  font-style: normal;
  font-weight: 400;
  font-size: 14px;
  line-height: 18px;
  text-align: center;
  color: #495B80;

  text-decoration: none;
}

.olimp-appoint__mask {
    position: absolute;
    width: 808.99px;
    height: 808.99px;
    left: 796.66px;
    top: -83.99px;
    pointer-events: none;
}

.olimp-appoint__frame{
    position: absolute;
    width: 572.27px;
    height: 482.82px;
    left: -1px;
    top: 80px;
    border-radius: 12px;
    transform: rotate(-45deg);
    overflow: hidden;
}

.olimp-appoint__frame--border{
  border: 20px solid rgba(243, 149, 29, 0.4);
  box-sizing: border-box;
  z-index: 1;
  background: transparent;
}

.olimp-appoint__frame--inner{
  border: 0;
  z-index: 2;
}

.olimp-appoint__frame--border {
    border: 20px solid rgb(243 149 29);
    box-sizing: border-box;
    z-index: 6;
}

.olimp-appoint__frame--inner{
  z-index: 2;
}

.olimp-appoint__img{
  width: 100%;
  height: 100%;

  object-fit: cover;
  object-position: center;

  transform: rotate(45deg) scale(1.05);
}

.rb-article__related {
    margin-top: 60px;
    padding-bottom: 60px;
}
.rb-article__related-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
}
.rb-article__related-title {
    font-size: 22px;
    font-weight: 900;
    text-transform: uppercase;
    margin: 0;
}
.rb-article__related-arrows {
    display: flex;
    gap: 8px;
}
.rb-article__related-arrows a {
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 1;
    transition: opacity 0.2s;
}
.rb-article__related-arrows a:hover { opacity: 0.8; }

.rb-related-card {
    display: block;
    text-decoration: none;
    color: inherit;
}
.rb-related-card__img {
    border-radius: 8px;
    overflow: hidden;
    aspect-ratio: 4 / 3;
    margin-bottom: 12px;
}
.rb-related-card__img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.3s;
}
.rb-related-card:hover .rb-related-card__img img {
    transform: scale(1.04);
}
.rb-related-card__date {
    font-size: 12px;
    color: #999;
    display: block;
    margin-bottom: 6px;
}
.rb-related-card__title {
    font-size: 14px;
    font-weight: 700;
    color: #1a1a2e;
    line-height: 1.4;
    margin-bottom: 6px;
    transition: color 0.2s;
}
.rb-related-card:hover .rb-related-card__title { color: #F3951D; }
.rb-related-card__excerpt {
    font-size: 13px;
    color: #777;
    line-height: 1.4;
    margin: 0;
}

@media (max-width: 1024px) {
    .rb-article__layout {
        grid-template-columns: 1fr 260px;
        gap: 28px;
    }
}
@media (max-width: 900px) {
	.olimp-appoint {
    background: url(https://olimp5.devmoab.ru/wp-content/themes/olimp5/assets/img/form_mob.png);
	background-size: cover;
	background-repeat: no-repeat;
}
    .rb-article__layout {
        grid-template-columns: 1fr;
    }
    .rb-article__sidebar {
        position: static;
        order: -1;
    }
    .rb-article__author-card {
        display: grid;
        grid-template-columns: 72px 1fr;
        column-gap: 14px;
    }
    .rb-article__author-photo { grid-row: 1 / 4; margin-bottom: 0; }
    .rb-article__author-label,
    .rb-article__author-name,
    .rb-article__author-area  { grid-column: 2; }
    .rb-article__author-bio,
    .rb-article__meta         { grid-column: 1 / -1; }

    .rb-article__title { font-size: 24px; }

    .rb-article__cta-image { display: none; }
    .rb-article__cta-text  { flex: 1; padding: 30px 20px; }
    .rb-article__cta-title { font-size: 22px; }
}

@media (max-width: 700px) {
	.rb-article__related {
		margin-top: 30px;
	}
}
@media (max-width: 600px) {
    .rb-article__related-title { font-size: 18px; }
}
.rb-article__layout {
    display: grid;
    grid-template-columns: 1fr 280px;
    gap: 40px;
    align-items: start;
}

.rb-article__title {
    font-size: 32px;
    font-weight: 800;
    line-height: 1.2;
    text-transform: uppercase;
    margin-bottom: 24px;
}
	.article-content {
		margin-bottom: 100px;
	}
.rb-article__body {
    font-size: 15px;
    line-height: 1.7;
    color: #333;
}

.rb-article__body h2 {
    font-size: 20px;
    font-weight: 700;
    color: #42567d;
    margin: 32px 0 12px;
}

.rb-article__body h3 {
    font-size: 17px;
    font-weight: 600;
    margin: 24px 0 10px;
}

.rb-article__body p {
    margin-bottom: 14px;
}

.rb-article__body ul,
.rb-article__body ol {
    padding-left: 20px;
    margin-bottom: 14px;
}

.rb-article__body li {
    margin-bottom: 6px;
}

.rb-article__body img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
}



.rb-article__author-card {
    background: #F9F9F9;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 20px;
}

.rb-article__author-photo {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    overflow: hidden;
    margin-bottom: 10px;
	background: #FFFFFF;
}

.rb-article__author-photo img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.rb-article__author-label {
    font-size: 16px;
    color: #999;
    margin-bottom: 4px;
}

.rb-article__author-name {
    font-size: 15px;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 4px;
    line-height: 1.3;
}

.rb-article__author-name a {
    color: inherit;
    text-decoration: none;
}

.rb-article__author-name a:hover {
    color: #F3951D;
}

.rb-article__author-area {
    font-size: 16px;
    color: #666;
    margin-bottom: 8px;
    line-height: 1.4;
}

.rb-article__author-bio {
    font-size: 12px;
    color: #888;
    line-height: 1.5;
    margin-bottom: 12px;
}
.rb-article__meta-item {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 14px;
    color: #888;
    white-space: nowrap;
}

.rb-article__meta-item svg {
    flex-shrink: 0;
}

.rb-article__toc {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 20px;
}

.rb-article__toc-title {
    font-size: 15px;
    font-weight: 700;
    color: #1a1a2e;
    margin-bottom: 0px;
}

.rb-article__toc-list {
    list-style: none;
    padding: 0;
    margin: 0;
    counter-reset: toc-counter;
}

.rb-article__toc-list li {
    counter-increment: toc-counter;
    margin-bottom: 8px;
    padding-left: 0;
    display: flex;
    gap: 6px;
    align-items: flex-start;
}

.rb-article__toc-list li::before {
    content: counter(toc-counter) ".";
    font-size: 13px;
    color: #aaa;
    flex-shrink: 0;
    padding-top: 1px;
}

.rb-article__toc-list a {
    font-size: 13px;
    color: #444;
    text-decoration: none;
    line-height: 1.4;
    transition: color 0.2s;
}

.rb-article__toc-list a:hover {
    color: #F3951D;
}

.rb-article__toc-list li.is-active a {
    color: #F3951D;
    font-weight: 600;
}

@media (max-width: 900px) {
    .rb-article__layout {
        grid-template-columns: 1fr;
    }

    .rb-article__sidebar {
        position: static;
        order: -1;
    }

    .rb-article__author-card {
        display: flex;
        grid-template-columns: 72px 1fr;
        grid-template-rows: auto auto auto auto;
        column-gap: 14px;
		        flex-direction: column;
    }

    .rb-article__author-photo {
        grid-row: 1 / 4;
        margin-bottom: 0;
        align-self: start;
    }

    .rb-article__author-label  { grid-column: 2; }
    .rb-article__author-name   { grid-column: 2; }
    .rb-article__author-area   { grid-column: 2; }

    .rb-article__author-bio,
    .rb-article__meta {
        grid-column: 1 / -1;
    }

    .rb-article__title {
        font-size: 24px;
    }
}

@media (max-width: 600px) {
    .rb-article__layout {
        gap: 20px;
    }
}

@media (max-width: 600px){

  .olimp-appoint{
    height: 520px;
    border-radius: 16px;
  }

  .olimp-appoint__title,
  .olimp-appoint__text,
  .olimp-appoint__btn{
    position: static !important;
    width: auto !important;
    height: auto !important;
    left: auto !important;
    top: auto !important;
  }

  .olimp-appoint{
    padding: 26px 22px;
  }

  .olimp-appoint__title{
    font-size: 44px;
    line-height: 44px;
    margin: 0 0 14px 0;
  }

  .olimp-appoint__text{
    font-size: 16px;
    line-height: 140%;
    margin: 0 0 18px 0;
    max-width: 260px;
  }

  .olimp-appoint__btn{
    width: 170px !important;
    height: 56px !important;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .olimp-appoint__mask{
    left: auto !important;
    top: auto !important;

    right: -80px;
    bottom: -90px;

    width: 420px;
    height: 420px;
  }

  .olimp-appoint__frame{
    width: 290px;
    height: 290px;
    left: 95px;
    top: 70px;
    border-radius: 12px;
    transform: rotate(-45deg);
    overflow: hidden;
  }

  .olimp-appoint__img{
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    transform: rotate(45deg) scale(1.08);
  }
}
.olimp-appoint__img{
  position: absolute;
    left: 32%;
    top: 47%;

  width: 142%;
  height: 142%;

  object-fit: cover;
  object-position: center;

  transform: translate(-50%, -50%) rotate(45deg);
  transform-origin: center;
}

.olimp-appoint__frame{
  overflow: hidden;
  position: absolute;
}
	@media (max-width: 600px) {
    .olimp-appoint__frame {
        width: 290px;
        height: 290px;
        left: 95px;
        top: 70px;
        border-radius: 12px;
        transform: rotate(-45deg);
        overflow: hidden;
    }
}
	@media (max-width: 900px) {
	    .rb-page-header h1, .rb-page-header {
        font-size: 40px!important;
        line-height: 40px!important;
        width: auto;
    }
	}
	ol, ul {
    margin: 0;
    padding: 0;
    list-style-type: unset;
}
	.rb-page-header h1, .rb-page-header, h1.rb-page-header {
    font-family: 'Bebas Neue';
    font-style: normal;
    font-weight: 700;
    font-size: 62px;
    line-height: 66px;
}
	.olimp-cta-wrap{
		    padding-top: 50px;
    padding-bottom: 50px;
	}
	.olimp-cta-wrap .rb-container {
		padding: 0;
	}
	.rb-article__layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 40px;
    align-items: start;
}

	.rb-page,
.rb-container,
.rb-article,
.rb-article__layout {
    overflow: visible;
}
	.rb-article__layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 40px;
    align-items: start;
}

.rb-article__content {
    min-width: 0;
}


.rb-page,
.rb-container,
.rb-article,
.rb-article__layout {
    overflow: visible;
}

@media (max-width: 900px) {
    .rb-article__layout {
        grid-template-columns: 1fr;
    }

    .rb-article__sidebar-sticky {
        position: static;
    }
}
	.rb-article__layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 40px;
    align-items: start;
}

.rb-article__content {
    min-width: 0;
}


@media (max-width: 900px) {
    .rb-article__layout {
        grid-template-columns: 1fr;
    }

    .rb-article__sidebar {
        order: -1;
    }

    .rb-article__sidebar-sticky {
        position: static;
    }
}
	.rb-article__layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 300px;
    gap: 40px;
    align-items: start;
}

.rb-article__content {
    min-width: 0;
}

.rb-article__sidebar {
    position: relative;
    min-width: 0;
    align-self: start;
}

.rb-article__sidebar-sticky {
    position: relative;
    top: 0;
    transform: translateY(0);
    transition: transform 0.08s linear;
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.rb-page,
.rb-container,
.rb-article,
.rb-article__layout {
    overflow: visible !important;
}

.rb-article__author-card {
    background: #F9F9F9;
    border: 1px solid #eee;
    border-radius: 12px;
    padding: 20px;
}

@media (max-width: 900px) {
    .rb-article__layout {
        grid-template-columns: 1fr;
    }

    .rb-article__sidebar {
        order: -1;
    }

    .rb-article__sidebar-sticky {
        transform: none !important;
    }
}
</style>
<?php get_footer(); ?>
<script>
	document.addEventListener('DOMContentLoaded', function () {
		if (document.querySelector('.sl-swiper-related')) {
			new Swiper('.sl-swiper-related', {
				slidesPerView: 1.2,
				spaceBetween: 16,
				navigation: {
					prevEl: '.related-arrow-prev',
					nextEl: '.related-arrow-next',
				},
				breakpoints: {
					600: { slidesPerView: 2, spaceBetween: 20 },
					900: { slidesPerView: 3, spaceBetween: 20 },
					1200: { slidesPerView: 4, spaceBetween: 24 },
				}
			});
		}
	});

	const singleDoctorsEducationHide = document.querySelector('.single-doctors-education_hide .rb-doctors-page__education-wrap');
	const singleDoctorsEducationRead = document.querySelector('.single-doctors-education_hide .single-doctors-education_read');

    if (singleDoctorsEducationHide.scrollHeight > 250) {
        singleDoctorsEducationHide.setAttribute('style', 'max-height: 250px;');

        singleDoctorsEducationRead.addEventListener('click', (e) => {
		e.preventDefault();
		if (!singleDoctorsEducationHide.classList.contains('active')) {
            singleDoctorsEducationRead.innerHTML = 'Скрыть';
			singleDoctorsEducationHide.classList.add('active');
			singleDoctorsEducationHide.setAttribute('style', 'max-height:' + singleDoctorsEducationHide.scrollHeight + 'px;');
		} else {
			singleDoctorsEducationHide.classList.remove('active');
			singleDoctorsEducationHide.setAttribute('style', 'max-height: 250px;');
            singleDoctorsEducationRead.innerHTML = 'Читать';
		}
	});
    } else {
        singleDoctorsEducationRead.setAttribute('style', 'display: none;');
    }



</script>