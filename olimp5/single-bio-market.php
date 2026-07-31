<?php

	get_header();

	if ( function_exists( 'carbon_get_post_meta' ) ) {

		$rb_market_features = carbon_get_post_meta( get_the_id(), 'rb_market_features' );

	}

	$rb_market_country = get_post_meta( get_the_ID(), '_rb_market_country', true );
	$rb_market_volume = get_post_meta( get_the_ID(), '_rb_market_volume', true );
	$rb_market_price = get_post_meta( get_the_ID(), '_rb_market_price', true );

?>

	<section class="rb-product rb-page">
		<div class="rb-container">
		
			<div class="news__content">

				<div class="rb-product__info flex-wrap">
					<picture class="rb-product__info-image col-4 col-s-12">
						<?php echo get_the_post_thumbnail( get_the_ID(), 'medium' ); ?>
					</picture>

					<div class="rb-product__info-text col-8 col-s-12">
						<div class="rb-product__info-text--wrap">
							<h1><?php the_title(); ?></h1>
							<?php if ( $rb_market_country ) : ?>
								<div class="bio_detail--prop">
									<div class="col-6_xs-12">
										<div class="bio_detail--prop--name">
											Производитель
										</div>
										<div class="bio_detail--prop--value">
											<?php echo esc_html( $rb_market_country ); ?>
										</div>
									</div>
								</div>
							<?php endif; ?>
							<?php if ( $rb_market_volume ) : ?>
								<div class="bio_detail--prop">
									<div class="col-6_xs-12">
										<div class="bio_detail--prop--name">
											Объём
										</div>
										<div class="bio_detail--prop--value">
											<?php echo esc_html( $rb_market_volume ); ?>
										</div>
									</div>
								</div>
							<?php endif; ?>
							<?php if ( $rb_market_price ) : ?>
								<div class="bio_detail--prop">
									<div class="col-6_xs-12">
										<div class="bio_detail--prop--name">
											Цена
										</div>
										<div class="bio_detail--prop--value">
											<?php echo esc_html( $rb_market_price ); ?>
										</div>
									</div>
								</div>
							<?php endif; ?>
							<?php if ( $rb_market_features ) : ?>
								<div class="bio_detail--prop">
									<?php foreach( $rb_market_features as $features_item ): ?>
										<div class="col-6_xs-12">
											<div class="bio_detail--prop--name">
												<?php echo esc_html( $features_item['title'] ); ?>
											</div>
											<div class="bio_detail--prop--value">
												<?php echo esc_html( $features_item['val'] ); ?>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

				<div class="rb-product__block">
					<span class="rb-product__block-title">О товаре</span>
					<div class="rb-product__block-text">
						<?php the_content(); ?>
					</div>
				</div>


				<div class="rb-product__block">
					<span class="rb-product__block-title">Как заказать</span>
					<div class="rb-product__block-text">
						<p>Узнать больше об ассортименте и программах домашнего ухода Вам помогут наши консультанты. Биомаркет расположен на 1 этаже.</p>
						<p>Телефон биомаркета: <a class="bio_detail--phone" href="tel:+74732200200">+7 (473) 2200-200</a></p>
					</div>
				</div>

			</div>
		</div>

	</section>
	<?php get_template_part( 'template-part/forms/form', 'market' ); ?>
<?php get_footer(); ?>