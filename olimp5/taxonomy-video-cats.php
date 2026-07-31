<?php 

get_header();

$curr_obj = get_queried_object();

?>
	<div class="rb-video rb-page">
		<div class="rb-container">


				<?php 
					if( ! $_COOKIE["video__cookie"] && ! ( isset( $_GET['roman'] ) && 'check' == $_GET['roman'] ) ): 

					?>


						<section class="rb-video-form flex-wrap">
							<div class="col-6 col-s-12">
								<div class="form__intro">
									<h1 class="rb-page-header">Видеоинструкции для врачей</h1>
									<div class="rb-video-form__info">
										<h3>Уважаемые коллеги!</h3>

										<p>В этом разделе размещены информационные ролики по нашему оборудованию, методикам и отдельным услугам.</p>

										<p>Для получения доступа к ним просим Вас заполнить форму. Мы запрашиваем данные с целью сбора статистической информации.</p>
									</div>

								</div>
							</div>
							<?php get_template_part( 'template-part/forms/form', 'video' ); ?>
						</section>
						<!-- form end -->
				<?php 
					else : 

					$video_cats = get_terms( array( 'taxonomy' => 'video-cats' ) );

				?>

					<div class="doctors__header">
							<h1 class="rb-page-header">
								<?php echo $curr_obj->name; ?>
							</h1>
						</div>

					<div class="rb-video-page-wrap">
						<div class="sections__menu">
							 <ul class="rb-programms__category flex">
								<?php foreach ( $video_cats as $video_cat ) : ?>
										<li <?php if ( $curr_obj->term_id == $video_cat->term_id ) : ?> class="rb-programms__curr" <?php endif; ?> >
											<a href="<?php echo get_term_link( $video_cat->term_id ); ?>"><?php echo $video_cat->name; ?></a>
										</li>
									<?php endforeach; ?>
								</ul>
						</div>
						<div id="video__section" class="rb-video__list-wrap">

				               
							<ul class="rb-video__list flex-wrap">

								<?php
									$new_video_query = new WP_Query( 
										array(  
											'post_type' => 'video-doctors',
											'posts_per_page' => -1,
											'tax_query' => array(
												array(
													'taxonomy' => 'video-cats',
													'field'   => 'term_id',
													'terms'    => $curr_obj->term_id
												)
											)
										) 
									);

									if ( $new_video_query->have_posts() ) {
										
										while ( $new_video_query->have_posts() ){
											$new_video_query->the_post();

											?>
												<li class="col-6 col-m-12">
													<?php get_template_part( 'template-part/video', 'item' ); ?>
												</li>
											<?php
										}

										wp_reset_postdata();
										
									} else {

										echo '<div class="col-12">В данном разделе нет видео</div>';

									}
								?>

							</ul>
						</div>
					</div>
				</div>

			<?php endif; ?>

		</div>
	</div>
<?php get_footer(); ?>