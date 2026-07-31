<?php 

get_header();

/*
Template Name: О центре
*/

$rb_about_title = get_post_meta( get_the_ID(), '_rb_about_title', true ); 
$rb_about_text = get_post_meta( get_the_ID(), '_rb_about_text', true ); 
$rb_about_image = get_post_meta( get_the_ID(), '_rb_about_image', true ); 

?>


    <!-- about start -->
        <div class="rb-about">
            <section class="rb-about__top">
                <div class="rb-container flex-wrap rb-about__top-wrap">
                    <div class="col-6 col-s-12">
                        <div class=" no_padding rb-page-header">
                            <h1><?php the_title();?></h1>
                        </div>
                        <picture class="rb-about__top-img2">
                            <?php echo get_the_post_thumbnail( get_the_ID(), 'large', array( 'class' => 'rb-img-cover' ) ); ?>
                        </picture>
                        <div class="rb-about__top-desc rb-text">
                            <?php the_content(); ?>
                        </div>
                        <div class="rb-about__top-btn rb-button__orange js-open-modal" data-modal="1">
                            Записаться на прием
                        </div>
                    </div>
                    <div class="col-6 col-s-12">
                        <picture class="rb-about__top-img">
                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/about.jpg" alt="" class="rb-img-cover">
                        </picture>
                    </div>
                </div>
            </section>
            <section class="rb-about__infoblock">
                <div class="rb-container">
                    <div class="col-6 col-m-12 col-s-12">
                        <span>О центре</span>
                        <h2 class="rb-title"><?php echo $rb_about_title; ?></h2>
                    </div>
                    <div class="flex rb-about__infoblock-wrap">
                        <div class="col-6 col-m-12 col-s-12 rb-about__infoblock-img">
                            <?php echo wp_get_attachment_image( $rb_about_image, 'large' ); ?>
                        </div>
                        <div class="col-6 col-m-12 col-s-12 rb-text rb-about__infoblock-desc">
                            <?php echo apply_filters( 'the_content', $rb_about_text ); ?>
                        </div>
                    </div>
                    <!-- <p class="rb-text rb-about__infoblock-bottom col-6 col-m-12 col-s-12">Инновационные медицинские технологии, собранные в Центре со всего мира, помогают держать под контролем состояние организма, увеличить продолжительность и улучшить качество жизни.</p> -->
                </div>
            </section>
            <?php  
            	if ( function_exists( 'carbon_get_post_meta' ) ) {

            		$rb_about_managers_list = carbon_get_post_meta( get_the_ID(), 'rb_about_managers_list' );

            	}

            	if ( $rb_about_managers_list ) : 
            ?>
	            <section class="rb-about__persons">
	                <div class="rb-container">
	                	<?php foreach( $rb_about_managers_list as $rb_about_manager ) : ?>
		                    <div class="rb-about__persons-item flex-wrap">
		                        <div class="rb-about__persons-title flex col-12">
		                            <p class="col-9 col-m-12 col-s-12 rb-title"><?php echo esc_html( $rb_about_manager['cite'] ); ?></p>
		                        </div>
<picture class="col-3 col-m-6 col-s-12 rb-about__persons-img">
	<?php
	echo wp_get_attachment_image( $rb_about_manager['image'], 'rb_standart', false, array(
		'class' => 'rb-img-cover',
		'sizes' => '(max-width: 576px) 100vw, (max-width: 992px) 50vw, 270px'
	) );
	?>
