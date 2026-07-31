<?php 

get_header();

/*
Template Name: Скидки на услуги
*/

$rb_title = get_post_meta( get_the_ID(), '_rb_discount_title', true ) ?: get_the_title();
$rb_desc = get_post_meta( get_the_ID(), '_rb_discount_textarea', true );

if ( function_exists( 'carbon_get_post_meta' ) ) {

	$rb_discount_list = carbon_get_post_meta( get_the_ID(), 'rb_discount_list' );

}


?>
	<div class="rb-serv-discount rb-page">
		<div class="rb-container">
			<div class="rb-serv-discount-top flex">
				<div class="rb-serv-discount-left col-6 col-s-12">
					<h1 class="rb-serv-discount-title"><?php echo $rb_title; ?></h1>
					<div class="rb-serv-discount-subtitle">
						<?php echo apply_filters( 'the_content', $rb_desc ); ?>
					</div>
				</div>
				<div class="rb-serv-discount-right col-6">
					
				</div>
			</div>
			<?php if( $rb_discount_list ) : ?>
				<div class="rb-serv-discount-body">
					<?php 
						$i = 1;
						foreach ( $rb_discount_list as $rb_discount_item ) :

					    		?>		
									<section class="rb-discount__archive rb-serv-discount-main">
					                    <h2 class="rb-title"><?php echo $rb_discount_item['title']; ?></h2>
					                    <p class="rb-subtitle"><?php echo $rb_discount_item['subtitle']; ?></p>
										<div class="rb-news__menu rb-container rb-discount-menu">
					                        <ul>
					                        	<?php 
					                        		$ii = 1;
					                        		foreach( $rb_discount_item['params'] as $discount_item ) : 

					                        			if( $discount_item['title'] ) :
					                        			?> 
								                            <li <?php if( count( $rb_discount_item['params'] ) < 2 ) : ?> class="rb-discount-menu-hide" <?php endif; ?> >
								                                <span class="rb-discount__tab-item <?php if( $ii == 1 ) : ?>active <?php endif; ?>" data-tab="<?php echo $ii; ?>"><?php echo $discount_item['title']; ?></span>
								                            </li>   
						                        		<?php 
						                        		endif;
						                        		$ii++;
						                        	endforeach; 
						                        ?>
					                        </ul>
					                    </div>
					                    <?php 
					                    	$iii = 0;
					                    	foreach( $rb_discount_item['params'] as $discount_item ) :

					                    		$items_arr = array_map( function($item){ return $item['id']; }, $discount_item['rb_services_list']  )?: array();


							                    $all_services = new WP_Query(
									    			array(
									    				'post_type' => 'service',
									    				'post__in' => $items_arr,
									    			)
									    		);
							                    
										    	if ( $all_services->have_posts() ) :

										    		if( $discount_item['title'] ) {

										    			$iii++;

										    		}

							                    	?>
									                    <div class="rb-discount-list <?php if( $discount_item['title'] ) : ?> rb-swiper <?php endif; ?> swiper <?php if( $discount_item['title'] && $iii == 1 ) : ?> rb-swiper-show <?php endif; ?>" <?php if( $discount_item['title'] ) : ?> data-num="<?php echo $iii; ?>" <?php endif; ?> id="rb-swiper-<?php echo $i; ?>">
										                    <div class="swiper-wrapper">
										                     	<?php 
										                     		while( $all_services->have_posts() ) {
													    				$all_services->the_post();

													    				get_template_part( 'template-part/service-discount', 'item' ); 

													    			} 
													    		?>
										                    </div>
									                    	<div class="swiper-button-prev"></div>
									                    	<div class="swiper-button-next"></div>
									                  	</div>
						                  			<?php 
						                  		endif; 
						                  		$i++;
					                  		endforeach;
					                  	?>
					                  	<?php if ( $discount_item['price'] ) : ?>
						                  	<div class="rb-discount-bottom-price">
						                  		<p>Стоимость услуг по отдельности (без скидки): <span><?php echo $discount_item['price']; ?> ₽</span></p>
						                  	</div>
						                <?php 
						            		endif; 
						            		if ( $discount_item['discount'] ) : 
						            	?>
						               		<div class="rb-discount-bottom-discount">
						                  		<p>Стоимость сочетания услуг со скидкой: <span><?php echo $discount_item['discount']; ?> ₽</span></p>
						                  	</div>
						                <?php endif; ?>
						                <div class="rb-discount-bottom-btn">
						                	<a class="rb-button__orange-full rb-programms-page__btn-modal js-open-modal" data-service="<?php echo $rb_discount_item['params'][0]['title']; ?>">Оставить заявку</a>
						                </div>
					                </section>
				    			<?php 
				    		
				    		wp_reset_postdata();
				    	endforeach;
			    	?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php get_template_part( 'template-part/forms/form', 'subscribe3' ); ?>
<?php get_footer(); ?>