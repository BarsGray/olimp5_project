<?php 

get_header();

/*
Template Name: Врачам
*/


?>
	<div class="rb-video rb-page">
		<div class="rb-container">
			<div class="">


				<?php 

					// if( $_GET['roman'] && $_GET['roman'] == 'ok' ) {

					// 	echo '<pre>';
					// 	print_r( $_COOKIE );

					// }

					if( ! $_COOKIE["video__cookie"] && ! ( isset( $_GET['roman'] ) && 'check' == $_GET['roman'] )  ): 

					?>


						<section class="rb-video-form flex-wrap">
							<div class="col-6 col-s-12">
								<div class="form__intro">
									<h1 class="rb-page-header">Видеоинструкции для врачей</h1>
									<div class="rb-video-form__info">
										<?php the_content(); ?>
									</div>

								</div>
							</div>
							<?php get_template_part( 'template-part/forms/form', 'video' ); ?>
						</section>
						<!-- form end -->
				<?php 
					else: 

					$video_cats = get_terms(array('taxonomy'=>'video-cats'));

				?>

					<div class="doctors__header">
							<h1 class="rb-page-header">
								<?php the_title(); ?>
							</h1>
						</div>

					<div class="rb-video-page-wrap">
						<div class="sections__menu">
							 <ul class="rb-programms__category flex">
								<?php foreach ( $video_cats as $video_cat ) : ?>
										<li <?php if ( $video_cats[0]->term_id == $video_cat->term_id ) : ?> class="rb-programms__curr" <?php endif; ?> >
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
													'terms'    => $video_cats[0]->term_id
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
	</div>
<?php get_footer(); ?>