</picture>
		                        <div class="col-9 col-m-6 col-s-12 rb-about__persons-info">
		                            <div class="rb-about__persons-info--top col-9 col-m-12 col-m-12 rb-text">
		                                <?php echo apply_filters( 'the_content', $rb_about_manager['desc'] ); ?>
		                            </div>
		                            <p class="rb-about__persons-info--bot col-3 col-m-12 col-m-12">
		                                <?php echo esc_html( $rb_about_manager['title'] ); ?><br><?php echo $rb_about_manager['occup']; ?>
		                            </p>
		                        </div>
		                    </div>
		                <?php endforeach; ?>
	                    <!-- <div class="rb-about__persons-item flex-wrap">
	                        <div class="rb-about__persons-title flex col-12">
	                            <p class="col-9 col-m-12 col-s-12 rb-title">Мы предложили пациентам новый подход к лечению и сохранению здоровья – превентивную медицину – и понимаем, что он найдёт своих приверженцев не сразу.</p>
	                        </div>
	                        <picture class="col-3 col-m-6 col-s-12 rb-about__persons-img">
	                            <img src="/assets/img/solovieva.jpeg" alt="Н.А. Соловьева" class="rb-img-cover">
	                        </picture>
	                        <div class="col-9 col-m-6 col-s-12 rb-about__persons-info">
	                            <div class="rb-about__persons-info--top col-9 col-m-12 col-m-12 rb-text">
	                                <p>Наша задача информировать пациентов о необходимых профилактических мерах, донести до них простую истину – предупреждение и профилактика всегда проще и дешевле лечения!</p>
	                            </div>
	                            <p class="rb-about__persons-info--bot col-3 col-m-12 col-m-12">
	                                Н.А. Соловьева<br> Председатель совета директоров ГК «Олимп Здоровья»
	                            </p>
	                        </div>
	                    </div>
	                    <div class="rb-about__persons-item flex-wrap">
	                        <div class="rb-about__persons-title flex col-12">
	                            <p class="col-9 col-m-12 col-s-12 rb-title">Главная цель создания центра – способствовать развитию в нашем регионе культуры жизни, направленной на активное долголетие.</p>
	                        </div>
	                        <picture class="col-3 col-m-6 col-s-12 rb-about__persons-img">
	                            <img src="/assets/img/volynkina.jpg" alt="Соловьёв" class="rb-img-cover">
	                        </picture>
	                        <div class="col-9 col-m-6 col-s-12 rb-about__persons-info">
	                            <div class="rb-about__persons-info--top col-9 col-m-12 col-m-12 rb-text">
	                                <p>Важно не только лечить уже существующие проблемы со здоровьем, но и предупреждать их развитие с помощью перспективных методов восстановительного и профилактического лечения.</p>
	                            </div>
	                            <p class="rb-about__persons-info--bot col-3 col-m-12 col-m-12">
	                                А.П. Волынкина<br> Главный врач Центра культуры здоровья «Олимп Пять»
	                            </p>
	                        </div>
	                    </div> -->
	                </div>
	            </section>
	        <?php endif; ?>
            <section class="rb-about__clinics">
                <div class="rb-container">
                    <h2 class="rb-title">В состав группы компаний «Олимп Здоровья»<br> <span>входят медицинские клиники:</span></h2>
                    <div class="flex-wrap rb-about__clinics-links">
                        <div class="rb-about__clinics-item">
                            <noindex>
                                <a href="https://www.evkaliptmed.ru" target="_blank" rel="nofollow">
                                    <picture class="rb-about__clinics-item--content">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/evkalipt.jpg" alt="Клиника семейной медецины Эвкалипт" class="rb-img-contain">
                                    </picture>
                                </a>
                            </noindex>
                        </div>
                        <div class="rb-about__clinics-item">
                            <noindex>
                                <a href="https://olimp03.ru" target="_blank" rel="nofollow">
                                    <picture class="rb-about__clinics-item--content">
                                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/olimp_zdorovja.png" alt="Центр семейной медицины Олимп Здоровья" class="rb-img-contain">
                                    </picture>
                                </a>
                            </noindex>
                        </div>
                        <div class="rb-about__clinics-item">
                            <noindex>
                                <a href="https://olymp5.clinic/" target="_blank" rel="nofollow">
                                    <picture class="rb-about__clinics-item--content">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo3.png" alt="Олимп клиник" class="rb-img-contain">
                                    </picture>
                                </a>
                            </noindex>
                        </div>
                        <div class="rb-about__clinics-item">
                            <noindex>
                                <a href="https://olimp-medgroup.ru/" target="_blank" rel="nofollow">
                                    <picture class="rb-about__clinics-item--content">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/olimpiya.png" alt="Клиника Олимпия" class="rb-img-contain">
                                    </picture>
                                </a>
                            </noindex>
                        </div>

                        <div class="rb-about__clinics-item rb-about__clinics-item--big">
                            <noindex>
                                <a href="https://ogni.clinic/" target="_blank" rel="nofollow">
                                    <picture class="rb-about__clinics-item--content">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/olimp_lights.png" alt="" class="rb-img-contain">
                                    </picture>
                                </a>
                            </noindex>
                        </div>
                        <div class="rb-about__clinics-item rb-about__clinics-item--big">
                            <noindex>
                                <a href="https://olimp5.ru/" target="_blank" rel="nofollow">
                                    <picture class="rb-about__clinics-item--content">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/logo.svg" alt="" class="rb-img-contain">
                                    </picture>
                                </a>
                            </noindex>
                        </div>
                        <div class="rb-about__clinics-item rb-about__clinics-item--big">
                            <noindex>
                                <a href="https://olymp.clinic/clinics/mars/" target="_blank" rel="nofollow">
                                    <picture class="rb-about__clinics-item--content">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/mars_clinic.webp" alt="" class="rb-img-contain">
                                    </picture>
                                </a>
                            </noindex>
                        </div>
                        <div class="rb-about__clinics-item rb-about__clinics-item--big">
                            <noindex>
                                <a href="https://www.donresort.ru/" target="_blank" rel="nofollow">
                                    <picture class="rb-about__clinics-item--content">
                                        <img src="<?php echo get_stylesheet_directory_uri(); ?>/assets/img/sanatoriy_don.jpg" alt="" class="rb-img-contain">
                                    </picture>
                                </a>
                            </noindex>
                        </div>
                        <div class="rb-about__clinics-item">
                            <p class="rb-about__clinics-item--content">
                                <span>8</span> клиник
                            </p>
                        </div>
                        <div class="rb-about__clinics-item">
                            <p class="rb-about__clinics-item--content">
                                Более <span>10 000</span> услуг
                            </p>
                        </div>
                        <div class="rb-about__clinics-item">
                            <p class="rb-about__clinics-item--content">
                                Более <span>1 000</span> специалистов
                            </p>
                        </div>
                        <div class="rb-about__clinics-item">
                            <p class="rb-about__clinics-item--content">
                                Более <span>1 000</span> специальностей
                            </p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="rb-about__equipment rb-container">
                <p class="rb-title rb-about__equipment-title">
                    <span class="rb-about__equipment-subtitle">Оснащение</span>«Олимп Пять» оснащён современным медицинским
                    оборудованием от <span>ведущих мировых производителей</span>: Италии, Германии, США, Бельгии, Нидерландов, Швейцарии и Франции, а также российского Центра аэрокосмической медицины и технологий.
                </p>
                <!-- <span class="rb-about__equipment-desc">Гармония природы и технологий <br>для защиты вашего здоровья</span> -->
            </section>
            <?php 

            	$reviews_query = new WP_Query(
            		array(
            			'post_type' => 'reviews',
            			'posts_per_page' => -1,
            		)
            	);

				if ( function_exists( 'carbon_get_post_meta' ) ) {

					$rb_about_reviews_more = carbon_get_post_meta( get_the_ID(), 'rb_about_reviews_more' );

				}

            	if ( $reviews_query->have_posts() ) : 

            		?>
		           
		    <?php endif; wp_reset_postdata(); ?>  
            <!-- <section class="rb-main__form-bottom rb-about__form">
                <div class="rb-container flex rb-main__form-bottom-text flex--middle">
                    <div class="col-6 col-m-12 col-s-12">
                        <div class="rb-main__form-bottom--info">
                            <span class="rb-about__form-title">
                                Возможна оплата услуг онлайн. 
                            </span>
                            <p class="rb-about__form-text">
                                Возврат денежных средств за оплаченные и не оказанные услуги осуществляется на банковскую карту, с которой производилась оплата, при наличии оформленного письменного заявления.
                            </p>
                             <span class="rb-about__form-subtitle">
                                Подробности можно узнать по телефону <a href="tel:84732255555">225-55-55</a>. 
                            </span>
                        </div>
                    </div>
                    <div class="col-6 col-m-12 col-s-12">
                        <div class="rb-main__form-bottom--wrap rb-form-subscribe-wrap">
                            <form action="/local/ajax/sub.php" method="post" id="subscribe_form" class="rb-main__form">

                                <span class="rb-main__form--title">Подписаться на рассылку</span>

                                <div class="rb-main__form-top--wrapper flex-wrap">
                                    <div class="col-12 rb-main__form--item">
                                        <label for="rb-bot-form-name">Email</label>
                                        <input id="rb-bot-form-name" type="email" name="email" value="" placeholder="example@mail.ru" autocomplete="off" required>
                                    </div>

                                    <div class="col-12 rb-main__form--button">
                                        <input type="submit" class="rb-btn-white" name="submit" id="subscribe_form_button" value="Отправить заявку">
                                    </div>
                                </div>
                                   
                             </form>
                        </div>
                    </div>
                    
                </div>
            </section>    -->          
            
        </div>
    <!-- about end -->

<?php get_footer(); ?>