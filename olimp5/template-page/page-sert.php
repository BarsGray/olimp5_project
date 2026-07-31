<?php 

get_header();

/*
Template Name: Подарочный сертификат
*/


?>
	<div class="rb-sert rb-page">
		<div class="rb-container">

			<?php 

					$rb_sert_title = get_post_meta( get_the_ID(), '_rb_sert_title', true );
					$rb_sert_desc = get_post_meta( get_the_ID(), '_rb_sert_desc', true );
					$rb_sert_desc_2 = get_post_meta( get_the_ID(), '_rb_sert_desc_2', true );
					$rb_sert_adv_btn_name = get_post_meta( get_the_ID(), '_rb_sert_adv_btn_name', true );
					$rb_sert_adv_title = get_post_meta( get_the_ID(), '_rb_sert_adv_title', true );
					$rb_sert_subtext = get_post_meta( get_the_ID(), '_rb_sert_subtext', true );
					$rb_sert_bot_image = get_post_meta( get_the_ID(), '_rb_sert_bot_image', true );
					$rb_sert_bot_title = get_post_meta( get_the_ID(), '_rb_sert_bot_title', true );
					$rb_sert_bot_text = get_post_meta( get_the_ID(), '_rb_sert_bot_text', true );
					$rb_sert_bot_btn_name = get_post_meta( get_the_ID(), '_rb_sert_bot_btn_name', true );
					$rb_sert_bot_info = get_post_meta( get_the_ID(), '_rb_sert_bot_info', true );
					if ( function_exists( 'carbon_get_post_meta' ) ) {
						$rb_sert_adv_list = carbon_get_post_meta( get_the_ID(), 'rb_sert_adv_list' );
					}

			?>

				<section class="rb-service__top flex-wrap">
		            <div class="col-6 col-s-12 rb-service__top-text rb-page-header">
		                <h1><?php echo $rb_sert_title; ?></h1>
		                <div class="rb-text rb-service__top-desc">
		                	<?php echo apply_filters( 'the_content', $rb_sert_desc ); ?>
		                </div>
		                <button class="rb-service__top-link rb-button__orange "  data-giftery-widget="21898"> <!-- rb-js-open-modal -->
		                    <?php echo $rb_sert_adv_btn_name; ?>
		                </button>
		            </div>
					<?php if( has_post_thumbnail() ) : ?>
		                <picture class="col-6 col-s-12 rb-sert-img">
		                    <?php echo get_the_post_thumbnail( get_the_ID(), 'large', array( 'class' => 'rb-img-cover' ) ); ?>
		                </picture>
		            <?php endif; ?>
			    </section>
		    	<div class="rb-text rb-service__top-desc">
                	<?php echo apply_filters( 'the_content', $rb_sert_desc_2 ); ?>
                </div>
			    <section class="rb-sert-adv">

	                <div class="col-7 col-m-12 col-s-12 rb-title">
	                    <?php echo $rb_sert_adv_title; ?>
	                </div>
	                <?php if( $rb_sert_adv_list ) : ?>
		                <ul class="flex flex-wrap rb-sert-adv-list">
		                    <?php 
		                    	foreach ( $rb_sert_adv_list as $rb_sert_adv_item ) {

		                    		echo '<li>' . $rb_sert_adv_item['text'] . '</li>';

		                    	} 
		                    ?>
		                </ul>
		            <?php endif; ?>
	                <p class="rb-sert-subtext"><?php echo $rb_sert_subtext; ?></p>
	            </section>
				<section class="rb-sert-bottom flex-wrap flex">
					<?php if( $rb_sert_bot_image ) : ?>
		                <picture class="col-6 col-s-12 rb-sert-image">
		                    <?php echo wp_get_attachment_image( $rb_sert_bot_image, 'large', '', array( 'class' => 'rb-img-cover' ) ); ?>
		                </picture>
		            <?php endif; ?>
		            <div class="col-6 col-s-12 rb-sert-text">
		                <h2 class="rb-title"><?php echo $rb_sert_bot_title; ?></h2>
		                <div class="rb-text rb-service__top-desc">
		                	<?php echo apply_filters( 'the_content', $rb_sert_bot_text ); ?>
		                </div>
		                <button class="rb-service__top-link rb-button__orange" data-giftery-widget="21898">
		                    <?php echo $rb_sert_bot_btn_name; ?>
		                </button>
		            </div>
			    </section>
			    <?php if( $rb_sert_bot_info ) : ?>
					<section class="rb-sert-bottom flex-wrap flex">
			            <div class="col-12 rb-sert-text">
			                <div class="rb-text rb-service__top-desc">
			                	<?php echo apply_filters( 'the_content', $rb_sert_bot_info ); ?>
			                </div>
			            </div>
				    </section>
				<?php endif; ?>
		</div>
	</div>
<?php get_footer(); ?>