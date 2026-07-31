<?php
	get_header();


	$post_terms = wp_get_post_terms( get_the_ID(), 'programms-type', array( 'fields' => 'ids' ) );

?>


		<div class="rb-programms-page rb-page">

			<div class="rb-container">
				

				<h1 class="rb-page-header"><?php the_title(); ?></h1>

				<div class="rb-programms-page__content">
					<?php the_content(); ?>
				</div>

				<div class="rb-programms-page__info flex-wrap">

					<?php
						global $post;     
						$children_query = get_posts( 
							array(
								'post_type' => 'programms',
								'post_parent' => get_the_ID(),
							) 
						);
						if ( ! $post->post_parent && count( $children_query ) > 0 ) {

						    
					?>

						<div class="rb-programms">

							<?php 

								 $programms_query = new WP_Query(
                                        array(
                                            'post_type' => 'programms',
                                            'posts_per_page' => -1,
                                            'order' => 'ASC',
                                            'post_parent' => get_the_ID(),
                                        )
                                    );

								 if ( $programms_query->have_posts() ) :

							?>

							<ul class="rb-programms__list flex-wrap">
                                                
                                <?php 
                                    while( $programms_query->have_posts() ) {
                                        $programms_query->the_post();

                                        get_template_part( 'template-part/programms', 'item' );

                                    }

                                ?>

                            </ul>

                        	<?php endif; ?>

						</div>

					<?php
						} else {


						$rb_price = get_post_meta( get_the_ID(), '_rb_price', true );
						$for_whom = get_post_meta( get_the_ID(), '_rb_for_whom', true );
						$rb_effect = get_post_meta( get_the_ID(), '_rb_effect', true );
						$rb_compound = get_post_meta( get_the_ID(), '_rb_compound', true );
						$for_whom_title = get_post_meta( get_the_ID(), '_rb_for_whom_title', true ) ?: 'Кому подходит';
						$rb_effect_title = get_post_meta( get_the_ID(), '_rb_effect_title', true ) ?: 'Эффект';
						$rb_compound_title = get_post_meta( get_the_ID(), '_rb_compound_title', true ) ?: 'Состав программы';
						$rb_price_from_hide = get_post_meta( get_the_ID(), '_rb_price_from_hide', true );

					?>				

						<div class="col-6 col-s-12 rb-programms-page__info-wrap">

							<ul class="rb-programms-page__list">
								<?php if( $for_whom ) : ?>
									<li class="rb-programms-page__item">
										
										<span class="rb-programms-page__item-title"><?php echo $for_whom_title; ?></span>
										<div class="rb-programms-page__item-text">
											<ul>
												<?php echo apply_filters( 'the_content', $for_whom ); ?>
											</ul>
										</div>

									</li>
								<?php 
									endif;
									if( $rb_effect ) : 
								?>
									<li class="rb-programms-page__item">
										
										<span class="rb-programms-page__item-title"><?php echo $rb_effect_title; ?></span>
										<div class="rb-programms-page__item-text">
											<ul>
												<?php echo apply_filters( 'the_content', $rb_effect ); ?>
											</ul>
										</div>

									</li>
								<?php 
									endif;
									if( $rb_compound ) : 
								?>
									<li class="rb-programms-page__item">
										
										<span class="rb-programms-page__item-title"><?php echo $rb_compound_title; ?></span>
										<div class="rb-programms-page__item-text">
											<ul>
												<?php echo apply_filters( 'the_content', $rb_compound ); ?>
											</ul>
										</div>

									</li>
								<?php endif; ?>
							</ul>

							<div class="rb-programms-page__bottom">

								<?php if( $rb_price ) : ?>
									<div class="rb-programms-page__price">Стоимость программы: <?php if( $rb_price_from_hide ) : ?><span>от</span> <?php endif; echo $rb_price; ?>&nbsp;₽
									</div>
								<?php endif; ?>

								<div class="flex-wrap rb-programms-page__btns <?php if( get_the_ID() == 1913 ) { echo 'column'; }?>">


									<a class="rb-button__orange-full rb-programms-page__btn-modal js-open-modal pr" data-doctors="
									" data-programms="<?php echo get_the_title(); ?>" data-service="" data-servicestax="">Записаться</a>
									<?php if( get_the_ID() == 1913 ) : ?>
										<!-- <p class="rb-programms-page__btns--text">Рассчитайте стоимость программы с проживанием</p> -->
									<?php endif; ?>
									<!-- <a href="<?php //echo site_url( 'sanatorium/?pr=' . get_the_ID() );?>/"  class="rb-button__orange rb-programms-page__btn-link">Рассчитать стоимость</a> -->
								</div>

							</div>

						</div>
						<div class="col-6 col-s-12">
							<picture class="rb-programms-page__image ">
								<?php the_post_thumbnail( 'large' ); ?>
							</picture>
						</div>
					</div>		

				<?php } ?>

			</div>

		</div>
            <?php 

	            $programms_query = new WP_Query(
	                array(
	                    'post_type' => 'programms',
	                    'posts_per_page' => 9999,
	                    'order' => 'ASC',
	                    'tax_query' => array(
	                        array(
	                            'taxonomy' => 'programms-type',
	                            'field' => 'term_id',
	                            'terms' => $post_terms
	                        )
	                    ),
	                    'post__not_in' => array(get_the_ID()),
	                    'post_parent' => 0,
	                )
	            );

	            if ( $programms_query->have_posts() ) :
            ?>
				<section class="rb-programms__analog">

		                <div class="rb-container">
							<h2 class="rb-title">Другие программы</h2>
		                    <div class="rb-news__body--list swiper rb-slider" id="rbSwiperProgramms">
									<div class="filter_list swiper-wrapper">

				                        <?php 
				                            while( $programms_query->have_posts() ) {
				                                $programms_query->the_post();

				                                echo '<div class="swiper-slide">';
				                                get_template_part( 'template-part/programms', 'item' );
				                                echo '</div>';

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
		<section>
			<?php get_template_part( 'template-part/forms/form', 'programms' ); ?>
		</section>
		<section class="footer-disclaimer rb-bg-white">
			<div class="rb-container flex footer-disclaimer__wrapper">
				<div class="footer-disclaimer__sign">
					<svg width="34" height="35" viewBox="0 0 34 35" fill="none" xmlns="http://www.w3.org/2000/svg">
						<rect x="0.25" y="0.849609" width="33.5" height="33.5" rx="16.75" fill="#FFD19A"/>
						<rect x="0.25" y="0.849609" width="33.5" height="33.5" rx="16.75" stroke="#FFD19A" stroke-width="0.5"/>
						<path d="M15.497 19.1765C15.5692 19.914 15.6917 20.4621 15.8604 20.8237C16.0309 21.1841 16.3346 21.3641 16.7716 21.3639C16.8534 21.3639 16.9265 21.3514 16.9991 21.3368C17.0735 21.3514 17.1463 21.3639 17.2287 21.3639C17.6653 21.3639 17.9686 21.1839 18.1387 20.8237C18.3083 20.4621 18.4287 19.914 18.5025 19.1765L18.8909 13.4822C18.9634 12.3723 18.9998 11.5758 19 11.0925C19.0002 10.4345 18.8246 9.92118 18.4733 9.55247C18.1206 9.18396 17.6572 8.99981 17.0833 9C17.0526 9 17.0297 9.0067 16.9997 9.00787C16.9712 9.0067 16.9477 9 16.9179 9C16.3428 9 15.8803 9.18387 15.5282 9.55247C15.1763 9.92176 15.0002 10.4352 15 11.0928C14.9998 11.5761 15.0363 12.3726 15.1094 13.4824L15.497 19.1765ZM17.0143 23.4453C16.457 23.4453 15.9835 23.6176 15.5903 23.962C15.1974 24.3068 15.0008 24.7249 15.0006 25.2164C15.0006 25.7709 15.1999 26.2074 15.5954 26.5245C15.9927 26.8415 16.4562 27 16.9857 27C17.5248 27 17.9953 26.8435 18.3972 26.5306C18.7987 26.2182 18.9994 25.7794 18.9994 25.217C18.9994 24.7255 18.8073 24.3074 18.423 23.9626C18.039 23.6176 17.5691 23.4451 17.0134 23.4453" fill="#FF9B23"/>
					</svg>
				</div>
				<div class="footer-disclaimer__text">
					<span class="footer-disclaimer__text-title">
						Уважаемые пациенты!
					</span>
					<p class="footer-disclaimer__text-main">
						Указанные на сайте цены не являются публичной офертой. Для уточнения стоимости услуг и записи на процедуру обращайтесь по телефону <a href="tel:+74732111540">+7 (473) 211–15–40</a>
					</p>
				</div>
			</div>
		</section>

<?php 
	get_footer(); 
?>